<section class="best-practices-featured featured-module">
    <div class="container">
        <div class="title-container">
            <div class="introduction-column two-thirds">
                <?php $term = get_term_by('slug', 'best-practices-guides', 'resource-type');?>
                <?php if (get_sub_field( 'title' )) {?>
                    <h2 class="taxonomy-title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="taxonomy-description text-black"><?php echo get_sub_field( 'text' ); ?></p>
                <?php } else { ?>
                    <h2 class="taxonomy-title text-black"><?php echo $term->name; ?></h2>
                    <p class="taxonomy-description text-black"><?php echo $term->description; ?></p>
                <?php } ?>
            </div>
            <div class="link-container one-third">
                <a class="red-text-link" href="<?php echo get_term_link($term); ?>"><?php echo get_sub_field( 'view_all_text' ); ?></a>
            </div>
        </div>
        <div class="post-container grid-wrapper">
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
                                'terms'    => 'best-practices-guides'
                            ),
                        )
                    );

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div class="item one-third best-practice-item">
                            <span class="image-container">
                                <a href="<?php the_permalink(); ?>">
                                    <span class="bg-container">
                                        <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                        <?php if ( $best_practice_listing_image ) { ?>
                                            <img src="<?php echo $best_practice_listing_image['url']; ?>" alt="<?php echo $best_practice_listing_image['alt']; ?>" />
                                        <?php } ?>
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
                    <?php wp_reset_postdata(); ?>
    			<?php endif; ?>
            <?php } else { ?>
                <?php if ( have_rows( 'posts' ) ) : ?>
    				<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
    					<?php $post_object = get_sub_field( 'post' ); ?>
    					<?php if ( $post_object ): ?>
    						<?php $post = $post_object; ?>
    						<?php setup_postdata( $post ); ?>
                            <div class="item one-third best-practice-item">
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                            <?php if ( $best_practice_listing_image ) { ?>
                                                <img src="<?php echo $best_practice_listing_image['url']; ?>" alt="<?php echo $best_practice_listing_image['alt']; ?>" />
                                            <?php } ?>
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
    						<?php wp_reset_postdata(); ?>
    					<?php endif; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            <?php } ?>
        </div>
    </div>
</section>
