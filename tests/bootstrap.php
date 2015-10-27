<?php

require_once __DIR__ . '/../../../../wp-load.php';

require_once ABSPATH . '/wp-admin/includes/plugin.php';

// reset plugin
$plugin_file = plugin_basename( dirname( __DIR__ ) . '/comfort.php' );
deactivate_plugins( [ $plugin_file ] );
activate_plugin( $plugin_file );