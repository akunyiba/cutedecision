<?php
/**
 * Post weekly views counter.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_filter( 'cron_schedules', 'cdn_add_cron_intervals' );
/**
 * Add weekly and monthly intervals.
 *
 * @since 1.0.0
 */
function cdn_add_cron_intervals( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 604800,
		'display'  => __( 'Once Weekly' )
	);

//	$schedules['monthly'] = array(
//		'interval' => 2635200,
//		'display'  => __( 'Once a month' )
//	);

	return $schedules;
}

/**
 * Initialize `post_weekly_views` counting of a single post.
 *
 * @since 1.0.0
 */
function cdn_post_weekly_views_count_init( $post_id = null ) {
	if ( ! wp_next_scheduled( 'cdn_weekly_event' ) ) {
		wp_schedule_event( time(), 'weekly', 'cdn_weekly_event' );
	}

	cdn_post_view_count($post_id, 'post_weekly_views');
}

/**
 * Count post view and store it to db as post meta.
 *
 * Helper function.
 *
 * @since 1.0.0
 */
function cdn_post_view_count( $post_id, $meta_key_name ){
	$count     = get_post_meta( $post_id, $meta_key_name, true );

	if ( $count == '' ) {
		$count = 0;

		delete_post_meta( $post_id, $meta_key_name );
		add_post_meta( $post_id, $meta_key_name, '0' );
	} else {
		$count ++;

		update_post_meta( $post_id, $meta_key_name, $count );
	}
}


add_action( 'cdn_weekly_event', 'cdn_post_views_weekly_event' );
/**
 * WP-cron weekly event that resets `post_weekly_views` meta value of all posts.
 *
 * @since 1.0.0
 */
function cdn_post_views_weekly_event() {
	$posts = get_posts( array( 'posts_per_page' => - 1 ) );

	foreach ( $posts as $post ) {
		$count_key = 'post_weekly_views';
		delete_post_meta( $post->ID, $count_key );
		add_post_meta( $post->ID, $count_key, '0' );
	}
}