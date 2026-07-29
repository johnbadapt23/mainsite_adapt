<?php
/**
 * Template Name: Benchmarks and Maturity Assessment Template
 */

get_header();

?>

<main class="page flexible benchmarks-maturity" id="main">  
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'centered_text_with_links' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_centered-text-links' ); ?>
            <?php elseif ( get_row_layout() == 'slide_stack' ) : ?>  
                <?php get_template_part( 'templates/benchmarks-maturity-components/_slide-stack' ); ?>
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>
            <?php elseif ( get_row_layout() == 'image_text_vertical_carousel' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_image-text-vertical-carousel' ); ?>
            <?php elseif ( get_row_layout() == 'quicklinks_with_hover_scale' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_quicklinks-with-hover' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_image_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-text' ); ?>
            <?php elseif ( get_row_layout() == 'four_column_title_and_text' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_four-column' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_image_and_text_cards' ) : ?>
                <?php get_template_part( 'templates/benchmark-components/_three-column-cards' ); ?> 
            <?php elseif ( get_row_layout() == 'auto_play_card_carousel' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_auto-play-card-carousel' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_steps' ) : ?>
                <?php get_template_part( 'templates/benchmarks-maturity-components/_two-column-steps-benchmark' ); ?>
            <?php elseif ( get_row_layout() == 'faqs' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_faqs' ); ?>   
            <?php elseif ( get_row_layout() == 'form_module' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?>  
            <?php elseif ( get_row_layout() == 'full_suite_slider' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_full-suite-slider' ); ?>
            <?php elseif ( get_row_layout() == 'list_block' ) : ?> 
                <?php get_template_part( 'templates/benchmarks-maturity-components/_list-block' ); ?> 
            <?php endif; ?>            
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>