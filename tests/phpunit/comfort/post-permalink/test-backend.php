<?php

namespace PHPUnit\Comfort\Post_Permalink;

use Brain\Monkey;
use PHPUnit\Comfort\TestCase;

class BackendTest extends TestCase {
	protected $backupComfortPermalinkBlogBase;
	protected $comfortPermalinkBlogBase;

	public function testItAddAnSettingsFieldToThePermalinks() {
		if ( ! did_action( 'load-options-permalink.php' ) ) {
			do_action( 'load-options-permalink.php' );
		}

		global $wp_settings_fields;

		$this->assertArrayHasKey( 'permalink', $wp_settings_fields );
		$this->assertArrayHasKey( 'optional', $wp_settings_fields['permalink'] );
		$this->assertArrayHasKey( 'comfort_permalink_blog_base', $wp_settings_fields['permalink']['optional'] );

		$setting = $wp_settings_fields['permalink']['optional']['comfort_permalink_blog_base'];

		$this->assertTrue( is_callable( $setting['callback'] ) );

		ob_start();
		$setting['callback']();

		$this->assertContains( 'comfort_permalink_blog_base', ob_get_clean() );
	}

	/**
	 * @backupGlobals
	 */
	public function testItChangesThePostPrefixOnSave() {
		$newValue                             = uniqid();
		$_POST['comfort_permalink_blog_base'] = $newValue;

		// assert it has the filter
		$this->assertEquals( 10, has_filter( 'load-options-permalink.php', 'comfort_admin_options_permalink' ) );

		comfort_admin_options_permalink();

		$this->assertEquals($newValue, get_option('comfort_permalink_blog_base'));
	}

	public function testTheSettingsFieldContainsTheCurrentBlogPrefix() {
		if ( ! did_action( 'load-options-permalink.php' ) ) {
			do_action( 'load-options-permalink.php' );
		}

		global $wp_settings_fields;

		$this->assertArrayHasKey( 'permalink', $wp_settings_fields );
		$this->assertArrayHasKey( 'optional', $wp_settings_fields['permalink'] );
		$this->assertArrayHasKey( 'comfort_permalink_blog_base', $wp_settings_fields['permalink']['optional'] );

		$setting = $wp_settings_fields['permalink']['optional']['comfort_permalink_blog_base'];

		$this->assertTrue( is_callable( $setting['callback'] ) );

		ob_start();
		$setting['callback']();

		$this->assertContains( 'value="' . esc_attr( $this->comfortPermalinkBlogBase ) . '"', ob_get_clean() );
	}

	protected function setUp() {
		parent::setUp();
		Monkey::setUp();

		require_once ABSPATH . '/wp-admin/includes/template.php';

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

