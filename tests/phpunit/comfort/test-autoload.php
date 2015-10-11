<?php

namespace Comfort;

use org\bovigo\vfs\vfsStream;

class AutoloadTest extends TestCase {
	protected $dummyClassName;

	protected function setUp() {
		$this->setUpCleanSpl();
		$this->setUpClassDummy();
	}

	public function setUpCleanSpl() {
		// Given the loader is not registered
		spl_autoload_unregister( $this->getSplTarget() );

		// And the loader status is cleaned-up
		$class = new \ReflectionClass( '\\Comfort\\Loader' );
		$prop  = $class->getProperty( '_is_registered' );
		$prop->setAccessible( true );
		$prop->setValue( new Loader(), false );

		$target = [ 'Comfort\\Loader', 'load_class' ];
		$this->assertFalse( $prop->getValue( new Loader() ) );
		$this->assertNotContains( $target, spl_autoload_functions() );
	}

	/**
	 * @return array
	 */
	protected static function getSplTarget() {
		return [ 'Comfort\\Loader', 'load_class' ];
	}

	public function setUpClassDummy() {
		$vfsStream = vfsStream::setup( 'home' );

		$fileName = Loader::class_to_file( $this->getDummyClassName() );
		file_put_contents( $vfsStream->url() . '/' . $fileName, '<?php class ' . $this->getDummyClassName() . ' {} ' );

		Loader::register_directory( $vfsStream->url() );
	}

	/**
	 * @return string
	 */
	protected function getDummyClassName() {
		if ( ! $this->dummyClassName ) {
			$this->dummyClassName = 'SomeDummy' . uniqid();
		}

		return $this->dummyClassName;
	}

	public function tearDown() {
		$this->dummyClassName = null;
	}

	public static function tearDownAfterClass() {
		if ( ! in_array( static::getSplTarget(), spl_autoload_functions() ) ) {
			spl_autoload_register( static::getSplTarget() );
		}
	}

	public function testLoadsClassesFromDirectories() {
		Loader::register();

		$this->assertFalse( class_exists( $this->getDummyClassName(), false ) );
		$this->assertTrue( class_exists( $this->getDummyClassName() ) );
	}

	public function testMeetsWpCodingStandardsForClassFiles() {
		$this->assertEquals(
			'foo-bar/bar/baz/class-qux.php',
			Loader::class_to_file( '\\Foo_Bar\\Bar\\Baz\\Qux' )
		)
		;
	}

	public function testRegistersAutoloadForClasses() {
		// When I register the autoload
		Loader::register();

		// Then the SPL autoload functions contain the loader.
		$this->assertContains( $this->getSplTarget(), spl_autoload_functions() );
	}
}