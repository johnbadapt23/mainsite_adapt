<section class="thank-you-featured articles-featured featured-module">
    <div class="container">
        <div class="thank-you-post-introduction-container">
            <div class="introduction-column">
                <h3 class="introduction black-text"><?php echo get_sub_field( 'introduction' ); ?></h3>
				<?php $arrow_image = get_sub_field( 'arrow_image' ); ?>
				<?php if ( $arrow_image ) { ?>
					<span class="arrow-container">
						<?php echo wp_get_attachment_image( $arrow_image['ID'], 'full', false, array(
							'alt'     => $arrow_image['alt'],
							'loading' => 'lazy',
						) ); ?>
					</span>
				<?php } ?>
            </div>
        </div>
        <div class="post-list-container grid-wrapper">
            <?php if ( get_sub_field( 'select_posts' ) == 'most-recent') { ?>

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
                <?php $articleCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php if ($articleCounter == 1){ ?>
                            <div class="item full-width articles">
                                <div class="item-column image-column one-half">
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
                                </div>
                                <div class="item-column content-column one-half">
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
                        </div>
                    </div>
                    <div class="article-container-three-post background-light-grey">
                        <div class="container">
                            <div class="grid-wrapper">
                        <?php } else { ?>
                            <div class="item one-third articles">
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
                        <?php $articleCounter++; ?>
                    <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                    <?php wp_reset_postdata(); ?>
    			<?php endif; ?>
			<?php } else { ?>
                <?php if ( have_rows( 'posts' ) ) : ?>
                    <?php $articleCounter = 1; ?>
    				<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
    					<?php $post_object = get_sub_field( 'post' ); ?>
    					<?php if ( $post_object ): ?>
    						<?php $post = $post_object; ?>
    						<?php setup_postdata( $post ); ?>
                            <?php if ($articleCounter == 1){ ?>
                                <div class="item full-width articles">
                                    <div class="item-column image-column one-half">
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
                                    </div>
                                    <div class="item-column content-column one-half">
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
                            </div>
                        </div>
                        <div class="article-container-three-post background-light-grey">
                            <div class="container">
                                <div class="grid-wrapper">
                            <?php } else { ?>
                                <div class="item one-third articles">
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
    						<?php wp_reset_postdata(); ?>
    					<?php endif; ?>
                        <?php $articleCounter++; ?>
    				<?php endwhile; ?>
                </div>
            </div>
        </div>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            <?php } ?>
        </div>
    </div>
</section>
