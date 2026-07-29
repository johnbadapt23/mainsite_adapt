<?php
/**
 * Template Name: GTM Template
 */

get_header();
?>

<main class="page flexible gtm background-black" id="main">
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'cards' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_cards-dark' ); ?> 
            <?php elseif ( get_row_layout() == 'two_column_map_module' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_two-column-map' ); ?> 
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>               
            <?php elseif ( get_row_layout() == 'faqs' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_faqs' ); ?>  
            <?php elseif ( get_row_layout() == 'full_suite_slider' ) : ?>    
                <?php get_template_part( 'templates/gtm-components/_full-suite-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'form_module' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?> 
            <?php elseif ( get_row_layout() == 'title_video_and_three_column_icon_and_text' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_three-column-with-video' ); ?> 
            <?php elseif ( get_row_layout() == 'stats_card' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_stats' ); ?> 
            <?php elseif ( get_row_layout() == 'overlapping_cards' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_overlapping-cards' ); ?> 			
            <?php elseif ( get_row_layout() == 'large_testimonial_slider' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_large-testimonials' ); ?> 
            <?php elseif ( get_row_layout() == 'map_with_numbers' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_numbers-map' ); ?>
            <?php elseif ( get_row_layout() == 'static_cards' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_static-cards' ); ?>		
            <?php elseif ( get_row_layout() == 'repeatable_image_text_with_border' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_repeatable-image-text' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_image_text' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_image-text' ); ?>		
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
