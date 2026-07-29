<?php
/**
 * Template Name: Customer Stories Template
 */

get_header();

?>

<main class="page flexible customer-stories" id="main">  
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'title_block' ) : ?>
                <?php get_template_part( 'templates/customer-story-components/_title-block' ); ?> 
            <?php elseif ( get_row_layout() == 'hero_slider_module' ) : ?>  
                <?php get_template_part( 'templates/customer-story-components/_hero-slider' ); ?> 
            <?php elseif ( get_row_layout() == 'logo_scroller' ) : ?>	
                <?php get_template_part( 'templates/customer-story-components/_logo-scroller' ); ?>
            <?php elseif ( get_row_layout() == 'customer_story_category_slider' ) : ?>
                <?php get_template_part( 'templates/customer-story-components/_category-three-column' ); ?>
            <?php elseif ( get_row_layout() == 'form_module' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>