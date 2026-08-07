<section class="partner-form landing-form background-black" id="<?php echo get_sub_field( 'id' ); ?>">
    <div class="container">
        <div class="form-text-container background-black">
            <div class="column text-content-column one-half">
    			<h2 class="form-module-title white-text"><?php echo get_sub_field( 'title' ); ?></h2>
	             <span class="text white-text"><?php echo get_sub_field( 'text' ); ?></span>
                 <span class="image-arrow-container">
                     <span class="image-container">
                         <span class="bg-container">
                             <?php $image = get_sub_field( 'image' ); ?>
                 			<?php if ( $image ) { ?>
                 				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                 					'alt'     => $image['alt'],
                 					'loading' => 'lazy',
                 				) ); ?>
                 			<?php } ?>
                         </span>
                         <span class="arrow-container">
                             <span class="image-container">
                                 <span class="bg-container">
                                     <?php $arrow_image = get_sub_field( 'arrow_image' ); ?>
                         			<?php if ( $arrow_image ) { ?>
                         				<?php echo wp_get_attachment_image( $arrow_image['ID'], 'full', false, array(
                         					'alt'     => $arrow_image['alt'],
                         					'loading' => 'lazy',
                         				) ); ?>
                         			<?php } ?>
                                </span>
                            </span>
                         </span>
                     </span>
                 </span>
                 <span class="bottom-container">
                    <?php if(get_sub_field('form_type') == 'hubspot-form'){ ?> 
                        <span class="form-popup-button-container"><a class="formPopupHubspot stdBtn std-button" href="#parterFormPopup"><?php echo get_sub_field( 'hubspot_form_button' ); ?></a></span>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="parterFormPopup">
                                <div class="form-container"><?php echo get_sub_field( 'mobile_form_embed' ); ?></div>
                            </div>
                        </div>    
                    <?php } else { ?> 
                        <span class="form-popup-button-container"><?php echo get_sub_field( 'mobile_form_button' ); ?></span>
                        <span class="popup-form-container"><?php echo get_sub_field( 'mobile_form_embed' ); ?></span>
                    <?php } ?>
                     
                 </span>
            </div>
            <div class="column one-half form-column">
                <span class="form-container">
                    <?php echo get_sub_field( 'form_embed' ); ?>
                </span>

            </div>
        </div>
    </div>
</section>