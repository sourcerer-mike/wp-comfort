<?php

namespace Comfort;

require_once ABSPATH . '/wp-admin/includes/plugin.php';

class GeneralTest extends \PHPUnit_Framework_TestCase {
	public function test_plugin_is_listed() {
		$plugins = get_plugins();

		$pluginName = basename( dirname ( dirname ( dirname( __DIR__ ) ) ) );
		$pluginName = $pluginName . '/' . $pluginName . '.php';

		$this->assertArrayHasKey( $pluginName, $plugins );
	}
}
