<?php
/**
 * Custom loops.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Standard genesis loop but without 'genesis_entry_content' hook call and markup around it.
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