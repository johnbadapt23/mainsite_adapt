<?php
/**
 * Template Name: Go to Market Landing Template
 */

get_header();

?>

<main class="page flexible go-to-market" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
        <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'form_block' ) : ?>
                <?php get_template_part( 'templates/components/_market-form' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_image_text' ) : ?>
                <?php get_template_part( 'templates/components/_market-two-column' ); ?>
            <?php elseif ( get_row_layout() == 'testimonial' ) : ?>
                <?php get_template_part( 'templates/components/_market-testimonial' ); ?>
            <?php elseif ( get_row_layout() == 'featured_posts' ) : ?>
                 <?php get_template_part( 'templates/components/_market-featured' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
