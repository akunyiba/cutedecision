<?php
/**
 * Custom loops.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_action( 'pre_get_posts', 'cdn_home_pre_get_posts' );
/**
 * Home page main loop offset.
 *
 * The first three entries are displayed in the 'cdn_home_latest_stories_loop'. So the main loop needs an offset.
 * Alter the main query before the actual query is run.
 *
 * @since 1.0.0
 */
function cdn_home_pre_get_posts( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$posts_per_page = get_option( 'posts_per_page' );
		$stickies       = get_option( 'sticky_posts' );
		$offset         = 3;

		$query->set( 'post__not_in', $stickies );

		if ( $query->is_paged ) {
			$page_offset = $offset + ( ( $query->query_vars['paged'] - 1 ) * $posts_per_page );
			$query->set( 'offset', $page_offset );
		} else {
			$query->set( 'offset', $offset );
		}
	}
}


add_filter( 'found_posts', 'cdn_adjust_offset_pagination', 1, 2 );
/**
 * Reduce WordPress's found_posts count by the offset on home page.
 *
 * @since 1.0.0
 */
function cdn_adjust_offset_pagination( $found_posts, $query ) {
	$offset = 3;

	if ( $query->is_home() && $query->is_main_query() ) {
		return $found_posts - $offset;
	}

	return $found_posts;
}


remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'cdn_do_loop' );
/**
 * Customized 'genesis_do_loop' Genesis function.
 *
 * Is removed: 'genesis_entry_content' hook and markup around it. 'genesis_before_while', 'genesis_before_entry',
 * 'genesis_before_entry_content', 'genesis_after_entry_content', 'genesis_entry_footer'
 *
 * Is added: 'wp_localize_script' with '$localized_load_more' array of localized data
 * that holds settings for ajax call via JS. // TODO: продолжить...
 *
 * @since 1.0.0
 */
function cdn_do_loop() {
	global $wp_query;

	if ( is_home() ) {
		cdn_do_loop_header( 'More stories' );
	}

	if ( have_posts() ) {
		$localized_load_more = array(
			'action' => 'cdn_home_loop_ajax_load_more',
			'nonce'  => wp_create_nonce( 'cdn_ajax_load_more_nonce' ),
			'query'  => ''
		);

		wp_localize_script( 'main-js', 'localizedLoadMore', $localized_load_more );

		while ( have_posts() ) {
			the_post();

			printf( '<article %s>', genesis_attr( 'entry' ) );
			do_action( 'genesis_entry_header' );
			do_action( 'genesis_before_entry_content' );

			if ( is_single() ) {
				cdn_post_views_init( get_the_ID() );
				cdn_post_shares_init( get_the_ID() );
				cdn_post_weekly_views_count_init( get_the_ID() );

				printf( '<div %s>', genesis_attr( 'entry-content' ) );
				cdn_do_social_share_buttons();
				do_action( 'genesis_entry_content' );
				cdn_do_social_share_buttons();
				echo '</div>';
			}

			echo '</article>';
			do_action( 'genesis_after_entry' );

			if ( is_category() ) {
				if ( $wp_query->current_post == 1 ) {
					do_action( 'genesis_taxonomy_inside_loop_widget_area' );
				}
			}

			if ( is_home() ) {
				if ( $wp_query->current_post == 3 ) {
					do_action( 'genesis_home_inside_loop_widget_area' );
				}
			}

			if ( $wp_query->current_post + 1 == $wp_query->found_posts || is_search()) {
				remove_action( 'genesis_after_loop', 'cdn_do_ajax_load_more_button' );
			}
		}
	}
}


add_action( 'genesis_after_header', 'cdn_do_featured_stories_loop', 11 );
/**
 * Outputs featured stories area's markup on home and single pages.
 *
 * @since 1.0.0
 */
function cdn_do_featured_stories_loop() {
	if ( ! is_home() && ! is_single() ) {
		return;
	}

	$posts_per_page = 6;
	$stickies       = get_option( 'sticky_posts' );

	if ( ! $stickies || empty( $stickies ) ) {
		return;
	}

	$stickies = array_slice( $stickies, 0, $posts_per_page );

	$query_args = array(
		'posts_per_page'         => 6,
		'post__in'               => $stickies,
		'ignore_sticky_posts'    => 1,
		'no_found_rows'          => 1,
		'update_post_term_cache' => 0,
		'update_post_meta_cache' => 0
	);

	$thumb_args = array(
		'size' => 'cdn-thumb-featured',
		'attr' => genesis_parse_attr( 'entry-thumb' )
	);

	$featured_stories = get_posts( $query_args );

	if ( $featured_stories ) {
		echo '<aside class="featured-stories">';
		echo '<a class="featured-stories-arrow arrow-prev" href="#">Previous</a>';
		echo '<a class="featured-stories-arrow arrow-next" href="#">Next</a>';
		echo '<ul class="featured-stories-list">';

		foreach ( $featured_stories as $post ) {
			$entry_media = cdn_make_entry_media( $post->ID, array( 'badge' => 0, 'size' => 'cdn-thumb-featured' ), 0 );
			$post_title  = cdn_make_post_title( $post->ID );

			echo '<li class="featured-story">';
			printf( '<article %s>', genesis_attr( 'entry' ) );
			echo $entry_media;
			printf( '<header %s>', genesis_attr( 'entry-header' ) );
			echo $post_title;
			echo '</header>';
			echo '</article>';
		}
		echo '</aside>';
	}
}


add_action( 'genesis_after_header', 'cdn_home_latest_stories_loop', 12 );
/**
 * Outputs latest stories area's markup on home, category, tag, taxonomy and single pages.
 *
 * @since 1.0.0
 */
function cdn_home_latest_stories_loop() {
	if ( ! is_home() && ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}

	$stickies         = get_option( 'sticky_posts' );
	$query_args       = array(
		'post__not_in'           => $stickies,
		'posts_per_page'         => 3,
		'ignore_sticky_posts'    => 1,
		'no_found_rows'          => 1,
		'update_post_term_cache' => 0,
		'update_post_meta_cache' => 0
	);
	$entry_media_args = array(
		'badge' => 0,
		'size'  => 'cdn-thumb-latest'
	);
	$post_info_args   = array(
		'comments'   => 0,
		'categories' => 0
	);

	$latest_stories = get_posts( $query_args );

	if ( $latest_stories ) {
		echo '<aside class="latest-stories">';
		echo '<h2 class="latest-stories-title">' . __( 'Latest Stories', 'cutedecision' ) . '</h2>';

		foreach ( $latest_stories as $post ) {
			$post_title  = cdn_make_post_title( $post->ID );
			$post_info   = cdn_make_post_info( $post->ID, $post_info_args );
			$entry_media = cdn_make_entry_media( $post->ID, $entry_media_args );

			printf( '<article %s>', genesis_attr( 'entry' ) );
			echo $entry_media;
			printf( '<header %s>', genesis_attr( 'entry-header' ) );
			echo $post_info . $post_title;
			echo '</header>';
			echo '</article>';
		}
		echo '</aside>';
	}
}


add_action( 'genesis_after_entry', 'cdn_do_single_related_posts_loop', 8 );
/**
 * Outputs related posts area's markup after content on a single page.
 *
 * Matches posts by tags.
 *
 * @since 1.0.0
 */
function cdn_do_single_related_posts_loop() {
	if ( ! is_single() ) {
		return;
	}

	$id             = get_the_ID();
	$tags           = wp_get_post_tags( $id );
	$post_info_args = array( 'comments' => 0 );

	if ( $tags ) {
		$tags_arr = '';

		foreach ( $tags as $tag ) {
			$tags_arr .= $tag->slug . ',';
		}

		$query_args = array(
			'tag'          => $tags_arr,
			'numberposts'  => 4,
			'post__not_in' => array( $id )
		);

		$related_posts = get_posts( $query_args );

		if ( $related_posts ) {
			echo '<aside class="related-stories">';
			cdn_do_loop_header( 'You may also like' );

			foreach ( $related_posts as $post ) {
				$entry_media = cdn_make_entry_media( $post->ID );
				$post_title  = cdn_make_post_title( $post->ID );
				$post_info   = cdn_make_post_info( $post->ID, $post_info_args );

				printf( '<article %s>', genesis_attr( 'entry' ) );
				echo $entry_media;
				printf( '<header %s>', genesis_attr( 'entry-header' ) );
				echo $post_info . $post_title;
				echo '</header>';
				echo '</article>';
			}
			echo '</aside>';
		}
	}
}


add_action( 'genesis_after_entry', 'cdn_do_single_more_from_category_loop', 8 );
/**
 * Outputs more from category posts area's markup after related posts on a single page.
 *
 * @since 1.0.0
 */
function cdn_do_single_more_from_category_loop() {
	if ( ! is_single() ) {
		return;
	}

	$id             = get_the_ID();
	$tags           = wp_get_post_tags();
	$categories     = get_the_category();
	$category_id    = $categories[0]->cat_ID;
	$category_name  = $categories[0]->cat_name;
	$category_link  = get_category_link( $category_id );
	$post_info_args = array(
		'comments'   => 0,
		'categories' => 0
	);
	$post_meta_args = array( 'avatar' => 30 );
	$query_args     = array(
		'posts_per_page' => 4,
		'post__not_in'   => array( $id ),
		'cat'            => $category_id
	);

	$more_from_category = new WP_Query( $query_args );

	if ( $more_from_category->have_posts() ) {
		echo '<aside class="more-from-category">';
		cdn_do_loop_header( 'More from: ', '<a href="' . $category_link . '">' . $category_name . '</a>');

		while ( $more_from_category->have_posts() ) {
			$more_from_category->the_post();

			$entry_media = cdn_make_entry_media();
			$post_info   = cdn_make_post_info( null, $post_info_args );
			$post_title  = cdn_make_post_title();
			$post_meta   = cdn_make_post_meta( $post_meta_args );

			printf( '<article %s>', genesis_attr( 'entry' ) );
			echo $entry_media;
			printf( '<header %s>', genesis_attr( 'entry-header' ) );
			echo $post_info . $post_title . $post_meta;
			echo '</header>';
			echo '</article>';
		}
		echo '</aside>';
	}
}


add_action( 'wp_ajax_cdn_home_loop_ajax_load_more', 'cdn_home_loop_ajax_load_more' );
add_action( 'wp_ajax_nopriv_cdn_home_loop_ajax_load_more', 'cdn_home_loop_ajax_load_more' );
/**
 * AJAX Load More
 *
 * @since 1.0.0
 */
function cdn_home_loop_ajax_load_more() {
	check_ajax_referer( 'cdn_ajax_load_more_nonce', 'nonce' );

	$offset         = 3;
	$stickies       = get_option( 'sticky_posts' );
	$posts_per_page = get_option( 'posts_per_page' );
	$end_of_loop    = false;

	$args                   = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
	$args['post_type']      = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
	$args['post_status']    = isset( $args['post_status'] ) ? esc_attr( $args['post_status'] ) : 'publish';
	$args['paged']          = esc_attr( $_POST['page'] );
	$args['posts_per_page'] = $posts_per_page;
	$args['post__not_in']   = $stickies;

	if ( $args['paged'] > 1 ) {
		$page_offset    = $offset + ( ( $args['paged'] - 1 ) * $args['posts_per_page'] );
		$args['offset'] = $page_offset;
	}

	// Darn you, WordPress! Gotta output buffer.
	ob_start();
	$loop = new WP_Query( $args );

	if ( $loop->found_posts - $args['offset'] <= $posts_per_page ) {
		$end_of_loop = true;
	}

	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) {
			$loop->the_post();

			printf( '<article %s>', genesis_attr( 'entry-added' ) );
			do_action( 'genesis_entry_header' );
			echo '</article>';
		}
	}
	wp_reset_postdata();

	$content = ob_get_clean();
	$data    = array( 'html' => $content, 'end' => $end_of_loop );

	wp_send_json_success( $data );
	wp_die();
}


add_action( 'wp_ajax_cdn_template_loop_ajax_load_more', 'cdn_template_loop_ajax_load_more' );
add_action( 'wp_ajax_nopriv_cdn_template_loop_ajax_load_more', 'cdn_template_loop_ajax_load_more' );
/**
 * AJAX Load More
 *
 * @since 1.0.0
 */
function cdn_template_loop_ajax_load_more() {
	check_ajax_referer( 'cdn_ajax_load_more_nonce', 'nonce' );

	$args                   = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
	$args['post_type']      = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
	$args['paged']          = esc_attr( $_POST['page'] );
	$args['posts_per_page'] = isset( $args['posts_per_page'] ) ? esc_attr( $args['posts_per_page'] ) : get_option( 'posts_per_page' );
	$end_of_loop            = false;

	// Darn you, WordPress! Gotta output buffer.
	ob_start();
	$loop = new WP_Query( $args );

	if ( $args['paged'] == $loop->max_num_pages ) {
		$end_of_loop = true;
	}

	cdn_do_template_loop( $loop );

	wp_reset_postdata();
	$content = ob_get_clean();
	$data    = array( 'html' => $content, 'end' => $end_of_loop );

	wp_send_json_success( $data );
	wp_die();
}


add_action( 'genesis_after_loop', 'cdn_do_ajax_load_more_button' );
/**
 * AJAX Load More Button
 *
 * @since 1.0.0
 */
function cdn_do_ajax_load_more_button() {
	echo '<div class="load-more">';
	echo '<a class="load-more-button" href="#">' . __( 'Load More', 'cutedecision' ) . '</a>';
	echo '<i class="icon icon-load"></i>';
	echo '</div>';
}


/**
 * Outputs LPHT (Latest, Popular, Hot, Trending) loop.
 *
 * @since 1.0.0
 */
function cdn_do_lpht_loop( $args = '' ) {
	$defaults = array(
		'posts_per_page'      => 6,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'meta_key'            => 'post_views',
		'orderby'             => 'meta_value_num',
		'ignore_sticky_posts' => 1,
		'paged'               => 1
	);
	$args     = wp_parse_args( $args, $defaults );

	$lpht_loop = new WP_Query( $args );

	$localized_load_more = array(
		'action' => 'cdn_template_loop_ajax_load_more',
		'nonce'  => wp_create_nonce( 'cdn_ajax_load_more_nonce' ),
		'query'  => $lpht_loop->query
	);

	wp_localize_script( 'main-js', 'localizedLoadMore', $localized_load_more );

	cdn_do_template_loop( $lpht_loop );
}


/**
 * Outputs common template loop.
 *
 * @since 1.0.0
 */
function cdn_do_template_loop( $query ) {
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$entry_media = cdn_make_entry_media( null, array(
				'size'  => 'cdn-thumb-lpht',
				'badge' => 'number'
			) );
			$post_title  = cdn_make_post_title();

			printf( '<article %s>', genesis_attr( 'entry' ) );
			echo $entry_media;
			printf( '<header %s>', genesis_attr( 'entry-header' ) );
			echo $post_title;
			echo '</header>';
			echo '</article>';
		}
	}
}


/**
 * Outputs loop header area with loop title.
 *
 * Helper function.
 *
 * @since 1.0.0
 */
function cdn_do_loop_header( $title, $after = '' ) {
	echo '<header class="loop-header">';
	echo '<h2 class="loop-title">';
	echo __( $title, 'cutedecision' ) . $after;
	echo '</h2>';
	echo '</header>';
}