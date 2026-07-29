<section class="advisors-carousel">
    <?php $expertise_ids = get_sub_field('expertise'); ?>
    <?php if ($expertise_ids): ?>

        <?php 
        $carousel_posts = array(); // Store all posts for output & width calculation

        // ---------- LOOP 1: Checked adapt_analyst ----------
        $checked_args = array(
            'post_type'      => 'speaker',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'expertise',
                    'field'    => 'term_id',
                    'terms'    => $expertise_ids,
                    'operator' => 'IN',
                ),
            ),
            'meta_query' => array(
                array(
                    'key'     => 'adapt_analyst',
                    'value'   => '1', // checked
                    'compare' => '=',
                ),
            ),
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        );

        $checked_query = new WP_Query($checked_args);
        if ($checked_query->have_posts()):
            $carousel_posts = array_merge($carousel_posts, $checked_query->posts);
        endif;
        wp_reset_postdata();

        // ---------- LOOP 2: Unchecked / not 1 / empty ----------
        $unchecked_args = array(
            'post_type'      => 'speaker',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'expertise',
                    'field'    => 'term_id',
                    'terms'    => $expertise_ids,
                    'operator' => 'IN',
                ),
            ),
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key'     => 'adapt_analyst',
                    'compare' => 'NOT EXISTS', // key doesn't exist
                ),
                array(
                    'key'     => 'adapt_analyst',
                    'value'   => '1',          // key exists but not 1
                    'compare' => '!=',
                ),
            ),
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        );

        $unchecked_query = new WP_Query($unchecked_args);
        if ($unchecked_query->have_posts()):
            $carousel_posts = array_merge($carousel_posts, $unchecked_query->posts);
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