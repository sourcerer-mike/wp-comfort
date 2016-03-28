<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;
use PHPUnit\Comfort\TestCase;

abstract class Abstract_Post_Type_Test extends TestCase {
	protected $_previous_screen;

	/**
	 * @return Post_Type
	 */
	protected function newTarget() {
		return new Post_Type( 'locations' );
	}

	protected function setUp() {
		require_once ABSPATH . '/wp-admin/includes/screen.php';
		require_once ABSPATH . '/wp-admin/includes/class-wp-screen.php';

		global $current_screen;

		$this->_previous_screen = get_current_screen();

		$current_screen            = \WP_Screen::get( 'locations' );
		$current_screen->post_type = 'locations';

		$GLOBALS['current_screen'] = $current_screen;
	}

	protected function tearDown() {
		global $current_screen;

		if ( ! $this->_previous_screen ) {
			unset( $GLOBALS['current_screen'] );
		}

		$current_screen = $this->_previous_screen;
	}
}