<?php

namespace Comfort;

require_once ABSPATH . '/wp-admin/includes/plugin.php';

class GeneralTest extends \PHPUnit_Framework_TestCase {
	public function test_plugin_is_listed() {
		$this->assertArrayHasKey(
			plugin_basename( COMFORT_FILE ),
			get_plugins()
		);
	}
}
