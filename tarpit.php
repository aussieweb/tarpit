<?php

/**
 * Plugin Name: GMT Tarpit
 * Plugin URI: https://github.com/cferdinandi/tarpit
 * GitHub Plugin URI: https://github.com/cferdinandi/tarpit
 * Description: Reduce comment spam with a smart <a href="http://en.wikipedia.org/wiki/Honeypot_(computing)">honeypot</a> to capture bots. Forked from <a href="https://github.com/freak3dot/wp-smart-honeypot">WP Comment Smart Honeypot</a> by Ryan Johnston. Adjust settings under <a href="options-general.php?page=tarpit_theme_options">Settings &rarr; Tarpit</a>.
 * Version: 2.0.0
 * Author: Chris Ferdinandi
 * Author URI: http://gomakethings.com
 * License: GPLv2
 */

require_once( dirname( __FILE__) . '/tarpit-options.php' );
require_once( dirname( __FILE__) . '/tarpit-methods.php' );