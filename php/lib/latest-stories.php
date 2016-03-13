<?php
/**
 * Latest posts.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Outputs latest posts area HTML markup.
 *
 * @since 1.0.0
 */
function cdn_do_latest_stories() {
	global $wp_query;

	$args = array(
		'posts_per_page' => 3
	);

	$latest_stories = $wp_query->query( $args );


	$html = '<aside class="latest-stories">';
	$html .= '<h2 class="latest-stories-title">' . __('Latest Stories', 'cutedecision') . '</h2>';

	foreach ( $latest_stories as $post ) {
		$html .= '<article class="latest-story">';

		if ( get_the_post_thumbnail( $post ) ) {
			$html .= '<figure class="entry-media">'
			         . '<a href="' . get_permalink( $post ) . '">' . $post->post_title . '</a>'
			         . '</figure>';
		}

		$html .= '<p>' . $post->post_title . '</p>';
		$html .= '</article>';
	}
	$html .= '</aside>';
	echo $html;

	//	$args     = array(
//		'posts_per_page'      => 3,
////		'ignore_sticky_posts' => true,
////		'meta_key'            => '_featured-post',
////		'meta_value'          => 1
//	);
//	$latest_stories = new WP_Query( $args );
}