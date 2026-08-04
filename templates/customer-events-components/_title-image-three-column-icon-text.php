<section class="three-column-icon-text-ecosystems three-column-partnered-research <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-true-black<?php }?> <?php echo get_sub_field( 'padding_top' ); ?> <?php echo get_sub_field( 'padding_bottom' ); ?>">
    <div class="container">        
        <div class="title-container">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <?php $bg_image = get_sub_field( 'bg_image' ); ?>						
        <div class="image-background-container" <?php if ( $bg_image ) { ?>style="background-image: url(<?php echo $bg_image['url']; ?>);"<?php } ?>>
            <div class="image-inner-container">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                        'class'   => 'desktop-image mobile-hide',
                        'alt'     => $image['alt'],
                        'loading' => false,
                    ) ); ?>
                <?php } ?>
                <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
                <?php if ( $mobile_image ) { ?>
                    <?php echo wp_get_attachment_image( $mobile_image['ID'], 'full', false, array(
                        'class'   => 'dmobile-image desktop-hide',
                        'alt'     => $mobile_image['alt'],
                        'loading' => 'lazy',
                    ) ); ?>
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
                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                                    'alt'     => $icon['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                        <span class="text-container">
                            <span class="labelXL"><?php echo get_sub_field( 'title' ); ?></span>
                            <p class="p-small text-medium-grey"><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
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
                    <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                        <?php $file = get_sub_field( 'file' ); ?>
                        <a class="scroll-to-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) == 'hubspot-form') { ?> 
                        <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#threeColumnFormPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="threeColumnFormPopup<?php echo $buttonCounter; ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                </div>
                            </div> 
                    <?php } else { ?> 
                        <span style="display: none"><?php echo get_sub_field( 'form_code' ); ?></span>
                        <span class="form-popup-button-container <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>"><?php echo get_sub_field( 'form_button' ); ?></span>                                                               
                    <?php } ?>                     	
                    <?php $buttonCounter++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </span>
    </div>
</section>