<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\TestCase;
use PHPUnit\Comfort\Test_Doubles\Simple_Post;
use PHPUnit\Comfort\Test_Doubles\Simple_Post\Simple_Meta;

class AbstractMetaTest extends TestCase {
	public function test_properties_are_meta_keys() {
		$simple_meta = $this->create_simple_meta();

		$meta_key = uniqid( 'sub' );
		$meta_val = uniqid( 'val' );

		$this->assertEmpty( $simple_meta->$meta_key );

		$simple_meta->$meta_key = $meta_val;

		$this->assertEquals( (array) $meta_val, $simple_meta->$meta_key );

		wp_cache_flush();
		$this->assertEquals(
			(array) $meta_val,
			get_post_meta( $simple_meta->get_facade()->ID, $simple_meta->get_prefix() . '_' . $meta_key )
		);
	}

	/**
	 * @return Simple_Meta
	 */
	public function create_simple_meta() {
		$simple_meta = $this->create_simple_post()->simple;

		$this->assertInstanceOf( '\\PHPUnit\\Comfort\\Test_Doubles\\Simple_Post\\Simple_Meta', $simple_meta );

		return $simple_meta;
	}

	/**
	 * @return Simple_Post
	 */
	protected function create_simple_post() {
		$posts = get_posts();
		$this->assertNotNull( $posts );

		$post = current( $posts );
		$this->assertInstanceOf( '\\WP_Post', $post );

		return new Simple_Post( $post->ID );
	}
}

