<?php
/**
 * Template Name: Edge Events Partner Landing Template
 */

get_header();

?>
<div class="landing-header edge-events-partner-landing">
    <div class="container">
        <div class="container-inner">
            <span class="logo-container">
                <?php $header_logo = get_field( 'header_logo' ); ?>
                <?php if ( $header_logo ) { ?>
                    <?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array(
                        'alt'     => $header_logo['alt'],
                        'loading' => 'lazy',
                    ) ); ?>
                <?php } ?>            
            </span>
            <span class="text-container-right">
                <span class="powered-by text-grey">Powered by ADAPT ®</span>
            </span>
        </div>
    </div>
</div>

<main class="page event-listing edge-events-partner" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'title_block_background' ) : ?>            
            <?php get_template_part( 'templates/event-components/_title-block-background' ); ?>
        <?php elseif ( get_row_layout() == 'intro_block' ) : ?>
            <?php get_template_part( 'templates/landing-components/_intro-block' ); ?>
        <?php elseif ( get_row_layout() == 'title_block_left_align' ) : ?>
            <?php get_template_part( 'templates/event-components/_title-block-left' ); ?>
        <?php elseif ( get_row_layout() == 'animated_text' ) : ?>
            <?php get_template_part( 'templates/event-components/_animated-text' ); ?>
        <?php elseif ( get_row_layout() == 'stats_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_stats' ); ?>
        <?php elseif ( get_row_layout() == 'video_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_video-block' ); ?>
        <?php elseif ( get_row_layout() == 'logos_text_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_logos-text-block' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_image_text_blocks' ) : ?>
            <?php get_template_part( 'templates/event-components/_two-column-image-text' ); ?>
        <?php elseif ( get_row_layout() == 'cards' ) : ?>
            <?php get_template_part( 'templates/event-components/_partner-cards' ); ?>
        <?php elseif ( get_row_layout() == 'vendors' ) : ?>
            <?php get_template_part( 'templates/event-components/_vendors' ); ?>
        <?php elseif ( get_row_layout() == 'flip_cards' ) : ?>
            <?php get_template_part( 'templates/event-components/_flip-cards' ); ?>
        <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_quote-slider' ); ?>
        <?php elseif ( get_row_layout() == 'events_list' ) : ?>
            <?php get_template_part( 'templates/event-components/_events-listing-partners' ); ?>
        <?php elseif ( get_row_layout() == 'form_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_form-block' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_icon_text' ) : ?>
            <?php get_template_part( 'templates/event-components/_icon-text' ); ?>
        <?php elseif ( get_row_layout() == 'customise_cards' ) : ?>
            <?php get_template_part( 'templates/event-components/_customise-cards' ); ?>
        <?php elseif ( get_row_layout() == 'red_cta_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_red-cta' ); ?>
        <?php elseif ( get_row_layout() == 'three_column_video_posts' ) : ?>
            <?php get_template_part( 'templates/event-components/_three-column-video' ); ?>
        <?php elseif ( get_row_layout() == 'community_block' ) : ?>
            <?php get_template_part( 'templates/event-components/_community' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
