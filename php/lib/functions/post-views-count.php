<?php
/**
 * Count post views.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Return a number of posts.
 *
 * @since 1.0.0
 */
function cdn_get_post_views( $post_id = null ) {
	$count_key = 'post_views';
	$count     = get_post_meta( $post_id, $count_key, true );

	if ( $count == '' ) {
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );

		return '0';
	}

	return $count;
}

/**
 * Count views.
 *
 * @since 1.0.0
 */
function cdn_post_views_init( $post_id = null ) {
	$count_key = 'post_views';
	$count     = get_post_meta( $post_id, $count_key, true );

	if ( $count == '' ) {
		$count = 0;

		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
	} else {
		$count ++;

		update_post_meta( $post_id, $count_key, $count );
	}
}
