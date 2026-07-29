<div class="infogram-container" <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
   <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
       <div class="featureBlock">
            <?php if ( get_sub_field('image_link')) { ?> 
                <a href="<?php echo get_sub_field( 'image_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
            <?php } ?>
                <img class="featureImage" src="<?php echo get_sub_field( 'image' ); ?>"/>
            <?php if ( get_sub_field('image_link')) { ?> 
            </a>
            <?php } ?>
       </div>
   <?php } else { ?>
       <div class="infogram-container">
           <?php echo get_sub_field( 'infogram' ); ?>
       </div>
       <img loading="lazy" class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
   <?php } ?>
</div>
