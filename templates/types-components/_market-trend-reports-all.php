<div class="item one-third market-trend-reports">
    <span class="image-container">
        <span class="bg-container">
            <?php $featured_image = get_field( 'featured_image' ); ?>
            <?php if ( $featured_image ) { ?>
            	<img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
            <?php } ?>
        </span>
        <span class="bg-container bg-container-hover">
            <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
            <?php if ( $listing_hover_image ) { ?>
            	<img src="<?php echo $listing_hover_image['url']; ?>" alt="<?php echo $listing_hover_image['alt']; ?>" />
            <?php } ?>
        </span>
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
