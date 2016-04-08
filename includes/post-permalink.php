<?php

/**
 * Show input field for additional permalink settings.
 */
function comfort_post_permalink_settings_handle() {
	echo '<input name="comfort_permalink_blog_base"
				id="comfort_permalink_blog_base"
				type="text"
				value="' . esc_attr( get_option( 'comfort_permalink_blog_base' ) ) . '"
				class="regular-text code">';
}

/**
 * Register additional permalink settings.
 */
function comfort_admin_options_permalink() {
	add_settings_field(
		'comfort_permalink_blog_base',
		__( 'Blog base' ),
		'comfort_post_permalink_settings_handle',
		'permalink',
		'optional',
		[
			'label_for' => 'comfort_permalink_blog_base'
		]
	);

	if ( ! isset( $_POST['comfort_permalink_blog_base'] ) ) {
		return;
	}

	update_option( 'comfort_permalink_blog_base', $_POST['comfort_permalink_blog_base'] );
}

add_action( 'load-options-permalink.php', 'comfort_admin_options_permalink' );

/**
 * Adds post base to the given rewrite rules.
 *
 * @param $rewrite_rules
 *
 * @return array
 */
function comfort_rewrite_rules_add_post_base( $rewrite_rules ) {
	$with_blog = array();

	$base = get_option( 'comfort_permalink_blog_base' );

	if ( ! $base ) {
		return $rewrite_rules;
	}

	foreach ( $rewrite_rules as $key => $value ) {
		$with_blog[ $base . '/' . $key ] = $value;
	}

	return $with_blog;
}

add_filter( 'post_rewrite_rules', 'comfort_rewrite_rules_add_post_base' );


function comfort_post_get_permalink( $permalink, $post ) {
	if ( $post->post_type != 'post' ) {
		return $permalink;
	}

	return '/' . get_option( 'comfort_permalink_blog_base' ) . $permalink;
}

add_filter( 'pre_post_link', 'comfort_post_get_permalink', 10, 2 );


function comfort_post_slug_init() {
	global $wp_post_types;

	$base = get_option( 'comfort_permalink_blog_base' );

	if ( ! $base ) {
		return;
	}

	$wp_post_types['post']->rewrite = array( 'slug' => $base );
}

add_action( 'init', 'comfort_post_slug_init' );
