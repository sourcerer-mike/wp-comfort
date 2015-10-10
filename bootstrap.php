<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// use composer autoload
if ( is_file( ABSPATH . '/vendor/autoload.php' ) ) {
	require_once ABSPATH . '/vendor/autoload.php';
}

// load own settings and autoload method
require_once __DIR__ . DIRECTORY_SEPARATOR . 'settings.php';
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

// Fetch all files
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
