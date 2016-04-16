<?php
/**
 * Count post shares.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Count shares.
 *
 * @since 1.0.0
 */
function cdn_post_shares_init( $post_id = null ) {
	$count_key   = 'post_shares';
	$post_shares = get_post_meta( $post_id, $count_key, true );

	if ( $post_shares == '' ) {
		$post_shares = 0;

		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
	} else {
		$post_shares_upd = pssc_all( $post_id );

		if( $post_shares_upd !== $post_shares){
			update_post_meta( $post_id, $count_key, $post_shares_upd );
		}
	}
}