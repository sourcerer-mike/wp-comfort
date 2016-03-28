<?php

namespace PHPUnit\Comfort;

class TestCase extends \PHPUnit_Framework_TestCase {
	public function getPluginBasename() {
		return plugin_basename( $this->getPluginFile() );
	}

	public function getPluginTextdomain() {
		return COMFORT_TEXTDOMAIN;
	}

	public function getPluginDirectory() {
		return COMFORT_DIR;
	}

	/**
	 * @return string
	 */
	protected function getPluginFile() {
		return COMFORT_FILE;
	}
}