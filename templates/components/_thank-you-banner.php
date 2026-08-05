<?php if ( have_rows( 'button' ) ) : ?>
    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
        <?php if( get_sub_field( 'download_link_or_video' ) == 'video') { ?>
            <?php $videoURL = get_sub_field( 'vimeo_code' ); ?>
        <?php } ?>
    <?php endwhile; ?>
<?php endif; ?>
<section class="thank-you-banner background-white">
    <div class="container">
        <div class="column one-half image-column desktop-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo $videoURL; ?>">
                        <div class="image-container">
                            <div class="bgContainer">
                                <?php $image = get_sub_field( 'image' ); ?>
                                <video width="100%" muted="muted" autoplay="autoplay" playsinline="playsinline" loop="loop" poster="<?php echo $image['url']; ?>">
                                    <source type="video/mp4" src="<?php echo get_sub_field( 'video_url' ); ?>" />
                                </video>
                            </div>
                        </div>
                    </a>
                    <div class="details-container">
                        <?php
                        $date_string = get_sub_field('date');
                        $date = DateTime::createFromFormat('Ymd', $date_string);
                        ?>
                        <span class="date"><?php echo $date->format('j F, Y'); ?></span>
                        <span class="location"><?php echo get_sub_field( 'location' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php $location = get_sub_field( 'location' ); ?>
        <div class="column one-half text-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <span class="text-inner">
                		<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                        <span class="text-details-container">
                            <span class="text-details">
                                <?php echo get_sub_field( 'text' ); ?>
                                <span class="mobile-video-container">
                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo $videoURL; ?>">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                        'alt'     => $image['alt'],
                                                        'loading' => false,
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="video-button-icon"></span>
                                        </span>
                                    </a>
                                    <div class="details-container">
                                        <span class="date"><?php echo $date->format('j F, Y'); ?></span>
                                        <span class="location"><?php echo $location; ?></span>
                                    </div>
                                </span>
                                <?php echo get_sub_field( 'pre_button_text' ); ?>
                            </span>
                        </span>
                		<?php if ( have_rows( 'button' ) ) : ?>
                            <span class="button-container">
                    			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                    <?php if( get_sub_field( 'download_link_or_video' ) == 'download') { ?>
                                        <a class="site-button download-link-button" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php } ?>
                                    <?php if( get_sub_field( 'download_link_or_video' ) == 'link') { ?>
                                        <a class="site-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php }?>
                                    <?php if( get_sub_field( 'download_link_or_video' ) == 'video') { ?>
                                        <a class="site-button popup-vimeo video-button black-outline mobile-hide" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php }?>
                    			<?php endwhile; ?>
                            </span>
                		<?php else : ?>
                			<?php // no rows found ?>
                		<?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
