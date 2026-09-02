<section class="speaker-module partner-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container align-top">
            <?php if (get_sub_field( 'small_text' )) { ?>
                <span class="label-small"><?php echo get_sub_field( 'small_text' ); ?></span>
            <?php } else { ?>
                <span class="label-small">Our Partners</span>
            <?php } ?>            
            <span class="title-container-inner"> 
                <h2 class=" black-text bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if (get_sub_field( 'text' )) { ?> 
                    <h5 role="heading" aria-level="3" class="black-text"><?php echo get_sub_field( 'text' ); ?>  </h5>
                <?php } ?>
            </span>        
        </div>
        <div class="speakers-container-outer">
            <div class="filter-container-outer">
                <?php $expertise_ids = get_sub_field( 'expertise' ); ?>
                <div class="position-sticky filter-container sticky-filter-container">
                    <span class="expertise-title">Expertise</span>
                    <span class="mobile-trigger">Filter by expertise</span>
                    <form id="partnerFilter">
                        <?php
                            if ( $expertise_ids && ! empty( $expertise_ids ) ) {
                                // Get terms for the selected expertise IDs
                                $expertise_terms = get_terms( array(
                                    'taxonomy' => 'partner-category',
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
                    </form>
                </div>
            </div>
            <div class="partners-filter-inner speaker-filter-inner">
                <div class="speakers" id="partners-container">
                    <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        if ( $expertise_ids ) {
                            // Set up the query arguments
                            $args = array(
                                'post_type'      => 'partners',
                                'posts_per_page' => 12,
                                'paged'         => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'partner-category',
                                        'field'    => 'term_id',
                                        'terms'    => $expertise_ids,
                                        'operator' => 'IN',
                                    ),
                                )                                                          
                            );

                            // Run the query
                            $speakers_query = new WP_Query( $args );

                            // Check if there are posts
                            if ( $speakers_query->have_posts() ) {
                                while ( $speakers_query->have_posts() ) {
                                    $speakers_query->the_post();
                                    $post_slug = get_post_field( 'post_name', get_post() );
                                    $term_slugs = wp_get_post_terms(get_the_ID(), 'partner-category', array('fields' => 'slugs'));
                                    $filter_slugs = implode(' ', $term_slugs);
                                    ?>
                                    <div class="one-third speaker-item one-third column" data-filter="<?php echo esc_attr( $filter_slugs ); ?>">
                                        <a class="slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                                            <span class="image-container">
                                                <span class="bg-container">
                                                    <?php $team_member_image = get_field( 'logo' ); ?>
                                                    <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                                </span>
                                                <span class="text-container mobile-hide">
                                                    <h5 class="labelMedium"><?php the_title(); ?></h5>                                                    
                                                </span>
                                            </span>  
                                            <span class="text-container desktop-hide">
                                                <span class="p-small"><?php the_title(); ?></span> 
                                                <span class="text-link red-text external-link underline-link">Learn More</span>                                                   
                                            </span>                                                                        
                                        </a>
                                        <div id="<?php echo $post_slug; ?>" class="full-bio">
                                            <div class="bio-content-wrapper">
                                                <span class="close-bio"></span>
                                                <span class="bio-top">
                                                    <span class="image-container">
                                                        <span class="bg-container">
                                                            <?php $team_member_image = get_field( 'logo' ); ?>
                                                            <img loading="lazy" src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                                        </span>
                                                        <span class="border-offset"></span>
                                                    </span>
                                                    <span class="text">
                                                        <h2><?php the_title(); ?></h2>                                                    
                                                        <a class="website" href="<?php echo get_field('website_url'); ?>" target="_blank" rel="noopener noreferrer"><img loading="lazy" class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/website.svg" alt="Website" width="28" /></a>
                                                    </span>
                                                </span>
                                                <span class="bio-bottom">
                                                    <?php echo get_field('partner_details'); ?>                                               
                                                </span>
                                               
                                            </div>
                                             <span class="speaker-button-container">
                                                    <span class="std-button form-popup-button-container red-button"><?php echo get_field( 'consulting_partners_form_button', 'options' ); ?></span>
                                                    <span style="display:none"><?php echo get_field( 'consulting_partners_form_script', 'options' ); ?></span>
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
                <div class="page-navi-container partner-pagination-container">
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
            