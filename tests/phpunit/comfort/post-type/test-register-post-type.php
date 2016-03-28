<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;

class Register_Post_Type_Test extends Abstract_Post_Type_Test {
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
}