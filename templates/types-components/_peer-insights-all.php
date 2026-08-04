<div class="item one-third peer-insights-item">
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
