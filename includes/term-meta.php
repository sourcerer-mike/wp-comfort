<?php

global $wpdb;

$wpdb->termmeta = $wpdb->prefix . 'termmeta';

if ( ! function_exists( 'get_term_meta' ) ) {
	/**
	 * Retrieve term meta field for a term.
	 *
	 * @since 1.1.0
	 *
	 * @param int    $term_id  Term ID.
	 * @param string $meta_key The meta key to retrieve.
	 *                         By default, returns data for all keys.
	 * @param null   $single   Whether to return a single value. Default false.
	 *
	 * @return mixed Will be an array if $single is false.
	 *               Will be value of meta data field if $single is true.
	 */
	function get_term_meta( $term_id, $meta_key = '', $single = null ) {
		return get_metadata( 'term', $term_id, $meta_key, $single );
	}
}

if ( ! function_exists( 'update_term_meta' ) ) {
	/**
	 * Update term meta field based on term ID.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and term ID.
	 *
	 * If the meta field for the term does not exist, it will be added.
	 *
	 * @since 1.1.0
	 *
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 *                           Must be serializable if non-scalar.
	 * @param mixed  $prev_value Previous value to check before removing.
	 *
	 * @return bool|int Meta ID if the key didn't exist, true on successful update, false on failure.
	 *
	 */
	function update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		return update_metadata(
			'term',
			$term_id,
			$meta_key,
			$meta_value,
			$prev_value
		);
	}
}

if ( ! function_exists( 'delete_term_meta' ) ) {
	/**
	 * Remove metadata matching criteria from a term.
	 *
	 * You can match based on the key, or key and value.
	 * Removing based on key and value, will keep from removing duplicate metadata with the same key.
	 * It also allows removing all metadata matching key, if needed.
	 *
	 * @since 1.1.0
	 *
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata name.
	 * @param mixed  $meta_value Optional. Metadata value. Must be serializable if
	 *                           non-scalar. Default empty.
	 *
	 * @return bool True on success, false on failure.
	 */
	function delete_term_meta( $term_id, $meta_key, $meta_value = '', $delete_all = null ) {
		return delete_metadata(
			'term',
			$term_id,
			$meta_key,
			$meta_value,
			$delete_all
		);
	}
}