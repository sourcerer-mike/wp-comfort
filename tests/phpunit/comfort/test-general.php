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

		$this->assertTrue( static::$isDomainLoaded );
	}

	public function test_registers_autoload_for_classes() {

		// Given the loader is not registered
		foreach ( spl_autoload_functions() as $target ) {
			if ( ! is_array( $target ) ) {
				continue;
			}

			if ( ! is_object( $target[0] ) ) {
				continue;
			}

			if ( $target[0] instanceof Loader ) {
				spl_autoload_unregister( $target );
			}
		}

		// And the loader status is cleaned-up
		$class = new \ReflectionClass( '\\Comfort\\Loader' );
		$prop  = $class->getProperty( '_is_registered' );
		$prop->setAccessible( true );
		$prop->setValue( new Loader(), false );

		$target = [ 'Comfort\\Loader', 'load_class' ];
		$this->assertFalse( $prop->getValue( new Loader() ) );
		$this->assertNotContains( $target, spl_autoload_functions() );

		// When I register the autoload
		Loader::register();

		// Then it shall have such status
		$this->assertTrue( $prop->getValue( new Loader() ) );

		// And the SPL autoload functions contain the loader.
		$this->assertContains( $target, spl_autoload_functions() );
	}
}
