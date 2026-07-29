<section class="videoBlock scrollPos" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <?php if( get_sub_field('dark_overlay') == 'yes') { ?>
        <span class="dark-overlay"></span>
    <?php } ?>
    <div class="container">
        <div class="content">
            <?php if( get_sub_field ( 'video_title' ) ) { ?>
                <div class="column title">
                    <span class="title"><?php the_sub_field('video_title'); ?></span>
                </div>
                <hr>
            <?php } ?>
            <?php if( get_sub_field ( 'video_description' ) ) { ?>
                <div class="column text">
                    <span class="text"><?php the_sub_field('video_description'); ?></span>
                </div>
            <?php } ?>
            <span class="videoLink">
                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                    <a href="https://vimeo.com/<?php the_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                <?php } else { ?>
                    <a href="#" class="playBtnVideoBlock">
                <?php } ?>     
                    <span class="icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
                    </span>
                    <span class="text">
                        <span><?php if( get_sub_field('video_button_text')) { ?><?php the_sub_field('video_button_text') ?><?php } else { ?>Watch Video<?php } ?></span>
                        <span><?php the_sub_field('video_duration') ?></span>
                    </span>
                </a>
            </span>
        </div>
    </div>
    <div class="videoPlayerContainer videoBlock">
        <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
        <div class="videoWrapper">
            <video width="100%" id="popupVideo" controls controlsList="nodownload">
                <source type="video/mp4" src="<?php the_sub_field('vimeo_code'); ?>" />
            </video>
        </div>
    </div>
</section>
