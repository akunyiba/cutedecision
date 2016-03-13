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
include_once( get_stylesheet_directory() . '/lib/widgets/ad-banner.php' );

add_action( 'widgets_init', 'cdn_load_widgets' );
/**
 * Register widgets for use in the theme.
 *
 * @since 1.0.0
 */
function cdn_load_widgets() {
	register_widget( 'Cdn_Ad_Banner' );
}
