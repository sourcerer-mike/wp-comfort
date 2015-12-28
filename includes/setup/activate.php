<?php
/**
 * Contains hooks.
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

function comfort_activate() {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';

	$version_option = basename( COMFORT_FILE, '.php' ) . '_version';
	$version        = get_option(
		$version_option,
		'0.0.0'
	);

	$data           = get_plugin_data( COMFORT_FILE );
	$data           = apply_filters( 'plugin_data', $data );
	$plugin_version = $data['Version'];

	foreach ( glob( __DIR__ . '/*-*.php' ) as $update_file ) {
		$data = explode( '-', basename( $update_file ), 2 );

		if ( version_compare( $data[0], $version ) <= 0 ) {
			continue;
		}

		if ( version_compare( $data[0], $plugin_version ) > 0 ) {
			continue;
		}

		$callable = require $update_file;

		if ( is_callable( $callable ) ) {
			call_user_func( $callable );
		}

		update_option( $version_option, $data[0], false );
	}
}

register_activation_hook( COMFORT_FILE, 'comfort_activate' );
