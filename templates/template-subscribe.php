<?php
/**
 * Template Name: Subscribe Template
 */

get_header();

?>

<main class="page subscribe" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        <?php if ( get_row_layout() == 'introduction_block' ) : ?>
            <?php get_template_part( 'templates/subscribe-components/_introduction' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_image_text_blocks' ) : ?>
            <?php get_template_part( 'templates/subscribe-components/_two-column-image-text' ); ?>
        <?php elseif ( get_row_layout() == 'icon_text_block' ) : ?>
            <?php get_template_part( 'templates/subscribe-components/_icon-text-block' ); ?>
        <?php elseif ( get_row_layout() == 'three_column_icon_text' ) : ?>
            <?php get_template_part( 'templates/subscribe-components/_three-column-icon-text' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_subscribe_blocks' ) : ?>
            <?php get_template_part( 'templates/subscribe-components/_two-column-subscribe' ); ?>
        <?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
