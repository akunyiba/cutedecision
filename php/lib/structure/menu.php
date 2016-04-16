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

add_action( 'genesis_setup', 'cdn_register_nav_menus', 11 );
/**
 * Register custom menu locations.
 *
 * @since 1.0.0
 */
function cdn_register_nav_menus() {
	register_nav_menu( 'footer', __( 'Footer Navigation Menu', 'cutedecision' ) );
}


add_action( 'genesis_setup', 'cdn_create_subnav_menu_data', 11 ); // TODO: Возможно, захардкодить это меню (subnav)
/**
 * Create theme secondary navigation data (menu in admin).
 *
 * @since 1.0.0
 */
function cdn_create_subnav_menu_data() {
	// Registering Quick Nav custom menu
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
			'menu-item-url'     => home_url( '/' ),
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


remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'cdn_do_nav' );
/**
 * Main site navigation.
 *
 * Outputs HTML markup with responsive menu, follow and search toggle buttons.
 *
 * @since 1.0.0
 */
function cdn_do_nav() {

	// TODO: Данные из настроек темы
	$follow_links = array(
		'facebook'    => 'https://www.facebook.com/cutedecision/',
		'twitter'     => 'https://www.facebook.com/cutedecision/',
		'google-plus' => 'https://www.facebook.com/cutedecision/',
		'pinterest'   => 'https://www.facebook.com/cutedecision/',
		'instagram'   => 'https://www.facebook.com/cutedecision/'
	);

	$class = 'menu menu-primary';

//	if ( genesis_superfish_enabled() ) {
//		$class .= ' js-superfish';
//	}

	$nav = genesis_get_nav_menu( array(
		'theme_location' => 'primary',
		'menu_class'     => $class,
		'menu_id'        => 'nav-primary-menu',
	) );

	if ( genesis_a11y( 'headings' ) ) {
		printf( '<h2 class="screen-reader-text">%s</h2>', __( 'Main navigation', 'cutedecision' ) );
	}

	$navbar = '<div class="navbar">';
	$navbar .= '<div class="navbar-wrap">';
	$navbar .= '<div class="navbar-toggle toggle-menu">';
	$navbar .= '<a class="toggle-link burger" href="#" aria-hidden="true">';
	$navbar .= '<i class="icon icon-ion-navicon-round"></i>';
	$navbar .= '</a>';
	$navbar .= '</div>';

	$navbar .= $nav;

	$navbar .= '<div class="navbar-toggle toggle-follow">';
	$navbar .= '<a class="toggle-link" href="#" aria-hidden="true">';
	$navbar .= '<i class="icon icon-share"></i>';
	$navbar .= '</a>';
	$navbar .= '<div class="toggle-content content-follow">';
	$navbar .= '<ul class="follow-items">';

	foreach ( $follow_links as $key => $value ) {
		$navbar .= '<li class="follow-item item-' . $key . '"><a class="follow-link" href="' . $value . '" target="_blank"><i class="icon icon-' . $key . '"></i></a></li>';
	}

	$navbar .= '</ul>';
	$navbar .= '</div>';
	$navbar .= '</div>';
	$navbar .= '<div class="navbar-toggle toggle-search">';
	$navbar .= '<a class="toggle-link" href="#" aria-hidden="true">';
	$navbar .= '<i class="icon icon-search"></i>';
	$navbar .= '<span class="navbar-toggle-arrow"></span>';
	$navbar .= '</a>';
	$navbar .= '<div class="toggle-content content-search">' . genesis_search_form() . '</div>';
	$navbar .= '</div>';
	$navbar .= '</div>';
	$navbar .= '</div>';

	echo $navbar;
}

add_filter( 'wp_nav_menu_items', 'cdn_add_primary_menu_extras', 10, 2 );
/**
 * Filter menu items, appending a today's date in human-readable format.
 *
 * @since 1.0.0
 */
function cdn_add_primary_menu_extras( $menu, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $menu;
	}

	$menu .= '<li class="menu-close"><a class="menu-close-link" href="#"><i class="icon icon-android-close"></i></a></li>';

	return $menu;
}


remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'cdn_do_subnav' );
/**
 * Outputs secondary navigation menu.
 *
 * @since 1.0.0
 */
function cdn_do_subnav() {
	if ( ! genesis_nav_menu_supported( 'secondary' ) ) {
		return;
	}

	genesis_nav_menu( array(
		'theme_location' => 'secondary',
		'menu'           => 'CDN Quick Navigation', // TODO: Not sure whether it actually works?
		'menu_class'     => 'menu menu-secondary',
		'menu_id'        => 'genesis-nav-secondary'
	) );
}