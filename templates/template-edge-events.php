<?php
/**
 * Template Name: Edge Events Template
 */

get_header();

?>

<main class="page event-listing" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'title_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_title-block' ); ?>
        <?php elseif ( get_row_layout() == 'animated_text' ) : ?>
            <?php get_template_part( 'templates/event-components/_animated-text' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'cards' ) : ?>
            <?php get_template_part( 'templates/event-components/_cards' ); ?>
        <?php elseif ( get_row_layout() == 'slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_slider' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>
        <?php elseif ( get_row_layout() == 'sneak_peak_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_sneak-peak' ); ?>
        <?php elseif ( get_row_layout() == 'ticker_tape_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_ticker-tape' ); ?>
        <?php elseif ( get_row_layout() == 'events_list' ) : ?>
            <?php get_template_part( 'templates/event-components/_events-listing' ); ?>
        <?php elseif ( get_row_layout() == 'logos_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_logos' ); ?>
        <?php elseif ( get_row_layout() == 'red_cta_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_red-cta' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
