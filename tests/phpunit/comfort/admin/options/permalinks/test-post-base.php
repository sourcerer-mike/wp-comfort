<?php

namespace PHPUnit\Comfort\Admin\Options\Permalinks;

use PHPUnit\Comfort\TestCase;

/**
 * Class Post_Base
 *
 * @package PHPUnit\Comfort\Admin\Options\Permalinks
 */
class Post_Base extends TestCase {
	protected $old_comfort_permalink_blog_base;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		require_once ABSPATH . '/wp-admin/includes/template.php';

		do_action( 'load-options-permalink.php' );
	}

	public function testItAddsTheBaseToPostLinks() {
		return;
		$post = new \WP_Post( new \stdClass() );

		$post = get_posts();
		$post = $post[0];

		$permalink = get_permalink( $post );
		$this->assertTrue(
			0 === strpos( $permalink, get_site_url() . '/veryunique/' ),
			'Permalink ' . $permalink . ' is wrong'
		);
	}

	public function testItHasPostBaseOption() {
		return;
		global $wp_settings_fields;

		$this->assertArrayHasKey( 'permalink', $wp_settings_fields );
		$this->assertArrayHasKey( 'optional', $wp_settings_fields['permalink'] );
		$this->assertArrayHasKey( 'comfort_permalink_blog_base', $wp_settings_fields['permalink']['optional'] );
	}

	public function testItStoresPostBaseAsOption() {
		$_POST['comfort_permalink_blog_base'] = uniqid();

		comfort_admin_options_permalink();

		\WP_Mock::expectAction( 'update_option' );

		$this->assertEquals( $_POST['comfort_permalink_blog_base'], get_option( 'comfort_permalink_blog_base' ) );

		unset( $_POST['comfort_permalink_blog_base'] );
	}

	protected function setUp() {
		parent::setUp();

		\WP_Mock::setUp();

		$this->setBackupGlobals( true );

		$this->old_comfort_permalink_blog_base = get_option( 'comfort_permalink_blog_base' );

		update_option( 'comfort_permalink_blog_base', 'veryunique' );
	}

	protected function tearDown() {
		parent::tearDown();

		\WP_Mock::tearDown();

		update_option( 'comfort_permalink_blog_base', $this->old_comfort_permalink_blog_base );
	}
}