<?php
    query_posts('meta_key=post_views_count&posts_per_page=4&orderby=meta_value_num&order=DESC');
    if (have_posts()) : while (have_posts()) : the_post();
 ?>
     <div class="most-popular-posts">
         <div class="most-popular-inner">
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
 <?php
 endwhile; endif;
 wp_reset_query();
 ?>
