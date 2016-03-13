<?php
/**
 * Search customizations.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Custom search form input box text.
 *
 * @since 1.0.0
 */
function cdn_change_search_text( $text ) {
	return esc_attr( 'Search...' );
}