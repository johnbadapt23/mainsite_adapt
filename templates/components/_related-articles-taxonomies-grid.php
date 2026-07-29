
<section class="blogWrapper relatedArticles post-insights gridModule scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
        <div class="viewContainer relatedArticles"><a class="tooltip list"><span class="tooltiptext">Switch to list view</a></div>
        <?php $layout = get_sub_field( 'layout' ); ?>
            <div id="loop" class="<?php if($layout){ echo $layout; } else { ?>grid<?php } ?>">
                <?php if (get_sub_field( 'taxonomy_type' ) == 'event') { ?>
                    <?php
                    $post_type = 'post';
                    $taxonomy  = 'insights-event';
                    $terms     =  get_sub_field( 'event' );

                    foreach( $terms as $term ) :
                        $args = array(
                            'post_type'      => $post_type,
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field'    => 'term_id',
                                    'terms'    => $term,
                                ),
                            ),
                        );

                        $posts = new WP_Query( $args );
                         if( $posts->have_posts() ): ?>
                         <?php $counter = -1; ?>
                          <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                              <?php if(current_user_can('mepr_auth')) {?>
                                <span class="postLink layout<?php echo $counter; ?>">
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <a href="<?php the_permalink(); ?>" class="imageContainer">
                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                        <span class="watchIcon"></span>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </a>
                                        <span class="blogText">
                                            <?php if(get_field('event_short_description_for_listing')) { ?>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt events-excerpt" style="display: block;">
                                                        <?php the_field('event_short_description_for_listing'); ?>

                                                        <style>
                                                            .blogWrapper .layout1 .excerpt.events-excerpt,
                                                            .blogWrapper .layout4 .excerpt.events-excerpt {
                                                                color: #fff!important;
                                                            }
                                                        </style>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>
                                                                <span class="date list-info">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime list-info">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>

                                                            <?php } else { ?>
                                                                <span class="date">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>
                                                            <?php }?>
                                                        </span>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt">
                                                        <?php echo the_excerpt(); ?>
                                                    </span>
                                                <?php } ?>


                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            $term_m = 'topic';
                                            ?>
                                            <?php
                                            $terms = get_the_terms( $post, 'topic' );
                                            ?>
                                            <?php if ( $terms ) { ?>
                                                <span class="grid-bottom-details">
                                                    <span class="date grid-info">
                                                        <?php echo get_the_date('d.m.Y'); ?> |
                                                    </span>
                                                    <span class="readTime grid-info">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } else { ?>
                                <span class="postLink layout<?php echo $counter; ?>">
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <a href="<?php the_permalink(); ?>" class="imageContainer">
                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                        <span class="watchIcon"></span>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </a>
                                        <span class="blogText">
                                            <?php if(get_field('event_short_description_for_listing')) { ?>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt events-excerpt" style="display: block;">
                                                        <?php the_field('event_short_description_for_listing'); ?>

                                                        <style>
                                                            .blogWrapper .layout1 .excerpt.events-excerpt,
                                                            .blogWrapper .layout4 .excerpt.events-excerpt {
                                                                color: #fff!important;
                                                            }
                                                        </style>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>
                                                                <span class="date list-info">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime list-info">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>

                                                            <?php } else { ?>
                                                                <span class="date">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>
                                                            <?php }?>
                                                        </span>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt">
                                                        <?php echo the_excerpt(); ?>
                                                    </span>
                                                <?php } ?>


                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            $term_m = 'topic';
                                            ?>
                                            <?php
                                            $terms = get_the_terms( $post, 'topic' );
                                            ?>
                                            <?php if ( $terms ) { ?>
                                                <span class="grid-bottom-details">
                                                    <span class="date grid-info">
                                                        <?php echo get_the_date('d.m.Y'); ?> |
                                                    </span>
                                                    <span class="readTime grid-info">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } ?>
                        <?php $counter++; ?>
                        <?php if ($counter == 8) {
                            $counter = -1;
                        } ?>
                    <?php endwhile; endif;
                endforeach;
                wp_reset_postdata();
                ?>
            <?php } else if (get_sub_field( 'taxonomy_type' ) == 'topic') { ?>
                <?php
                $post_type = 'post';
                $taxonomy  = 'topic';
                $terms     =  get_sub_field( 'topic' );

                foreach( $terms as $term ) :
                    $args = array(
                        'post_type'      => $post_type,
                        'posts_per_page' => -1,
                        'tax_query'      => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field'    => 'term_id',
                                'terms'    => $term,
                            ),
                        ),
                    );
                    $posts = new WP_Query( $args );
                     if( $posts->have_posts() ): ?>
                     <?php $counter = -1; ?>
                      <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                          <?php if(current_user_can('mepr_auth')) {?>
                            <span class="postLink layout<?php echo $counter; ?>">
                                <div class="linkWrapper">
                                    <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                        <span class="podcast"></span>
                                    <?php } ?>
                                    <a href="<?php the_permalink(); ?>" class="imageContainer">
                                        <?php if ( get_field( 'listing_image') ) { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                    <span class="watchIcon"></span>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </a>
                                    <span class="blogText">
                                        <?php if(get_field('event_short_description_for_listing')) { ?>
                                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                <span class="excerpt events-excerpt" style="display: block;">
                                                    <?php the_field('event_short_description_for_listing'); ?>

                                                    <style>
                                                        .blogWrapper .layout1 .excerpt.events-excerpt,
                                                        .blogWrapper .layout4 .excerpt.events-excerpt {
                                                            color: #fff!important;
                                                        }
                                                    </style>
                                                </span>
                                            <?php } else { ?>
                                                <span class="postDetails">
                                                    <span class="info">
                                                        <?php
                                                        $term_m = 'topic';
                                                        ?>
                                                        <?php
                                                        $terms = get_the_terms( $post, 'topic' );
                                                        ?>
                                                        <?php if ( $terms ) { ?>
                                                            <?php $counterTopic = 0; ?>
                                                            <?php $len = count($terms); ?>
                                                            <?php foreach($terms as $term) { ?>
                                                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                     <?php echo $term -> name; ?>
                                                                </span>
                                                                <?php $counterTopic++; ?>
                                                            <?php } ?>
                                                            <span class="date list-info">
                                                                <?php echo get_the_date('d.m.Y'); ?>
                                                            </span>
                                                            <span class="readTime list-info">
                                                                <?php the_field( 'read_time' ); ?>
                                                            </span>

                                                        <?php } else { ?>
                                                            <span class="date">
                                                                <?php echo get_the_date('d.m.Y'); ?>
                                                            </span>
                                                            <span class="readTime">
                                                                <?php the_field( 'read_time' ); ?>
                                                            </span>
                                                        <?php }?>
                                                    </span>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                <span class="excerpt">
                                                    <?php echo the_excerpt(); ?>
                                                </span>
                                            <?php } ?>


                                        <?php
                                            $post_tags = get_the_tags();
                                        ?>

                                        <?php if ( $post_tags ) { ?>
                                            <div class="tags">
                                                <?php foreach( $post_tags as $tag ) { ?>
                                                    <span>
                                                        <?php echo '#' . $tag->name  ; ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        $term_m = 'topic';
                                        ?>
                                        <?php
                                        $terms = get_the_terms( $post, 'topic' );
                                        ?>
                                        <?php if ( $terms ) { ?>
                                            <span class="grid-bottom-details">
                                                <span class="date grid-info">
                                                    <?php echo get_the_date('d.m.Y'); ?> |
                                                </span>
                                                <span class="readTime grid-info">
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    </span>
                                </div>
                            </span>
                        <?php } else { ?>
                            <span class="postLink layout<?php echo $counter; ?>">
                                <div class="linkWrapper">
                                    <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                        <span class="podcast"></span>
                                    <?php } ?>
                                    <a href="<?php the_permalink(); ?>" class="imageContainer">
                                        <?php if ( get_field( 'listing_image') ) { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                    <span class="watchIcon"></span>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </a>
                                    <span class="blogText">
                                        <?php if(get_field('event_short_description_for_listing')) { ?>
                                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                <span class="excerpt events-excerpt" style="display: block;">
                                                    <?php the_field('event_short_description_for_listing'); ?>

                                                    <style>
                                                        .blogWrapper .layout1 .excerpt.events-excerpt,
                                                        .blogWrapper .layout4 .excerpt.events-excerpt {
                                                            color: #fff!important;
                                                        }
                                                    </style>
                                                </span>
                                            <?php } else { ?>
                                                <span class="postDetails">
                                                    <span class="info">
                                                        <?php
                                                        $term_m = 'topic';
                                                        ?>
                                                        <?php
                                                        $terms = get_the_terms( $post, 'topic' );
                                                        ?>
                                                        <?php if ( $terms ) { ?>
                                                            <?php $counterTopic = 0; ?>
                                                            <?php $len = count($terms); ?>
                                                            <?php foreach($terms as $term) { ?>
                                                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                     <?php echo $term -> name; ?>
                                                                </span>
                                                                <?php $counterTopic++; ?>
                                                            <?php } ?>
                                                            <span class="date list-info">
                                                                <?php echo get_the_date('d.m.Y'); ?>
                                                            </span>
                                                            <span class="readTime list-info">
                                                                <?php the_field( 'read_time' ); ?>
                                                            </span>

                                                        <?php } else { ?>
                                                            <span class="date">
                                                                <?php echo get_the_date('d.m.Y'); ?>
                                                            </span>
                                                            <span class="readTime">
                                                                <?php the_field( 'read_time' ); ?>
                                                            </span>
                                                        <?php }?>
                                                    </span>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                <span class="excerpt">
                                                    <?php echo the_excerpt(); ?>
                                                </span>
                                            <?php } ?>


                                        <?php
                                            $post_tags = get_the_tags();
                                        ?>

                                        <?php if ( $post_tags ) { ?>
                                            <div class="tags">
                                                <?php foreach( $post_tags as $tag ) { ?>
                                                    <span>
                                                        <?php echo '#' . $tag->name  ; ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        $term_m = 'topic';
                                        ?>
                                        <?php
                                        $terms = get_the_terms( $post, 'topic' );
                                        ?>
                                        <?php if ( $terms ) { ?>
                                            <span class="grid-bottom-details">
                                                <span class="date grid-info">
                                                    <?php echo get_the_date('d.m.Y'); ?> |
                                                </span>
                                                <span class="readTime grid-info">
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    </span>
                                </div>
                            </span>
                        <?php } ?>
                        <?php $counter++; ?>
                        <?php if ($counter == 8) {
                            $counter = -1;
                        } ?>
                        <?php endwhile; endif;
                    endforeach;
                    wp_reset_postdata();
                    ?>
                <?php } else if (get_sub_field( 'taxonomy_type' ) == 'filter-type') { ?>
                    <?php
                    $post_type = 'post';
                    $taxonomy  = 'filter-types';
                    $terms     =  get_sub_field( 'filter_type' );

                    foreach( $terms as $term ) :
                        $args = array(
                            'post_type'      => $post_type,
                            'posts_per_page' => -1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field'    => 'term_id',
                                    'terms'    => $term,
                                ),
                            ),
                        );
                        $posts = new WP_Query( $args );
                         if( $posts->have_posts() ): ?>
                         <?php $counter = -1; ?>
                          <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                              <?php if(current_user_can('mepr_auth')) {?>
                                <span class="postLink layout<?php echo $counter; ?>">
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <a href="<?php the_permalink(); ?>" class="imageContainer">
                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                        <span class="watchIcon"></span>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </a>
                                        <span class="blogText">
                                            <?php if(get_field('event_short_description_for_listing')) { ?>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt events-excerpt" style="display: block;">
                                                        <?php the_field('event_short_description_for_listing'); ?>

                                                        <style>
                                                            .blogWrapper .layout1 .excerpt.events-excerpt,
                                                            .blogWrapper .layout4 .excerpt.events-excerpt {
                                                                color: #fff!important;
                                                            }
                                                        </style>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>
                                                                <span class="date list-info">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime list-info">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>

                                                            <?php } else { ?>
                                                                <span class="date">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>
                                                            <?php }?>
                                                        </span>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt">
                                                        <?php echo the_excerpt(); ?>
                                                    </span>
                                                <?php } ?>


                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            $term_m = 'topic';
                                            ?>
                                            <?php
                                            $terms = get_the_terms( $post, 'topic' );
                                            ?>
                                            <?php if ( $terms ) { ?>
                                                <span class="grid-bottom-details">
                                                    <span class="date grid-info">
                                                        <?php echo get_the_date('d.m.Y'); ?> |
                                                    </span>
                                                    <span class="readTime grid-info">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } else { ?>
                                <span class="postLink layout<?php echo $counter; ?>">
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <a href="<?php the_permalink(); ?>" class="imageContainer">
                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                        <span class="watchIcon"></span>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                    </div>
                                                <?php } else { ?>
                                                    <?php if ( get_field ( 'listing_page_grid_image' )) { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </a>
                                        <span class="blogText">
                                            <?php if(get_field('event_short_description_for_listing')) { ?>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt events-excerpt" style="display: block;">
                                                        <?php the_field('event_short_description_for_listing'); ?>

                                                        <style>
                                                            .blogWrapper .layout1 .excerpt.events-excerpt,
                                                            .blogWrapper .layout4 .excerpt.events-excerpt {
                                                                color: #fff!important;
                                                            }
                                                        </style>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>
                                                                <span class="date list-info">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime list-info">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>

                                                            <?php } else { ?>
                                                                <span class="date">
                                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                                </span>
                                                                <span class="readTime">
                                                                    <?php the_field( 'read_time' ); ?>
                                                                </span>
                                                            <?php }?>
                                                        </span>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                                    <span class="excerpt">
                                                        <?php echo the_excerpt(); ?>
                                                    </span>
                                                <?php } ?>


                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            $term_m = 'topic';
                                            ?>
                                            <?php
                                            $terms = get_the_terms( $post, 'topic' );
                                            ?>
                                            <?php if ( $terms ) { ?>
                                                <span class="grid-bottom-details">
                                                    <span class="date grid-info">
                                                        <?php echo get_the_date('d.m.Y'); ?> |
                                                    </span>
                                                    <span class="readTime grid-info">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } ?>
                            <?php $counter++; ?>
                            <?php if ($counter == 8) {
                                $counter = -1;
                            } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else { ?>
                        <span>No posts</span>
                    <?php  }?>

            </div>

        <?php if ( have_rows( 'button_block' ) ) : ?>
            <div class="buttonBlock">
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
