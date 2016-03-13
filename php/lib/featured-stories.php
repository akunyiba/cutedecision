<?php
/**
 * Featured posts area.
 *
 * @package      Cutedecision
 * @author       Eolytic
 * @link         http://eolytic.com
 * @copyright    Copyright (c) 2016, Eolytic
 * @license      GPL-3.0+
 */

/**
 * Outputs featured posts area HTML markup.
 *
 * @since 1.0.0
 */
function cdn_do_featured_stories() {
	$args     = array(
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_featured-post',
		'meta_value'          => 1
	);
	$featured_stories = new WP_Query( $args );
	?>

	<?php if ( $featured_stories->have_posts() ) : ?>
		<aside class="featured-stories">
			<a class="featured-stories-arrow arrow-prev" href="javascript:void(0);">Previous</a>
			<a class="featured-stories-arrow arrow-next" href="javascript:void(0);">Next</a>
			<ul class="featured-stories-list">
				<?php while ( $featured_stories->have_posts() ) : $featured_stories->the_post(); ?>
					<li class="featured-story">
						<article>
							<?php if ( has_post_thumbnail() ) : ?>
								<figure class="entry-media"><a
										href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'featured-size', array( 'class' => 'entry-image' ) ); ?></a>
								</figure>
							<?php endif; ?>
							<header class="entry-header">
								<h3 class="entry-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
							</header>
						</article>
					</li>
				<?php endwhile; ?>
			</ul>
		</aside>
	<?php endif; ?>

	<?php
}