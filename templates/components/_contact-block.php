<?php
    $linkedInLink = get_field( 'linked_in', 'options'  );
    $youtubeLink = get_field( 'you_tube', 'options'  );
?>
<section class="contact-module background-black">
    <div class="container">
        <div class="contact-innner">
            <div class="title-details-column column">
                <span class="contact-image-container mobile">
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
                    </span>
                </span>
                <h2 class="white-text"><?php echo get_sub_field( 'contact_form_title' ); ?></h2>
                <span class="white-text text"><?php echo get_sub_field( 'contact_form_text' ); ?></span>
                <span class="contact-image-container desktop">
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
                    </span>
                </span>
                <span class="bottom-container">
                    <?php if(get_sub_field('form_type') == 'hubspot'){ ?> 
                        <span class="form-popup-button-container">
                            <a class="formPopupHubspot stdBtn std-button red-button" href="#contact-block_formPopup"><?php echo get_sub_field( 'mobile_form_button_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="contact-block_formPopup">
                                    <div class="form-container"><?php echo get_sub_field( 'contact_form_embed' ); ?></div>
                                </div>
                            </div> 
                        </span>
                    <?php } else { ?> 
                        <span class="form-popup-button-container"><?php echo get_sub_field( 'mobile_contact_form_popup_button_code' ); ?></span>
                        <span class="popup-form-container"><?php echo get_sub_field( 'mobile_contact_form_embed' ); ?></span>
                    <?php } ?>
                    
                    <span class="fast-track-text"><?php echo get_sub_field( 'fast_track_text' ); ?></span>
                    <span class="social-links">
                        <?php if ($linkedInLink) {?>
                            <a class="social-link linkedin" href="<?php echo $linkedInLink;?>" target="_blank" rel="noopener noreferrer">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
                                <style type="text/css">
                                    .st0{fill:#ffffff;}
                                </style>
                                <path class="st0" d="M19.3,2V18c0,0.4-0.1,0.7-0.4,1c-0.3,0.3-0.6,0.4-1,0.4H2c-0.4,0-0.7-0.1-1-0.4c-0.3-0.3-0.4-0.6-0.4-1V2
                                    c0-0.4,0.1-0.7,0.4-1c0.3-0.3,0.6-0.4,1-0.4H18c0.4,0,0.7,0.1,1,0.4C19.2,1.3,19.3,1.7,19.3,2z M6.2,7.8H3.4v8.8h2.7V7.8z M6.4,4.8
                                    c0-0.2,0-0.4-0.1-0.6C6.2,4,6.1,3.8,5.9,3.7C5.8,3.5,5.6,3.4,5.4,3.3C5.2,3.2,5,3.2,4.8,3.2h0C4.4,3.2,4,3.4,3.7,3.7
                                    C3.4,4,3.2,4.4,3.2,4.8c0,0.4,0.2,0.8,0.5,1.1C4,6.2,4.4,6.4,4.8,6.4c0.2,0,0.4,0,0.6-0.1c0.2-0.1,0.4-0.2,0.5-0.3
                                    c0.2-0.1,0.3-0.3,0.4-0.5C6.4,5.2,6.4,5,6.4,4.8L6.4,4.8z M16.6,11.3c0-2.6-1.7-3.7-3.3-3.7c-0.5,0-1.1,0.1-1.6,0.3
                                    c-0.5,0.2-0.9,0.6-1.2,1.1h-0.1V7.8H7.8v8.8h2.7v-4.7c0-0.5,0.1-1,0.4-1.3s0.7-0.6,1.2-0.6h0.1c0.9,0,1.5,0.5,1.5,1.9v4.7h2.7
                                    L16.6,11.3z"/>
                                </svg>

                            </a>
                         <?php } ?>
                         <?php if ($youtubeLink) {?>
                          <a class="social-link youtube" href="<?php echo $youtubeLink;?>" target="_blank" rel="noopener noreferrer">
                              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 23 15" style="enable-background:new 0 0 23 15;" xml:space="preserve">
                              <style type="text/css">
                                .st0{fill:#ffffff;}
                              </style>
                              <path class="st0" d="M22.2,2.3c-0.1-0.4-0.4-0.9-0.7-1.2c-0.3-0.3-0.8-0.6-1.2-0.7C18.5,0,11.6,0,11.6,0C8.7,0,5.8,0.1,2.9,0.4
                                C2.4,0.6,2,0.8,1.7,1.1C1.3,1.5,1.1,1.9,1,2.3C0.6,4,0.5,5.7,0.5,7.4c0,1.7,0.1,3.4,0.5,5.1c0.1,0.4,0.4,0.8,0.7,1.2
                                C2,14,2.4,14.2,2.9,14.3c1.8,0.5,8.7,0.5,8.7,0.5c2.9,0,5.8-0.1,8.7-0.4c0.5-0.1,0.9-0.4,1.2-0.7c0.3-0.3,0.6-0.7,0.7-1.2
                                c0.3-1.7,0.5-3.4,0.5-5.1C22.7,5.7,22.5,4,22.2,2.3L22.2,2.3z M9.4,10.5V4.2l5.8,3.2L9.4,10.5z"/>
                              </svg>
                          </a>
                         <?php } ?>
                    </span>
                </span>
            </div>
            <div class="sub-title-form-column column">
                <span class="form-container desktop">
                    <?php echo get_sub_field( 'contact_form_embed' ); ?>
                </span>

            </div>
        </div>
    </div>
</section>
