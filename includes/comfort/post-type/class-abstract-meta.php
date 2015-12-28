<?php

namespace Comfort\Post_Type;

abstract class Abstract_Meta {

	protected $_facade;

	protected $_prefix;

	public function __construct( $post_id, $prefix = null ) {
		$this->_facade = get_post( $post_id );

		if ( ! $this->_facade ) {
			throw new \Exception(
				sprintf(
					'Post not found (ID=%d)',
					$post_id
				)
			);
		}

		if ( null === $prefix ) {
			$prefix = current(array_slice( explode( '\\', get_class( $this ) ), - 1 ));
			$prefix = strtolower( $prefix );
			$prefix = preg_replace( '/_meta$/', '', $prefix );
		}

		$this->_prefix = $prefix;
	}

	public function __get( $key ) {
		$key = $this->get_prefix() . '_' . $key;

		return get_post_meta( $this->get_facade()->ID, $key );
	}

	public function __set( $key, $value ) {
		$key = $this->get_prefix() . '_' . $key;

		return update_post_meta( $this->get_facade()->ID, $key, $value );
	}

	public function get_prefix() {
		return $this->_prefix;
	}

	public function get_facade() {
		return $this->_facade;
	}

	function __unset( $key ) {
		delete_post_meta( $this->get_facade()->ID, $this->get_prefix() . '_' . $key );
	}
}