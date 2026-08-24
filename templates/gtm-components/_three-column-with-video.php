<section class="three-column-video-gtm three-column-icon-text-ecosystems three-column-partnered-research <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-true-black<?php }?> <?php echo get_sub_field( 'padding_top' ); ?> <?php echo get_sub_field( 'padding_bottom' ); ?>" id="<?php echo get_sub_field( 'id' ); ?>">
    <div class="container">        
        <div class="title-container">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
        </div>
        <div class="image-video-container">
            <div class="video-image-inner">
                <?php if (get_sub_field( 'autoplay_video' )) { ?>
                    <div class="video-container" style="aspect-ratio: auto; padding-bottom: 56.5%;">
                        <div class="bg-container">
                            <?php $image = get_sub_field('poster_image'); ?>
                            <video width="100%" autoplay loop muted playsinline poster="<?php echo esc_url( adapt_webp_poster_url( $image['url'] ) ); ?>" style="height: 100%;">
                                <source type="video/mp4" src="<?php echo get_sub_field( 'autoplay_video' ); ?>" />
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
                                <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                    'class'   => 'desktop',
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
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
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-third">
                        <span class="icon-container">
                            <?php $icon = get_sub_field( 'icon' ); ?>
                            <?php if ( $icon ) { ?>
                                <?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
                                    'alt'     => $icon['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                        <span class="text-container">
                            <span class="labelXL"><?php echo get_sub_field( 'title' ); ?></span>
                            <p class="p-small text-dark-grey"><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
                        <?php if (get_sub_field( 'link' )) { ?>
                            <span class="link-container">
                                <a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                            </span> 
                        <?php } ?>                        
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
        <span class="links-container">
            <?php if ( have_rows( 'links' ) ) : ?>
                <?php $buttonCounter = 1;?>
                <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                    <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                        <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                        <a class="stdBtn std-button scroll-to-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                        <?php $file = get_sub_field( 'file' ); ?>
                        <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                        <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="videoformPopup">
                                <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                            </div>
                        </div> 
                    <?php } else { ?>                                 
                        <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="videoformPopup">
                                <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                            </div>
                        </div> 
                    <?php } ?>                     	
                    <?php $buttonCounter++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </span>
    </div>
</section>