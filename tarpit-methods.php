<?php

	new wpTarpit_plugin();

	class wpTarpit_plugin {

		protected $options;
		protected $insertAt;
		protected $label_list = array('Name', 'Email', 'Website', 'Comment');
		protected $icon_list = array('user', 'envelope', 'home', 'comment');
		protected $label;
		protected $addOn;
		protected $salt;
		protected $hideClass;
		protected $nameRegex = '/name="[a-z0-9]*"/i';
		protected $idRegex = '/id="[a-z0-9]*"/i';
		protected $forRegex = '/for="[a-z0-9]*"/i';
		protected $approvedComment = '';

		function __construct() {
			add_filter( 'comment_form_before_fields', array( &$this, 'tarpit_init') );
			add_filter( 'comment_form_field_author', array( &$this, 'tarpit_encrypt_author') );
			add_filter( 'comment_form_field_email', array( &$this, 'tarpit_encrypt_email') );
			add_filter( 'comment_form_field_url', array( &$this, 'tarpit_encrypt_url') );
			add_filter( 'comment_form_field_comment', array( &$this, 'tarpit_encrypt_comment') );
			add_action( 'pre_comment_approved', array( &$this, 'tarpit_check_form') );
			add_action( 'pre_comment_on_post', array( &$this, 'tarpit_pre_post') );
			add_action( 'comment_form', array( &$this, 'tarpit_hidden_field_and_script') );
		}

		function tarpit_init() {
			$this->options = tarpit_get_theme_options();
			$this->insertAt = rand()&3;
			$this->label = rand()&3;
			$this->addOn = substr(sha1(time().$this->salt),0,6);
			$this->salt = $this->options['random_key'] === '' ? 'thisisareallybadrandomkey' :  $this->options['random_key'];
			$this->hideClass = $this->options['hide_class'] === '' ? 'bzzz' : $this->options['hide_class'];
		}

		function tarpit_insertHoneyPot(){
			$hp_field =
				'<div class="' . strtolower($this->hideClass) . '">' .
					'<label for="' . strtolower($this->label_list[$this->label]) . '">' .
						$this->label_list[$this->label] .
					'</label>
					<input type="text" name="' . strtolower($this->label_list[$this->label]) . '" id="' . strtolower($this->label_list[$this->label]) . '" value="">' .
				'</div>';
			return $hp_field;
		}

		private function tarpit_make_field($unique, $addOn = false){
			if($addOn === false){ $addOn = $this->addOn; }
			return md5($unique . $addOn);
		}

		private function tarpit_replace_name_id($unique, $field){
			$field_name = $this->tarpit_make_field($unique);
			$field = preg_replace($this->nameRegex, 'name="' . $field_name . '"', $field);
			$field = preg_replace($this->idRegex, 'id="' . $field_name . '"', $field);
			$field = preg_replace($this->forRegex, 'for="' . $field_name . '"', $field);
			return $field;
		}

		function tarpit_encrypt_author($author_field){
			$author_field = $this->tarpit_replace_name_id('author', $author_field);
			if($this->insertAt == 0) { $author_field = $this->tarpit_insertHoneyPot() . $author_field; }
			return $author_field;
		}

		function tarpit_encrypt_email($email_field){
			$email_field = $this->tarpit_replace_name_id('email', $email_field);
			if($this->insertAt == 1) { $email_field = $this->tarpit_insertHoneyPot() . $email_field; }
			return $email_field;
		}

		function tarpit_encrypt_url($url_field){
			$url_field = $this->tarpit_replace_name_id('url', $url_field);
			if($this->insertAt == 2) { $url_field = $this->tarpit_insertHoneyPot() . $url_field; }
			return $url_field;
		}

		function tarpit_encrypt_comment($comment_field){
			$comment_field = $this->tarpit_replace_name_id('comment', $comment_field);
			if($this->insertAt == 3) { $comment_field = $this->tarpit_insertHoneyPot() . $comment_field; }
			return $comment_field;
		}

		function tarpit_pre_post(){
			$output = base64_decode($_POST['enc-type']);
			$output = rtrim($output, '');
			$addOn   = substr($output, 0, -1);
			if (!empty($_POST[strtolower($this->label_list[substr($output, -1, 1)])])) {
					$this->approved = 'spam';
			}
			$author = $this->tarpit_make_field('author', $addOn);
			$_POST['author'] = $_POST[$author];
			$email = $this->tarpit_make_field('email', $addOn);
			$_POST['email'] = $_POST[$email];
			$url = $this->tarpit_make_field('url', $addOn);
			$_POST['url'] = $_POST[$url];
			$comment = $this->tarpit_make_field('comment', $addOn);
			$_POST['comment'] = $_POST[$comment];
		}

		function tarpit_check_form($approved) {
			if ($this->approved == 'spam') { return 'spam'; }
			return $approved;
		}

		function tarpit_hidden_field_and_script(){
			$output = base64_encode($this->addOn . $this->label);
			echo '<input type="hidden" name="enc-type" value="' .$output . '"/>';
		}

	}