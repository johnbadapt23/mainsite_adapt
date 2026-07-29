<section class="resources-featured featured-module">
    <div class="container">
        <div class="slider-column one-half">
            <?php if ( have_rows( 'post_slider' ) ) : ?>
                <div class="resources-featured-slider">
        			<?php while ( have_rows( 'post_slider' ) ) : the_row(); ?>
                        <?php if ( get_sub_field( 'select_or_most_recent' ) == 'most-recent') { ?>
                            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                            <?php
                                $args = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 3,
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
                                                                <img src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
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
                                                                <img loading="lazy" src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
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
                        <?php } else { ?>
                            <?php if ( have_rows( 'posts' ) ) : ?>
            					<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
            						<?php $post_object = get_sub_field( 'post' ); ?>
            						<?php if ( $post_object ): ?>
            							<?php $post = $post_object; ?>
            							<?php setup_postdata( $post ); ?>
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
                                                                    <img loading="lazy" src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
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
                                                                    <img loading="lazy" src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
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
            							<?php wp_reset_postdata(); ?>
            						<?php endif; ?>
            					<?php endwhile; ?>
            				<?php else : ?>
            					<?php // no rows found ?>
            				<?php endif; ?>
                        <?php } ?>
        			<?php endwhile; ?>
                </div>
    		<?php else : ?>
    			<?php // no rows found ?>
    		<?php endif; ?>
        </div>
        <div class="side-bar-column one-half">
			<?php if ( have_rows( 'sidebar_posts' ) ) : ?>
				<?php while ( have_rows( 'sidebar_posts' ) ) : the_row(); ?>
                    <div class="recent-sidebar">
                        <span class="labelXXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
    					<?php if (get_sub_field( 'most_recent_or_most_popular' ) == 'most-recent') { ?>
                            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                            <?php
                                $args = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 4,
                                    'paged'=> $paged,
                                    'tax_query' => array(
                                        array (
                                            'taxonomy' => 'resource-type',
                                            'field' => 'slug',
                                            'terms'    => 'in-the-news',
                                            'operator' => 'NOT IN',
                                        ),
                                    )
                                );

                            $posts = new WP_Query( $args );
                            if( $posts->have_posts() ): ?>
                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                    <div class="resources-side-posts">
                                        <div class="resources-side-posts">
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
                                                                <img loading="lazy" src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
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
                                                                <img loading="lazy" src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
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
                                                <a href="<?php the_permalink(); ?>" class="title text-black"><h4 class="title text-black labelLarge"><?php the_title(); ?></h4></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
    						<?php endif; ?>
                        <?php } else { ?>
                            <?php
                                query_posts('meta_key=post_views_count&posts_per_page=4&orderby=meta_value_num&order=DESC');
                                if (have_posts()) : while (have_posts()) : the_post();
                             ?>
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
                                                             <img loading="lazy" src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
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
                                                             <img loading="lazy" src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
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
                                             <a href="<?php the_permalink(); ?>" class="title text-black"><h4 class="title text-black labelLarge"><?php the_title(); ?></h4></a>
                                         </div>
                                     </div>
                                 </div>
                             <?php
                             endwhile; endif;
                             wp_reset_query();
                             ?>
                        <?php }?>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
