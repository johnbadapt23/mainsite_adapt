<section class="market-form" <?php if( get_sub_field('id')){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="half-background background-light-grey"></div>
    <div class="container">
        <div class="market-column-container">
            <div class="column one-half content-column">
                <span class="pre-title black-text"><?php echo get_sub_field( 'pre_title' ); ?></span>
                <h1 class="black-text"><?php echo get_sub_field( 'title' ); ?></h1>
                <span class="black-text text"><?php echo get_sub_field( 'text' ); ?></span>
                <?php if ( have_rows( 'points' ) ) : ?>
                    <span class="points-container">
                        <?php while ( have_rows( 'points' ) ) : the_row(); ?>
                           <span class="points black-text"><img class="arrow-right-circle" src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-circle-black-right.svg" alt="" width="20"/><?php echo get_sub_field( 'text' ); ?></span>                            
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>

                <?php if(get_sub_field('form_type') == 'hubspot'){ ?>
                    <span class="form-popup-button-container">
                        <a class="formPopupHubspot stdBtn std-button red-button" href="#gtm-contact-block_formPopup"><?php echo get_sub_field( 'mobile_form_button_text' ); ?></a>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="gtm-contact-block_formPopup">
                                <div class="form-container"><?php echo get_sub_field( 'contact_form_embed' ); ?></div>
                            </div>
                        </div> 
                    </span>
                <?php } else { ?> 
                    <span class="form-popup-button-container"><?php echo get_sub_field( 'mobile_contact_form_popup_button_code' ); ?></span>
                    <span class="popup-form-container"><?php echo get_sub_field( 'mobile_contact_form_embed' ); ?></span>
                <?php } ?>

                <span class="contact-image-container desktop">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                            <?php } ?>
                        </span>
                    </span>
                </span>
            </div>
            
            <div class="column one-half form-column">
                <span class="form-container desktop">
                    <?php echo get_sub_field( 'contact_form_embed' ); ?>
                </span>
            </div>
        </div>
    </div>
</section>
