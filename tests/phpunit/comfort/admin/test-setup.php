<?php

namespace Comfort\Admin;

class SetupTest extends \PHPUnit_Framework_TestCase {
	public function test_activation_hook_exists() {
		$key = 'activate_' . plugin_basename(COMFORT_FILE);

		global $wp_filter;

		$this->assertArrayHasKey($key, $wp_filter);
	}

	public function test_deactivation_hook_exists() {
		$key = 'deactivate_' . plugin_basename(COMFORT_FILE);

		global $wp_filter;

		$this->assertArrayHasKey($key, $wp_filter);
	}
}