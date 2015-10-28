<?php
/**
 *
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

		require $update_file;

		update_option( $version_option, $data[0], false );
	}
}

register_activation_hook( COMFORT_FILE, 'comfort_activate' );
