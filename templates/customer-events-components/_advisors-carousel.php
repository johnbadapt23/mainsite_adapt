<section class="advisors-carousel">
    <?php $expertise_ids = get_sub_field('expertise'); ?>
    <?php if ($expertise_ids): ?>

        <?php
        $carousel_posts = array(); // Store all posts for output & width calculation

        // ---------- Speakers, excluding adapt-analysts / adapt-advisors ----------
        $args = array(
            'post_type'      => 'speaker',
            'posts_per_page' => -1,
            'tax_query'      => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'expertise',
                    'field'    => 'term_id',
                    'terms'    => $expertise_ids,
                    'operator' => 'IN',
                ),
                array(
                    'taxonomy' => 'expertise',
                    'field'    => 'term_id',
                    'terms'    => array( 15788, 15789 ), // adapt-analysts, adapt-advisors
                    'operator' => 'NOT IN',
                ),
            ),
            // Replaced by the expertise tax_query exclusion above.
            // 'meta_query' => array(
            //     array(
            //         'key'     => 'adapt_analyst',
            //         'value'   => '1', // checked
            //         'compare' => '=',
            //     ),
            // ),
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        );

        $speakers_query = new WP_Query($args);
        if ($speakers_query->have_posts()):
            $carousel_posts = array_merge($carousel_posts, $speakers_query->posts);
        endif;
        wp_reset_postdata();

        // ---------- CALCULATE CAROUSEL WIDTH & ANIMATION ----------
        $speakers_count = count($carousel_posts);
        $carousel_width = $speakers_count * 280; // adjust width per item
        $animation_duration = $speakers_count * 5; // seconds

        // ---------- OUTPUT CAROUSEL ----------
        if ($speakers_count > 0): ?>
            <div class="carousel-wrapper" style="overflow: hidden;">
                <div class="carousel-container" 
                     style="width: <?php echo $carousel_width; ?>px; animation-duration: <?php echo $animation_duration; ?>s;">
                    <?php
                    foreach ($carousel_posts as $post):
                        setup_postdata($post);
                        $post_slug   = get_post_field('post_name', $post);
                        $term_slugs  = wp_get_post_terms($post->ID, 'expertise', array('fields' => 'slugs'));
                        $filter_slugs = implode(' ', $term_slugs);
                        $team_member_image = get_field('speaker_image', $post->ID);
                        ?>
                        <div class="speaker-item column">
                            <span class="image-container">
                                <span class="bg-container">
                                    <img src="<?php echo esc_url($team_member_image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" />
                                </span>
                                <span class="text-container">
                                    <h5><?php echo get_the_title($post); ?></h5>
                                    <span class="label-Xsmall white-text"><?php echo get_field('speaker_description', $post->ID); ?></span>
                                </span>
                            </span>                    
                        </div>
                    <?php endforeach; wp_reset_postdata(); ?>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carouselContainer = document.querySelector('.carousel-container');
        const carouselItems = carouselContainer.innerHTML;

        // Duplicate items to create the infinite effect
        carouselContainer.innerHTML += carouselItems;

        // Adjust speed if necessary based on total width
        const totalWidth = carouselContainer.offsetWidth;
        const speed = totalWidth / 30; // Adjust as necessary for speed
        carouselContainer.style.animationDuration = `${speed}s`;
    });
</script>