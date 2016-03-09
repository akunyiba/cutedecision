<?php
/**
 * Register widget areas
 *
 * @package      CDN-Reborn
 * @author       Philip Akunyiba
 * @link         http://codingismycardio.com
 * @copyright    Copyright (c) 2016, Coding is my cardio
 * @license      GPL-3.0+
 */

genesis_register_sidebar( array(
		'id'          => 'Ad-728x90',
		'name'        => __( 'Ad 728x90', 'cdn-reborn' ),
		'description' => __( 'Horizontal ad banner (728x90). Placed before the content, at the top of the page.', 'cdn-reborn' )
) );

genesis_register_sidebar( array(
		'id'          => 'Ad-300x250',
		'name'        => __( 'Ad 300x250', 'cdn-reborn' ),
		'description' => __( 'Ad banner (300x250). Placed in sidebar.', 'cdn-reborn' )
) );

genesis_register_sidebar( array(
		'id'          => 'Ad-580x400',
		'name'        => __( 'Ad 580x400', 'cdn-reborn' ),
		'description' => __( 'Ad banner (580x400). Placed after the post content.', 'cdn-reborn' )
) );

