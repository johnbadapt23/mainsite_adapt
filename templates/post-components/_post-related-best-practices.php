<div class="related-articles best-practices">
    <div class="container">
        <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
            <span class="labelXXLarge"><?php echo get_sub_field( 'title' ); ?></span>
            <div class="post-container">
                <?php if ( get_sub_field( 'select_articles_or_by_taxonomy' ) == 'choose-articles' ){ ?>
                    <?php if ( have_rows( 'articles' ) ) : ?>
                        <?php while ( have_rows( 'articles' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'post' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?>
                                <div class="related-item one-third best-practice-item">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                            <?php if ( $best_practice_listing_image ) { ?>
                                                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'full', false, array(
                                                    'alt'     => $best_practice_listing_image['alt'],
                                                    'loading' => false,
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
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
                <?php } else { ?>

                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                    <?php if( get_sub_field( 'taxonomy' ) =='type'){ ?>
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
                        <?php
                            $args = array(
                                'post_type' => 'post',
                                'posts_per_page' => 3,
                                'paged'=> $paged,
                                'tax_query' => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'resource-type',
                                        'field' => 'slug',
                                        'terms'    => $postType->slug
                                    ),
                                )
                            ); ?>
                    <?php } else { ?>
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
                        <?php $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'paged'=> $paged,
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'topic',
                                    'field' => 'slug',
                                    'terms'    => $postTopic->slug
                                ),
                            )
                        ); ?>
                    <?php  } ?>
                    <?php $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="related-item one-third best-practice-item">
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                            <?php if ( $best_practice_listing_image ) { ?>
                                                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'full', false, array(
                                                    'alt'     => $best_practice_listing_image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
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
                <?php } ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
