<?php
/**
 * Template Name: Landing Template
 */

get_header();

?>

<main class="page flexible landing" id="main">  
    <div class="landing-header">
        <div class="container">
            <div class="container-inner">
                <span class="logo-container">
                    <?php $header_logo = get_field( 'header_logo' ); ?>
                    <?php if ( $header_logo ) { ?>
                        <?php echo wp_get_attachment_image( $header_logo['ID'], 'adapt-optimized', false, array(
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
    <?php if ( have_rows( 'content_blocks' ) ): ?>
        <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'intro_video_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_intro-video-block' ); ?>
            <?php elseif ( get_row_layout() == 'logo_ticker_tape' ) : ?>
                <?php get_template_part( 'templates/landing-components/_logo-ticker' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_text_and_image_slider' ) : ?>
                <?php get_template_part( 'templates/landing-components/_two-column-slider' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_icon_text' ) : ?>
                <?php get_template_part( 'templates/landing-components/_icon-text-cards' ); ?>
            <?php elseif ( get_row_layout() == 'video_and_quote_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_video-quote' ); ?>
            <?php elseif ( get_row_layout() == 'community_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_community' ); ?>
            <?php elseif ( get_row_layout() == 'sponsorship_table' ) : ?>
                <?php get_template_part( 'templates/landing-components/_sponsorship' ); ?>
            <?php elseif ( get_row_layout() == 'quote_slider_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_quote-slider-block' ); ?>
            <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_form-block' ); ?>
            <?php elseif ( get_row_layout() == 'two_column_about_block' ) : ?>
                <?php get_template_part( 'templates/landing-components/_about-block' ); ?>
            <?php elseif ( get_row_layout() == 'delegation_sample' ) : ?>
                <?php get_template_part( 'templates/landing-components/_delegation' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
