<?php 
$padding_top = get_sub_field('padding_top');
$padding_bottom = get_sub_field('padding_bottom');
$bg = get_sub_field('background_colour') ?: 'background-black';
?>
<section class="video-module <?php echo "$padding_top $padding_bottom $bg"; ?>">
	<div class="container">
		<div class="image-video-container">
            <div class="video-image-inner">
                <?php if (get_sub_field( 'auto_play_video' )) { ?>
                    <div class="video-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field('poster_image'); ?>
                            <video width="100%" autoplay loop muted playsinline poster="<?php echo esc_url( adapt_webp_poster_url( $image['url'] ) ); ?>">
                                <source type="video/mp4" src="<?php echo get_sub_field( 'auto_play_video' ); ?>" />
                            </video>
                            <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                <span class="opacity-overlay"></span>
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="image-container">
                        <div class="bg-container">
                            <?php 
                            $image = get_sub_field('poster_image'); 
                            $image_id = attachment_url_to_postid( $image['url'] );
                            ?>
                            <?= wp_get_attachment_image($image_id, 'adapt-optimized', false, array('class' => 'desktop')); ?>
                            <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                <span class="opacity-overlay"></span>
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
	</div>
</section>
