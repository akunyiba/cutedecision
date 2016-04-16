<?php
/**
 * Hot posts.
 * Template Name: Hot
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

remove_action( 'genesis_loop', 'cdn_do_loop' );
add_action( 'genesis_loop', 'cdn_do_hot_loop' );

function cdn_do_hot_loop() {
	$args = array( 'meta_key' => 'post_views' );

	cdn_do_lpht_loop( $args );
}

genesis();