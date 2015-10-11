<?php

namespace Comfort;

class TestCase extends \PHPUnit_Framework_TestCase {
	public function getPluginBasename() {
		return plugin_basename( COMFORT_FILE );
	}

	public function getPluginTextdomain() {
		return 'comfort';
	}
}