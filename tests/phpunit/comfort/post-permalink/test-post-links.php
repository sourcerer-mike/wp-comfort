<?php

namespace PHPUnit\Comfort\Post_Permalink;

use Brain\Monkey;
use PHPUnit\Comfort\TestCase;

class PostLinksTest extends TestCase {
	protected $backupComfortPermalinkBlogBase;
	protected $comfortPermalinkBlogBase;

	public function testItAddsThePrefixToPostUrls() {
		$somePosts = get_posts();

		$this->assertNotEmpty( $somePosts );

		$randomPost = $somePosts[ array_rand( $somePosts, 1 ) ];

		$result = apply_filters( 'pre_post_link', '/%postname%/', $randomPost, false );

		$this->assertEquals( '/' . $this->comfortPermalinkBlogBase . '/%postname%/', $result );
	}

	public function testItDoesNotRewriteOtherPostTypes() {
		$somePosts = get_posts();

		$this->assertNotEmpty( $somePosts );
		$this->assertNotEmpty( get_option( 'comfort_permalink_blog_base' ) );

		$randomPost            = $somePosts[ array_rand( $somePosts, 1 ) ];
		$randomPost->post_type = uniqid();

		$result = apply_filters( 'pre_post_link', '/%postname%/', $randomPost, false );

		$this->assertEquals( '/%postname%/', $result );
	}

	public function testItWontAddThePrefixToPostUrlsWhenItsEmpty() {
		delete_option( 'comfort_permalink_blog_base' );

		$somePosts = get_posts();

		$this->assertNotEmpty( $somePosts );

		$randomPost = $somePosts[ array_rand( $somePosts, 1 ) ];

		$result = apply_filters( 'pre_post_link', '/%postname%/', $randomPost, false );

		$this->assertEquals( '/%postname%/', $result );
	}

	public function testItAddsASlugForPosts(  ) {
		global $wp_post_types;

		comfort_post_slug_init();

		$this->assertEquals($this->comfortPermalinkBlogBase, $wp_post_types['post']->rewrite['slug']);
	}

	public function testItDoesNotAddsASlugForPostsWhenBaseIsEmpty(  ) {
		global $wp_post_types;

		$wp_post_types['post']->rewrite['slug'] = null;
		delete_option( 'comfort_permalink_blog_base' );

		comfort_post_slug_init();

		$this->assertEmpty($wp_post_types['post']->rewrite['slug']);
	}

	protected function setUp() {
		parent::setUp();
		Monkey::setUp();

		$this->backupComfortPermalinkBlogBase = get_option( 'comfort_permalink_blog_base' );

		$this->comfortPermalinkBlogBase = uniqid();
		update_option( 'comfort_permalink_blog_base', $this->comfortPermalinkBlogBase );
	}

	protected function tearDown() {
		update_option( 'comfort_permalink_blog_base', $this->backupComfortPermalinkBlogBase );

		comfort_post_slug_init();

		Monkey::tearDown();
		parent::tearDown();
	}
}

