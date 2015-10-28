<?php

namespace Comfort\Admin;

use Comfort\TestCase;

class Activate_Plugin_Test extends TestCase {
	private static $backup_version = false;

	public function filterPluginData( $data ) {
		$data['Version'] = $this->getDummyVersion();

		return $data;
	}

	/**
	 * @return string
	 */
	public
	function getDummyVersion() {
		return '99.99.99';
	}

	protected function setUp() {
		$this->setUpDbVersion();
		$this->setUpDummyScript();

	}

	protected function setUpDbVersion() {
		static::$backup_version = get_option( $this->getDbName() );

		add_filter( 'plugin_data', [ $this, 'filterPluginData' ] );
		update_option( $this->getDbName(), '99.99.98' );
	}

	/**
	 * @return string
	 */
	protected function getDbName() {
		return basename( $this->getPluginBasename(), '.php' ) . '_version';
	}

	private function setUpDummyScript() {
		$setup_dummy = $this->getDummyScriptPathname();

		touch( $setup_dummy );
	}

	/**
	 * @return string
	 */
	public function getDummyScriptPathname() {
		return $this->getPluginDirectory()
		       . '/includes/setup/'
		       . $this->getDummyVersion() . '-foo.php';
	}

	protected function tearDown() {
		$this->tearDownDbVersion();
		$this->tearDownDummyScript();
	}

	protected function tearDownDbVersion() {
		update_option( $this->getDbName(), static::$backup_version );

		if ( static::$backup_version === false ) {
			delete_option( $this->getDbName() );
		}
	}

	private function tearDownDummyScript() {
		unlink( $this->getDummyScriptPathname() );
	}

	public function testItSkipsOldScripts() {
		$oldScript = dirname( $this->getDummyScriptPathname() ) . '/0.0.0-test.php';

		file_put_contents( $oldScript, '<?php throw new \\Exception();' );

		comfort_activate();

		$this->assertNotContains( $oldScript, get_included_files() );

		unlink( $oldScript );
	}

	public function testItIgnoresScriptsNewerThanThePluginVersion() {
		$newScript = dirname( $this->getDummyScriptPathname() ) . '/999.0.0-test.php';

		file_put_contents( $newScript, '<?php throw new \\Exception();' );

		comfort_activate();

		$this->assertNotContains( $newScript, get_included_files() );

		unlink( $newScript );
	}

	public function test_setup_scripts_are_rolled_out() {
		comfort_activate();

		$this->assertEquals( $this->getDummyVersion(), get_option( $this->getDbName() ) );
	}
}

