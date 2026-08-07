<?php global $displayed_posts;
$displayed_posts = array ();

?>
<?php

$q = get_queried_object();
$resourceType = get_field( 'type', $q );
$keyword = isset($_GET['searchWords']) ? sanitize_text_field($_GET['searchWords']) : '';
$filterTopic = isset($_GET['filter-topic']) ? sanitize_text_field($_GET['filter-topic']) : '';

// Tracks whether a high-priority (LCP) image has already been output on this page,
// so we never mark more than one image fetchpriority="high" per request.
$priorityImageRendered = false;

/**
 * Build the image attribute array for wp_get_attachment_image(), marking the
 * image eager + fetchpriority high only if it's the first priority-eligible
 * image on the page. Hover-state images should never be passed as priority.
 */
if ( ! function_exists( 'resources_image_attrs' ) ) {
    function resources_image_attrs( $alt, $isPriorityCandidate, &$priorityImageRendered ) {
        $attrs = array(
            'alt'     => $alt,
            'loading' => 'lazy',
        );
        if ( $isPriorityCandidate && ! $priorityImageRendered ) {
            $attrs['loading']      = 'eager';
            $attrs['fetchpriority'] = 'high';
            $priorityImageRendered = true;
        }
        return $attrs;
    }
}

if($keyword != '') {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'resource-type',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
} else {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'resource-type',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
}
?>
<?php if ( $q->slug == 'in-the-news' || $q->slug == 'media' ){ ?>
    <section class="filter-listing in-the-news-listing <?php echo $q->slug; ?><?php if($q->slug == 'media' ){ ?> background-black<?php } else { ?> background-secondary-light-grey<?php } ?>">
        <div class="container">
            <div class="title-container">
                <h1 class="type-title <?php if($q->slug == 'media' ){ ?>text-white<?php } else { ?>text-black<?php } ?>"><?php echo $q->name; ?></h1>
                <span class="type-description <?php if($q->slug == 'media' ){ ?>text-white<?php } else { ?>text-black<?php } ?>"><?php echo $q->description; ?></span>
            </div>
            <?php if($q->slug == 'media' ){ ?>
                <div class="sidebar-container">
                    <span class="media-enquiries-container">
                    </span>
                </div>
            <?php } ?>
        </div>
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 5,
                'paged'=> $paged,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'resource-type',
                        'field' => 'slug',
                        'terms'    => $q->slug
                    ),
                )
            );
            $posts = new WP_Query( $args );
            if($q->slug == 'media' ){ ?>
                <?php if( $posts->have_posts() ): ?>
                    <div class="press-listing-container media-listing-container">
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <?php get_template_part( 'templates/types-components/_media-all' ); ?>
                        <?php endwhile; ?>
                    </div>
                <?php endif;?>
            <?php } else { ?>
                <?php if( $posts->have_posts() ): ?>
                    <div class="press-listing-container news-listing-container">
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <?php get_template_part( 'templates/types-components/_press-releases-all' ); ?>
                        <?php endwhile; ?>
                    </div>
                <?php endif;?>
            <?php } ?>
    </section>
<?php } else { ?>
    <section class="filter-title-block<?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> background-black<?php } ?>" <?= $q->slug == 'podcast' ? 'style="padding-bottom: 0;"' : ''; ?>>
        <div class="container <?= $q->slug == 'podcast' ? 'podcast-container' : ''; ?>">
            <div class="title-container">
                <h1 class="type-title<?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?= $q->slug == 'podcast' ? 'Insider ' : ''; ?><?php echo $q->name; ?></h1>
                <p class="type-description<?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?php echo $q->description; ?></p>
            </div>
            <?php if( $q->slug == 'podcast' ) : ?>
            <div class="podcast-listen-on-container">
                <div class="podcast-listen-on-wrapper">
                    <div>Listen on:</div>
                    <div class="podcast-links">
                        <?php if( get_field('podcast_spotify', 'option') ) : ?>
                        <a href="<?= get_field('podcast_spotify', 'option'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Spotify Link">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_21501_12855)">
                                    <path d="M15.625 3.08325C8.49483 3.08325 2.70837 8.86971 2.70837 15.9999C2.70837 23.1301 8.49483 28.9166 15.625 28.9166C22.7552 28.9166 28.5417 23.1301 28.5417 15.9999C28.5417 8.86971 22.7552 3.08325 15.625 3.08325Z" fill="white"/>
                                    <path d="M23.886 14.7025C23.6152 14.7025 23.4485 14.6348 23.2141 14.4993C19.5058 12.2858 12.8756 11.7546 8.5839 12.9525C8.3964 13.0046 8.16203 13.0879 7.91203 13.0879C7.22453 13.0879 6.69849 12.5514 6.69849 11.8587C6.69849 11.1504 7.13599 10.7493 7.60474 10.6139C9.43807 10.0775 11.4902 9.82227 13.7245 9.82227C17.5266 9.82227 21.511 10.6139 24.4224 12.3118C24.8287 12.5462 25.0943 12.8691 25.0943 13.4889C25.0943 14.1973 24.5214 14.7025 23.886 14.7025ZM22.2714 18.6712C22.0006 18.6712 21.8183 18.5514 21.6308 18.4525C18.3756 16.5254 13.5214 15.7493 9.20369 16.9212C8.95369 16.9889 8.81828 17.0566 8.5839 17.0566C8.02661 17.0566 7.57349 16.6035 7.57349 16.0462C7.57349 15.4889 7.84432 15.1191 8.38078 14.9681C9.8287 14.5618 11.3079 14.2598 13.4745 14.2598C16.8547 14.2598 20.1204 15.0983 22.6933 16.6296C23.1152 16.8796 23.2818 17.2025 23.2818 17.6556C23.2766 18.2181 22.8391 18.6712 22.2714 18.6712ZM20.8704 22.0879C20.6516 22.0879 20.5162 22.0202 20.3131 21.9004C17.0631 19.9421 13.2818 19.8587 9.54745 20.6243C9.34432 20.6764 9.0787 20.7598 8.92765 20.7598C8.42245 20.7598 8.10474 20.3587 8.10474 19.9368C8.10474 19.4004 8.42244 19.1452 8.81307 19.0618C13.0787 18.1191 17.4381 18.2025 21.1568 20.4264C21.4745 20.6296 21.662 20.8118 21.662 21.2858C21.662 21.7598 21.2922 22.0879 20.8704 22.0879Z" fill="#222222"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_21501_12855">
                                        <rect width="25.8333" height="26.6667" fill="white" transform="translate(2.70837 2.66663)"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php if( get_field('podcast_itunes', 'option') ) : ?>
                        <a href="<?= get_field('podcast_itunes', 'option'); ?>" target="_blank" rel="noopener noreferrer" aria-label="iTunes Link">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.931 2.66638C21.2484 2.66638 21.5658 2.66686 21.8831 2.66833C22.1503 2.66981 22.4177 2.67268 22.6849 2.68005C23.2673 2.69556 23.8552 2.73004 24.431 2.83337C25.0149 2.93894 25.5594 3.11005 26.0902 3.38025C26.6121 3.64603 27.0901 3.99342 27.5042 4.40759C27.9183 4.82171 28.2649 5.29882 28.5306 5.82068C28.8008 6.35218 28.9726 6.89619 29.0775 7.48083C29.1808 8.05661 29.2163 8.64353 29.2318 9.22595C29.2392 9.49312 29.242 9.76054 29.2435 10.0277L29.2445 10.9799V20.9301C29.2445 21.2474 29.2447 21.5648 29.2425 21.8822C29.241 22.1494 29.2382 22.4168 29.2308 22.684C29.2153 23.2656 29.1808 23.8534 29.0775 24.4291C28.9726 25.0138 28.7998 25.5587 28.5296 26.0902C28.2639 26.612 27.9173 27.0892 27.5033 27.5033C27.0891 27.9174 26.6111 28.2639 26.0892 28.5297C25.5584 28.7999 25.0147 28.9717 24.43 29.0765C23.8544 29.1798 23.2671 29.2153 22.6849 29.2308C22.4178 29.2382 22.1502 29.2411 21.8831 29.2426C21.5657 29.244 21.2475 29.2435 20.93 29.2435H10.9808C10.6635 29.2435 10.3459 29.2448 10.0286 29.2426C9.76155 29.2411 9.49398 29.2382 9.22689 29.2308C8.6445 29.2153 8.05654 29.1799 7.48079 29.0765C6.89683 28.971 6.35243 28.7999 5.82161 28.5297C5.2997 28.2639 4.8217 27.9174 4.40755 27.5033C3.9935 27.0892 3.64693 26.612 3.38118 26.0902C3.11098 25.5587 2.93817 25.0138 2.83333 24.4291C2.73004 23.8534 2.69551 23.2663 2.68001 22.684C2.67264 22.4168 2.66977 22.1494 2.66829 21.8822C2.66682 21.5648 2.66732 21.2474 2.66732 20.9301V10.9799C2.66732 10.6626 2.66682 10.3452 2.66829 10.0287C2.66977 9.76144 2.67263 9.49418 2.68001 9.22693C2.69551 8.64524 2.73 8.0576 2.83333 7.48181C2.93816 6.89714 3.11101 6.35317 3.38118 5.82166C3.64691 5.2998 3.99349 4.82269 4.40755 4.40857C4.82172 3.9944 5.29965 3.647 5.82161 3.38123C6.35239 3.11105 6.89613 2.93918 7.48079 2.83435C8.05653 2.73031 8.64452 2.69629 9.22689 2.68005C9.49398 2.67268 9.76155 2.66981 10.0286 2.66833C10.3459 2.66686 10.6635 2.66638 10.9808 2.66638H20.931ZM20.7513 6.84802L12.8519 8.44177L12.849 8.44275C12.6431 8.48631 12.4815 8.55963 12.3568 8.66443C12.2063 8.79058 12.1229 8.96913 12.0911 9.17712C12.0845 9.22141 12.0736 9.31189 12.0736 9.4447C12.0736 9.4447 12.0736 17.5145 12.0736 19.3314C12.0736 19.5625 12.0553 19.7877 11.8988 19.9789C11.7422 20.1701 11.5483 20.2271 11.3216 20.2728C11.1497 20.3075 10.9779 20.3426 10.806 20.3773C10.1534 20.5087 9.7287 20.5983 9.34408 20.7474C8.97657 20.8899 8.70099 21.0709 8.48177 21.3011C8.047 21.7566 7.87054 22.3748 7.93099 22.9535C7.98265 23.4473 8.20477 23.9197 8.58626 24.2689C8.84388 24.5051 9.16585 24.685 9.54525 24.7611C9.93874 24.8401 10.3583 24.8129 10.971 24.6888C11.2973 24.6231 11.603 24.52 11.8939 24.348C12.1817 24.1782 12.4288 23.9522 12.6214 23.6761C12.8148 23.3993 12.9395 23.0911 13.0081 22.764C13.079 22.4268 13.096 22.122 13.096 21.7855V13.2103C13.096 12.7512 13.2256 12.6295 13.596 12.5394C13.596 12.5394 20.1627 11.2154 20.4691 11.1556C20.8963 11.0738 21.0978 11.1952 21.098 11.6429V17.4974C21.098 17.7291 21.0957 17.9638 20.9378 18.1556C20.7813 18.3469 20.5873 18.4048 20.3607 18.4506C20.1888 18.4852 20.0169 18.5194 19.8451 18.5541C19.1924 18.6855 18.7678 18.7751 18.3831 18.9242C18.0155 19.0667 17.7401 19.2486 17.5208 19.4789C17.0861 19.9344 16.8939 20.5525 16.9544 21.1312C17.0061 21.6251 17.2447 22.0975 17.6263 22.4467C17.8839 22.6828 18.2059 22.8572 18.5853 22.934C18.9786 23.0128 19.3978 22.9849 20.0101 22.8617C20.3363 22.796 20.6422 22.6976 20.9329 22.5258C21.2209 22.356 21.4678 22.129 21.6605 21.8529C21.8539 21.5761 21.9785 21.2678 22.0472 20.9408C22.118 20.6036 22.1214 20.2988 22.1214 19.9623V7.42517C22.1227 6.97073 21.8826 6.6901 21.4554 6.72693C21.3912 6.73283 20.82 6.834 20.7513 6.84802Z" fill="white"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php if( get_field('podcast_youtube', 'option') ) : ?>
                        <a href="<?= get_field('podcast_youtube', 'option'); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube Link">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_21524_9963)">
                                <g clip-path="url(#clip1_21524_9963)">
                                    <path d="M16.2852 5.99023C16.3282 5.99024 25.2255 5.99197 27.4473 6.58789C28.677 6.91726 29.6435 7.88353 29.9727 9.11328C30.5677 11.3405 30.5703 15.9902 30.5703 15.9902C30.5703 15.9902 30.5701 20.64 29.9727 22.8672C29.6435 24.0969 28.677 25.0633 27.4473 25.3926C25.2255 25.9885 16.3282 25.9902 16.2852 25.9902C16.2852 25.9902 7.35024 25.99 5.12305 25.3926C3.89323 25.0633 2.92695 24.097 2.59766 22.8672C2.00029 20.64 2 15.9902 2 15.9902C2 15.9902 2.00029 11.3405 2.59766 9.11328C2.92696 7.88347 3.89325 6.91719 5.12305 6.58789C7.35024 5.99052 16.2852 5.99023 16.2852 5.99023ZM13.4258 20.2754L20.8477 15.9902L13.4258 11.7061V20.2754Z" fill="white"/>
                                </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_21524_9963">
                                        <rect width="28.57" height="20" fill="white" transform="translate(2 5.99023)"/>
                                    </clipPath>
                                    <clipPath id="clip1_21524_9963">
                                        <rect width="28.57" height="20" fill="white" transform="translate(2 5.99023)"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="topic-button-container-outer">
                <div class="topic-button-container filter-button-container">
                    <a class="all filter-button<?php if ( $keyword != '' || $filterTopic != '' ){ ?><?php } else { ?> selected<?php } ?><?php if ($q->slug == 'peer-insights' || $q->slug == 'expert-presentations' ){ ?> peer-insights<?php } ?>" href="<?php echo get_term_link( $q );?>">All</a>
                    <?php $terms = array(); ?>
                    <?php $loop = new WP_Query( $args ); ?>
                    <?php if ( $loop->have_posts() ) : ?>
                        <?php while ( $loop->have_posts() ) : $loop->the_post();
                            $topics = get_the_terms( $post->ID, 'topic' );
                            if($topics){
                                foreach( $topics as $topic ){
                                    if($topic-> parent == 0){
                                        if( ! in_array( $topic, $terms )){
                                            $terms[] = $topic;
                                        }
                                    }
                                }
                            }
                        ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                    <?php endif; ?>
                    <?php wp_reset_query(); ?>
                    <?php wp_reset_postdata(); ?>
                    <?php foreach($terms as $term) { ?>
                        <a href="<?php echo get_term_link( $q );?>?filter-topic=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterTopic == '') { } else { if ($term -> slug == $filterTopic ) { ?> selected<?php }}?><?php if ($q->slug == 'peer-insights' || $q->slug == 'expert-presentations'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php if ( $keyword != '' || $filterTopic != '' ){ ?>
        <section class="filter-listing<?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> background-black<?php } ?>">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <div class="container">
                <div class="grid-wrapper" id="loop">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'paged'=> $paged,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'resource-type',
                            'field' => 'slug',
                            'terms'    => $q->slug
                        ),
                    )
                );

                if ( ! empty( $filterTopic ) ) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'topic',
                        'field'    => 'slug',
                        'terms'    => $filterTopic,
                        'operator' => 'IN',
                    );
                }

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php if ($q->slug == 'best-practices-guides'){ ?>
                            <?php get_template_part( 'templates/types-components/_best-practices-filtered' ); ?>
                        <?php } ?>
                        <?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' ){ ?>
                            <div class="item one-third peer-insights-item">
                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                <?php if ($video_link){ ?>
                                <?php } else { ?>
                                    <?php $video_link = get_field( 'vimeo_code' ); ?>
                                <?php } ?>
                                <span class="video-container">
                                    <?php if ($video_link){ ?>
                                        <a href="<?php the_permalink(); ?>">
                                    <?php } ?>
                                        <span class="bg-container">
                                            <?php
                                            $video_poster_image = get_field( 'video_poster' );
                                            if ( $video_poster_image ) {
                                                echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                            }
                                            ?>
                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                <span class="opacity-overlay"></span>
                                            <?php } ?>
                                            <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                            <?php if ($video_link){ ?>
                                                <span class="video-button">
                                                </span>
                                            <?php } ?>
                                        </span>
                                    <?php if ($video_link){ ?>
                                        </a>
                                    <?php } ?>
                                </span>
                                <span class="item-content-container">
                                    <span class="topic-filter">
                                        <?php if (yoast_get_primary_term_id('topic')) {
                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                            $postTopic = get_term( $primary_term_topic_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'topic' )){
                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                foreach($terms as $term) {
                                                    $postTopic = $term;
                                                }
                                            }
                                        }?>
                                        <?php if ( !empty( $postTopic ) ) { ?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                </span>
                            </div>
                        <?php } ?>
                        <?php if ($q->slug == 'expert-presentations'){ ?>
                            <div class="item one-third peer-insights-item">
                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                <?php if ($video_link){ ?>
                                <?php } else { ?>
                                    <?php $video_link = get_field( 'vimeo_code' ); ?>
                                <?php } ?>
                                <span class="video-container">
                                    <?php if ($video_link){ ?>
                                        <a href="<?php the_permalink(); ?>">
                                    <?php } ?>
                                        <span class="bg-container">
                                            <?php
                                            $video_poster_image = get_field( 'video_poster' );
                                            if ( $video_poster_image ) {
                                                echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                            }
                                            ?>
                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                <span class="opacity-overlay"></span>
                                            <?php } ?>
                                            <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                            <?php if ($video_link){ ?>
                                                <span class="video-button">
                                                </span>
                                            <?php } ?>
                                        </span>
                                    <?php if ($video_link){ ?>
                                        </a>
                                    <?php } ?>
                                </span>
                                <span class="item-content-container">
                                    <span class="topic-filter">
                                        <?php if (yoast_get_primary_term_id('topic')) {
                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                            $postTopic = get_term( $primary_term_topic_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'topic' )){
                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                foreach($terms as $term) {
                                                    $postTopic = $term;
                                                }
                                            }
                                        }?>
                                        <?php if ( !empty( $postTopic ) ) { ?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                </span>
                            </div>

                        <?php } ?>

                        <?php if ($q->slug == 'market-trend-reports'){ ?>
                            <?php if($postCounter == 1){ ?>
                                <div class="market-trend-reports-container-full-width">
                            <?php } ?>
                                <div class="item market-trend-reports">
                                    <span class="image-container">
                                        <a href="<?php the_permalink(); ?>">
                                            <span class="bg-container">
                                                <?php
                                                $featured_image = get_field( 'featured_image' );
                                                if ( $featured_image ) {
                                                    echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                                }
                                                ?>
                                            </span>
                                            <span class="bg-container bg-container-hover">
                                                <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                <?php if ( $listing_hover_image ) { ?>
                                                    <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                    	'alt'     => $listing_hover_image['alt'],
                                                    	'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </a>
                                    </span>
                                    <span class="item-content-container">
                                        <span class="topic-filter">
                                            <?php if (yoast_get_primary_term_id('topic')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                $postTopic = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'topic' )){
                                                    $terms = get_the_terms( $post->ID, 'topic' );
                                                    foreach($terms as $term) {
                                                        $postTopic = $term;
                                                    }
                                                }
                                            }?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                    </span>
                                </div>
                            <?php if($postCounter == 9){ ?>
                            </div>
                        </div>
                    </div>
                    <div class="market-trend-reports-container-three-post three-post-container background-light-grey">
                        <div class="container">
                            <div class="grid-wrapper">
                            <?php } ?>

                        <?php } ?>
                        <?php if ($q->slug == 'articles'){ ?>
                            <div class="item one-third articles">
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php
                                            $featured_image = get_field( 'featured_image' );
                                            if ( $featured_image ) {
                                                echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                            }
                                            ?>
                                        </span>
                                    </a>
                                </span>
                                <span class="item-content-container">
                                    <span class="topic-filter">
                                        <?php if (yoast_get_primary_term_id('topic')) {
                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                            $postTopic = get_term( $primary_term_topic_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'topic' )){
                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                foreach($terms as $term) {
                                                    $postTopic = $term;
                                                }
                                            }
                                        }?>
                                        <?php if ( !empty( $postTopic ) ) { ?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                </span>
                            </div>
                        <?php } ?>
                        <?php $postCounter++; ?>
                    <?php endwhile; ?>
                    <?php if ($q->slug == 'market-trend-reports'){ ?>
                        </div>
                    <?php } ?>
                <?php endif;?>
            </div>
        </div>
        <div class="page-navi-container">
            <div class="container">
                <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                    <?php wp_reset_postdata(); ?>
                <?php wp_reset_query(); ?>
            </div>
        </div>
        </section>

    

    <?php } else { ?>
        <!-- Featured Post Container  -->
        <?php  if(!is_paged()) { ?>
            <?php if ( have_rows( 'filter_landing_content', $q ) ): ?>
            	<?php while ( have_rows( 'filter_landing_content', $q ) ) : the_row(); ?>
                    <?php if ( get_row_layout() == 'featured_post' ) : ?>
                        <section class="resources-featured featured-module filter-featured-post <?php echo $resourceType; ?><?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> background-black<?php } ?>">
                            <div class="container">
                                <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                <?php if (get_sub_field( 'featured_or_most_recent' ) == 'featured') { ?>
                                    <?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?>
                                        <div class="column one-half insights-featured-column">
                                            <?php if ( $post_object ): ?>
                                                <?php $post = $post_object; ?>
                                                <?php setup_postdata( $post ); ?>
                                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                				<?php if ($video_link){ ?>
                                				<?php } else { ?>
                                					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                				<?php } ?>
                                                <span class="video-container">
                                                    <?php if ($video_link){ ?>
                                                        <a href="<?php the_permalink(); ?>">
                                                    <?php } ?>
                                                        <span class="bg-container">
                                                            <?php
                                                            $video_poster_image = get_field( 'video_poster' );
                                                            if ( $video_poster_image ) {
                                                                echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], true, $priorityImageRendered ) );
                                                            } else {
                                                                $featured_image = get_field( 'featured_image' );
                                                                if ( $featured_image ) {
                                                                    echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], true, $priorityImageRendered ) );
                                                                }
                                                            }
                                                            ?>
                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                <span class="opacity-overlay"></span>
                                                            <?php } ?>
                                                            <?php if ( get_field( 'video_time' )){ ?><span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span><?php } ?>                                                            
                                                            <?php if ($video_link){ ?>
                                                                <span class="video-button">
                                                                </span>
                                                            <?php } ?>
                                                            
                                                        </span>
                                                    <?php if ($video_link){ ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                                <span class="item-content-container">
                                                    <span class="topic-filter">
                                                        <?php if (yoast_get_primary_term_id('topic')) {
                                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                            $postTopic = get_term( $primary_term_topic_id );
                                                        } else {
                                                            if(get_the_terms( $post->ID, 'topic' )){
                                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                                foreach($terms as $term) {
                                                                    $postTopic = $term;
                                                                }
                                                            }
                                                        }?>
                                                        <?php if ( !empty( $postTopic ) ) { ?>
                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                        <?php } ?>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                                </span>
                                                <?php wp_reset_postdata(); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="side-bar-column one-half">
                                            <div class="recent-sidebar">
                                                <?php if ( have_rows( 'sidebar_posts' ) ) : ?>
                                                    <?php while ( have_rows( 'sidebar_posts' ) ) : the_row(); ?>
                                                        <?php if ( have_rows( 'posts' ) ) : ?>
                                                            <?php while ( have_rows( 'posts' ) ) : the_row(); ?>
                                                                <?php $post_object = get_sub_field( 'post' ); ?>
                                                                <?php if ( $post_object ): ?>
                                                                    <?php $post = $post_object; ?>
                                                                    <?php setup_postdata( $post ); ?>
                                                                    <div class="resources-side-posts">
                                                                        <div class="resources-side-posts-inner">
                                                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                                            <?php if ($video_link){ ?>
                                                                            <?php } else { ?>
                                                                                <?php $video_link = get_field( 'vimeo_code' ); ?>
                                                                            <?php } ?>
                                                                            <?php if ($video_link){ ?>
                                                                                <a href="<?php the_permalink(); ?>">
                                                                                    <span class="video-container">
                                                                                        <span class="bg-container">
                                                                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                                                            <?php if ( $video_poster_image ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, array(
                                                                                                	'alt'     => $video_poster_image['alt'],
                                                                                                	'loading' => 'lazy',
                                                                                                ) ); ?>
                                                                                            <?php } ?>
                                                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                                <span class="opacity-overlay"></span>
                                                                                            <?php } ?>
                                                                                            <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                                                            <?php if ($video_link){ ?>
                                                                                                <span class="video-button">
                                                                                                </span>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                    </span>
                                                                                </a>
                                                                            <?php } else { ?>
                                                                                <span class="image-container">
                                                                                    <a href="<?php the_permalink(); ?>">
                                                                                        <span class="bg-container">
                                                                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                                            <?php if ( $featured_image ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, array(
                                                                                                	'alt'     => $featured_image['alt'],
                                                                                                	'loading' => 'lazy',
                                                                                                ) ); ?>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                    </a>
                                                                                </span>
                                                                            <?php } ?>
                                                                            <div class="post-content-container">
                                                                                <span class="topic-filter">
                                                                                    <?php if (yoast_get_primary_term_id('topic')) {
                                                                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                                        $postTopic = get_term( $primary_term_topic_id );
                                                                                    } else {
                                                                                        if(get_the_terms( $post->ID, 'topic' )){
                                                                                            $terms = get_the_terms( $post->ID, 'topic' );
                                                                                            foreach($terms as $term) {
                                                                                                $postTopic = $term;
                                                                                            }
                                                                                        }
                                                                                    }?>
                                                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                                    <?php } ?>
                                                                                </span>
                                                                                <a href="<?php the_permalink(); ?>" class="title text-white"><h4 role="heading" aria-level="2" class="title text-white labelLarge"><?php the_title(); ?></h4></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php wp_reset_postdata(); ?>
                                                                <?php endif; ?>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ( $post_object ): ?>
                                        	<?php $post = $post_object; ?>
                                        	<?php setup_postdata( $post ); ?>
                                                <div class="item <?php echo $q->slug; ?> full-width">
                                                    <div class="item-column one-half image-column">
                                                        <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                        <?php if ($video_link){ ?>
                                                        <?php } else { ?>
                                                            <?php $video_link = get_field( 'vimeo_code' ); ?>
                                                        <?php } ?>
                                                        <?php if ($video_link){ ?>
                                                            <span class="video-container">
                                                                <a href="<?php the_permalink(); ?>">
                                                                    <span class="bg-container">
                                                                        <?php
                                                                        $video_poster_image = get_field( 'video_poster' );
                                                                        if ( $video_poster_image ) {
                                                                            echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], true, $priorityImageRendered ) );
                                                                        }
                                                                        ?>
                                                                        <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                            <span class="opacity-overlay"></span>
                                                                        <?php } ?>
                                                                        <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                                        <?php if ($video_link){ ?>
                                                                            <span class="video-button">
                                                                            </span>
                                                                        <?php } ?>
                                                                    </span>
                                                                </a>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="image-container">
                                                                <a href="<?php the_permalink(); ?>">
                                                                    <span class="bg-container">
                                                                        <?php
                                                                        $featured_image = get_field( 'featured_image' );
                                                                        if ( $featured_image ) {
                                                                            echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], true, $priorityImageRendered ) );
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                                    <?php if ( $listing_hover_image ) { ?>
                                                                        <span class="bg-container bg-container-hover">
                                                                            <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                                            	'alt'     => $listing_hover_image['alt'],
                                                                            	'loading' => 'lazy',
                                                                            ) ); ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                </a>
                                                            </span>
                                                        <?php }?>
                                                    </div>
                                                    <div class="item-column one-half text-column">
                                                        <span class="item-content-container">
                                                            <span class="topic-filter">
                                                                <?php if (yoast_get_primary_term_id('topic')) {
                                                                    $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                    $postTopic = get_term( $primary_term_topic_id );
                                                                } else {
                                                                    if(get_the_terms( $post->ID, 'topic' )){
                                                                        $terms = get_the_terms( $post->ID, 'topic' );
                                                                        foreach($terms as $term) {
                                                                            $postTopic = $term;
                                                                        }
                                                                    }
                                                                }?>
                                                                <?php if ( !empty( $postTopic ) ) { ?>
                                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                <?php } ?>
                                                            </span>
                                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                                            <span class="excerpt"><?php the_excerpt(); ?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                                    <?php if($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?>
                                        <?php $postNumber = 4 ?>
                                    <?php } else { ?>
                                        <?php $postNumber = 1 ?>
                                    <?php } ?>
                                    <?php
                                        $args = array(
                                            'post_type' => 'post',
                                            'posts_per_page' => $postNumber,
                                            'paged'=> $paged,
                                            'tax_query' => array(
                                                'relation' => 'AND',
                                                array (
                                                    'taxonomy' => 'resource-type',
                                                    'field' => 'slug',
                                                    'terms'    => $q->slug
                                                )
                                            )
                                        );
                                        $posts = new WP_Query( $args ); ?>
                                        <?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?>
                                            <?php if( $posts->have_posts() ): ?>
                                                <?php $peerCounter = 1; ?>
                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                    <?php if($peerCounter == 1){ ?>
                                                        <div class="column one-half insights-featured-column">
                                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                            				<?php if ($video_link){ ?>
                                            				<?php } else { ?>
                                            					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                            				<?php } ?>
                                                            <span class="video-container">
                                                                <?php if ($video_link){ ?>
                                                                    <a href="<?php the_permalink(); ?>">
                                                                <?php } ?>
                                                                    <span class="bg-container">
                                                                        <?php
                                                                        $video_poster_image = get_field( 'video_poster' );
                                                                        if ( $video_poster_image ) {
                                                                            echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], $peerCounter == 1, $priorityImageRendered ) );
                                                                        } else {
                                                                            $featured_image = get_field( 'featured_image' );
                                                                            if ( $featured_image ) {
                                                                                echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], $peerCounter == 1, $priorityImageRendered ) );
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                            <span class="opacity-overlay"></span>
                                                                        <?php } ?>
                                                                        <?php if ( get_field( 'video_time' )){ ?><span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span><?php } ?>                                                            
                                                                        <?php if ($video_link){ ?>
                                                                            <span class="video-button">
                                                                            </span>
                                                                        <?php } ?>
                                                                        
                                                                    </span>
                                                                <?php if ($video_link){ ?>
                                                                    </a>
                                                                <?php } ?>
                                                            </span>
                                                            <span class="item-content-container">
                                                                <span class="topic-filter">
                                                                    <?php if (yoast_get_primary_term_id('topic')) {
                                                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                        $postTopic = get_term( $primary_term_topic_id );
                                                                    } else {
                                                                        if(get_the_terms( $post->ID, 'topic' )){
                                                                            $terms = get_the_terms( $post->ID, 'topic' );
                                                                            foreach($terms as $term) {
                                                                                $postTopic = $term;
                                                                            }
                                                                        }
                                                                    }?>
                                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                    <?php } ?>
                                                                </span>
                                                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                                            </span>

                                                        </div>
                                                        <div class="side-bar-column one-half">
                                                            <div class="recent-sidebar">
                                                    <?php } else { ?>
                                                        <div class="resources-side-posts">
                                                            <div class="resources-side-posts-inner">
                                                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                				<?php if ($video_link){ ?>
                                                				<?php } else { ?>
                                                					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                                				<?php } ?>
                                                                <?php if ($video_link){ ?>
                                                                    <a href="<?php the_permalink(); ?>">
                                                                        <span class="video-container">
                                                                            <span class="bg-container">
                                                                                <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                                                <?php if ( $video_poster_image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, array(
                                                                                    	'alt'     => $video_poster_image['alt'],
                                                                                    	'loading' => 'lazy',
                                                                                    ) ); ?>
                                                                                <?php } ?>
                                                                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                    <span class="opacity-overlay"></span>
                                                                                <?php } ?>
                                                                                <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                                                <?php if ($video_link){ ?>
                                                                                    <span class="video-button">
                                                                                    </span>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <span class="image-container">
                                                                        <a href="<?php the_permalink(); ?>">
                                                                            <span class="bg-container">
                                                                                <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                                <?php if ( $featured_image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, array(
                                                                                    	'alt'     => $featured_image['alt'],
                                                                                    	'loading' => 'lazy',
                                                                                    ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </a>
                                                                    </span>
                                                                <?php } ?>
                                                                <div class="post-content-container">
                                                                    <span class="topic-filter">
                                                                        <?php if (yoast_get_primary_term_id('topic')) {
                                                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                            $postTopic = get_term( $primary_term_topic_id );
                                                                        } else {
                                                                            if(get_the_terms( $post->ID, 'topic' )){
                                                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                                                foreach($terms as $term) {
                                                                                    $postTopic = $term;
                                                                                }
                                                                            }
                                                                        }?>
                                                                        <?php if ( !empty( $postTopic ) ) { ?>
                                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <a href="<?php the_permalink(); ?>" class="title text-white"><h4 class="title text-white labelLarge"><?php the_title(); ?></h4></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                <?php $peerCounter++; ?>
                                                <?php endwhile; ?>
                                                        </div>
                                                    </div>
                                                <?php wp_reset_postdata(); ?>
                                            <?php endif; ?>
                                        <?php } else { ?>
                                            <?php if( $posts->have_posts() ): ?>
                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                    <div class="item <?php echo $q->slug; ?> full-width">
                                                        <div class="item-column one-half image-column">
                                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                            <?php if ($video_link){ ?>
                                                                <span class="video-container">
                                                                    <a href="<?php the_permalink(); ?>">
                                                                        <span class="bg-container">
                                                                            <?php
                                                                            $video_poster_image = get_field( 'video_poster' );
                                                                            if ( $video_poster_image ) {
                                                                                echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], true, $priorityImageRendered ) );
                                                                            }
                                                                            ?>
                                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                <span class="opacity-overlay"></span>
                                                                            <?php } ?>
                                                                            <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                                            <?php if ($video_link){ ?>
                                                                                <span class="video-button">
                                                                                </span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </a>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="image-container">
                                                                    <a href="<?php the_permalink(); ?>">
                                                                        <span class="bg-container">
                                                                            <?php
                                                                            $featured_image = get_field( 'featured_image' );
                                                                            if ( $featured_image ) {
                                                                                echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], true, $priorityImageRendered ) );
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                    </a>
                                                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                                    <?php if ( $listing_hover_image ) { ?>
                                                                        <span class="bg-container bg-container-hover">
                                                                            <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                                            	'alt'     => $listing_hover_image['alt'],
                                                                            	'loading' => 'lazy',
                                                                            ) ); ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                </span>
                                                            <?php }?>
                                                        </div>
                                                        <div class="item-column one-half text-column">
                                                            <span class="item-content-container">
                                                                <span class="topic-filter">
                                                                    <?php if (yoast_get_primary_term_id('topic')) {
                                                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                        $postTopic = get_term( $primary_term_topic_id );
                                                                    } else {
                                                                        if(get_the_terms( $post->ID, 'topic' )){
                                                                            $terms = get_the_terms( $post->ID, 'topic' );
                                                                            foreach($terms as $term) {
                                                                                $postTopic = $term;
                                                                            }
                                                                        }
                                                                    }?>
                                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                    <?php } ?>
                                                                </span>
                                                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                                                <span class="excerpt text-black"><?php the_excerpt(); ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                                <?php wp_reset_postdata(); ?>
                                			<?php endif; ?>
                                        <?php } ?>
                                <?php } ?>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <?php // no layouts found ?>
            <?php endif; ?>
        <?php } ?>
        <section class="filter-listing<?php if ($q->slug == 'peer-insights' || $q->slug == 'podcast' || $q->slug == 'expert-presentations'){ ?> background-black<?php } ?>">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <div class="container">
                <?php if ($q->slug == 'peer-insights' || $q->slug == 'expert-presentations'){ ?>
                    <?php $pagNumber = 8; ?>
                <?php } ?>
                <?php if ($q->slug == 'market-trend-reports'){ ?>
                    <?php $pagNumber = 18; ?>
                <?php } ?>
                <?php if ($q->slug == 'best-practices-guides' || $q->slug == 'podcast'){ ?>
                    <?php $pagNumber = 9; ?>
                <?php } ?>
                <?php if ($q->slug == 'articles'){ ?>
                    <?php $pagNumber = 12; ?>
                <?php } ?>
                <div class="grid-wrapper" id="loop">
                    <?php $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => $pagNumber,
                        'paged'=> $paged,
                        'tax_query' => array(
                            'relation' => 'AND',
                            array (
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms'    => $q->slug
                            ),
                        )
                    );
                    $posts = new WP_Query( $args ); ?>
                    <?php if ($q->slug == 'market-trend-reports'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <?php if($postCounter == 1){ ?>
                                    <div class="market-trend-reports-container-side-bar">
                                <?php } ?>
                                    <div class="item market-trend-reports">
                                        <span class="image-container">
                                            <a href="<?php the_permalink(); ?>">
                                                <span class="bg-container">
                                                    <?php
                                                    $featured_image = get_field( 'featured_image' );
                                                    if ( $featured_image ) {
                                                        echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                                    }
                                                    ?>
                                                </span>
                                                <span class="bg-container bg-container-hover">
                                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                    <?php if ( $listing_hover_image ) { ?>
                                                    	<?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                    		'alt'     => $listing_hover_image['alt'],
                                                    		'loading' => 'lazy',
                                                    	) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </a>
                                        </span>
                                        <span class="item-content-container">
                                            <span class="topic-filter">
                                                <?php if (yoast_get_primary_term_id('topic')) {
                                                    $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                    $postTopic = get_term( $primary_term_topic_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'topic' )){
                                                        $terms = get_the_terms( $post->ID, 'topic' );
                                                        foreach($terms as $term) {
                                                            $postTopic = $term;
                                                        }
                                                    }
                                                }?>
                                                <?php if ( !empty( $postTopic ) ) { ?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                        </span>
                                    </div>
                                <?php if($postCounter == 6){ ?>
                                </div>
                                <div class="sidebar market-trend-reports-sidebar">
                                    <span class="subscribe-sidebar-form background-pink">
                                        <span class="icon-container">
                                            <span class="icon-inner">
                                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                            </span>
                                        </span>
                                        <span class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></span>
                                        <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>

                    					<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="market-trend-reports-container-three-post three-post-container background-light-grey">
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php if($postCounter == 9){ ?>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="grid-wrapper">
                                    <div class="market-trend-reports-container-full-width">
                                <?php } ?>
                                <?php $postCounter++; ?>
                            <?php endwhile; ?>
                        <?php endif;?>
                        </div>
                    <?php } ?>
                    <?php if ($q->slug == 'best-practices-guides'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <div class="item one-third best-practice-item">
                                        <span class="image-container">
                                            <a href="<?php the_permalink(); ?>">
                                                <span class="bg-container">
                                                    <?php
                                                    $best_practice_listing_image = get_field( 'best_practice_listing_image' );
                                                    if ( $best_practice_listing_image ) {
                                                        echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'adapt-optimized', false, resources_image_attrs( $best_practice_listing_image['alt'], $posts->current_post === 0, $priorityImageRendered ) );
                                                    }
                                                    ?>
                                                </span>
                                            </a>
                                            <span class="content-container-absolute">
                                                <span class="topic-filter">
                                                    <?php if (yoast_get_primary_term_id('topic')) {
                                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                        $postTopic = get_term( $primary_term_topic_id );
                                                    } else {
                                                        if(get_the_terms( $post->ID, 'topic' )){
                                                            $terms = get_the_terms( $post->ID, 'topic' );
                                                            foreach($terms as $term) {
                                                                $postTopic = $term;
                                                            }
                                                        }
                                                    }?>
                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge <?php echo get_field( 'best_practice_listing_text_colour' ); ?>"><?php the_title(); ?></a>
                                            </span>
                                        </span>
                            </div>
                            <?php endwhile; ?>
                            <?php $postCounter++; ?>
                        <?php endif;?>
                    <?php } ?>
                    <?php if ( $q->slug == 'podcast' ){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <div class="peer-insights-full-container podcast-insights-full-container">
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <div class="item one-third peer-insights-item">
                                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    				<?php if ($video_link){ ?>
                    				<?php } else { ?>
                    					<?php $video_link = get_field( 'vimeo_code' ); ?>
                    				<?php } ?>
                                    <span class="video-container">
                                        <?php if ($video_link){ ?>
                                            <a href="<?php the_permalink(); ?>">
                                        <?php } ?>
                                            <span class="bg-container">
                                                <?php
                                                $video_poster_image = get_field( 'video_poster' );
                                                if ( $video_poster_image ) {
                                                    echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], $posts->current_post === 0, $priorityImageRendered ) );
                                                }
                                                ?>
                                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                    <span class="opacity-overlay"></span>
                                                <?php } ?>
                                                <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                <?php if ($video_link){ ?>
                                                    <span class="video-button">
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        <?php if ($video_link){ ?>
                                            </a>
                                        <?php } ?>
                                    </span>
                                    <span class="item-content-container">
                                        <span class="topic-filter">
                                            <?php if (yoast_get_primary_term_id('topic')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                $postTopic = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'topic' )){
                                                    $terms = get_the_terms( $post->ID, 'topic' );
                                                    foreach($terms as $term) {
                                                        $postTopic = $term;
                                                    }
                                                }
                                            }?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                    </span>
                                </div>
                        <?php endwhile; ?>
                        </div>
                    <?php endif;?>
                    <?php } ?>
                    <?php if ($q->slug == 'peer-insights' || $q->slug == 'expert-presentations'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <?php if($postCounter == 1) { ?>
                                    <div class="peer-insights-container-side-bar asd">
                                <?php } ?>
                                <div class="item one-third peer-insights-item">
                                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    				<?php if ($video_link){ ?>
                    				<?php } else { ?>
                    					<?php $video_link = get_field( 'vimeo_code' ); ?>
                    				<?php } ?>
                                    <span class="video-container">
                                        <?php if ($video_link){ ?>
                                            <a href="<?php the_permalink(); ?>">
                                        <?php } ?>
                                            <span class="bg-container">
                                                <?php
                                                $video_poster_image = get_field( 'video_poster' );
                                                if ( $video_poster_image ) {
                                                    echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, resources_image_attrs( $video_poster_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                                }
                                                ?>
                                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                    <span class="opacity-overlay"></span>
                                                <?php } ?>
                                                <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                                <?php if ($video_link){ ?>
                                                    <span class="video-button">
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        <?php if ($video_link){ ?>
                                            </a>
                                        <?php } ?>
                                    </span>
                                    <span class="item-content-container">
                                        <span class="topic-filter">
                                            <?php if (yoast_get_primary_term_id('topic')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                $postTopic = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'topic' )){
                                                    $terms = get_the_terms( $post->ID, 'topic' );
                                                    foreach($terms as $term) {
                                                        $postTopic = $term;
                                                    }
                                                }
                                            }?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                    </span>
                                </div>
                                <?php if($postCounter == 2){ ?>
                                    <div class="sidebar">
                                        <span class="subscribe-sidebar-form background-tertiary-black">
                                            <span class="icon-container">
                                                <span class="icon-inner">
                                                    <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                                </span>
                                            </span>
                                            <h5 class="labelXXLarge text-white"><?php echo get_field( 'title', 'options' ); ?></h5>
                                            <p class="text-white"><?php echo get_field( 'text', 'options' ); ?></p>

                        					<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="peer-insights-full-container">
                                <?php } ?>
                                <?php $postCounter++; ?>
                        <?php endwhile; ?>
                        </div>
                    <?php endif;?>
                    <?php } ?>
                    <?php if ($q->slug == 'articles'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                    <div class="item one-third articles">
                                        <span class="image-container">
                                            <a href="<?php the_permalink(); ?>">
                                                <span class="bg-container">
                                                    <?php
                                                    $featured_image = get_field( 'featured_image' );
                                                    if ( $featured_image ) {
                                                        echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, resources_image_attrs( $featured_image['alt'], $postCounter == 1, $priorityImageRendered ) );
                                                    }
                                                    ?>
                                                </span>
                                            </a>
                                        </span>
                                        <span class="item-content-container">
                                            <span class="topic-filter">
                                                <?php if (yoast_get_primary_term_id('topic')) {
                                                    $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                    $postTopic = get_term( $primary_term_topic_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'topic' )){
                                                        $terms = get_the_terms( $post->ID, 'topic' );
                                                        foreach($terms as $term) {
                                                            $postTopic = $term;
                                                        }
                                                    }
                                                }?>
                                                <?php if ( !empty( $postTopic ) ) { ?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                        </span>
                                    </div>
                                <?php if($postCounter == 3){ ?>
                            </div>
                        </div>
                        <div class="article-container-three-post three-post-container background-light-grey">
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php if($postCounter == 9){ ?>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php $postCounter++; ?>
                            <?php endwhile; ?>
                        <?php endif;?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="page-navi-container asdasdas">
                <div class="container">
                    <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                    <?php wp_reset_postdata(); ?>
                    <?php wp_reset_query(); ?>
                </div>
            </div>
        </section>
    <?php } ?>
<?php } ?>


<?php if( $q->slug == 'podcast' ) : ?>
<style>
    section.podcast-newsletter{
        position: relative;
        border-top-left-radius: 36px;
        border-bottom-right-radius: 36px;
    }
    section.podcast-newsletter .container{
        position: relative;
        z-index: 11;
    }
section.podcast-newsletter .absolute-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40px;
    z-index: -1;
}
section.podcast-newsletter .absolute-top {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 40px;
    z-index: -1;
}
</style>
<!-- <section class="podcast-newsletter background-red">
    <span class="absolute-top background-black"></span>
    <span class="absolute-bottom background-black"></span>
    <div class="container" style="text-align: center; padding-top: 80px; padding-bottom: 80px;">
        <h2 class="type-title text-white">Never Miss an Episode</h2>
        <p>Get every new episode delivered straight to your inbox</p>
        <a class="formPopupHubspotHome stdBtn std-button white-button" href="#subscribe-form">Subscribe</a>

        <div style="display: none;">
            <div class="subscribe-form" id="subscribe-form">
                <div class="form-container">
                    <div class="form">
                        <script src="https://js.hsforms.net/forms/embed/developer/8336221.js" defer></script>
                        <div class="hs-form-html" data-region="na1" data-form-id="fb9276c9-f87b-4831-9afe-fe009b819497" data-portal-id="8336221"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<?php endif; ?>