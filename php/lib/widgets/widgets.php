<?php
/**
 * Widgets.
 *
 * @package      Sample project
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

//* Include widget class files
include_once( get_stylesheet_directory() . '/lib/widgets/ad-banner-widget.php' );

add_action( 'widgets_init', 'cdn_load_widgets' );
/**
 * Register widgets for use in the theme.
 *
 * @since 1.0.0
 */
function cdn_load_widgets() {
	register_widget( 'Cdn_Ad_Banner_Widget' );
}


remove_filter( 'genesis_register_sidebar_defaults', 'genesis_a11y_register_sidebar_defaults' );
add_filter( 'genesis_register_sidebar_defaults', 'cdn_a11y_register_sidebar_defaults' );
/**
 * Widget heading filter, default H4 in Widgets and sidebars modified to an H3 if genesis_a11y( 'headings' ) support
 *
 * For using a semantic heading structure, improves accessibility
 *
 * @since 1.0.0
 *
 * @param array $args Arguments
 *
 * @return array $args
 */
function cdn_a11y_register_sidebar_defaults( $args ) {

	if ( genesis_a11y( 'headings' ) ) {
		$args['before_title'] = '<header class="widget-header"><h3 class="widget-title">';
		$args['after_title']  = "</h3></header>\n";
	}

	return $args;
}


add_action( 'widgets_init', 'cdn_remove_recent_comment_style' );
/**
 * Remove the hardcoded inline style added in the header by WP_Widget_Recent_Comments class.
 *
 * <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
 *
 * @since 1.0.0
 */
function cdn_remove_recent_comment_style() {
	global $wp_widget_factory;
	remove_action(
			'wp_head',
			array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' )
	);
}
