<?php
/**
 * Post customizations.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_action( 'genesis_entry_header', 'cdn_do_post_info_before_title' );
function cdn_do_post_info_before_title() {
	if ( is_single() ) {
		$args = array(
			'shares'     => 0,
			'comments'   => 0,
			'categories' => 2,
			'wrap'       => 0
		);

		echo cdn_make_post_info( null, $args );
	} else if ( is_search() ) {
		$args = array( 'views' => 1 );

		echo cdn_make_post_info( null, $args );
	} else {
		echo cdn_make_post_info();
	}
}

function cdn_make_post_info( $post_id = null, $args = '' ) {
	$defaults = array(
		'shares'     => 1,
		'comments'   => 1,
		'categories' => 1,
		'views'      => 0,
		'wrap'       => 1
	);
	$args     = wp_parse_args( $args, $defaults );

	$post_id ? '' : $post_id = get_the_ID(); // TODO: Тонкий момент, может быть в других местах
	$categories    = get_the_category( $post_id );
	$post_category = $categories[0]->cat_name;
	$post_views    = get_post_meta( $post_id, 'post_views', true );
	$post_shares   = pssc_all();
	$post_comments = get_comments_number( $post_id );
	$output        = '';

	if ( $args['wrap'] ) { // TODO: Возможно, зарефакторить
		$output .= '<div class="entry-info">';
	}
	if ( $args['shares'] ) {
		$output .= '<p class="entry-shares-count"><i class="icon icon-share"></i> ' . $post_shares . ' Shares</p>';
	}
	if ( $args['views'] ) {
		$output .= '<p class="entry-views-count"><i class="icon icon-ion-eye"></i> ' . $post_views . ' Views</p>';
	}
	if ( $args['comments'] ) {
		$output .= '<p class="entry-comments-count"><i class="icon icon-chatbubble"></i> ' . $post_comments . '</p>';
	}
	if ( $args['categories'] == 1 ) {
		$output .= '<p class="entry-category">' . $post_category . '</p>';
	}
	if ( $args['categories'] == 2 ) {
		$output .= '<p class="entry-categories">';

		foreach ( $categories as $key => $category ) {
			$output .= '<a href="' . get_category_link( $category->term_id ) . '" class="entry-category">' . $category->cat_name . '</a>';
		}

		$output .= '</p>';
	}
	if ( $args['wrap'] ) {
		$output .= '</div>';
	}

	return $output;
}

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_header', 'cdn_do_post_title' );
/**
 * Outputs post title after entry header.
 *
 * @since 1.0.0
 */
function cdn_do_post_title() {
	echo cdn_make_post_title();
}

/**
 * Make the title of a post by ID.
 *
 * Alternative to genesis_do_post_title(). Helper function that can output the title by post ID.
 *
 * @since 1.0.0
 *
 * @uses genesis_html5()          Check for HTML5 support.
 * @uses genesis_get_SEO_option() Get SEO setting value.
 * @uses genesis_markup()         Contextual markup.
 *
 * @param int|WP_Post $post_id Post ID or WP_Post object. Default is null (will be used global $post).
 *
 * @return null Return early if the length of the title string is zero.
 */
function cdn_make_post_title( $post_id = null ) {
	$title = apply_filters( 'genesis_post_title_text', get_the_title( $post_id ) );

	if ( 0 === mb_strlen( $title ) ) {
		return;
	}

	//* Link it
	$title = sprintf( '<a href="%s" rel="bookmark">%s</a>', get_permalink( $post_id ), $title );

	//* Wrap in H1 on singular pages
	$wrap = is_singular() ? 'h1' : 'h2';

	//* Also, if HTML5 with semantic headings, wrap in H1
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	/**
	 * Entry title wrapping element
	 *
	 * The wrapping element for the entry title.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'genesis_entry_title_wrap', $wrap );

	//* Build the output
	$output = genesis_markup( array(
		'html5'   => "<{$wrap} %s>",
		'xhtml'   => sprintf( '<%s class="entry-title">%s</%s>', $wrap, $title, $wrap ),
		'context' => 'entry-title',
		'echo'    => false,
	) );

	$output .= genesis_html5() ? "{$title}</{$wrap}>" : '';

	$output = apply_filters( 'genesis_post_title_output', "$output \n" );

	return $output;
}

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'cdn_do_post_meta', 12 );
function cdn_do_post_meta() {
	if ( is_single() ) {
		echo cdn_make_post_meta( array( 'views_and_comments' => 1 ) );
	} else if ( is_category() || is_tag() || is_tax() || is_search() ) {
		echo cdn_make_post_meta( array( 'avatar' => 30 ) );
	} else {
		echo cdn_make_post_meta( array( 'avatar' => 0 ) );
	}
}

/**
 * Echo the post meta markup.
 *
 * Helper function to output entry meta elements markup.
 *
 * @since 1.0.0
 *
 * @param array $args State of the elements - avatar, views_and_comments. By default both are set to true.
 */
function cdn_make_post_meta( $args = '' ) {
	$defaults = array(
		'avatar'             => 40,
		'views_and_comments' => 0
	);
	$args     = wp_parse_args( $args, $defaults );

	$date_time     = genesis_post_date_shortcode( array( 'format' => 'relative', 'label' => 'about ' ) );
	$author        = get_the_author();
	$author_url    = get_author_posts_url( get_the_author_meta( 'ID' ) );
	$post_comments = get_comments_number();
	$post_views    = cdn_get_post_views( get_the_ID() );

	$output = genesis_markup( array(
		'html5'   => '<p %s>',
		'xhtml'   => '<div class="post-info">',
		'context' => 'entry-meta-before-content',
		'echo'    => false,
	) );

	if ( $args['views_and_comments'] ) {
		$output .= '<span class="wrap">';
	}

	$output .= sprintf( '<span %s>', genesis_attr( 'entry-author' ) );
	$output .= '<span class="entry-author-label">by </span>';
	$output .= sprintf( '<a href="%s" %s>', $author_url, genesis_attr( 'entry-author-link' ) );

	if ( $args['avatar'] ) {
		$output .= get_avatar( get_the_author_meta( 'email' ), $args['avatar'] );
	}

	$output .= sprintf( '<span %s>', genesis_attr( 'entry-author-name' ) );
	$output .= esc_html( $author );
	$output .= '</a></span><br>';
	$output .= $date_time;

	if ( $args['views_and_comments'] ) {
		$output .= '</span>';
		$output .= '<span class="wrap">'; // TODO: Добавить функционал flag, как в оригинале
		$output .= '<span class="entry-views-count"><i class="icon icon-ion-eye"></i>' . $post_views . '<span class="entry-views-count-label"> Views</span></span>';
		$output .= '<span class="entry-comments-count"><i class="icon icon-chatbubble"></i> ' . $post_comments . '</span>';
		$output .= '</span>';
	}

	$output .= genesis_html5() ? '</p>' : '</div>';

	return $output;
}


add_action( 'genesis_before_entry_content', 'cdn_do_single_post_thumb' );
/**
 * Outputs post thumbnail (if present) on single post pages.
 *
 * @since 1.0.0
 */
function cdn_do_single_post_thumb() {
	if ( is_single() && has_post_thumbnail() ) {
		$thumb_args = array('attr'    => genesis_parse_attr( 'entry-thumb', array( 'alt' => get_the_title() ) ));

		printf( '<figure class="entry-media">%s</figure>', genesis_get_image( $thumb_args ) );
	}
}


remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'cdn_do_entry_media', 4 );
/**
 * Custom post thumbnail with figure markup and additional content.
 *
 * @since 1.0.0
 */
function cdn_do_entry_media() {
	if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
		echo cdn_make_entry_media();
	}
}


/**
 * Echo the post entry media image markup.
 *
 * Helper function to output entry media element with its elements.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post_id Post ID or WP_Post object. Default is null (will be used global $post).
 * @param array $args State of the elements (additional meta absolutely positioned on image) - shares, comments, category. By default all three are set to true.
 */
function cdn_make_entry_media( $post_id = null, $args = '' ) {
	$defaults = array(
		'number' => '',
		'badge'  => 'flag',
		'size'   => 'cdn-thumb'
	);
	$args     = wp_parse_args( $args, $defaults );

	$post_url   = get_permalink( $post_id );
	$post_image = genesis_get_image( array(
		'post_id' => $post_id,
		'size'    => $args['size'],
		'attr'    => genesis_parse_attr( 'entry-thumb', array( 'alt' => get_the_title( $post_id ) ) ),
	) );

	if ( empty( $post_image ) ) {
		$post_image = '<img src="' . get_stylesheet_directory_uri() . '/images/thumb-default.jpg">';
	}

	$output = genesis_markup( array(
		'html5'   => '<figure %s>',
		'xhtml'   => '<div>',
		'context' => 'entry-media',
		'echo'    => 0
	) );

	if ( $args['badge'] ) {
		if ( $args['badge'] == 'flag' ) {
			$output .= '<div class="entry-badge badge-flag"><i class="icon icon-fire"></i></div>'; // TODO: Динамический flag на основании рейтинга (latest, views, comments, social sharing)
		}
		if ( $args['badge'] == 'number' ) {
			$output .= '<div class="entry-badge badge-number"></div>';
		}
	}

	$output .= sprintf( '<a href="%s" aria-hidden="true">%s</a>', $post_url, $post_image );

	$output .= genesis_markup( array(
		'html5'   => '</figure>',
		'xhtml'   => '<div>',
		'context' => 'entry-media',
		'echo'    => 0
	) );

	return $output;
}


add_action( 'genesis_after_entry_content', 'cdn_prev_next_post_nav', 11 );
/**
 * Display links to previous and next post, from a single post.
 *
 * @since 1.0.0
 *
 * @return null Return early if not a post.
 */
function cdn_prev_next_post_nav() {

	if ( ! is_singular( 'post' ) ) {
		return;
	}

	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div class="navigation">',
		'context' => 'adjacent-entry-pagination',
	) );

	echo '<div class="pagination-previous">';
	echo '<strong>Previous article</strong>';
	previous_post_link( '%link' );
	echo '</div>';

	echo '<div class="pagination-next">';
	echo '<strong>Next article</strong>';
	next_post_link( '%link' );
	echo '</div>';

	echo '</div>';
}


add_filter( 'genesis_author_box_title', 'cdn_author_box_title' );
/**
 * Customize the author box title.
 *
 * @since 1.0.0
 */
function cdn_author_box_title() {
	$author_name = get_the_author();

	return '<span class="author-title-label">Written by </span><strong>' . $author_name . '</strong>';
}


add_filter( 'genesis_author_box_gravatar_size', 'cdn_author_box_gravatar_size' );
/**
 * Customize the author box gravatar size.
 *
 * @since 1.0.0
 */
function cdn_author_box_gravatar_size( $size ) {
	return '40';
}


/**
 * Outputs shortcode of Easy Social Share plugin.
 *
 * @since 1.0.0
 */
function cdn_do_social_share_buttons() {
	echo do_shortcode( '[easy-social-share buttons="facebook,pinterest,more,twitter,google,mail" morebutton="1" morebutton_icon="plus" counters=1 counter_pos="hidden" total_counter_pos="leftbig" style="button" template="fancy-retina"]' );
}


add_action( 'genesis_entry_header', 'cdn_do_post_excerpt', 11 );
function cdn_do_post_excerpt() {
	if ( is_search() ) {
//		the_excerpt();
		printf( '<p class="entry-excerpt">%s</p>', esc_html( get_the_excerpt() ) );
	}
}