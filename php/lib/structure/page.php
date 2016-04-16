<?php
/**
 * Common pages settings.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

// Remove unnecessary titles
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
remove_action( 'genesis_before_loop', 'genesis_do_search_title' );

add_action( 'genesis_after_header', 'cdn_page_header', 11 );
/**
 * Displays page header area with current page/taxonomy title.
 *
 * @since 1.0.0
 */
function cdn_page_header() {
	if ( is_home() || is_single() ) {
		return;
	}

	$title        = '';
	$title_before = '';
	$subtitle     = '';

	if ( is_category() ) {
		$title        = cdn_get_taxonomy_title();
		$title_before = 'Category:';
	}

	if ( is_page_template() ) {
		$title = strip_tags( get_the_title() );
	}

	if ( is_search() ) {
		$title        = get_search_query();
		$title_before = 'Phrase:';
		$subtitle     = 'Search results';
	}

	if ( is_404() ) {
		$title    = 'OOOPS, SORRY! WE COULDN\'T FIND IT';
		$subtitle = 'You have requested a page or file which doesn\'t exist';
	}

	cdn_do_page_header( $title, $subtitle, $title_before );
}

function cdn_do_page_header( $title, $subtitle = '', $title_before = '' ) {

	$title_output = sprintf( '<h1 %s>%s %s</h1>', genesis_attr( 'page-title' ), esc_html__($title_before, 'cutedecision'), esc_html__( $title, 'cutedecision' ) );

	if ( $subtitle ) {
		$subtitle_output = sprintf( '<h2 %s>%s</h2>', genesis_attr( 'page-subtitle' ), esc_html__( $subtitle, 'cutedecision' ) );
	}

	printf( '<header %s><div class="wrap">%s %s</div></header>', genesis_attr( 'page-header' ), $title_output, $subtitle_output );
}

/**
 * Customized `genesis_do_taxonomy_title_description` Genesis function.
 *
 * @since 1.0.0
 */
function cdn_get_taxonomy_title() {
	global $wp_query;

	$title_before = '';

	if ( ! is_category() && ! is_tag() && ! is_tax() && ! is_page() ) {
		return;
	}

	$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term ) {
		return;
	}

	if ( is_category() ) {
		$pre_title = __( 'Category: ', 'cutedecision' );
	}

	$title = get_term_meta( $term->term_id, 'headline', true );

	if ( ! $title ) {
		if ( genesis_a11y( 'headings' ) ) {
			$title = $title = $term->name;
		}
	}

	return $title;
}