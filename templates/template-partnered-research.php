<?php
/**
 * Template Name: Partnered Research Template
 */

get_header();

?>

<main class="page flexible" id="main">  
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'two_column_image_and_slider' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-slider' ); ?>                 
            <?php elseif ( get_row_layout() == 'centered_text_with_links' ) : ?>  
                <?php get_template_part( 'templates/customer-events-components/_centered-text-links' ); ?> 
            <?php elseif ( get_row_layout() == 'two_column_image_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-image-text-partnered' ); ?> 
            <?php elseif ( get_row_layout() == 'three_column_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-text' ); ?> 
            <?php elseif ( get_row_layout() == 'faqs' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_faqs' ); ?> 
            <?php elseif ( get_row_layout() == 'form_module' ) : ?> 
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?>  
            <?php elseif ( get_row_layout() == 'full_suite_slider' ) : ?>    
                <?php get_template_part( 'templates/gtm-components/_full-suite-slider' ); ?>   
            <?php elseif ( get_row_layout() == 'parallax_image' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_parallax-image' ); ?>
            <?php elseif ( get_row_layout() == 'fixed_scroller' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_fixed-scroller-partner' ); ?>
            <?php elseif ( get_row_layout() == 'title_image_three_column_icon_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_title-image-three-column-icon-text' ); ?>
			<?php elseif ( get_row_layout() == 'form_popup_slider' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_form-popup-slider' ); ?>
			





            <?php elseif ( get_row_layout() == 'advisors_carousel' ) : ?>   
                <?php get_template_part( 'templates/customer-events-components/_advisors-carousel' ); ?>                           
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>         
            <?php elseif ( get_row_layout() == 'image_or_video_block' ) : ?>
                 <?php get_template_part( 'templates/event-components/_video-block' ); ?>                
                 
            <?php elseif ( get_row_layout() == 'speaker_text_carousel' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_speaker-text-carousel' ); ?> 
            <?php elseif ( get_row_layout() == 'partner_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_partner-module' ); ?> 
            <?php elseif ( get_row_layout() == 'topic_industry_switch_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_topic-industry-switcher' ); ?> 
            <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_quote-slider' ); ?>  
            <?php elseif ( get_row_layout() == 'three_column_icon_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-icon-text' ); ?>        
            <?php elseif ( get_row_layout() == 'single_quote_block' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_single-quote' ); ?> 
            <?php elseif ( get_row_layout() == 'two_column_logo_carousel' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_two-column-carousel' ); ?> 
            <?php elseif ( get_row_layout() == 'full_width_image_and_text' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_full-image-text' ); ?>                             
                             
            <?php elseif ( get_row_layout() == 'company_slider' ) : ?>
               <?php get_template_part( 'templates/customer-events-components/_company-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'infinite_image_carousel' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_infinite-images' ); ?> 
                             
            <?php elseif ( get_row_layout() == 'map_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_map-block' ); ?>                              
            <?php elseif ( get_row_layout() == 'cta' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_cta-module' ); ?>  
            <?php elseif ( get_row_layout() == 'two_column_image_and_text_cards' ) : ?>     
                <?php get_template_part( 'templates/customer-events-components/_two-column-cards' ); ?> 
            <?php elseif ( get_row_layout() == 'three_column_image_and_text_cards' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-cards' ); ?> 
        					
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>