<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<?php $has_bullets = 'no-bullets'; ?>
<?php if ( have_rows( 'bullet_points' ) ) : ?>
    <?php while ( have_rows( 'bullet_points' ) ) : the_row(); ?>  
        <?php $has_bullets = 'with-bullets'; ?>
    <?php endwhile; ?>
 <?php endif; ?>
<section class="left-text-links advisors-centered-text-links left-text-links-image <?php echo get_sub_field( 'background_colour' ); ?> <?php echo $has_bullets; ?>">    
    <div class="container">  
        <div class="column-container">      
            <div class="text-container text-column one-half">   
                <?php if (get_sub_field( 'sub_title' )) { ?> 
                    <span class="sub-title labelSmall <?php echo $textColour; ?>"><?php echo get_sub_field( 'sub_title' ); ?></span>
                <?php } ?>                         
                <h2 <?php if(get_sub_field( 'title_max_width' )){ ?> style="max-width:<?php echo get_sub_field( 'title_max_width' ); ?>px;"<?php } ?>class="h1-style bold-red <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="text p.large <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                <div class="mobile-image-container">
                    <div class="image-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <span class="links-container">
                    <?php if ( have_rows( 'links' ) ) : ?>
                        <?php $buttonCounter = 1;?>
                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                <?php $file = get_sub_field( 'file' ); ?>
                                <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                             <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup<?php echo $buttonCounter; ?>">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?> 
                                 <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup<?php echo $buttonCounter; ?>">
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
            <div class="column one-half image-column">
                <div class="image-container">
                    <div class="bg-container">
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ( have_rows( 'bullet_points' ) ) : ?>
            <div class="bullet-point-container">
                <div class="column-container">
                    <?php while ( have_rows( 'bullet_points' ) ) : the_row(); ?>                
                        <div class="column bullet-point-column">
                            <span class="bullet-icon-container">
                                <?php $icon = get_sub_field( 'icon' ); ?>
                                <?php if ( $icon ) { ?>
                                    <?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
                                        'alt'     => $icon['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                <?php } ?>
                            </span>
                            <span class="bullet-text labelLarge"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>                        
                    <?php endwhile; ?>
                </div>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>