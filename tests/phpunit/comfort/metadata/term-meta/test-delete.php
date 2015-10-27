<?php

namespace Comfort\Metadata\Term_Meta;

use Comfort\TestCase;

class Delete_Test extends TestCase {
	protected $meta_key;
	protected $meta_value;
	protected $object_type = 'post';
	protected $taxonomy    = 'unique_tax';
	protected $term        = 'unique_term';
	protected $term_id;
	protected $term_taxonomy_id;

	/**
	 * Stub taxonomy, term and some metadata.
	 */
	protected function setUp() {
		$this->taxonomy = uniqid( 'comfort_test_' );
		$this->term     = uniqid( 'comfort_test_' );

		register_taxonomy( $this->taxonomy, 'post' );
		$data = wp_insert_term( $this->term, $this->taxonomy );

		if ( $data instanceof \WP_Error ) {
			throw new \Exception( $data->get_error_message() );
		}

		$this->term_id          = $data['term_id'];
		$this->term_taxonomy_id = $data['term_taxonomy_id'];

		$this->meta_key   = uniqid( 'comfort_test_' );
		$this->meta_value = uniqid();

		update_term_meta( $this->term_id, $this->meta_key, $this->meta_value );
	}

	protected function tearDown() {
		wp_delete_term( $this->term_id, $this->taxonomy );
		unregister_taxonomy_for_object_type( $this->taxonomy, $this->object_type );
	}

	public function testItDeletesExistingData() {
		$this->assertEquals( get_term_meta( $this->term_id, $this->meta_key, true ), $this->meta_value );

		delete_term_meta($this->term_id, $this->meta_key);

		$this->assertEquals( get_term_meta( $this->term_id, $this->meta_key, true ), null );
	}


}