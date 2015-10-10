<?php
/**
 *
 */

register_activation_hook(
	COMFORT_FILE,
	function () {
		$version_option = basename( COMFORT_FILE, '.php' ) . '-version';
		$version        = get_option(
			$version_option,
			'0.0.0'
		);

		$data           = get_plugin_data( COMFORT_FILE );
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

			update_option( $version_option, $data[0] );
		}
	}
);
