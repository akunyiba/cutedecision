<?php
/**
 * Custom menus.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Create quick navigation menu.
 *
 * @since 1.0.0
 */
function cdn_create_quick_menu_with_data() {
	//* Registering Quick Nav custom menu
	$menu_name       = __( 'CDN Quick Navigation', 'cutedecision' );
	$menu_exists     = wp_get_nav_menu_object( $menu_name );
	$menu_item_class = 'menu-item';

	// If it doesn't exist, let's create it
	if ( ! $menu_exists ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		// Set up default menu items
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'   => __( 'Latest', 'cutedecision' ),
			'menu-item-classes' => $menu_item_class . ' ' . $menu_item_class . '-latest',
			'menu-item-url'     => home_url( '/latest/' ),
			'menu-item-status'  => 'publish'
		) );
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'   => __( 'Popular', 'cutedecision' ),
			'menu-item-classes' => $menu_item_class . ' ' . $menu_item_class . '-popular',
			'menu-item-url'     => home_url( '/popular/' ),
			'menu-item-status'  => 'publish'
		) );
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'   => __( 'Hot', 'cutedecision' ),
			'menu-item-classes' => $menu_item_class . ' ' . $menu_item_class . '-hot',
			'menu-item-url'     => home_url( '/hot/' ),
			'menu-item-status'  => 'publish'
		) );
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'   => __( 'Trending', 'cutedecision' ),
			'menu-item-classes' => $menu_item_class . ' ' . $menu_item_class . '-trending',
			'menu-item-url'     => home_url( '/trending/' ),
			'menu-item-status'  => 'publish'
		) );
	}
}


/**
 * Output quick navigation.
 *
 * @since 1.0.0
 */
function cdn_do_quick_nav() {
	wp_nav_menu( array(
		'menu'            => 'CDN Quick Navigation',
		'container'       => 'nav',
		'container_class' => 'quick-nav'
	) );
}


/**
 * Main site navigation.
 *
 * It outputs HTML markup with responsive menu, follow and search toggle buttons.
 *
 * @since 1.0.0
 */
function cdn_do_navbar( $nav ) {

	// TODO: Данные из настроек темы
	$follow_links = array(
		'facebook'    => 'https://www.facebook.com/cutedecision/',
		'twitter'     => 'https://www.facebook.com/cutedecision/',
		'google-plus' => 'https://www.facebook.com/cutedecision/',
		'pinterest'   => 'https://www.facebook.com/cutedecision/',
		'instagram'   => 'https://www.facebook.com/cutedecision/'
	);

	$navbar = '<div class="navbar">'
	            . '<div class="navbar-toggle toggle-menu">'
	            . '<a class="toggle-link hamburger-link" href="javascript:void(0);">'
	            . '<i class="hamburger-icon"></i>'
	            . '</a>'
	            . '</div>';

	$navbar .= $nav;

	$navbar .= '<div class="navbar-toggle toggle-follow">'
	             . '<a class="toggle-link" href="javascript:void(0);">'
	             . '<i class="icon icon-share"></i>'
	             . '<span class="navbar-toggle-arrow"></span>'
	             . '</a>'
	             . '<div class="toggle-content content-follow">'
	             . '<ul class="follow-items">';

	foreach ( $follow_links as $key => $value ) {
		$navbar .= '<li class="follow-item item-' . $key . '"><a class="follow-link" href="' . $value . '" target="_blank"><i class="icon icon-' . $key . '"></i></a></li>';
	}

	$navbar .= '</ul>'
	             . '</div>'
	             . '</div>'
	             . '<div class="navbar-toggle toggle-search">'
	             . '<a class="toggle-link" href="javascript:void(0);">'
	             . '<i class="icon icon-search"></i>'
	             . '<span class="navbar-toggle-arrow"></span>'
	             . '</a>'
	             . '<div class="toggle-content content-search">' . genesis_search_form() . '</div>'
	             . '</div>'
	             . '</div>';

	return $navbar;
}