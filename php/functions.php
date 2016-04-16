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

add_action( 'genesis_setup', 'cdn_theme_setup' ); // TODO: Priority was 15
/**
 * Theme setup.
 *
 * Attach all of the site-wide functions to the correct hooks and filters. All
 * the functions themselves are placed in the corresponding files which are included
 * below this setup function.
 *
 * @since 1.0.0
 */
function cdn_theme_setup() {
	// Define theme constants
	define( 'CHILD_THEME_NAME', 'cutedecision' );
	define( 'CHILD_THEME_URL', 'http://github.com/akunyiba/cutedecision' );
	define( 'CHILD_THEME_VERSION', '1.0.0' );

	// Dependencies
	require_once( get_stylesheet_directory() . '/lib/classes/share.count.php' );
	include_once( get_stylesheet_directory() . '/lib/functions/post-views-count.php' );
	include_once( get_stylesheet_directory() . '/lib/functions/post-shares-count.php' );
	include_once( get_stylesheet_directory() . '/lib/functions/post-weekly-views-count.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/menu.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/footer.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/loops.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/markup.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/sidebar.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/post.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/page.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/archive.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/template.php' );
	include_once( get_stylesheet_directory() . '/lib/structure/search.php' );
	include_once( get_stylesheet_directory() . '/lib/widgets/widgets.php' );
	include_once( get_stylesheet_directory() . '/lib/widgets/widget-areas.php' );

	// Load child theme text domain
	load_child_theme_textdomain( 'cutedecision' );

	// Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form' ) );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// Add theme support for accessibility
	add_theme_support( 'genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	) );

	// Add theme support for footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

	// Display author box on single posts
	add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );

	// Remove support for structural wraps
	remove_theme_support( 'genesis-structural-wraps' );

	// Remove Genesis SEO Settings menu link
	remove_theme_support( 'genesis-seo-settings-menu' );

	// Remove Genesis in-post Layout Settings
	remove_theme_support( 'genesis-inpost-layouts' );

	// Remove Genesis in-post SEO Settings
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

	// Disable default pagination
	remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

	// Disable entry footer
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

	// Deactivate unused sidebars
	unregister_sidebar( 'sidebar-alt' );
	unregister_sidebar( 'header-right' );

	// Deactivate layouts that are using secondary sidebar
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content' );
	genesis_unregister_layout( 'full-width-content' );

	// Custom thumbnail sizes
	add_image_size( 'cdn-thumb-featured', 200, 110, true );
	add_image_size( 'cdn-thumb-lpht', 360, 150, true );
	add_image_size( 'cdn-thumb', 370, 210, true );
	add_image_size( 'cdn-thumb-latest', 750, 420, true );

	remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
}


add_action( 'widgets_init', 'unregister_genesis_widgets', 20 );
/**
 * Unregister Genesis widgets.
 *
 * @since 1.0.0
 */
function unregister_genesis_widgets() {
//	unregister_widget( 'Genesis_eNews_Updates' );
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_Latest_Tweets_Widget' );
	unregister_widget( 'Genesis_Menu_Pages_Widget' );
	unregister_widget( 'Genesis_User_Profile_Widget' );
	unregister_widget( 'Genesis_Widget_Menu_Categories' );
}


add_action( 'wp_enqueue_scripts', 'cdn_enqueue_scripts' );
/**
 * Load JS project files and vendor libraries.
 *
 * @since 1.0.0
 */
function cdn_enqueue_scripts() {
	$localized = array(
			'templateDir' => get_stylesheet_directory_uri(),
			'ajaxUrl'     => admin_url( 'admin-ajax.php' )
	);

	// Disable the superfish script
	wp_deregister_script( 'superfish' ); // TODO: Скрипт все равно подгружается...
	wp_deregister_script( 'superfish-args' );

//	wp_register_script( 'libs-js', get_stylesheet_directory_uri() . '/js/libs.concat.js', '', '', true );
	wp_register_script( 'main-js', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), '', true );

//	wp_enqueue_script( 'libs-js' );
	wp_enqueue_script( 'main-js' );

	wp_localize_script( 'main-js', 'localized', $localized );
}