<section class="sticky-slider-cards background-light-grey">
    <div class="slider-scrolling-container">
        <div class="heading-container">
            <div class="container">
                <div class="inner-text-container">
                    <span class="red-text labelSmall"><?php echo get_sub_field( 'pre_title' ); ?></span>
                    <h2 class="bold-red text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="p-large text-black"><?php echo get_sub_field( 'text' ); ?></span>
                </div>
            </div>
        </div>
        <div class="sticky-slider-container">
            <div class="slide-nav-container position-sticky">
                 <?php if ( have_rows( 'slides' ) ) : ?>
                    <?php $slide_index = 0; ?>
                    <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                        <span class="slide-nav-item <?php echo ($slide_index === 0) ? 'active' : ''; ?>">
                            0<?php echo $slide_index + 1; ?>
                        </span>
                        <?php $slide_index++; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <?php if ( have_rows( 'slides' ) ) : ?>
                <div class="slides-container">
                    <div class="container">
                        <div class="column-container">
                            <div class="column one-half text-column">
                                <?php $slideCount = 1; ?>
                                <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                    <div class="slider-scrolling-content">
                                        <div class="slider-scrolling-content-inner">
                                            <span class="mobile-slide-count">
                                                <span class="slide-number labelSmall text-black">0<?php echo $slideCount; ?> </span>
                                                <span class="total labelSmall text-medium-grey"> / 05</span>
                                            </span>
                                            <h3 class="card-title bold-red black-text"><?php the_sub_field( 'title' ); ?></h3>  
                                            <?php $image = get_sub_field( 'image' ); ?>
                                            <span class="mobile-image-container">
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                                'alt'     => $image['alt'],
                                                                'loading' => false,
                                                            ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="list-container">
                                                <?php if ( have_rows( 'list' ) ) : ?>
                                                    <?php while ( have_rows( 'list' ) ) : the_row(); ?>
                                                        <span class="list-item">
                                                            <span class="icon-container">
                                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                                <?php if ( $icon ) { ?>
                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                                                                        'alt'     => $icon['alt'],
                                                                        'loading' => 'lazy',
                                                                    ) ); ?>
                                                                <?php } ?>
                                                            </span>
                                                            <span class="text-container">
                                                                <?php echo get_sub_field( 'text' ); ?>
                                                            </span>
                                                        </span>                                                                                                                   
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </span>
                                            <span class="link-container">
                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                        <?php if ( get_sub_field( 'link_type' ) == 'link') { ?> 
                                                            <a class="text-link red-text large-link-text red-underline-link  external-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } else if ( get_sub_field( 'link_type' ) == 'form' ) { ?> 
                                                            <span class="text-link red-text large-link-text red-underline-link"><?php echo get_sub_field( 'form_button_code' ); ?></span>
                                                            <span style="display: none;"><?php echo get_sub_field( 'form_code' ); ?></span>
                                                        <?php } else if ( get_sub_field( 'link_type' ) == 'scroll-to') { ?> 
                                                            <a class="text-link red-text large-link-text red-arrow red-underline-link  scroll-to" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } else if( get_sub_field( 'link_type' ) == 'link_type') { ?> 
                                                            <a class="formPopupHubspot text-link red-text large-link-text red-underline-link" href="#stickySliderLink<?php echo $slideCount; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                            <div style="display: none;">         
                                                                <div class="preview-cta-form login-form-container" id="stickySliderLink<?php echo $slideCount; ?>">
                                                                    <div class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></div>
                                                                </div>
                                                            </div>                         
                                                        <?php } else { ?> 
                                                            <?php if ( get_sub_field( 'file' ) ) { ?>
                                                                <a class="text-link red-text large-link-text red-underline-link  download-text-link" href="<?php the_sub_field( 'file' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </span>                                      
                                        </div>
                                    </div>
                                    <?php $slideCount++; ?>
                                <?php endwhile; ?>
                            </div>
                            <div class="column one-half image-column sticky-column">
                                <div class="image-container">
                                    <?php $image_index = 0; ?>
                                    <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                        <div class="slider-bg-container bg-container <?php echo ($image_index === 0) ? 'active' : ''; ?>">
                                            <?php $image = get_sub_field( 'image' ); ?>
                                            <?php if ( $image ) { ?>
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                    'alt'     => $image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </div>
                                        <?php $image_index++; ?>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div> 
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>               
        </div>
    </div>
</section>


