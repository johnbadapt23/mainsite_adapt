<?php if(get_sub_field( 'background_colour' ) == 'background-white'){ ?>
    <?php $textColour = 'text-black'; ?>
<?php } else { ?>
    <?php $textColour = 'white-text'; ?>
<?php }?>
<section class="left-text-links gtm-image-text gtm-map-block advisors-centered-text-links left-text-links-image background-black">    
    <div class="container">  
        <div class="column-container">      
            <div class="text-container column text-column one-half">   
                <span class="inner-text">                        
                    <h1 class="h1-style bold-red primary-white"><?php echo get_sub_field( 'title' ); ?></h1>
                    <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                </span>
                <div class="mobile-image-container">
                    <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
                    <?php if ( $mobile_image ) { ?>
                        <span class="background-container">
                            <img src="<?php echo $mobile_image['url']; ?>" alt="<?php echo $mobile_image['alt']; ?>" />
                        </span>
                    <?php } ?>
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
                                <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?>                                 
                                <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup">
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
            <div class="column one-half image-column map-column">
                <div class="map-container">
                    <?php $desktop_image = get_sub_field( 'desktop_image' ); ?>
                    <?php if ( $desktop_image ) { ?>
                        <img loading="lazy" src="<?php echo $desktop_image['url']; ?>" alt="<?php echo $desktop_image['alt']; ?>" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>


			