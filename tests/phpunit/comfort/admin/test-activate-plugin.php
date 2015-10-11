<?php

namespace Comfort\Admin;

use Comfort\TestCase;

class Activate_Plugin_Test extends TestCase {
	private static $backup_version = false;

	/**
	 * @return string
	 */
	public function getDummyScriptPathname() {
		return $this->getPluginDirectory()
		       . '/includes/setup/'
		       . $this->getDummyVersion() . '-foo.php';
	}

	protected function setUp() {
		$this->setUpDbVersion();
		$this->setUpDummyScript();

	}

	protected function tearDown() {
		$this->tearDownDbVersion();
		$this->tearDownDummyScript();
	}

	public function filterPluginData( $data ) {
		$data['Version'] = $this->getDummyVersion();

		return $data;
	}


	public function test_setup_scripts_are_rolled_out() {
		comfort_activate();

		$this->assertEquals( $this->getDummyVersion(), get_option( $this->getDbName() ) );
	}

	/**
	 * @return string
	 */
	protected function getDbName() {
		return basename( $this->getPluginBasename(), '.php' ) . '-version';
	}

	private function setUpDummyScript() {
		$setup_dummy = $this->getDummyScriptPathname();

		touch( $setup_dummy );
	}

	private function tearDownDummyScript() {
		unlink( $this->getDummyScriptPathname() );
	}

	/**
	 * @return string
	 */
	public
	function getDummyVersion() {
		return '99.99.99';
	}

	protected function tearDownDbVersion() {
		update_option( $this->getDbName(), static::$backup_version );

		if ( static::$backup_version === false ) {
			delete_option( $this->getDbName() );
		}
	}

	protected function setUpDbVersion() {
		static::$backup_version = get_option( $this->getDbName() );

		add_filter( 'plugin_data', [ $this, 'filterPluginData' ] );
		update_option( $this->getDbName(), '99.99.98' );
	}
}

