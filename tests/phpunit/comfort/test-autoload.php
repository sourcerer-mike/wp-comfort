<?php

namespace PHPUnit\Comfort;

use Comfort\Loader;
use org\bovigo\vfs\vfsStream;

class AutoloadTest extends TestCase {
	protected static $backupDirectories = [ ];
	protected        $dummyClassName;

	protected function setUp() {
		$this->setUpCleanDirectories();
		$this->setUpCleanSpl();
		$this->setUpClassDummy();
	}

	private function setUpCleanDirectories() {
		static::$backupDirectories = Loader::get_directories();

		$reflect = new \ReflectionClass( '\\Comfort\\Loader' );
		$prop    = $reflect->getProperty( '_directories' );

		$prop->setAccessible( true );
		$prop->setValue( new Loader(), [ $this->getPluginDirectory() . '/includes' ] );
	}

	public function setUpCleanSpl() {
		// Given the loader is not registered
		spl_autoload_unregister( $this->getSplTarget() );

		// And SPL is cleaned-up
		$this->assertNotContains( $this->getSplTarget(), spl_autoload_functions() );
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

		$this->tearDownCleanDirectories();
	}

	public function tearDownCleanDirectories() {
		$reflect = new \ReflectionClass( '\\Comfort\\Loader' );
		$prop    = $reflect->getProperty( '_directories' );

		$prop->setAccessible( true );
		$prop->setValue( new Loader(), static::$backupDirectories );
	}

	public static function tearDownAfterClass() {
		if ( ! in_array( static::getSplTarget(), spl_autoload_functions() ) ) {
			spl_autoload_register( static::getSplTarget() );
		}
	}

	public function testBaseDirectoriesCanBeRegistered() {
		$current = Loader::get_directories();

		Loader::register_directory( getcwd() );

		$this->assertNotEquals( $current, Loader::get_directories() );
		$this->assertContains( getcwd(), Loader::get_directories() );
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