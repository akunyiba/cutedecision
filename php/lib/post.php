<?php
/**
 * Post customizations.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Custom entry meta in the entry header with "human time format" date.
 *
 * @since 1.0.0
 */
function cdn_post_info() {
	$human_time = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago';
	$post_info  = 'by [post_author_posts_link] <br>about ' . $human_time . ' [post_comments] [post_edit]';

	return $post_info;
}


/**
 * Custom post thumbnail with figure markup and additional content.
 *
 * @since 1.0.0
 */
function cdn_do_post_image() {
	if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
		$img = genesis_get_image( array(
			'format'  => 'html',
			'size'    => '100%',
			'context' => 'archive',
			'attr'    => genesis_parse_attr( 'entry-media', array( 'alt' => get_the_title() ) ),
		) );

		if ( ! empty( $img ) ) {
			$entry_img = '<figure class="entry-media">'
			             . '<figcaption class="entry-flag"><i class="icon icon-fire"></i></figcaption>' // TODO: Динамический flag на основании рейтинга (latest, views, comments, social sharing)
			             . sprintf( '<a href="%s" aria-hidden="true">%s</a>', get_permalink(), $img )
			             . '<figcaption class="entry-info">' // TODO: Динамические данные (shares, comments, category)
			             . '<div class="entry-info-shares"><i class="icon icon-share"></i> 271 Shares</div>'
			             . '<div class="entry-info-comments"><i class="icon icon-chatbubble"></i> 11</div>'
			             . '<div class="entry-info-category">WTF</div>'
			             . '</figcaption>'
			             . '</figure>';
			echo $entry_img;
		}
	}
}
