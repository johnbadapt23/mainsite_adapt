<?php
/**
 * Template Name: Services Template
 */

get_header();

?>

<main class="page flexible" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        <?php if ( get_row_layout() == 'introduction' ) : ?>
            <?php get_template_part( 'templates/services-components/_introduction' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_image_text' ) : ?>
            <?php get_template_part( 'templates/services-components/_two-column-image-text' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'three_column_icon_text' ) : ?>
            <?php get_template_part( 'templates/services-components/_three-column-icon-text' ); ?>
        <?php elseif ( get_row_layout() == 'cards' ) : ?>
            <?php get_template_part( 'templates/services-components/_services-cards' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_list_and_image_switcher' ) : ?>
            <?php get_template_part( 'templates/services-components/_two-column-switcher' ); ?>
        <?php elseif ( get_row_layout() == 'background_image_and_stats' ) : ?>
            <?php get_template_part( 'templates/services-components/_background-stats' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>
        <?php elseif ( get_row_layout() == 'red_cta_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_red-cta' ); ?>
        <?php elseif ( get_row_layout() == 'content_slider' ) : ?>
            <?php get_template_part( 'templates/components/_content-slider' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_accordion' ) : ?>
            <?php get_template_part( 'templates/services-components/_services-accordion' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_animation_and_icons' ) : ?>
            <?php get_template_part( 'templates/services-components/_two-column-animation' ); ?>
        <?php elseif ( get_row_layout() == 'vendors' ) : ?>
            <?php get_template_part( 'templates/event-components/_vendors' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
