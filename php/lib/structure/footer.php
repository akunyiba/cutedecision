<?php
/**
 * Site footer.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_before_footer', 'cdn_footer_widget_areas' );
/**
 * Customized `genesis_footer_widget_areas` Genesis function.
 *
 * Is added: max-width wrapper div with `wrap` class.
 *
 * @since 1.0.0
 *
 * @uses genesis_structural_wrap() Optionally adds wrap with footer-widgets context.
 *
 * @return null Return early if number of widget areas could not be determined, or nothing is added to the first widget area.
 */
function cdn_footer_widget_areas() {
	$footer_widgets = get_theme_support( 'genesis-footer-widgets' );

	if ( ! $footer_widgets || ! isset( $footer_widgets[0] ) || ! is_numeric( $footer_widgets[0] ) ) {
		return;
	}

	$footer_widgets = (int) $footer_widgets[0];

	//* Check to see if first widget area has widgets. If not, do nothing. No need to check all footer widget areas.
	if ( ! is_active_sidebar( 'footer-1' ) ) {
		return;
	}

	$inside  = '';
	$output  = '';
	$counter = 1;

	while ( $counter <= $footer_widgets ) {

		//* Darn you, WordPress! Gotta output buffer.
		ob_start();
		dynamic_sidebar( 'footer-' . $counter );
		$widgets = ob_get_clean();

		$inside .= sprintf( '<div class="footer-widgets-%d widget-area">%s</div>', $counter, $widgets );

		$counter ++;

	}

	if ( $inside ) {

		$output .= genesis_markup( array(
			'html5'   => '<div %s>' . '<div class="wrap">' . genesis_sidebar_title( 'Footer' ),
			'xhtml'   => '<div id="footer-widgets" class="footer-widgets">',
			'context' => 'footer-widgets',
			'echo'    => false,
		) );

		$output .= genesis_structural_wrap( 'footer-widgets', 'open', 0 );

		$output .= $inside;

		$output .= genesis_structural_wrap( 'footer-widgets', 'close', 0 );

		$output .= '</div>';
		$output .= '</div>';

	}

	echo apply_filters( 'genesis_footer_widget_areas', $output, $footer_widgets );
}


remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'cdn_do_footer' );
/**
 * Outputs customized footer markup.
 *
 * @since 1.0.0
 */
function cdn_do_footer() {
	$args      = array(
		'theme_location' => 'footer',
		'menu_class'     => 'menu menu-footer'
	);
	$nav_menu = genesis_get_nav_menu( $args );
	$copyright = genesis_footer_copyright_shortcode( array( 'first' => '2011' ) );

	echo '<div class="wrap">';
	echo '<p class="footer-text">';
	echo $copyright . ' &middot; made with <i class="icon icon-heart"></i> by <a href="http://codingismycardio.com">coding is my cardio</a>';
	echo '</p>';
	echo $nav_menu;
	echo '<a href="#" class="to-top">Back to top</a>';
	echo '</div>';
}