<section class="featured-module type-two-column<?php if ($q->slug == 'peer-insights'){ ?> background-black<?php } ?>">
    <div class="container">
        <div class="post-container grid-wrapper">
            <?php $topic_term = get_sub_field( 'topic' ); ?>
			<?php if ( $topic_term ): ?>
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                <?php if($q->slug == 'market-trend-reports'){ ?>
                    <?php $postNumber = 6 ?>
                <?php } else { ?>
                    <?php $postNumber = 2 ?>
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
                            ),
                            array (
                                'taxonomy' => 'topic',
                                'field' => 'slug',
                                'terms'    => $topic_term->slug
                            ),
                        )
                    );

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div class="item <?php echo $q->slug; ?>">
                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                            <?php if ($video_link){ ?>
                            <?php } else { ?>
                                <?php $video_link = get_field( 'vimeo_code' ); ?>
                            <?php } ?>
                            <?php if ($video_link){ ?>
                                <span class="video-container">
                                    <a href="<?php the_permalink(); ?>">
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
                                    </a>
                                </span>
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
                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                    <?php if ( $listing_hover_image ) { ?>
                                        <span class="bg-container bg-container-hover">
                                        	<?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'full', false, array(
                                        		'alt'     => $listing_hover_image['alt'],
                                        		'loading' => 'lazy',
                                        	) ); ?>
                                        </span>
                                    <?php } ?>
                                </span>
                            <?php }?>
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
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
    			<?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="sidebar">
            <span class="subscribe-sidebar-form <?php if ($q->slug == 'peer-insights'){ ?> background-tertiary-black<?php } else { ?> background-pink<?php } ?>">
                <span class="icon-container">
                    <span class="icon-inner">
                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                    </span>
                </span>
                <h5 class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></h5>
                <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>
                
                <span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
            </span>
        </div>
    </div>
</section>
