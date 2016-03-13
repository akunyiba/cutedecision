<?php
/**
 * Custom meta boxes.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Add custom meta boxes.
 *
 * @since 1.0.0
 */
function cdn_add_meta_boxes() {
	add_meta_box( "featured-post", "Featured Options", "cdn_meta_box_featured_markup", "post", "normal", "high", null );
}

/**
 * Save custom meta boxes.
 *
 * @since 1.0.0
 */
function cdn_save_meta_boxes( $post_id, $post, $update ) {
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
 * In-post meta box markup - Featured post.
 *
 * Outputs basic wrapping HTML with checkbox.
 *
 * @since 1.0.0
 */
function cdn_meta_box_featured_markup( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'cdn-post-nonce' );
	$featured = get_post_meta( $post->ID, '_featured-post', true );
	echo '<label for="featured-post">' . __( 'Feature this post? ', 'cutedecision' ) . '</label>';
	echo '<input type="checkbox" name="featured-post" id="featured-post" value="1" ' . checked( 1, $featured, false ) . ' />';
}