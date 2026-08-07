<section class="resources-featured featured-module featured-home">
    <div class="container">
        <div class="title-container">
            <div class="introduction-column two-thirds">
                    <h2 class="taxonomy-title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
            <div class="link-container one-third">
                <a class="red-text-link" href="/all-resources/">All Resources</a>
            </div>
        </div>
        <div class="post-list-container">
            <?php if ( get_sub_field( 'choose_posts' ) == 'no') { ?>
                <div class="first-post-column one-half">
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                    <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'paged'=> $paged,
                        'tax_query' => array(
                            array (
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms'    => 'in-the-news',
                                'operator' => 'NOT IN',
                            ),
                            array (
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms'    => 'best-practices-guides',
                                'operator' => 'NOT IN',
                            ),
                        )
                    );

                    $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="resources-featured-slide">
                                <div class="resources-slide-inner">
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
                                                        <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                            'alt'     => $video_poster_image['alt'],
                                                            'loading' => false,
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
                                                        <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
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
                                            <?php if (yoast_get_primary_term_id('resource-type')) {
                                                $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                                $postType= get_term( $primary_term_type_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'resource-type' )){
                                                    $terms = get_the_terms( $post->ID, 'resource-type' );
                                                    foreach($terms as $term) {
                                                        $postType= $term;
                                                    }
                                                }
                                            }?>
                                            <?php if ( !empty( $postType ) ) { ?>
                                                <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                            <?php } ?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title text-black"><h2 class="title text-black"><?php the_title(); ?></h2></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
    				<?php endif; ?>
                </div>
                <div class="side-bar-column one-half">
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                    <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'paged'=> $paged,
                            'offset' => 1,
                            'tax_query' => array(
                                array (
                                    'taxonomy' => 'resource-type',
                                    'field' => 'slug',
                                    'terms'    => 'in-the-news',
                                    'operator' => 'NOT IN',
                                ),
                                array (
                                    'taxonomy' => 'resource-type',
                                    'field' => 'slug',
                                    'terms'    => 'best-practices-guides',
                                    'operator' => 'NOT IN',
                                ),
                            )
                        );

                    $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <div class="recent-sidebar">
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
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
                                                        <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
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
                                                        <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
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
                                            <?php if (yoast_get_primary_term_id('resource-type')) {
                                                $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                                $postType= get_term( $primary_term_type_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'resource-type' )){
                                                    $terms = get_the_terms( $post->ID, 'resource-type' );
                                                    foreach($terms as $term) {
                                                        $postType= $term;
                                                    }
                                                }
                                            }?>
                                            <?php if ( !empty( $postType ) ) { ?>
                                                <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                            <?php } ?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title text-black"><h4 role="heading" aria-level="3" class="title text-black labelLarge"><?php the_title(); ?></h4></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                    </div>
                </div>
    <?php } else { ?>
        <?php $post_object = get_sub_field( 'featured_post' ); ?>
			<?php if ( $post_object ): ?>
				<?php $post = $post_object; ?>
				<?php setup_postdata( $post ); ?>
                    <div class="first-post-column one-half">
                        <div class="resources-featured-slide">
                            <div class="resources-slide-inner">
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
                                                    <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
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
                                                    <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
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
                                        <?php if (yoast_get_primary_term_id('resource-type')) {
                                            $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                            $postType= get_term( $primary_term_type_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'resource-type' )){
                                                $terms = get_the_terms( $post->ID, 'resource-type' );
                                                foreach($terms as $term) {
                                                    $postType= $term;
                                                }
                                            }
                                        }?>
                                        <?php if ( !empty( $postType ) ) { ?>
                                            <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                        <?php } ?>
                                        <?php if ( !empty( $postTopic ) ) { ?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title text-black"><h2 class="title text-black"><?php the_title(); ?></h2></a>
                                </div>
                            </div>
                        </div>
					</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
            <?php if ( have_rows( 'side_posts' ) ) : ?>
				<?php while ( have_rows( 'side_posts' ) ) : the_row(); ?>
                    <div class="side-bar-column one-half">
                        <div class="recent-sidebar">
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
                                                            <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
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
                                                            <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
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
                                                <?php if (yoast_get_primary_term_id('resource-type')) {
                                                    $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                                    $postType= get_term( $primary_term_type_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'resource-type' )){
                                                        $terms = get_the_terms( $post->ID, 'resource-type' );
                                                        foreach($terms as $term) {
                                                            $postType= $term;
                                                        }
                                                    }
                                                }?>
                                                <?php if ( !empty( $postType ) ) { ?>
                                                    <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                                <?php } ?>
                                                <?php if ( !empty( $postTopic ) ) { ?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title text-black"><h4 role="heading" aria-level="3" class="title text-black labelLarge"><?php the_title(); ?></h4></a>
                                        </div>
                                    </div>
                                </div>
        						<?php wp_reset_postdata(); ?>
        					<?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
    <?php } ?>
        </div>
    </div>
</section>
