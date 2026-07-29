<?php
/**
 * Template Name: Resources Landing
 */

get_header();

?>
<?php global $displayed_posts;
$displayed_posts = array (); ?>

<main class="page flexible resource-landing" id="main">
    <?php if ( have_rows( 'content' ) ): ?>
    	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'featured_posts' ) : ?>
                <?php get_template_part( 'templates/resources-components/_resources-featured-block' ); ?>
            <?php elseif ( get_row_layout() == 'market_trend_reports' ) : ?>
                <?php get_template_part( 'templates/resources-components/_market-trend-featured-block' ); ?>
            <?php elseif ( get_row_layout() == 'peer_insights' ) : ?>
                <?php get_template_part( 'templates/resources-components/_peer-insights-featured-block' ); ?>
            <?php elseif ( get_row_layout() == 'expert_presentations' ) : ?>
                <?php get_template_part( 'templates/resources-components/_expert-featured-block' ); ?>
            <?php elseif ( get_row_layout() == 'articles' ) : ?>
                <?php get_template_part( 'templates/resources-components/_articles-featured-block' ); ?>
            <?php elseif ( get_row_layout() == 'best_practices_guides' ) : ?>
                <?php get_template_part( 'templates/resources-components/_best-practices-featured-block' ); ?>
            <?php endif; ?>
    	<?php endwhile; ?>
    <?php else: ?>
    	<?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
