<?php
/**
 * Template Name: Benchmarking Template
 */

get_header();

?>

<main class="page flexible benchmarking" id="main">  
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'two_column_image_and_slider' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'left_text_with_links_image' ) : ?>  
                <?php get_template_part( 'templates/benchmark-components/_left-text-links-image' ); ?>
            <?php elseif ( get_row_layout() == 'left_aligned_text_with_links_and_image_slider' ) : ?>  
                <?php get_template_part( 'templates/benchmark-components/_left-text-links-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'fixed_scroller' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_fixed-scroller' ); ?> 
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>
            <?php elseif ( get_row_layout() == 'single_quote_block' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_single-quote' ); ?> 
            <?php elseif ( get_row_layout() == 'three_column_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-text' ); ?> 
            <?php elseif  ( get_row_layout() == 'cards' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_cards-dark' ); ?>
            <?php elseif  ( get_row_layout() == 'cards_-_fixed_background' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_cards-fixed-bg' ); ?> 
            <?php elseif ( get_row_layout() == 'form_module' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?> 
            <?php elseif ( get_row_layout() == 'sticky_text_image_columns' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_sticky-cards' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_icon_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-icon-text' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_image_and_text_cards' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_three-column-cards' ); ?>                 
            <?php elseif ( get_row_layout() == 'faqs' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_faqs' ); ?>                                          
            <?php elseif ( get_row_layout() == 'form_module' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?> 
            <?php elseif ( get_row_layout() == 'four_column_title_and_text' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_four-column' ); ?> 
            <?php elseif ( get_row_layout() == 'quicklinks_with_hover_scale' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_quicklinks-with-hover-light' ); ?>
            <?php elseif ( get_row_layout() == 'black_stats_card' ) : ?>
                <?php get_template_part( 'templates/market-buyer-components/_black-stats' ); ?>
            <?php elseif ( get_row_layout() == 'full_suite_slider' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_full-suite-slider' ); ?>
            <?php elseif ( get_row_layout() == 'gtm_full_suite_slider' ) : ?> 
                <?php get_template_part( 'templates/gtm-components/_gtm-full-suite-slider' ); ?>
            <?php elseif ( get_row_layout() == 'manual_full_suite_slider' ) : ?> 
                <?php get_template_part( 'templates/gtm-components/_full-suite-slider' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_image_and_list' ) : ?> 
                <?php get_template_part( 'templates/benchmark-components/_two-column-image-list' ); ?>
            <?php endif; ?>            
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>