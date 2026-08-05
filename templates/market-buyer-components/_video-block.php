<section class="video-module">
    <div class="container">
        <div class="image-video-container">
            <div class="video-image-inner">
                <?php if (get_sub_field( 'auto_play_video' )) { ?>
                    <div class="video-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field('poster_image'); ?>
                            <video width="100%" autoplay loop muted playsinline poster="<?php echo $image['url']; ?>">
                                <source type="video/mp4" src="<?php echo get_sub_field( 'auto_play_video' ); ?>" />
                            </video>
                            <?php if( get_sub_field( 'vimeo_code' )) { ?>                                
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                            <?php } ?>
                            <button type="button" class="pause-autoplay" aria-label="Pause video"></button>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="image-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field('poster_image'); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'class'   => 'desktop',
                                    'alt'     => $image['alt'],
                                    'loading' => false,
                                ) ); ?>
                            <?php } ?>
                            <?php if( get_sub_field( 'vimeo_code' )) { ?>                                
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
