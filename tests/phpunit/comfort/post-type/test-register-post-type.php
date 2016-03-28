<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;

class Register_Post_Type_Test extends Abstract_Post_Type_Test {
	protected static $registerArgs = [ ];
	protected        $_register_args_listener;

	public function testMenuPositionIsSetViaMenuName() {
		$post_type = new Post_Type( 'locations' );
		$post_type->scaffold_labels( 'Location', 'Locations' );

		$post_type->register_post_type();

		$this->assertEquals( ord( 'l' ) - 97 + 26, static::$registerArgs['menu_position'] );
	}

	public function testPostTypeIsRegisteredInWordPress() {
		$locations = new Post_Type( 'locations' );

		$locations->register_post_type();

		$this->assertArrayHasKey( 'locations', get_post_types() );
	}

	protected function setUp() {
		parent::setUp();

		$self = $this;

		$this->_register_args_listener = function ( $args ) use ( $self ) {
			$self::$registerArgs = $args;

			return $args;
		};

		add_filter( 'register_post_type_args', $this->_register_args_listener );

		static::$registerArgs = [ ];
	}

	protected function tearDown() {
		remove_filter( 'register_post_type_args', $this->_register_args_listener );
		parent::tearDown();
	}
}