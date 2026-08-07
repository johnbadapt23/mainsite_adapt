<section class="speaker-text-carousel reverse-layout">
    <div class="container">
        <div class="container-inner">
            <div class="title-container bottom-align">
                <div class="column-container">
                    <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                    <?php if ( have_rows( 'link' ) ) : ?>
                        <span class="link-container">                        
                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                    <a class="text-link red-link external-link red-arrow-link-external" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='scroll-to') { ?> 
                                    <a class="text-link red-link red-arrow-link-external external-link scroll-to-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else { ?> 
                                    <span class="form-popup-container text-link red-link with-red-underline-link with-external-link red-arrow-link-external"><?php echo get_sub_field( 'form_button' ); ?></span>
                                <?php } ?>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                        <?php if(get_sub_field( 'text' )) { ?> 
                            <span class="text-container">
                                <span class="white-text p-large"><?php echo get_sub_field( 'text' ); ?></span>
                            </span>
                        <?php } ?>
                    <?php endif; ?>                   
                </div>
            </div>
            <div class="speaker-slider-outer">
                <div class="speaker-slider-text-container">
                    <div class="speaker-slider-text-outer">
                        <?php if ( have_rows( 'slides' ) ) : ?>
                            <?php $speakerCounter = 1; ?>
                            <?php while ( have_rows( 'slides' ) ) : the_row(); ?>                        
                                <div class="speaker-slide-text<?php if ($speakerCounter == 1){ ?> active<?php } ?>">
                                    <span class="progress">
                                        <span class="progress-inner"></span>
                                    </span>
                                    <h3 class="white-text"><?php echo get_sub_field( 'title' ); ?></h3>
                                    <p class="p-medium medium-grey"><?php echo get_sub_field( 'text' ); ?></p>
                                </div>
                                <?php $speakerCounter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>   
                    </div>
                </div>
                <div class="speaker-slider-image-container">
                    <div class="speaker-slider-image-outer">
                        <?php if ( have_rows( 'slides' ) ) : ?>
                            <?php $imageCounter = 1; ?>
                            <?php while ( have_rows( 'slides' ) ) : the_row(); ?>                        
                                <div class="speaker-slide-image image-container <?php if ($imageCounter == 1){ ?> active<?php } ?>">                                    
                                    <span class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                'alt'     => $image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </span> 
                                    <?php if(get_sub_field( 'name' )) { ?> 
                                        <span class="bottom-text-container">
                                            <span class="label-XL white-text"><?php echo get_sub_field( 'name' ); ?></span>
                                            <span class="label-Xsmall"><?php echo get_sub_field( 'role' ); ?></span>                                    
                                        </span>                                   
                                    <?php } ?>
                                </div>
                                <?php $imageCounter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>