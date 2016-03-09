<?php

/**
 * Theme customizations
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

load_child_theme_textdomain( 'cutedecision' );

add_action( 'wp_enqueue_scripts', 'cdn_enqueue_scripts' );
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

	//* Dependencies
	include_once( get_stylesheet_directory() . '/inc/widget-areas.php' );

	//* Define theme constants
	define( 'CHILD_THEME_NAME', 'cutedecision' );
	define( 'CHILD_THEME_URL', 'http://github.com/akunyiba/cutedecision' );
	define( 'CHILD_THEME_VERSION', '1.0.0' );

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

	//* Remove Genesis SEO Settings menu link
	remove_theme_support( 'genesis-seo-settings-menu' );

	//* Remove Genesis in-post SEO Settings
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

	//* Remove Genesis in-post Layout Settings
	remove_theme_support( 'genesis-inpost-layouts' );

	//* Remove the entry meta in the entry footer (requires HTML5 theme support)
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

	//* Custom entry title tag
	add_filter( 'genesis_post_title_text', 'cdn_post_title_wrap' );

	//* Custom entry meta in the entry header (requires HTML5 theme support)
	add_filter( 'genesis_post_info', 'cdn_post_info_filter' );

	//* Custom navigation
	add_filter( 'genesis_do_nav', 'cdn_do_nav' );

	//* Custom search form input box text
	add_filter( 'genesis_search_text', 'cdn_search_text' );

	//* Replace the standard loop with custom loop
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_loop', 'cdn_do_loop' );

	//* Custom secondary navigation menu
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );
	add_action( 'genesis_header', 'cdn_do_quick_nav' );

	//* Custom post image (requires HTML5 theme support)
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header', 'cdn_do_post_image', 4 );

	//* Custom meta boxes
	add_action( 'add_meta_boxes', 'cdn_meta_box_featured' );
	add_action( 'save_post', 'cdn_save_featured_meta_box', 10, 3 );

	//* Featured posts area
	add_action( 'genesis_before_content_sidebar_wrap', 'cdn_featured_posts_area' );

	//* Custom thumbnail sizes
	add_image_size( 'featured-size', 200, 96, true );

	//* Deactivate secondary navigation menu
	add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

	//* Create custom navigation menu
	cdn_create_nav_menu();

	//* Disable entry footer
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
}

/**
 * Standard genesis loop without 'genesis_entry_content' hook call and markup around it.
 *
 * @since 1.1.0
 */
function cdn_do_loop() {

	//* Use old loop hook structure if not supporting HTML5
	if ( ! genesis_html5() ) {
		genesis_legacy_loop();
		return;
	}

	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		while ( have_posts() ) : the_post();

			do_action( 'genesis_before_entry' );

			printf( '<article %s>', genesis_attr( 'entry' ) );

			do_action( 'genesis_entry_header' );

			do_action( 'genesis_before_entry_content' );

//			printf( '<div %s>', genesis_attr( 'entry-content' ) );
//			do_action( 'genesis_entry_content' );
//			echo '</div>';

			do_action( 'genesis_after_entry_content' );

			do_action( 'genesis_entry_footer' );

			echo '</article>';

			do_action( 'genesis_after_entry' );

		endwhile; //* end of one post
		do_action( 'genesis_after_endwhile' );

	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop

}

/**
 * Create custom navigation menu
 *
 * @since 1.0.0
 */
function cdn_create_nav_menu() {
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

/**
 * Custom entry meta in the entry header with "human time format" date.
 *
 * @since 1.0.0
 */
function cdn_post_info_filter() {
	$human_time = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago';
	$post_info  = 'by [post_author_posts_link] <br>about ' . $human_time . ' [post_comments] [post_edit]';

	return $post_info;
}

/**
 * Custom search form input box text.
 *
 * @since 1.0.0
 */
function cdn_search_text( $text ) {
	return esc_attr( 'Search...' );
}

/**
 * Custom search form input box text.
 *
 * @since 1.0.0
 */
function cdn_do_nav( $nav ) {

	// TODO: Данные из настроек темы
	$follow_links = array(
			'facebook'    => 'https://www.facebook.com/cutedecision/',
			'twitter'     => 'https://www.facebook.com/cutedecision/',
			'google-plus' => 'https://www.facebook.com/cutedecision/',
			'pinterest'   => 'https://www.facebook.com/cutedecision/',
			'instagram'   => 'https://www.facebook.com/cutedecision/'
	);

	$site_nav = '<div class="site-nav">'
	            . '<div class="site-nav-hamburger">'
	            . '<a class="site-nav-hamburger-link">'
	            . '<i class="site-nav-hamburger-icon"></i>'
	            . '</a>'
	            . '</div>';

	$site_nav .= $nav;

	$site_nav .= '<div class="site-nav-follow">'
	             . '<a class="site-nav-follow-link"><i class="icon icon-share"></i><span class="site-nav-arrow"></span></a>'
	             . '<div class="site-nav-follow-content">'
	             . '<ul class="social">';

	foreach ( $follow_links as $key => $value ) {
		$site_nav .= '<li class="social-item social-item-' . $key . '"><a class="social-item-link" href="' . $value . '" target="_blank"><i class="icon icon-' . $key . '"></i></a></li>';
	}

	$site_nav .= '</ul>'
	             . '</div>'
	             . '</div>'
	             . '<div class="site-nav-search">'
	             . '<a class="site-nav-search-link"><i class="icon icon-search"></i><span class="site-nav-arrow"></span></a>'
	             . '<div class="site-nav-search-content">' . genesis_search_form() . '</div>'
	             . '</div>'
	             . '</div>';

	return $site_nav;
}

/**
 * Custom search form input box text.
 *
 * @since 1.0.0
 */
function cdn_post_title_wrap( $title ) {
	$wrap = apply_filters( 'genesis_entry_title_wrap', 'h3' );

	//* Build the output
	$output = genesis_markup( array(
			'html5'   => "<{$wrap} %s>",
			'xhtml'   => sprintf( '<%s class="entry-title">%s</%s>', $wrap, $title, $wrap ),
			'context' => 'entry-title',
			'echo'    => false,
	) );

	$output .= genesis_html5() ? "{$title}</{$wrap}>" : '';

	echo apply_filters( 'genesis_post_title_output', "$output \n" );
}

/**
 * Custom post thumbnail with figure markup and additional content.
 *
 * @since 1.0.0
 */
function cdn_do_post_image() {
	if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
		$img = genesis_get_image( array(
				'format'  => 'html',
				'size'    => '100%',
				'context' => 'archive',
				'attr'    => genesis_parse_attr( 'entry-thumb', array( 'alt' => get_the_title() ) ),
		) );

		if ( ! empty( $img ) ) {
			$entry_img = '<figure class="entry-thumb">'
			             . '<figcaption class="entry-thumb-flag"><i class="icon icon-fire"></i></figcaption>' // TODO: Динамический flag на основании рейтинга (latest, views, comments, social sharing)
			             . sprintf( '<a href="%s" aria-hidden="true">%s</a>', get_permalink(), $img )
			             . '<figcaption class="entry-thumb-meta">' // TODO: Динамические данные (shares, comments, category)
			             . '<div class="entry-thumb-meta-shares"><i class="icon icon-share"></i> 271 Shares</div>'
			             . '<div class="entry-thumb-meta-comments"><i class="icon icon-chatbubble"></i> 11</div>'
			             . '<div class="entry-thumb-meta-category">WTF</div>'
			             . '</figcaption>'
			             . '</figure>';
			echo $entry_img;
		}
	}
}

/**
 * ...
 *
 * @since 1.0.0
 */
function cdn_featured_meta_box_markup( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'cdn-post-nonce' );
	$featured = get_post_meta( $post->ID, '_featured-post', true );
	echo '<label for="featured-post">' . __( 'Feature this post? ', 'cutedecision' ) . '</label>';
	echo '<input type="checkbox" name="featured-post" id="featured-post" value="1" ' . checked( 1, $featured, false ) . ' />';
}

/**
 * ...
 *
 * @since 1.0.0
 */
function cdn_meta_box_featured() {
	add_meta_box( "featured-post", "Featured Options", "cdn_featured_meta_box_markup", "post", "normal", "high", null );
}

/**
 * ...
 *
 * @since 1.0.0
 */
function cdn_save_featured_meta_box( $post_id, $post, $update ) {
	if ( ! isset( $_POST["cdn-post-nonce"] ) || ! wp_verify_nonce( $_POST["cdn-post-nonce"], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	if ( ! current_user_can( "edit_post", $post_id ) ) {
		return $post_id;
	}

	if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	$slug = "post";
	if ( $slug != $post->post_type ) {
		return $post_id;
	}

	$featured_meta_box_value = "";

	if ( isset( $_POST["featured-post"] ) ) {
		$featured_meta_box_value = $_POST["featured-post"];
	}
	update_post_meta( $post_id, "_featured-post", $featured_meta_box_value );
}

/**
 * ...
 *
 * @since 1.0.0
 */
function cdn_featured_posts_area() {
	$args     = array(
			'posts_per_page'      => 5,
			'ignore_sticky_posts' => true,
			'meta_key'            => '_featured-post',
			'meta_value'          => 1
	);
	$featured = new WP_Query( $args );
	?>

	<?php if ( $featured->have_posts() ) : ?>
		<div class="featured">
			<ul class="featured-list">
				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
					<li class="featured-item">
						<article>
							<?php if ( has_post_thumbnail() ) : ?>
								<figure class="featured-item-thumb"><a
											href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'featured-size', array( 'class' => 'featured-item-image' ) ); ?></a>
								</figure>
							<?php endif; ?>
							<header class="featured-item-header">
								<a href="<?php the_permalink(); ?>"
								   class="featured-item-link"> <?php the_title(); ?></a>
							</header>
						</article>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php
}

/**
 * ...
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