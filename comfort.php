<?php
/*
Plugin Name: Comfort
Version: 1.1.0
Description: More comfort and security for your site.
Author: Mike Pretzlaw
Author URI: http://www.mike-pretzlaw.de
Plugin URI: https://github.com/sourcerer-mike/wp-comfort
Text Domain: comfort
Domain Path: /languages
*/

/*
 * LICENSE: This source file is subject to the MIT license
 * that is available through the world-wide-web at the following URI:
 * https://opensource.org/licenses/MIT. If you did not receive a copy
 * of the PHP License and are unable to obtain it through the web, please send
 * a note to pretzlaw@gmail.com so we can mail you a copy immediately.
 *
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2016 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/wp-comfort/LICENSE.md MIT License
 * @link      http://github.com/sourcerer-mike/wp-comfort
 */

@define( 'COMFORT_DIR', __DIR__ );
@define( 'COMFORT_FILE', __FILE__ );
@define( 'COMFORT_TEXTDOMAIN', basename( __DIR__ ) );

// Tear-up plugin and load all files in the include-directory.
require_once 'bootstrap.php';

// Add changes or enhancements for your own environment below this point.