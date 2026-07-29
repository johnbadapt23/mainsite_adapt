<?php
/**
 * Template Name: Customer Events Template
 */

get_header();

?>

<main class="page flexible" id="main">  
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'two_column_image_and_slider' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-slider' ); ?>               
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>                 
            <?php elseif ( get_row_layout() == 'image_or_video_block' ) : ?>
                 <?php get_template_part( 'templates/event-components/_video-block' ); ?>                
            <?php elseif ( get_row_layout() == 'fixed_scroller' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_fixed-scroller' ); ?>                 
            <?php elseif ( get_row_layout() == 'speaker_text_carousel' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_speaker-text-carousel' ); ?> 
            <?php elseif ( get_row_layout() == 'speaker_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_speaker-module' ); ?> 
            <?php elseif ( get_row_layout() == 'topic_industry_switch_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_topic-industry-switcher' ); ?> 
            <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_quote-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'full_width_image_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_full-image-text' ); ?>                             
            <?php elseif ( get_row_layout() == 'two_column_image_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-text' ); ?>                  
            <?php elseif ( get_row_layout() == 'company_slider' ) : ?>
               <?php get_template_part( 'templates/customer-events-components/_company-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'infinite_image_carousel' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_infinite-images' ); ?> 
            <?php elseif ( get_row_layout() == 'faqs' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_faqs' ); ?>                              
            <?php elseif ( get_row_layout() == 'map_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_map-block' ); ?>                              
            <?php elseif ( get_row_layout() == 'cta' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_cta-module' ); ?>                         					
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>