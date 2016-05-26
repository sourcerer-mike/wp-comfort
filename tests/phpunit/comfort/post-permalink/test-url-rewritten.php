<?php

namespace PHPUnit\Comfort\Post_Permalink;

use Brain\Monkey;
use PHPUnit\Comfort\TestCase;

class UrlRewrittenTest extends TestCase {
	protected $backupComfortPermalinkBlogBase;
	protected $comfortPermalinkBlogBase;

	public function testItAddsThePrefixToRewriteRules() {
		$rewriteRules = apply_filters('post_rewrite_rules', [
			'foo' => 'bar',
			'baz' => 'bazz',
		]);

		$this->assertArrayHasKey($this->comfortPermalinkBlogBase . '/foo', $rewriteRules);
		$this->assertArrayHasKey($this->comfortPermalinkBlogBase . '/baz', $rewriteRules);
    }

	public function testItAddsNothingToRewriteRulesWhenBlogBaseIsNotSet() {
		delete_option('comfort_permalink_blog_base');

		$rewriteRules = apply_filters('post_rewrite_rules', [
			'foo' => 'bar',
			'baz' => 'bazz',
		]);

		$this->assertArrayHasKey('foo', $rewriteRules);
		$this->assertArrayNotHasKey($this->comfortPermalinkBlogBase . '/foo', $rewriteRules);

		$this->assertArrayHasKey('baz', $rewriteRules);
		$this->assertArrayNotHasKey($this->comfortPermalinkBlogBase . '/baz', $rewriteRules);
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

