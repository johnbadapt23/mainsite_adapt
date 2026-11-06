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
            <?php elseif ( get_row_layout() == 'three_column_text' ) : ?>
                <?php // Temporary, for layout comparison only (per explicit
                // request): reuses the actual "Three Column Text" component
                // as-is (same partial, same fields: Background Colour,
                // Title, a Column repeater of Title/Text, and an optional
                // Button) already used on template-comparison.php,
                // template-evr.php, template-partnered-research.php and
                // template-benchmarking.php. Registered as its own layout on
                // this template's flexible content field (new layout key in
                // acf-json/group_f32c19feddec4.json) rather than imitated
                // with bespoke CSS, same pattern as the 'cards' and
                // 'sticky_text_image_columns' layouts above. ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-text' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_cards_benchmark' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/benchmark-components/_three-column-cards.php as-is
                // (same partial/fields as its "Three Column Image and Text
                // Cards" layout on template-benchmarking.php /
                // template-benchmarks-maturity.php). Image field in each card
                // is optional (if ($image) guard in the partial) and left
                // empty for this comparison, per explicit request. ?>
                <?php get_template_part( 'templates/benchmark-components/_three-column-cards' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_cards_events' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/customer-events-components/_three-column-cards.php
                // as-is (same partial/fields as its "Three Column Image and
                // Text Cards" layout on template-comparison.php and others).
                // Image field in each card is optional and left empty. ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-cards' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_icon_and_text_events' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/customer-events-components/_three-column-icon-text.php
                // as-is (same partial/fields as its "Three Column Icon and
                // Text" layout on template-comparison.php and others). Icon
                // field is optional and left empty. ?>
                <?php get_template_part( 'templates/customer-events-components/_three-column-icon-text' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_icon_text_services' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/services-components/_three-column-icon-text.php
                // as-is (same partial/fields as its "Three Column Icon Text"
                // layout on template-services.php / template-market-buyer.php).
                // Icon field is optional and left empty. ?>
                <?php get_template_part( 'templates/services-components/_three-column-icon-text' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_icon_text_subscribe' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/subscribe-components/_three-column-icon-text.php
                // as-is (same partial/fields as its "Three Column Icon Text"
                // layout on template-subscribe.php). Icon field is optional
                // and left empty. ?>
                <?php get_template_part( 'templates/subscribe-components/_three-column-icon-text' ); ?>
            <?php elseif ( get_row_layout() == 'three_column_with_video_gtm' ) : ?>
                <?php // Temporary, for layout comparison only: reuses
                // templates/gtm-components/_three-column-with-video.php as-is
                // (same partial/fields as its "Title, Video and Three Column
                // Icon and Text" layout on template-gtm.php). Poster
                // image/video fields are optional (if ($image)/
                // if (autoplay_video) guards) and left empty for this
                // comparison, per explicit request -- only the title/
                // sub_title/column text is filled in. ?>
                <?php get_template_part( 'templates/gtm-components/_three-column-with-video' ); ?>
            <?php elseif ( get_row_layout() == 'cards' ) : ?>
                <?php // Reuses the actual GTM "Cards" component as-is (same
                // partial, same fields: Title, Sub title, a Cards repeater of
                // Icon/Hover Icon/Card Title/Card Text, plus the optional Icon
                // Slider rows) -- already used on template-gtm.php (see
                // adapt.com.au/services/technology-vendors' dark card row).
                // Registered as its own layout on this template's flexible
                // content field (layout_0da62bb93485 in
                // acf-json/group_f32c19feddec4.json) rather than imitated with
                // bespoke CSS. ?>
                <?php get_template_part( 'templates/gtm-components/_cards-dark' ); ?>
            <?php elseif ( get_row_layout() == 'use_case_by_function' ) : ?>
                <?php get_template_part( 'templates/intelligence-components/_use-case-tabs' ); ?>
            <?php elseif ( get_row_layout() == 'sticky_text_image_columns' ) : ?>
                <?php // Reuses the actual Benchmarking "Sticky Text Image
                // Columns" component as-is (same partial, same fields: Title
                // Position, Title, Sub Title, Button Position, an optional
                // Button, and the Sticky Content repeater of
                // Title/Text/Image/Add Shadow) -- already used on
                // template-benchmarking.php. Registered as its own layout on
                // this template's flexible content field (layout_6d8e576f01c521
                // in acf-json/group_f32c19feddec4.json) rather than imitated
                // with bespoke CSS, same pattern as the 'cards' layout above. ?>
                <?php get_template_part( 'templates/benchmark-components/_sticky-cards' ); ?>
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
