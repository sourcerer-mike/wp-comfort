<?php
/*
Plugin Name: Comfort
Version: 1.0.0
Description: More comfort and security for your site.
Author: Mike Pretzlaw
Author URI: http://www.mike-pretzlaw.de
Plugin URI: https://github.com/sourcerer-mike/wp-comfort
Text Domain: comfort
Domain Path: /languages
*/

@define( 'COMFORT_DIR', __DIR__ );
@define( 'COMFORT_FILE', __FILE__ );
@define( 'COMFORT_TEXTDOMAIN', basename( __DIR__ ) );

// Tear-up plugin and load all files in the include-directory.
require_once 'bootstrap.php';

// Add changes or enhancements for your own environment below this point.