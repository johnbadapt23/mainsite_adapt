<section class="title-background-block">
    <div class="container">
        <div class="text-container">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="sub-title red-text"><?php echo get_sub_field( 'sub_title' ); ?></span>
                    <span class="button-container">
                        <?php if( get_sub_field( 'vimeo_code' )) { ?>
                            <span class="replay-button popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"><?php echo get_sub_field( 'video_button_text' ); ?></span>
                        <?php } ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="imageSizeContainer">
            <div class="bgContainer">
                <?php if ( get_sub_field( 'image_or_video_background' ) == 'image') { ?>
                    <?php $image = get_sub_field('image'); ?>
                    <?php if ( $image ) { ?>
                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                            'class'   => 'desktop',
                            'alt'     => $image['alt'],
                            'loading' => false,
                        ) ); ?>
                    <?php } ?>
                <?php } else { ?>
                    <video width="100%" muted="muted" autoplay="autoplay" playsinline="playsinline" loop="loop">
                        <source type="video/mp4" src="<?php echo get_sub_field( 'video_url' ); ?>" />
                    </video>
                <?php }?>
            </div>
        </div>
    </div>
</section>
