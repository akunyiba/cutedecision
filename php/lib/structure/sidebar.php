<?php
/**
 * Sidebar settings.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_action( 'get_header', 'cdn_adjust_sidebar' );
/**
 * Adjust sidebar for different pages.
 *
 * @since 1.0.0
 */
function cdn_adjust_sidebar() {
	if ( is_page_template() || is_search() ) {
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		add_action( 'genesis_sidebar', 'cdn_do_lpht_sidebar' );
	}
	else if( is_category() || is_404() ) {
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
	}
}


/**
 * Displays LPTH (Latest, Popular, Hot, Trending) sidebar.
 *
 * @since 1.0.0
 */
function cdn_do_lpht_sidebar() {
	dynamic_sidebar( 'cdn-lpht-sidebar' );
}