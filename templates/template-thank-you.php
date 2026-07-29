<?php
/**
 * Template Name: Thank You Template
 */

get_header();

?>

<main class="flexible thank-you registration no-banner-top" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
    	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
    		<?php if ( get_row_layout() == 'banner_block' ) : ?>
                <?php get_template_part( 'templates/components/_thank-you-banner-block' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_information_blocks' ) : ?>
                <?php get_template_part( 'templates/components/_information-blocks' ); ?>
            <?php elseif ( get_row_layout() == 'cta_block' ) : ?>
                <?php get_template_part( 'templates/components/_cta-block' ); ?>
            <?php elseif ( get_row_layout() == 'faq_block' ) : ?>
                <?php get_template_part( 'templates/components/_faq-block' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
    <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
