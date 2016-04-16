<?php
/**
 * Markup settings.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

add_filter( 'genesis_attr_entry-added', 'cdn_attr_entry_added' );
/**
 * Add attributes for entry element that was added with Ajax Load More.
 *
 * @since 1.0.0
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function cdn_attr_entry_added($attributes){

	$attributes['class'] = join( ' ', get_post_class() ) . ' entry-added';

	if ( ! is_main_query() && ! genesis_is_blog_template() ) {
		return $attributes;
	}

	$attributes['itemscope'] = true;
	$attributes['itemtype']  = 'http://schema.org/CreativeWork';

	return $attributes;
}


add_filter( 'genesis_attr_entry-media', 'cdn_attr_entry_media' );
/**
 * Add attributes for entry media object (thumbnail object).
 *
 * @since 1.0.0
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function cdn_attr_entry_media($attributes){
	$attributes['itemtype']  = 'http://schema.org/ImageObject';
	return $attributes;
}


add_filter( 'genesis_attr_entry-thumb', 'cdn_attr_entry_thumb' );
/**
 * Add attributes for entry media object image (thumbnail object image).
 *
 * @since 1.0.0
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function cdn_attr_entry_thumb($attributes){
	$attributes['class']  = 'entry-thumb';
	$attributes['itemtype']  = 'http://schema.org/image';
	return $attributes;
}