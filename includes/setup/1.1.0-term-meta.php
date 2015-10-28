<?php
/**
 * Create table for term metadata.
 */

function comfort_setup_term_meta() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$type = 'term';

	/** @var wpdb $wpdb */
	global $wpdb;

	$table_name = $wpdb->prefix . $type . 'meta';

	$charset_collate = '';
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}
	if ( ! empty( $wpdb->collate ) ) {
		$charset_collate .= " COLLATE {$wpdb->collate}";
	}

	$sql = "CREATE TABLE {$table_name} (
        meta_id bigint(20) NOT NULL AUTO_INCREMENT,
        {$type}_id bigint(20) NOT NULL default 0,

        meta_key varchar(255) DEFAULT NULL,
        meta_value longtext DEFAULT NULL,

        UNIQUE KEY meta_id (meta_id)
    ) {$charset_collate};";

	/** @var \wpdb $wpdb */
	global $wpdb;

	$wpdb->show_errors(false);
	dbDelta( $sql );
	$wpdb->show_errors();
}

return "comfort_setup_term_meta";