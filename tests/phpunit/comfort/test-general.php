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

	public function test_wp_coding_standards_for_class_files() {
		$this->assertEquals(
			'foo-bar/bar/baz/class-qux.php',
			Loader::class_to_file('\\Foo_Bar\\Bar\\Baz\\Qux')
		);
	}
}
