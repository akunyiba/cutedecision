<?php
/**
 * Widget areas.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_action( 'genesis_setup', 'cdn_register_widget_areas', 11 );
/**
 * Hook the callback that registers theme widget areas.
 *
 * @since 1.0.0
 */
function cdn_register_widget_areas() {
	// Home, Single -- Before Content Ad
	genesis_register_widget_area( array(
		'id'          => 'cdn-home-after-header-widget',
		'name'        => __( 'Home After Header Ad', 'cutedecision' ),
		'description' => __( 'Horizontal ad banner. Placed before the content, at the top of the page.', 'cutedecision' ),
	) );

	// Home -- Inside Content Ad
	genesis_register_widget_area( array(
			'id'          => 'cdn-home-inside-loop-widget',
			'name'        => __( 'Home Inside Loop Ad', 'cutedecision' ),
			'description' => __( 'Ad banner... Placed...', 'cutedecision' ) // TODO: Заполнить description
	) );

	// Single -- After Content Ad
	genesis_register_widget_area( array(
			'id'          => 'cdn-single-after-loop-widget',
			'name'        => __( 'Single After Content Ad', 'cutedecision' ),
			'description' => __( 'Ad banner... Placed...', 'cutedecision' )
	) );

	// Taxonomy -- Inside loop widget
	genesis_register_widget_area( array(
			'id'          => 'cdn-taxonomy-inside-loop-widget',
			'name'        => __( 'Taxonomy Inside Loop', 'cutedecision' ),
			'description' => __( 'Ad banner... Placed...', 'cutedecision' )
	) );

	// LPHT Sidebar
	genesis_register_sidebar( array(
			'id'          => 'cdn-lpht-sidebar',
			'name'        => __( 'LPHT Sidebar', 'cutedecision' ),
			'description' => __( 'This is a the sidebar displayed on Latest, Popular, Hot, Trending taxonomy pages.', 'cutedecision' )
	) );
}


add_action( 'genesis_after_header', 'cdn_do_home_after_header_widget', 11 );
/**
 * Outputs HTML markup of Ad widget before content.
 *
 * @since 1.0.0
 */
function cdn_do_home_after_header_widget() {
	if ( ! is_home() ) {
		return;
	}

	genesis_widget_area( 'cdn-home-after-header-widget', array(
			'before' => '<aside class="widget-area home-after-header-widget">' . genesis_sidebar_title( 'cdn-home-after-header-widget' ),
			'after'  => '</aside>',
	) );
}


add_action( 'genesis_home_inside_loop_widget_area', 'cdn_do_home_inside_loop_widget');
/**
 * Outputs HTML markup of Ad widget before content.
 *
 * @since 1.0.0
 */
function cdn_do_home_inside_loop_widget() {
	genesis_widget_area( 'cdn-home-inside-loop-widget', array(
			'before' => '<aside class="widget-area inside-loop-widget">' . genesis_sidebar_title( 'cdn-home-inside-loop-widget' ),
			'after'  => '</aside>',
	) );
}

//function cdn_inside_loop_widget_init(){
//	global $wp_query;
//
//
//	else {
//		return;
//	}
//}


add_action( 'genesis_after_entry_content', 'cdn_do_single_after_loop_widget' );
/**
 * Outputs HTML markup of Single After Content Ad widget.
 *
 * @since 1.0.0
 */
function cdn_do_single_after_loop_widget() {
	if ( ! is_home() ) {
		return;
	}

	genesis_widget_area( 'cdn-single-after-loop-widget', array(
			'before' => '<aside class="widget-area single-after-loop-widget">' . genesis_sidebar_title( 'cdn-single-after-loop-widget' ),
			'after'  => '</aside>',
	) );
}


add_action( 'genesis_taxonomy_inside_loop_widget_area', 'cdn_do_taxonomy_inside_loop_widget');
/**
 * Outputs HTML markup of Ad widget inside loop on taxonomy pages.
 *
 * @since 1.0.0
 */
function cdn_do_taxonomy_inside_loop_widget() {
	genesis_widget_area( 'cdn-taxonomy-inside-loop-widget', array(
			'before' => '<aside class="widget-area inside-loop-widget">' . genesis_sidebar_title( 'cdn-taxonomy-inside-loop-widget' ),
			'after'  => '</aside>',
	) );
}

