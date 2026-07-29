<div <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="videoBlock" style="background-image: url(<?php echo get_sub_field('video_poster_image'); ?>);">
    <?php if( get_sub_field('dark_overlay') == 'yes') { ?>
        <span class="dark-overlay"></span>
    <?php } ?>

    <div class="content">
       <?php if( get_sub_field ( 'video_title' ) ) { ?>
           <div class="column title">
               <span class="title"><?php echo get_sub_field('video_title'); ?></span>
           </div>
           <hr>
       <?php } ?>
       <?php if( get_sub_field ( 'video_description' ) ) { ?>
           <div class="column text">
               <span class="text"><?php echo get_sub_field('video_description'); ?></span>
           </div>
       <?php } ?>
       <span class="videoLink print-no">
           <?php if( get_sub_field('vimeo_code_popup')){ ?>
                <a href="https://vimeo.com/<?php echo get_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
            <?php } else { ?>
                 <a href="#" class="playBtnVideoBlock">
            <?php } ?>
               <span class="icon">
                   <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
               </span>
               <span class="text">
                   <span><?php if( get_sub_field('video_button_text')) { ?><?php echo get_sub_field('video_button_text') ?><?php } else { ?>Watch Video<?php } ?></span>
                   <span><?php echo get_sub_field('video_duration') ?></span>
               </span>
           </a>
       </span>
   </div>   
</div>
