<section class="speaker-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container align-top">
            <?php if (get_sub_field( 'small_text' )) { ?>
                <span class="label-small"><?php echo get_sub_field( 'small_text' ); ?></span>
            <?php } else { ?>
                <span class="label-small">&nbsp;</span>
            <?php } ?>            
            <span class="title-container-inner"> 
                <h2 class="h1-style black-text bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if (get_sub_field( 'text' )) { ?> 
                    <h5 role="heading" aria-level="3" class="black-text"><?php echo get_sub_field( 'text' ); ?>  </h5>
                <?php } ?>
            </span>        
        </div>
        <div class="speakers-container-outer">
            <div class="filter-container-outer">
                <?php
                    $expertise_ids = get_sub_field( 'expertise' );
                    // BUGFIX 2026-09-03: kept alongside $expertise_ids so the
                    // #speakers-container query below (which used to gate on
                    // $expertise_ids) can check "did this module have any
                    // expertise configured at all" using the value as
                    // originally set by ACF -- not the value further down
                    // the page, after the .expertise-group checkbox block
                    // has already destructively stripped 15788/15789
                    // (adapt-analysts/adapt-advisors) out of it via
                    // array_diff(). See the query below for why that
                    // mattered.
                    $expertise_ids_original = $expertise_ids;
                ?>
                <div class="position-sticky filter-container sticky-filter-container">
                    
                    <div>
                        <form id="speakerFilter">
                            <?php if( in_array(15788,$expertise_ids) || in_array(15789,$expertise_ids)) : ?>
                            <div class="analyst-advisor-checkboxes" style="padding-bottom: 20px;">
                                    <?php
                                        if ( $expertise_ids && ! empty( $expertise_ids ) ) {
                                            // Get terms for the selected expertise IDs
                                            $expertise_terms = get_terms( array(
                                                'taxonomy' => 'expertise',
                                                'hide_empty' => false,
                                                'include' => array(15788, 15789), // Only include terms with these IDs
                                            ) );

                                            if ( ! empty( $expertise_terms ) && ! is_wp_error( $expertise_terms ) ) {
                                                foreach ( $expertise_terms as $term ) {
                                                    // Generate checkbox for each term
                                                    ?>
                                                    <div class="expertise-checkbox">
                                                        <input type="checkbox" id="expertise-<?php echo esc_attr( $term->slug ); ?>" name="expertise[]" value="<?php echo esc_attr( $term->slug ); ?>">
                                                        <label for="expertise-<?php echo esc_html( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></label>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                            </div>
                            <?php endif; ?>
                            <?php
                                // Wrapped together (title + mobile trigger + the checkboxes
                                // themselves) so main.js can show/hide the whole "Expertise"
                                // group as one unit when an ADAPT Analysts/Advisors checkbox
                                // (above) is toggled -- see the #speakerFilter change handler.
                            ?>
                            <div class="expertise-group">
                            <span class="expertise-title">Expertise</span>
                            <span class="mobile-trigger">Filter by expertise</span>
                            <?php
                                if ( $expertise_ids && ! empty( $expertise_ids ) ) {
                                    // Get terms for the selected expertise IDs

                                    if( in_array(15788,$expertise_ids) || in_array(15789,$expertise_ids)) {
                                        $expertise_ids = array_diff($expertise_ids, [15788, 15789]);
                                        $expertise_ids = array_values($expertise_ids);
                                    }

                                    $expertise_terms = get_terms( array(
                                        'taxonomy' => 'expertise',
                                        'hide_empty' => false,
                                        'include' => $expertise_ids, // Only include terms with these IDs
                                    ) );

                                    if ( ! empty( $expertise_terms ) && ! is_wp_error( $expertise_terms ) ) {
                                        foreach ( $expertise_terms as $term ) {
                                            // Generate checkbox for each term
                                            ?>
                                            <div class="expertise-checkbox">
                                                <input type="checkbox" id="expertise-<?php echo esc_attr( $term->slug ); ?>" name="expertise[]" value="<?php echo esc_attr( $term->slug ); ?>">
                                                <label for="expertise-<?php echo esc_html( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></label>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="speaker-filter-inner">
                <div class="speakers" id="speakers-container">
                    <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $posts_per_page = 12; // Number of posts per page
                        $offset = ($paged - 1) * $posts_per_page;
                        // BUGFIX 2026-09-03: this used to be `if ( $expertise_ids )`,
                        // gating the entire query (and the #speakers-container
                        // markup) on whether this module had any expertise
                        // configured. Two problems: (1) by this point in the
                        // page, $expertise_ids had already been mutated by the
                        // .expertise-group checkbox block above (array_diff()
                        // strips out 15788/15789), so a module configured with
                        // ONLY adapt-analysts/adapt-advisors and no other
                        // expertise term saw $expertise_ids become an empty
                        // array here and rendered zero speakers on initial
                        // load -- even though speakers existed. (2) even where
                        // it didn't trip, gating the query at all didn't match
                        // filter_speakers_callback() in functions.php, whose
                        // own "safety net" branch is explicitly designed to
                        // never skip the query, just fall back to filtering on
                        // adapt-analysts/adapt-advisors alone -- see its
                        // comment ("no slugs at all ... still exclude untagged
                        // posts rather than skipping tax_query entirely").
                        // Gating on $expertise_ids_original instead (the
                        // value as ACF originally set it, just confirming
                        // this module has *some* expertise configured at
                        // all) fixes case (1) -- a module can no longer lose
                        // its speakers just because its only configured
                        // expertise happened to be 15788/15789 -- while
                        // leaving case (2)'s "not configured at all" instances
                        // rendering nothing, same as before.
                        if ( $expertise_ids_original ) {
                            // Set up the query arguments
                            $args = array(
                                'post_type'      => 'speaker',
                                'posts_per_page' => $posts_per_page,
                                'offset' => $offset,
                                'paged'         => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
                                'tax_query'      => array(
                                    'relation' => 'AND',
                                    // array(
                                    //     'taxonomy' => 'expertise',
                                    //     'field'    => 'term_id',
                                    //     'terms'    => $expertise_ids,
                                    //     'operator' => 'IN',
                                    // ),
                                    array(
                                        'taxonomy' => 'expertise',
                                        'field'    => 'term_id',
                                        'terms'    => array( 15788, 15789 ), // adapt-analysts, adapt-advisors
                                        'operator' => 'IN',
                                    ),
                                ),
                                'ignore_custom_sort' => true,
                                // Replaced by the expertise tax_query exclusion above.
                                // 'meta_query'     => array(
                                //     'relation' => 'OR',
                                //     array(
                                //         'key'     => 'adapt_analyst',
                                //         'compare' => 'EXISTS',
                                //     ),
                                //     array(
                                //         'key'     => 'adapt_analyst',
                                //         'compare' => 'NOT EXISTS',
                                //     ),
                                //
                                // ),
                                // BUGFIX 2026-09-03: dropped the stray 'meta_value' => 'DESC'
                                // left behind by the meta_query removal above -- no meta_key
                                // was ever set for it. Ordering is Advanced Post Types Order
                                // (NSP Code), which uses WordPress's native menu_order column;
                                // 'speaker' is configured for its manual drag-and-drop mode,
                                // so 'menu_order' alone is correct here. See
                                // filter_speakers_callback() in functions.php for the matching
                                // AJAX-side query, which needs the same fix.
                                'orderby'     => array( 'menu_order' => 'ASC' ),
                            );

                            // Run the query
                            $speakers_query = new WP_Query( $args );

                            // Check if there are posts
                            if ( $speakers_query->have_posts() ) {
                                while ( $speakers_query->have_posts() ) {
                                    $speakers_query->the_post();
                                    $post_slug = get_post_field( 'post_name', get_post() );
                                    $term_slugs = wp_get_post_terms(get_the_ID(), 'expertise', array('fields' => 'slugs'));
                                    $filter_slugs = implode(' ', $term_slugs);
                                    ?>
                                    <div class="one-third speaker-item one-third column" data-filter="<?php echo esc_attr( $filter_slugs ); ?>">
                                        <a class="slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                                            <span class="image-container">
                                                <span class="bg-container">
                                                    <?php $team_member_image = get_field( 'speaker_image' ); ?>
                                                    <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                                </span>
                                                <span class="text-container mobile-hide">
                                                    <h5><?php the_title(); ?></h5>
                                                    <span class="label-Xsmall white-text"><?php echo get_field('speaker_description'); ?></span>
                                                    <span class="learn-more text-link red-underline-link red-text">Learn More</span>
                                                </span>
                                            </span> 
                                             <span class="text-container desktop-hide">
                                                <span class="p-small black-text"><?php the_title(); ?></span>
                                                <span class="label-Xsmall dakr-grey-text"><?php echo get_field('speaker_description'); ?></span>
                                                <span class="learn-more text-link red-underline-link red-text external-link">Learn More</span>
                                            </span>                               
                                        </a>
                                        <div id="<?php echo $post_slug; ?>" class="full-bio">
                                            <div class="bio-content-wrapper">
                                                <span class="close-bio"></span>
                                                <span class="bio-top">
                                                    <span class="image-container">
                                                        <span class="bg-container">
                                                            <?php $team_member_image = get_field( 'speaker_image' ); ?>
                                                            <img loading="lazy" src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                                        </span>
                                                        <span class="border-offset"></span>
                                                    </span>
                                                    <span class="text">
                                                        <h2><?php the_title(); ?></h2>
                                                        <h3><?php echo get_field('speaker_description'); ?></h3>
                                                        <a class="linkedin" href="<?php echo get_field('linked_in_url'); ?>"><img loading="lazy" class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-new.svg" alt="LinkedIn" width="28" /></a>
                                                    </span>
                                                </span>
                                                <span class="bio-bottom">
                                                    <?php echo get_field('speaker_details'); ?>                                                
                                                </span>                                               
                                            </div>
                                            <span class="speaker-button-container">
                                                <?php
                                                    // Switched from the hosted hsforms.com share-link (iframe popup)
                                                    // to the real HubSpot JS embed, so it renders natively in the page
                                                    // (no cross-origin iframe) and matches the styling of other
                                                    // embeds on the site. The <script> loader is centralised once in
                                                    // functions.php (adapt_page_needs_hubspot_forms_embed()) rather
                                                    // than repeated per speaker/advisor card.
                                                    //
                                                    // The div is wrapped in a <template> so it isn't inserted (and
                                                    // doesn't get rendered by the embed script) until the popup is
                                                    // actually opened -- see .formPopupHubspot's callbacks.open in
                                                    // main.js, which also fills in the hidden "which advisors are you
                                                    // interested in meeting" field from data-prefill-title once the
                                                    // embed script finishes rendering the real form fields.
                                                    $speaker_form_id = 'speakerFormEmbed' . get_the_ID();
                                                ?>
                                                <span class="std-button form-popup-button-container red-button" style="padding: 0;">
                                                    <?php if( has_term('adapt-analysts', 'expertise') ) : ?>
                                                        <a class="formPopupHubspot" href="#<?= $speaker_form_id; ?>" data-embed-template="<?= $speaker_form_id; ?>Tpl" data-prefill-title="<?= esc_attr( get_the_title() ); ?>">Submit an Analyst Enquiry</a>
                                                    <?php elseif( has_term('adapt-advisors', 'expertise') ) : ?>
                                                        <a class="formPopupHubspot" href="#<?= $speaker_form_id; ?>" data-embed-template="<?= $speaker_form_id; ?>Tpl" data-prefill-title="<?= esc_attr( get_the_title() ); ?>">Submit an Advisor Enquiry</a>
                                                    <?php endif; ?>
                                                </span>
                                                <div style="display:none">
                                                    <div id="<?= $speaker_form_id; ?>">
                                                        <span class="form">
                                                            <template id="<?= $speaker_form_id; ?>Tpl">
                                                                <?php if( has_term('adapt-analysts', 'expertise') ) : ?>
                                                                    <?php echo get_field( 'speaker_form_script', 'options' ); ?>
                                                                <?php elseif( has_term('adapt-advisors', 'expertise') ) : ?>
                                                                    <?php echo get_field( 'speaker_form_script_advisor', 'options' ); ?>
                                                                <?php endif; ?>
                                                            </template>
                                                        </span>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="click-overlay"></div>
                                    </div>
                                    <?php
                                }
                            } 

                            // Restore original post data
                            wp_reset_postdata();
                        }
                        ?>

                </div>   
                <div class="page-navi-container speaker-pagination-container">
                    <div class="container">
                        <?php wp_pagenavi(array(
                            'query' => $speakers_query,
                            'prev_text' => 'Previous', // Set custom text for "Previous" link
                            'next_text' => 'Next',     // Set custom text for "Next" link
                        )); ?>
                        <?php wp_reset_postdata(); ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<span class="speaker-form" style="display:none;"><?php echo get_field( 'speaker_form_script', 'options' ); ?></span>
                
            