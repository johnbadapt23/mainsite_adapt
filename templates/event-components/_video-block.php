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
                            <?php // 2026-09-17: src shipped as data-autoplay-src, not src -- see
                            // adaptGateAutoplayVideos() in source/js/main.js. Keeps the file
                            // from downloading unconditionally on page load (flagged by
                            // Lighthouse as an "enormous network payload" on the pages using
                            // this pattern); JS decides client-side whether to actually load
                            // and play it, since this page is served from WP Rocket's
                            // full-page cache and a PHP-side Save-Data/etc. check can't work
                            // there (it would just get baked into whichever visitor's
                            // request happened to prime the cache). ?>
                            <video width="100%" loop muted playsinline poster="<?php echo esc_url( adapt_webp_poster_url( $image['url'] ) ); ?>">
                                <source type="video/mp4" data-autoplay-src="<?php echo get_sub_field( 'auto_play_video' ); ?>" />
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
