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

/**
 * Hook the callback that registers theme widget areas.
 *
 * @since 1.0.0
 */
function cdn_register_widget_areas(){
	//* Ad 728x90
	genesis_register_widget_area( array(
			'id'          => 'cdn-widget-before-content',
			'name'        => __( 'Ad 728x90', 'cutedecision' ),
			'description' => __( 'Horizontal ad banner (728x90). Placed before the content, at the top of the page.', 'cutedecision' )
	) );

//	//* Ad 300x250
//	genesis_register_widget_area( array(
//			'id'          => 'ad-300x250',
//			'name'        => __( 'Ad 300x250', 'cutedecision' ),
//			'description' => __( 'Ad banner (300x250). Placed in sidebar.', 'cutedecision' )
//	) );
//
//	//* Ad 580x400
//	genesis_register_widget_area( array(
//			'id'          => 'ad-580x400',
//			'name'        => __( 'Ad 580x400', 'cutedecision' ),
//			'description' => __( 'Ad banner (580x400). Placed after the post content.', 'cutedecision' )
//	) );

}

/**
 * Outputs HTML markup of Ad widget before content.
 *
 * @since 1.0.0
 */
function cdn_do_widget_before_content () {
	if ( is_home() || is_front_page() ) {
		genesis_widget_area( 'cdn-widget-before-content', array(
				'before' => '<aside class="widget-area widget-before-content">' . genesis_sidebar_title( 'cdn-widget-before-content' ),
				'after' => '</aside>',
		) );
	}
}

