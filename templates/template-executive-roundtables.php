<?php
/**
 * Template Name: Executive Roundtables Template
 */

get_header();

?>

<main class="page private-roundtables" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        <?php if ( get_row_layout() == 'title_block_left_align' ) : ?>
            <?php get_template_part( 'templates/roundtable-components/_title-block' ); ?>
        <?php elseif ( get_row_layout() == 'animated_text' ) : ?>
            <?php get_template_part( 'templates/roundtable-components/_animated-text' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'logos_block' ) : ?>
            <?php get_template_part( 'templates/roundtable-components/_logos' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_cta_module' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-cta' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>
        <?php elseif ( get_row_layout() == 'red_cta_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_red-cta' ); ?>
        <?php elseif ( get_row_layout() == 'cards_slider' ) : ?>
            <?php get_template_part( 'templates/roundtable-components/_card-slider' ); ?>
        <?php elseif ( get_row_layout() == 'what_youll_experience' ) : ?>
            <?php get_template_part( 'templates/roundtable-components/_experience' ); ?>
        <?php elseif ( get_row_layout() == 'form_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_form-block' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
