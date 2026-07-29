<div class="infogram-container" <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
   <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
       <div class="featureBlock">
            <?php if ( get_sub_field('image_link')) { ?>
                <a href="<?php echo get_sub_field( 'image_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
            <?php } ?>
                <?php
                $feature_image_url = get_sub_field( 'image' );
                $feature_image_id = $feature_image_url ? attachment_url_to_postid( $feature_image_url ) : 0;
                $feature_image_alt = $feature_image_id ? get_post_meta( $feature_image_id, '_wp_attachment_image_alt', true ) : '';
                ?>
                <img class="featureImage" src="<?php echo esc_url( $feature_image_url ); ?>" alt="<?php echo esc_attr( $feature_image_alt ); ?>"/>
            <?php if ( get_sub_field('image_link')) { ?>
            </a>
            <?php } ?>
       </div>
   <?php } else { ?>
       <div class="infogram-container">
           <?php echo get_sub_field( 'infogram' ); ?>
       </div>
       <img loading="lazy" class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>" alt=""/>
   <?php } ?>
</div>
