<?php
/**
 * Contains Abstract_Post class.
 *
 * LICENSE: This source file is subject to the MIT license
 * that is available through the world-wide-web at the following URI:
 * https://opensource.org/licenses/MIT. If you did not receive a copy
 * of the PHP License and are unable to obtain it through the web, please send
 * a note to pretzlaw@gmail.com so we can mail you a copy immediately.
 *
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2016 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/wp-comfort/LICENSE.md MIT License
 * @link      http://github.com/sourcerer-mike/wp-comfort
 */

namespace Comfort\Post_Type;

/**
 * Abstract definition for post entities.
 *
 * LICENSE: This source file is subject to the MIT license
 * that is available through the world-wide-web at the following URI:
 * https://opensource.org/licenses/MIT. If you did not receive a copy
 * of the PHP License and are unable to obtain it through the web, please send
 * a note to pretzlaw@gmail.com so we can mail you a copy immediately.
 *
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2016 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/wp-comfort/LICENSE.md MIT License
 * @link      http://github.com/sourcerer-mike/wp-comfort
 */
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

			return $this->_buffer[ $key ];
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