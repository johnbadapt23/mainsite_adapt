<?php
/**
 * Template Name: ADAPT Intelligence
 */

get_header();
?>

<main class="page flexible intelligence" id="main">
    <?php if ( have_rows( 'content' ) ): ?>
        <?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'intro_statement' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_intro-statement' ); ?>
            <?php elseif ( get_row_layout() == 'intelligence_hero' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_hero-ask' ); ?>
            <?php elseif ( get_row_layout() == 'animated_text_with_logos' ) : ?>
                <?php get_template_part( 'templates/customer-events-components/_animated-text-logos' ); ?>
            <?php elseif ( get_row_layout() == 'map_with_numbers' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_numbers-map' ); ?>
            <?php elseif ( get_row_layout() == 'pain_vs_promise' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_pain-vs-promise' ); ?>
            <?php elseif ( get_row_layout() == 'use_case_by_function' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_use-case-tabs' ); ?>
            <?php elseif ( get_row_layout() == 'large_testimonial_slider' ) : ?>
                <?php get_template_part( 'templates/gtm-components/_large-testimonials' ); ?>
            <?php elseif ( get_row_layout() == 'call_to_action_block' ) : ?>
                <?php get_template_part( 'templates/components/_call-to-action-block' ); ?>
            <?php elseif ( get_row_layout() == 'closing_cta_form' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_closing-cta-form' ); ?>
            <?php elseif ( get_row_layout() == 'form_module' ) : ?>
                <?php // Closing form section -- reuses the same Form Module
                // component already used on the GTM template pages (e.g.
                // /services/it-research-advisory), rather than the bespoke
                // closing_cta_form layout above. ?>
                <?php get_template_part( 'templates/customer-events-components/_form-module' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
