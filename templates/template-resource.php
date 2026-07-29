<?php
/**
 * Template Name: Resources Template
 */

get_header();
?>

<main class="flexible thank-you registration no-banner-top" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
    	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
    		<?php if ( get_row_layout() == 'banner_block' ) : ?>
                <?php get_template_part( 'templates/components/_resources-banner-block' ); ?>
            <?php elseif ( get_row_layout() == 'content_block_with_speakers' ) : ?>
                <?php get_template_part( 'templates/components/_resources-content-block' ); ?>
            <?php elseif ( get_row_layout() == 'resources_block' ) : ?>
                <?php get_template_part( 'templates/components/_resources-block' ); ?>
            <?php elseif ( get_row_layout() == 'cta_block' ) : ?>
                <?php get_template_part( 'templates/components/_resources-cta-block' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_block' ) : ?>
                <?php get_template_part( 'templates/components/_resources-two-column' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
    <?php // no layouts found ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
