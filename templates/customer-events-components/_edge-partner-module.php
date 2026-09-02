<section class="speaker-module partner-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container align-top">
            <?php if (get_sub_field( 'small_text' )) { ?>
                <span class="label-small"><?php echo get_sub_field( 'small_text' ); ?></span>
            <?php } else { ?>
                <span class="label-small">Our Partners</span>
            <?php } ?>            
            <span class="title-container-inner"> 
                <h2 class="black-text bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
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
                    <form id="edgePartnerFilter">
                        <?php
                            if ( $expertise_ids && ! empty( $expertise_ids ) ) {
                                // Get terms for the selected expertise IDs
                                $expertise_terms = get_terms( array(
                                    'taxonomy' => 'edge-partner-categories',
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
                <div class="speakers" id="edge-partners-container">
                    <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        if ( $expertise_ids ) {
                            // Set up the query arguments
                            $args = array(
                                'post_type'      => 'edge_partners',
                                'posts_per_page' => 12,
                                'paged'         => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'edge-partner-categories',
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
                                    $term_slugs = wp_get_post_terms(get_the_ID(), 'edge-partner-categories', array('fields' => 'slugs'));
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
                                                    <button type="button"
                                                        data-company="<?php the_title(); ?>"
                                                        class="open-form std-button red-button white-text text-white" style="color: #fff;">Request an Introduction
                                                        </button>
                                                    </span>
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
                <div class="page-navi-container edge-partner-pagination-container">
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
<style>
    /* Style for modal */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.7); /* Black w/opacity */
    padding: 30px;
    text-align: center;
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    margin: auto;
    padding: 20px;
    width: 90%;
    max-width: 600px;
    border-radius: 5px;
    display: inline-block;
    text-align: left;
}

/* Close button */
.close-btn {
    color: #fff;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 25px;
}

.close-btn:hover,
.close-btn:focus {
    color: #fff;
    text-decoration: none;
    cursor: pointer;
}

</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get modal and close button
    var modal = document.getElementById("formModal");
    var span = document.querySelector(".close-btn");

    // Check if modal exists to prevent errors
    if (!modal || !span) {
        console.error("Modal or close button not found!");
        return;
    }

    // Attach click event to all buttons with the class `.open-form`
    var buttons = document.querySelectorAll(".open-form");
    buttons.forEach(function (btn) {
        btn.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default link action

            // Get the company name from the button's data attribute
            var companyName = btn.getAttribute("data-company");

            // Populate the hidden field in the HubSpot form
            var hiddenField = document.querySelector('input[name="partner_company_name"]');
            if (hiddenField) {
                hiddenField.value = companyName;
            } else {
                console.error('Hidden field with name "partner_company_name" not found!');
            }

            // Show the modal
            modal.style.display = "block";
        });
    });

    // Close the modal when the close button is clicked
    span.onclick = function () {
        modal.style.display = "none";
    };

    // Close the modal if the user clicks anywhere outside of the modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});


</script>
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span> <!-- Close button -->
        <div class="webinar-register-form">
            <span class="webinar-subtitle"><?php echo get_field( 'edge_partners_form_title', 'options' ); ?></span>
            <div class="form-container">                                
                <?php echo get_field( 'edge_partners_form_script', 'options' ); ?>
            </div>
        </div>
    </div>
</div>
