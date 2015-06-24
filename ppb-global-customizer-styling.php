<?php
/*
Plugin Name: Pootle PB Global Customizer Styling Addon
Plugin URI: http://pootlepress.com/
Description: Boilerplate for fast track Pootle Page Builder Addon Development
Author: Shramee
Version: 0.1
Author URI: http://shramee.com/
*/

/**
 * Including Main Plugin class
 */
require_once 'class-ppb-global-customizer-styling.php';

/**
 * Styles output class
 */
require_once 'inc/styles-output.php';

Pootle_PB_Global_Customizer_Styling::instance();