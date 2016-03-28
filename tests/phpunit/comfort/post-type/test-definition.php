<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;
use PHPUnit\Comfort\TestCase;

class Definition_Test extends TestCase {
	public function testItStoresItsPostType() {
		$post_type = uniqid();
		$target    = new Post_Type( $post_type );

		$this->assertSame( $post_type, $target->get_post_type() );
	}
}