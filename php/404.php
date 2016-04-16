<?php
/**
 * Customized 404 page.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

remove_action( 'genesis_after_loop', 'cdn_do_ajax_load_more_button' ); // TODO: Условия в самой функции?

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'cdn_404' );
/**
 * This function outputs a 404 "Not Found" error message.
 *
 * @since 1.0.0
 */
function cdn_404() {
	$report_url = 'mailto:hello@cutedecision.com';
	$report_text = sprintf( wp_kses( __( 'Please write some descriptive information about your problem, and email our <a href="%s">webmaster</a>.', 'cutedecision' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $report_url ) );
	$go_back_text = sprintf( wp_kses( __( 'You can also <a href="%s">go back to the homepage</a> and start browsing from there.', 'cutedecision' ), array(  'a' => array( 'href' => array() ) ) ), '/' );

	$report_desc = sprintf( '<p class="nf-advice-desc">%s</p>', $report_text );
	$back_desc = sprintf( '<p class="nf-advice-desc">%s</p>', $go_back_text );

	echo '<article class="entry">';
	echo '<div class="entry-content">';

	printf('<div class="nf-advice"><i class="icon icon-search"></i><h2 class="nf-advice-title">%s</h2> %s</div>', esc_html__('Search our website', 'cutedecision'), genesis_search_form());
	printf('<div class="nf-advice"><i class="icon icon-android-mail"></i><h2 class="nf-advice-title">%s</h2> %s</div>', esc_html__('Report a problem', 'cutedecision'), $report_desc );
	printf('<div class="nf-advice"><i class="icon icon-ion-android-home"></i><h2 class="nf-advice-title">%s</h2> %s</div>', esc_html__('Back to the homepage', 'cutedecision'), $back_desc );

	echo '</div>';
	echo '</article>';
}

genesis();
