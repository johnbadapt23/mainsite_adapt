
<?php
/**
 * Template Name: Home Template
 */

get_header();

?>
<!-- <span class="loading-animation">
    <span class="v-wrap">
        <span class="v-box">
            <span class="animator-player">
                <lottie-player loop autoplay speed="1" src="<?php echo get_template_directory_uri(); ?>/assets/images/loading.json" background="transparent" style="width: 100%; height: auto"></lottie-player>
            </span>
        </span>
    </span>
</span> -->

<main class="page flexible" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        <?php if ( get_row_layout() == 'introduction_with_text_animation' ) : ?>
            <?php get_template_part( 'templates/components/_text-animation-introduction-v2' ); ?>
        <?php elseif ( get_row_layout() == 'logo_ticker_tape' ) : ?>
            <?php get_template_part( 'templates/components/_logo-ticker-v2' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_text' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-text-home-v2' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'list_cards' ) : ?>
            <?php get_template_part( 'templates/components/_list-cards' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_services' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-services' ); ?>


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
        <?php elseif ( get_row_layout() == 'two_column_cta_module' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-cta-home' ); ?>
        <?php elseif ( get_row_layout() == 'red_cta_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_red-cta' ); ?>
        <?php elseif ( get_row_layout() == 'centered_text_with_links' ) : ?>
            <?php get_template_part( 'templates/components/_centered-text-links' ); ?>
        <?php elseif ( get_row_layout() == 'content_slider' ) : ?>
            <?php get_template_part( 'templates/components/_content-slider' ); ?>
        <?php elseif ( get_row_layout() == 'featured_posts' ) : ?>
            <?php get_template_part( 'templates/components/_featured-posts' ); ?>
        <?php elseif ( get_row_layout() == 'resource_types' ) : ?>
            <?php get_template_part( 'templates/components/_featured-types' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
