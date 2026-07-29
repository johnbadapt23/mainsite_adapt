<section class="two-column-services landing-video-intro background-white">
    <div class="container">
        <div class="landing-video-intro-columns">
            <div class="column one-half text-column">
                <div class="text-content-inner">
                    <span class="pre-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
                    <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                    <span class="links-container desktop">
                        <?php if ( have_rows( 'button' ) ) : ?>
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if(get_sub_field( 'link_type' ) == 'scrollto') { ?>
                                    <a class="scroll-to-button std-button  red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else { ?>
                                    <a class="link std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <a class="text-link video-popup popup-vimeo video-link red-text red-underline-link" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>"><?php echo get_sub_field( 'video_play_text' ); ?></a>
                    </span>
                </div>
            </div>
            <div class="column one-half video-column">
                <div class="video-container">
                    <span class="frame"></span>
                    <div class="bg-container">
                        <?php $image = get_sub_field('poster_image'); ?>
                        <video width="100%" autoplay loop muted playsinline poster="<?php echo $image['url']; ?>">
                            <source type="video/mp4" src="<?php echo get_sub_field( 'auto_play_video' ); ?>" />
                        </video>
                        <?php if( get_sub_field( 'vimeo_code' )) { ?>
                            <span class="opacity-overlay"></span>
                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                        <?php } ?>
                    </div>
                </div> 
                <div class="links-container mobile">
                    <?php if ( have_rows( 'button' ) ) : ?>
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if(get_sub_field( 'link_type' ) == 'scrollto') { ?>
                                <a class="scroll-to-button std-button  red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else { ?>
                                <a class="link std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <a class="std-button video-popup popup-vimeo video-link red-outline-button red-outline-video" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>"><?php echo get_sub_field( 'video_play_text' ); ?></a>                           
                </div>
            </div>
        </div>
    </div>
</section>
