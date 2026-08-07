<section class="form-popup-slider-module background-white">
    <div class="container">
        <div class="title-container">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <?php if ( have_rows( 'slide' ) ) : ?>
            <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                <?php if(get_sub_field( 'form_embed_code' )){ ?> 
                    <span class="form-embed" style="display: none;">                                
                        <?php echo get_sub_field( 'form_embed_code' ); ?>
                    </span>
                <?php } ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <div class="form-popup-slider-container">
            <div class="form-popup-slider">
                <?php if ( have_rows( 'slide' ) ) : ?>
                    <?php $slideCounterPopup = 1;?>
                    <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                        <div class="slide">
                            <?php if(get_sub_field('form_type') == 'hubspot-form'){ ?> 
                                <span class="form-embed" style="display: none;">  
                                    <div class="preview-cta-form login-form-container" id="slideFormPopup<?php echo $slideCounterPopup; ?>">
                                        <div class="form-container"><?php echo get_sub_field( 'form_embed_code' ); ?></div>
                                    </div>                                                                  
                                </span>                             
                                <a class="formPopupHubspot" href="#slideFormPopup<?php echo $slideCounterPopup; ?>">                                                   
                                    <span class="form-popup-slide">
                                        <span class="logo-arrow-container">
                                            <span class="logo-container">
                                                <?php $title_logo = get_sub_field( 'title_logo' ); ?>
                                                <?php if ( $title_logo ) { ?>
                                                    <?php echo wp_get_attachment_image( $title_logo['ID'], 'full', false, array(
                                                        'alt'     => $title_logo['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="arrow-container"></span>
                                        </span>
                                        <span class="slide-image-container">
                                            <span class="bg-container-outer">
                                                <span class="slide-bg-container">
                                                    <?php $image_one = get_sub_field( 'image_one' ); ?>
                                                    <?php if ( $image_one ) { ?>
                                                        <?php echo wp_get_attachment_image( $image_one['ID'], 'full', false, array(
                                                            'alt'     => $image_one['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php } ?>
                                                </span>
                                                <span class="slide-bg-container">
                                                    <?php $image_two = get_sub_field( 'image_two' ); ?>
                                                    <?php if ( $image_two ) { ?>
                                                        <?php echo wp_get_attachment_image( $image_two['ID'], 'full', false, array(
                                                            'alt'     => $image_two['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php } ?>
                                                </span>
                                                <span class="slide-bg-container">
                                                    <?php $image_three = get_sub_field( 'image_three' ); ?>
                                                    <?php if ( $image_three ) { ?>
                                                        <?php echo wp_get_attachment_image( $image_three['ID'], 'full', false, array(
                                                            'alt'     => $image_three['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        </span>                                                            
                                    </span>
                                </a>
                            <?php } else { ?> 
                                <?php if(get_sub_field( 'form_data_id' )){ ?> 
                                    <?php $dataForm = get_sub_field( 'form_data_id' ); ?>
                                <?php } ?>                              
                                <?php
                                    $formButtonCode = get_sub_field( 'form_button_code' );
                                    $formEmbedCode  = get_sub_field( 'form_embed_code' );

                                    // Pull the bare form key ('i' value) out of the embed script, e.g. 'i':'zktbjjj'
                                    $formKey = '';
                                    if ( $formEmbedCode && preg_match( "/'i'\s*:\s*'([a-zA-Z0-9]+)/", $formEmbedCode, $m ) ) {
                                        $formKey = $m[1];
                                    }

                                    if ( $formButtonCode && $formKey ) {
                                        if ( preg_match( '/<a\b[^>]*>/i', $formButtonCode, $m ) && stripos( $m[0], 'href=' ) === false ) {
                                            $hrefTag = '<a href="https://formcrafts.com/a/' . esc_attr( $formKey ) . '"';
                                            $fixedTag = preg_replace( '/<a\b/i', $hrefTag, $m[0], 1 );
                                            $formButtonCode = preg_replace( '/<a\b[^>]*>/i', $fixedTag, $formButtonCode, 1 );
                                        }
                                        echo $formButtonCode;
                                    } elseif ( $formButtonCode ) {
                                        echo $formButtonCode; // fallback if key extraction fails
                                    }
                                ?>                                        
                                <span class="form-popup-slide" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                    <span class="logo-arrow-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                        <span class="logo-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                            <?php $title_logo = get_sub_field( 'title_logo' ); ?>
                                            <?php if ( $title_logo ) { ?>
                                                <?php echo wp_get_attachment_image( $title_logo['ID'], 'full', false, array(
                                                    'alt'     => $title_logo['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                        <span class="arrow-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>></span>
                                    </span>
                                    <span class="slide-image-container">
                                        <span class="bg-container-outer" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                            <span class="slide-bg-container">
                                                <?php $image_one = get_sub_field( 'image_one' ); ?>
                                                <?php if ( $image_one ) { ?>
                                                    <?php $image_one_attrs = array(
                                                        'alt'     => $image_one['alt'],
                                                        'loading' => 'lazy',
                                                    );
                                                    if ( $dataForm ) {
                                                        $image_one_attrs['data-fc-open'] = $dataForm;
                                                    }
                                                    echo wp_get_attachment_image( $image_one['ID'], 'full', false, $image_one_attrs ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="slide-bg-container">
                                                <?php $image_two = get_sub_field( 'image_two' ); ?>
                                                <?php if ( $image_two ) { ?>
                                                    <?php $image_two_attrs = array(
                                                        'alt'     => $image_two['alt'],
                                                        'loading' => 'lazy',
                                                    );
                                                    if ( $dataForm ) {
                                                        $image_two_attrs['data-fc-open'] = $dataForm;
                                                    }
                                                    echo wp_get_attachment_image( $image_two['ID'], 'full', false, $image_two_attrs ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="slide-bg-container">
                                                <?php $image_three = get_sub_field( 'image_three' ); ?>
                                                <?php if ( $image_three ) { ?>
                                                    <?php $image_three_attrs = array(
                                                        'alt'     => $image_three['alt'],
                                                        'loading' => 'lazy',
                                                    );
                                                    if ( $dataForm ) {
                                                        $image_three_attrs['data-fc-open'] = $dataForm;
                                                    }
                                                    echo wp_get_attachment_image( $image_three['ID'], 'full', false, $image_three_attrs ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </span>                                                            
                                </span>
                                <?php if(get_sub_field( 'form_button_code' )){ ?> 
                                    </a>
                            <?php } ?>   
                            <?php } ?>                         
                        </div>
                        <?php $slideCounterPopup++;?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <span class="progress-bar-outer"><span class="progress-bar-form-popup"></span></span>
        </div>
    </div>
</section>