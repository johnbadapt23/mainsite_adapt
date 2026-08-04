<section class="peer-insights-featured background-black featured-module">
    <div class="container">
        <div class="title-container">
            <div class="introduction-column two-thirds">
                <?php $term = get_term_by('slug', 'expert-presentations', 'resource-type');?>
                <?php if (get_sub_field( 'title' )) {?>
                    <h2 class="taxonomy-title text-white"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="taxonomy-description text-white"><?php echo get_sub_field( 'text' ); ?></p>
                <?php } else { ?>
                    <h2 class="taxonomy-title text-white"><?php echo $term->name; ?></h2>
                    <p class="taxonomy-description text-white"><?php echo $term->description; ?></p>
                <?php } ?>
            </div>
            <div class="link-container one-third">
                <a class="red-text-link" href="<?php echo get_term_link($term); ?>"><?php echo get_sub_field( 'view_all_text' ); ?></a>
            </div>
        </div>
        <div class="post-container">
            <div class="column two-thirds slide-column">
                <div class="expert-featured-slider">
                    <?php if ( get_sub_field( 'select_or_most_recent' ) == 'most-recent') { ?>
                        <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                        <?php
                            $args = array(
                                'post_type' => 'post',
                                'posts_per_page' => 6,
                                'paged'=> $paged,
                                'tax_query' => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'resource-type',
                                        'field' => 'slug',
                                        'terms'    => 'expert-presentations'
                                    ),
                                )
                            );

                        $posts = new WP_Query( $args );
                        if( $posts->have_posts() ): ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <div class="expert-slide">
                                    <div class="peer-slide-inner">
                                        <div class="item peer-insights-item">
                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                            <?php if ($video_link){ ?>
                                            <?php } else { ?>
                                                <?php $video_link = get_field( 'vimeo_code' ); ?>
                                            <?php } ?>
                                            <span class="video-container">
                                                <?php if ($video_link){ ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                <?php } ?>
                                                    <span class="bg-container test">
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
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                            <?php wp_reset_query(); ?>
            			<?php endif; ?>
                    <?php } else { ?>
                        <?php if ( have_rows( 'posts' ) ) : ?>
            				<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
            					<?php $post_object = get_sub_field( 'post' ); ?>
            					<?php if ( $post_object ): ?>
            						<?php $post = $post_object; ?>
            						<?php setup_postdata( $post ); ?>
                                    <div class="expert-slide">
                                        <div class="peer-slide-inner">
                                            <div class="item peer-insights-item">
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
                                        </div>
                                    </div>
            						<?php wp_reset_postdata(); ?>
            					<?php endif; ?>
            				<?php endwhile; ?>
            			<?php else : ?>
            				<?php // no rows found ?>
            			<?php endif; ?>
                    <?php } ?>
                </div>
            </div>
            <div class="column one-third next-slide-container">
                <div class="up-next-container">
                    <h3 class="label-XXLarge text-white">Up Next</h3>
                    <div class="expert-slider-preview"> </div>
                </div>
            </div>
        </div>
    </div>
</section>
