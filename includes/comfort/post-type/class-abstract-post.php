<?php

namespace Comfort\Post_Type;

abstract class Abstract_Post {
	protected $_buffer;
	protected $_facade;

	public function __construct( $post_id ) {
		$this->_facade = get_post( $post_id );

		if ( ! $this->_facade || is_wp_error( $this->_facade ) ) {
			throw new \Exception(
				sprintf(
					'Post not found (ID=%d)',
					$post_id
				)
			);
		}
	}

	public function __get( $key ) {
		if ( isset( $this->_buffer[ $key ] ) ) {
			return $this->_buffer[ $key ];
		}

		$meta_wrapper = get_class( $this ) . '\\' . ucfirst( $key ) . '_Meta';
		if ( class_exists( $meta_wrapper ) ) {
			$this->_buffer[ $key ] = new $meta_wrapper( $this->get_raw_post()->ID, $key );
		}

		return $this->get_raw_post()->$key;
	}

	/**
	 * @return \WP_Post
	 */
	public function get_raw_post() {
		return $this->_facade;
	}
}