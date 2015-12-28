<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\TestCase;
use PHPUnit\Comfort\Post_Type\Abstract_Meta\Simple_Meta;
use PHPUnit\Comfort\Test_Doubles\Simple_Post;

class AbstractPostTest extends TestCase {
	public function testItForwardsWPPostFields() {
		// Given I have a random post
		$posts = get_posts();

		$this->assertNotNull( $posts );
		$this->assertNotEmpty( $posts );
		$this->assertNotInstanceOf( '\WP_Error', $posts );

		/** @var \WP_Post $post */
		$post = current( $posts );

		$this->assertNotNull( $post );
		$this->assertNotEquals( 0, $post->ID );
		$this->assertInstanceOf( '\WP_Post', $post );

		// And I turn it into a comfort post
		$comfort_post = new Simple_Post( $post->ID );

		// And the meta key has no value
		$meta_key = uniqid( 'some_meta_' );
		$this->assertEmpty( $post->$meta_key );
		$this->assertEmpty( $comfort_post->$meta_key );

		// And I fill the meta entity
		$meta_value = uniqid( 'some_value_' );
		update_post_meta( $post->ID, $meta_key, $meta_value );

		// When I fetch the meta value via WP_Post
		wp_cache_flush();
		$this->assertEquals( $meta_value, $post->$meta_key );

		// Then It should equal the comfort post meta value
		wp_cache_flush();
		$this->assertEquals( $meta_value, $comfort_post->$meta_key );
	}

	public function testItIsFacadeForWPPost() {
		$posts = get_posts();
		$this->assertNotNull( $posts );

		$post = current( $posts );
		$this->assertInstanceOf( '\\WP_Post', $post );

		$comfort = new Simple_Post( $post->ID );

		$this->assertEquals( $post, $comfort->get_raw_post() );
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Post not found (ID=0)
	 */
	public function testItThrowsExceptionWhenPostIdIsIncorrect() {
		new Simple_Post( null );
	}

	public function test_it_creates_meta_object_when_class_exists() {
		$comfort = $this->create_simple_post();

		$this->assertInstanceOf( '\\PHPUnit\\Comfort\\Test_Doubles\\Simple_Post\\Simple_Meta', $comfort->simple );
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