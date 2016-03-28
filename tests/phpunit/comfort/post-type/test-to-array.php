<?php

namespace PHPUnit\Comfort\Post_Type;

use Comfort\Post_Type;

class To_Array_Test extends Abstract_Post_Type_Test {
	public function testCanBeTurnedToWordPressPostArray() {
		$post_type = $this->newTarget();

		$post_type->public      = true;
		$post_type->has_archive = true;

		$this->assertEquals(
			[
				'public'      => true,
				'has_archive' => true,
			],
			$post_type->to_array()
		);
	}

	public function testNewPostTypesHaveNoSettings() {
		$this->assertEmpty( $this->newTarget()->to_array() );
	}
}