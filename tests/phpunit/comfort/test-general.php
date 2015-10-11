<?php

namespace Comfort;

require_once ABSPATH . '/wp-admin/includes/plugin.php';

class GeneralTest extends TestCase {
	public static $isDomainLoaded = false;

	public function test_plugin_is_listed() {
		$this->assertArrayHasKey(
			plugin_basename( COMFORT_FILE ),
			get_plugins()
		)
		;
	}

	public function test_wp_coding_standards_for_class_files() {
		$this->assertEquals(
			'foo-bar/bar/baz/class-qux.php',
			Loader::class_to_file( '\\Foo_Bar\\Bar\\Baz\\Qux' )
		)
		;
	}

	public function test_load_own_textdomain_after_plugins_are_loaded() {
		GeneralTest::$isDomainLoaded = false;

		add_filter(
			'plugin_locale',
			function ( $locale, $domain ) {
				if ( $domain == $this->getPluginTextdomain() ) {
					GeneralTest::$isDomainLoaded = true;
				}

				return $locale;
			},
			10,
			2
		);

		do_action( 'plugins_loaded' );

		$this->assertTrue(static::$isDomainLoaded);
	}
}
