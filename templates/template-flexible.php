<?php
/**
 * Template Name: Flexible Template
 */

get_header();

?>

<main class="page flexible" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'introduction_block' ) : ?>
            <?php get_template_part( 'templates/components/_introduction-block' ); ?>
        <?php elseif ( get_row_layout() == 'basic_text_introduction_block' ) : ?>
            <?php get_template_part( 'templates/components/_basic-text-introduction-block' ); ?>
        <?php elseif ( get_row_layout() == 'team_block' ) : ?>
            <?php get_template_part( 'templates/components/_team-block' ); ?>
        <?php elseif ( get_row_layout() == 'call_to_action_block' ) : ?>
            <?php get_template_part( 'templates/components/_call-to-action-block' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_list' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-list' ); ?>
        <?php elseif ( get_row_layout() == 'open_positions' ) : ?>
            <?php get_template_part( 'templates/components/_open-positions' ); ?>
        <?php elseif ( get_row_layout() == 'text_editor_content' ) : ?>
            <?php get_template_part( 'templates/components/_text-editor-content' ); ?>
        <?php elseif ( get_row_layout() == 'staff_slider' ) : ?>
            <?php get_template_part( 'templates/components/_staff-slider' ); ?>
        <?php elseif ( get_row_layout() == 'values_full_screen_blocks' ) : ?>
            <?php get_template_part( 'templates/components/_values-full-screen' ); ?>
        <?php elseif ( get_row_layout() == 'life_at_adapt_slider' ) : ?>
            <?php get_template_part( 'templates/components/_life-slider' ); ?>
        <?php elseif ( get_row_layout() == 'introduction_with_animation' ) : ?>
            <?php get_template_part( 'templates/components/_animation-introduction' ); ?>
        <?php elseif ( get_row_layout() == 'animated_text' ) : ?>
            <?php get_template_part( 'templates/event-components/_animated-text' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_text' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-text' ); ?>
        <?php elseif ( get_row_layout() == 'four_column_stats' ) : ?>
            <?php get_template_part( 'templates/components/_four-column-stats' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_icon_text_switch_module' ) : ?>
            <?php get_template_part( 'templates/components/_switch-module' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_image_text_blocks' ) : ?>
            <?php get_template_part( 'templates/event-components/_two-column-image-text' ); ?>
        <?php elseif ( get_row_layout() == 'meet_the_team' ) : ?>
            <?php get_template_part( 'templates/components/_meet-team' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_cta_module' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-cta' ); ?>
        <?php elseif ( get_row_layout() == 'contact_form_block' ) : ?>
            <?php get_template_part( 'templates/components/_contact-block' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>


        <?php elseif ( get_row_layout() == 'title_background_image_block' ) : ?>
            <?php get_template_part( 'templates/components/_title-background-block' ); ?>
        <?php elseif ( get_row_layout() == 'logo_block' ) : ?>
            <?php get_template_part( 'templates/components/_logos-block' ); ?>
        <?php elseif ( get_row_layout() == 'faq_block' ) : ?>
            <?php get_template_part( 'templates/components/_faq-block' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
