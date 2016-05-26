<?php
/**
 * Loads environment for plugin.
 *
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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// use composer autoload
if ( is_file( ABSPATH . '/vendor/autoload.php' ) ) {
	require_once ABSPATH . '/vendor/autoload.php';
}

// load own settings and autoload method
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

\Comfort\Loader::register_directory( __DIR__ );

// load textdomain
add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain(
			COMFORT_TEXTDOMAIN,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}
);

// register activation and deactivation hooks for admin
if ( is_admin() ) {
	require_once __DIR__ . '/includes/setup/activate.php';
	require_once __DIR__ . '/includes/setup/deactivate.php';
}

/**
 * Fetch all files.
 *
 * This is following the best practices as described on
 * https://developer.wordpress.org/plugins/the-basics/best-practices/#folder-structure .
 * The "includes" directory contains all additional functionality.
 * Remove files to make the plugin more lightweight for your customer / project.
 */
foreach ( glob( __DIR__ . '/includes/*' ) as $file_node ) {
	$basename = basename( $file_node, '.php' );

	if ( is_dir( $file_node ) ) {
		continue;
	}

	if ( basename( $file_node ) != $basename ) {
		// Is php file: use it.
		require_once $file_node;
	}
}
