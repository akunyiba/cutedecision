<?php
/**
 * Theme customizations.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_action( 'genesis_setup', 'cutedecision_setup', 15 );
/**
 * Theme setup
 *
 * Attach all of the site-wide functions to the correct hooks and filters. All
 * the functions themselves are defined below this setup function.
 *
 * @since 1.0.0
 */
function cutedecision_setup() {

	//* Define theme constants
	define( 'CHILD_THEME_NAME', 'cutedecision' );
	define( 'CHILD_THEME_URL', 'http://github.com/akunyiba/cutedecision' );
	define( 'CHILD_THEME_VERSION', '1.0.0' );

	//* Dependencies
	include_once( get_stylesheet_directory() . '/lib/loops.php' );
	include_once( get_stylesheet_directory() . '/lib/post.php' );
	include_once( get_stylesheet_directory() . '/lib/menu.php' );
	include_once( get_stylesheet_directory() . '/lib/search.php' );
	include_once( get_stylesheet_directory() . '/lib/inpost-metaboxes.php' );
	include_once( get_stylesheet_directory() . '/lib/featured-stories.php' );
	include_once( get_stylesheet_directory() . '/lib/latest-stories.php' );
	include_once( get_stylesheet_directory() . '/lib/widgetize.php' );
	include_once( get_stylesheet_directory() . '/lib/widgets/widgets.php' );

	//* Load child theme text domain
	load_child_theme_textdomain( 'cutedecision' );

	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form' ) );

	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	//* Add theme support for accessibility
	add_theme_support( 'genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	) );

	//* Add theme support for footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

	//* Remove Genesis SEO Settings menu link
	remove_theme_support( 'genesis-seo-settings-menu' );

	//* Remove Genesis in-post Layout Settings
	remove_theme_support( 'genesis-inpost-layouts' );

	//* Remove Genesis in-post SEO Settings
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

	//* Remove the entry meta in the entry footer (requires HTML5 theme support)
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

	//* Deactivate unused sidebars
	unregister_sidebar( 'sidebar-alt' );
	unregister_sidebar( 'header-right' );
	unregister_widget( 'Genesis_eNews_Updates' );
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_Latest_Tweets_Widget' );
	unregister_widget( 'Genesis_Menu_Pages_Widget' );
	unregister_widget( 'Genesis_User_Profile_Widget' );
	unregister_widget( 'Genesis_Widget_Menu_Categories' );

	//* Deactivate layouts that are using secondary sidebar
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content' );
	genesis_unregister_layout( 'full-width-content' );

	//* Custom entry meta in the entry header (requires HTML5 theme support)
	add_filter( 'genesis_post_info', 'cdn_change_post_info' );

	//* Theme navbar (Main navigation)
	add_filter( 'genesis_do_nav', 'cdn_do_navbar' );

	//* Theme custom search form input box text
	add_filter( 'genesis_search_text', 'cdn_change_search_text' );

	//* Theme custom loop
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_loop', 'cdn_do_loop' );

	//* Theme custom secondary navigation menu
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );
	add_action( 'genesis_header', 'cdn_do_quick_nav' );

	//* Theme custom post image (requires HTML5 theme support)
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header', 'cdn_do_post_image', 4 );

	//* Theme in-post meta boxes
	add_action( 'add_meta_boxes', 'cdn_add_meta_boxes' );
	add_action( 'save_post', 'cdn_save_meta_boxes', 10, 3 );

	//* Display latest posts area
	add_action( 'genesis_after_header', 'cdn_do_latest_stories', 12 );

	//* Display theme widget areas
	add_action( 'genesis_after_header', 'cdn_do_widget_before_content', 11);

	//* Display featured posts area
	add_action( 'genesis_after_header', 'cdn_do_featured_stories' );

	//* Custom thumbnail sizes
	add_image_size( 'featured-size', 200, 100, true );

	//* Deactivate secondary navigation menu
	add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

	//* Create theme quick navigation menu
	cdn_create_quick_menu_with_data();

	//* Register theme widget areas
	cdn_register_widget_areas();

	//* Disable entry footer
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
}


add_action( 'wp_enqueue_scripts', 'cdn_enqueue_scripts' );
/**
 * Load JavaScript project files and libraries
 *
 * @since 1.0.0
 */
function cdn_enqueue_scripts() {
	wp_register_script( 'libs-js', get_stylesheet_directory_uri() . '/js/libs.concat.js', '', '', true );
	wp_register_script( 'main-js', get_stylesheet_directory_uri() . '/js/main.js', '', '', true );

	wp_enqueue_script( 'libs-js' );
	wp_enqueue_script( 'main-js' );

	// Array of localized data that is used in JS
	$wp_localized = array( 'template_dir' => get_stylesheet_directory_uri() );
	wp_localize_script( 'main-js', 'localized', $wp_localized );
}