<?php

/**
 * Theme Option Fields
 */

function tarpit_random_key() {
	$options = tarpit_get_theme_options();
	?>
	<input type="text" name="tarpit_theme_options[random_key]" id="random-key" value="<?php echo esc_attr( $options['random_key'] ); ?>" /><br />
	<label class="description" for="random-key"><?php printf( __( 'A random key that is used to generate new field names. Make one up or use this <a target="_blank" href="%s">random password generator</a>.', 'tarpit' ), esc_url( 'http://www.sethcardoza.com/tools/random-password-generator/' ) ); ?></label>
	<?php
}

function tarpit_hide_class() {
	$options = tarpit_get_theme_options();
	?>
	<input type="text" name="tarpit_theme_options[hide_class]" id="hide-class" value="<?php echo esc_attr( $options['hide_class'] ); ?>" /><br />
	<label class="description" for="hide-class"><?php _e( 'Class to apply to your hidden field. Default: <code>.tarpit</code>', 'tarpit' ); ?></label>
	<?php
}





/**
 * Theme Options Menu
 */

// Register the theme options page and its fields
function tarpit_theme_options_init() {
	register_setting(
		'tarpit_options', // Options group, see settings_fields() call in tarpit_theme_options_render_page()
		'tarpit_theme_options', // Database option, see tarpit_get_theme_options()
		'tarpit_theme_options_validate' // The sanitization callback, see tarpit_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'tarpit_theme_options' // Menu slug, used to uniquely identify the page; see tarpit_theme_options_add_page()
	);
	add_settings_field( 'random_key', __( 'Random Key', 'tarpit' ), 'tarpit_random_key', 'tarpit_theme_options', 'general' );
	add_settings_field( 'hide_class', __( 'Hide Class', 'tarpit' ), 'tarpit_hide_class', 'tarpit_theme_options', 'general' );
}
add_action( 'admin_init', 'tarpit_theme_options_init' );



// Create theme options menu
// The content that's rendered on the menu page.
function tarpit_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'WP Comment Smart Honeypot Plus', 'tarpit' ); ?></h2>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'tarpit_options' );
				do_settings_sections( 'tarpit_theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}



// Add the theme options page to the admin menu
function tarpit_theme_options_add_page() {
	$theme_page = add_submenu_page(
		'options-general.php', // parent slug
		'Tarpit', // Label in menu
		'Tarpit', // Label in menu
		'edit_theme_options', // Capability required
		'tarpit_theme_options', // Menu slug, used to uniquely identify the page
		'tarpit_theme_options_render_page' // Function that renders the options page
	);
}
add_action( 'admin_menu', 'tarpit_theme_options_add_page' );



// Restrict access to the theme options page to admins
function tarpit_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_tarpit_options', 'tarpit_option_page_capability' );







/**
 * Process Theme Options
 */

// Get the current options from the database.
// If none are specified, use these defaults.
function tarpit_get_theme_options() {
	$saved = (array) get_option( 'tarpit_theme_options' );
	$defaults = array(
		'random_key' => '',
		'hide_class' => '',
	);

	$defaults = apply_filters( 'tarpit_default_theme_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}



// Sanitize and validate updated theme options
function tarpit_theme_options_validate( $input ) {
	$output = array();

	if ( isset( $input['random_key'] ) && ! empty( $input['random_key'] ) )
		$output['random_key'] = wp_filter_nohtml_kses( $input['random_key'] );

	if ( isset( $input['hide_class'] ) && ! empty( $input['hide_class'] ) )
		$output['hide_class'] = wp_filter_nohtml_kses( $input['hide_class'] );

	return apply_filters( 'tarpit_theme_options_validate', $output, $input );
}