<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;
use PHPUnit\Comfort\TestCase;

class Labels_Test extends Abstract_Post_Type_Test {
	public function testItCanRewordTheTitlePlaceholderInTheBackend() {
		$post_type = new Post_Type( 'locations' );

		$post_type->set_title_placeholder( 'Enter name of the city here' );

		$this->assertEquals( 'Enter name of the city here', apply_filters( 'enter_title_here', '' ) );
	}

	public function testItScaffoldsAllLabels() {
		$target = new Post_Type( 'locations' );

		$singular = 'city';
		$plural   = 'cities';

		$target->scaffold_labels( $singular, $plural );

		$this->assertContains( $singular, $target->labels );
		$this->assertContains( $plural, $target->labels );

		$this->markTestIncomplete( 'Check if labels are used by WordPress after registration' );
	}

	public function testItSetsPlaceholderWhenNotAlreadySet() {
		$post_type = new Post_Type( 'locations' );

		$this->assertNull( $this->getObjectAttribute( $post_type, '_title_placeholder' ) );

		$post_type->scaffold_labels( 'city', 'cities' );

		$this->assertTrue( is_callable( $this->getObjectAttribute( $post_type, '_title_placeholder' ) ) );
	}

	public function testMultipleTitlePlaceholderReplaceEachOther() {
		$post_type = new Post_Type( 'locations' );

		$filter_var = $this->getObjectAttribute( $post_type, '_title_placeholder' );
		$this->assertNull( $filter_var );

		$post_type->set_title_placeholder( 'Enter name of the city here' );

		$filter_var = $this->getObjectAttribute( $post_type, '_title_placeholder' );

		$post_type->set_title_placeholder( 'What is the city called?' );

		global $wp_filter;

		foreach ( $wp_filter['enter_title_here'][10] as $item ) {
			if ( $item['function'] == $filter_var ) {
				$this->fail( 'Filter is still in the WordPress universe. Expected it to be deleted' );
			}
		}

		$filter_var = $this->getObjectAttribute( $post_type, '_title_placeholder' );
		remove_filter( 'enter_title_here', $filter_var );
	}

	public function testSettingPlaceholderWontHarmOtherPostTypes() {

		$post_type = new Post_Type( 'locations' );
		$post_type->set_title_placeholder( 'Do not harm others' );

		$tmp = $GLOBALS['current_screen']->post_type;

		global $current_screen;

		$current_screen->post_type = 'foo';

		$this->assertNotEquals( 'Do not harm others', apply_filters( 'enter_title_here', '' ) );

		$GLOBALS['current_screen']->post_type = $tmp;
	}
}