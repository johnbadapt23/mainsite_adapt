<section class="two-column-cta two-column-cta-home <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="cta-column-container">
            <span class="offset-image-one"></span>
            <span class="offset-image-two"></span>
            <span class="offset-image-three"></span>
            <div class="column one-half text-column <?php echo get_sub_field( 'image_column' ); ?>">
                <h2 class="title black-text"><?php echo get_sub_field( 'title' ); ?></h2>
    			<span class="text-container black-text"><?php echo get_sub_field( 'text' ); ?></span>
                <span class="button-container desktop">
        			<?php if ( have_rows( 'button' ) ) : ?>
        				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if(get_sub_field('button_type') == 'form-popup'){ ?>
                                <span class="form-popup-button-container with-red-button with-arrow white-white-arrow">
                                    <?php echo get_sub_field('formcraft_button'); ?>
                                </span>
                                <span class="formcraft-popup-container">
                                    <?php echo get_sub_field('formcraft_form'); ?>
                                </span>
                            <?php } else if( get_sub_field( 'button_type' ) == 'hubspot-form') { ?> 
                                <a class="formPopupHubspot std-button red-button button-with-arrow white-arrow-button" href="#twoColumnHomePopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="twoColumnHomePopup">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?>
                                <a class="std-button red-button button-with-arrow white-arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } ?>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </span>
            </div>
            <div class="column one-half image-column <?php echo get_sub_field( 'image_column' ); ?>">
                <div class="image-inner-container">
                    <?php $image = get_sub_field( 'image' ); ?>
        			<?php if ( $image ) { ?>
        				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
        			<?php } ?>
                    <?php if ( get_sub_field('caption')) { ?>
                        <span class="caption-text"><?php echo get_sub_field('caption'); ?>
                            <?php $arrow_image = get_sub_field( 'arrow_image' ); ?>
                			<?php if ( $arrow_image ) { ?>
                				<img loading="lazy" class="arrow" src="<?php echo $arrow_image['url']; ?>" alt="<?php echo $arrow_image['alt']; ?>" />
                			<?php } ?>
                        </span>
                    <?php } ?>
                </div>
                <span class="button-container mobile">
        			<?php if ( have_rows( 'button' ) ) : ?>
        				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if(get_sub_field('button_type') == 'form-popup'){ ?>
                                <span class="form-popup-button-container with-red-button with-arrow white-white-arrow">
                                    <?php echo get_sub_field('formcraft_button'); ?>
                                </span>
                                <span class="formcraft-popup-container">
                                    <?php echo get_sub_field('formcraft_form'); ?>
                                </span>
                            <?php } else { ?>
                                <a class="std-button red-button button-with-arrow white-arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } ?>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </span>
            </div>
        </div>
    </div>
</section>
