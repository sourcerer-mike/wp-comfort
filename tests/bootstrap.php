<?php

require_once __DIR__ . '/../../../../wp-load.php';

require_once ABSPATH . '/wp-admin/includes/plugin.php';

// reset plugin
$plugin_file = plugin_basename( dirname( __DIR__ ) . '/comfort.php' );
deactivate_plugins( [ $plugin_file ] );
activate_plugin( $plugin_file );

\Comfort\Loader::register_directory( __DIR__ );

// assert correct version after tests
define( 'COMFORT_VERSION', get_site_option( 'comfort_version', '0.0.0' ) );

register_shutdown_function(
	function () {
		update_option( 'comfort_version', COMFORT_VERSION );
	}
);