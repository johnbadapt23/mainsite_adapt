<div class="item one-third best-practice-item">
    <span class="image-container">
        <span class="bg-container">
            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
            <?php if ( $best_practice_listing_image ) { ?>
                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'adapt-optimized', false, array(
                    'alt'     => $best_practice_listing_image['alt'],
                    'loading' => 'lazy',
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
