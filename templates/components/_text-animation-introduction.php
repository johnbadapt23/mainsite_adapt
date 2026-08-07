<section class="text-animation-introduction <?php echo get_sub_field( 'background_colour' ); ?>">
    <?php $background_pattern = get_sub_field( 'background_pattern' ); ?>
	<?php if ( $background_pattern ) { ?>
        <div class="background-pattern" style="background-image: url(<?php echo $background_pattern['url']; ?>)"></div>
	<?php } ?>
    <div class="container">
        <div class="introduction-content-container">
            <div class="image-column column one-half">
                <div class="image-container">
                    <div class="bg-container contained-image">
                        <?php 
                        $image = get_sub_field( 'image' ); 
                        $image_id = attachment_url_to_postid( $image['url'] );
                        ?>
            			<?php if ( $image_id ) { ?>
                            <?= wp_get_attachment_image($image_id, 'full'); ?>
            			<?php } ?>
                    </div>
                </div>
            </div>
            <div class="text-column column one-half">
                <span class="animation-text-container">
                    <h1><?php echo get_sub_field( 'title' ); ?></h1>
                    <span class="animation-text-line">
                        <span class="pre-animation-text h1-style"><?php echo get_sub_field( 'pre_animation_text' ); ?></span>
                        <span class="text-animation-container rotating">
                            <?php if ( have_rows( 'text_animation' ) ) : ?>
                				<?php while ( have_rows( 'text_animation' ) ) : the_row(); ?>
                                    <span class="rotating-outer">
                    					<span class="content-list-item h1-style"><?php echo get_sub_field( 'text' ); ?></span>
                                    </span>
                				<?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( have_rows( 'text_animation' ) ) : ?>
                                <?php $counter=1;?>
                                <?php while ( have_rows( 'text_animation' ) ) : the_row(); ?>
                                    <?php if ($counter==1) { ?>
                                        <span class="rotating-outer">
                        					<span class="content-list-item h1-style"><?php echo get_sub_field( 'text' ); ?></span>
                                        </span>
                                    <?php } ?>
                                    <?php $counter++;?>
                				<?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    </span>
                </h1>
    			<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                <?php if(get_sub_field('form_embed')){ ?> 
                    <span class="form-container-outer">
                        <span class="pre-form-text"><span class="icon-container"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/form-arrow-down.svg" alt="" width="16"/></span><span class="medium-grey labelXXsmall"><?php echo get_sub_field('pre_form_text'); ?></span></span>
                        <span class="form-container-inner">
                            <span class="form-popup" id="animationForm">
                                <span class="form-container">
                                    <span class="form-intro">
                                        <?php 
                                        $popup_form_image = get_sub_field( 'popup_form_image' ); 
                                        $popup_form_image_id = attachment_url_to_postid( $popup_form_image['url'] );
                                        ?>
                                        <?php if ( $popup_form_image_id ) { ?>
                                            <?= wp_get_attachment_image($popup_form_image_id, 'full'); ?>
                                        <?php } ?>
                                        <h4 role="heading" aria-level="2" class="text-white"><?php echo get_sub_field( 'popup_form_title' ); ?></h4>
                                        <p class="p-xsmall medium-grey "><?php echo get_sub_field( 'popup_form_text' ); ?></p>
                                    </span>
                                    <span class="form">
                                        <?php echo get_sub_field('form_embed'); ?>
                                    </span>
                                </span> 
                                <span class="thank-you-message background-red" style="display: none;">
                                    <?php 
                                    $popup_form_thank_you_image = get_sub_field( 'popup_form_thank_you_image' ); 
                                    $popup_form_thank_you_image_id = attachment_url_to_postid( $popup_form_thank_you_image['url'] );
                                    ?>
                                    <?php if ( $popup_form_thank_you_image_id ) { ?>
                                        <?= wp_get_attachment_image($popup_form_thank_you_image_id, 'full'); ?>
                                    <?php } ?>
                                    <h4 class="white-text"><?php the_sub_field( 'popup_form_thank_you_title' ); ?></h4>
                                    <p class="p-xsmall white-text"><?php the_sub_field( 'popup_form_thank_you_text' ); ?></p>
                                </span>                               
                            </span>
                            <span class="button-container">
                                <a class="formPopupHubspotHome stdBtn std-button red-button" href="#animationForm"><?php echo get_sub_field('form_button_text');?></a>                                   
                            </span>
                        </apan>
                    </span>
                   <script>
                        window.addEventListener("message", function(event) {
                            // Only accept messages from HubSpot forms
                            if (!event.data || !event.data.formGuid || !event.data.accepted) {
                                // console.log("[HS-DEB] Ignored message:", event.data);
                                return;
                            }

                            console.log("[HS-DEB] HubSpot form submitted!", event.data);

                            // Detect our popup wrapper
                            const popup = document.querySelector("#animationForm");
                            if (!popup) {
                                // console.log("[HS-DEB] #animationForm wrapper not found");
                                return;
                            }

                            const containerInner = document.querySelector(".introduction-content-container .form-container-inner");
                            if (containerInner) {
                                containerInner.classList.add("submitted");
                                // console.log("[HS-DEB] .form-container-inner class 'submitted' added");
                            }

                            // Hide the form container
                            const formContainer = popup.querySelector(".form-container");
                            if (formContainer) {
                                formContainer.style.display = "none";
                                // console.log("[HS-DEB] .form-container hidden");
                            }

                            // Show thank-you message
                            const thankYou = popup.querySelector(".thank-you-message");
                            if (thankYou) {
                                thankYou.style.display = "flex";
                                // console.log("[HS-DEB] .thank-you-message shown");
                            }

                            // Update the CTA button
                            const btn = document.querySelector(".formPopupHubspotHome");
                            if (btn) {
                                btn.textContent = "Subscribed";
                                btn.classList.add("submitted");
                                // console.log("[HS-DEB] CTA button updated");
                            }
                        });
                    </script>



                <?php } ?>
    			<?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container">
                        <?php $counter = 1;?>
        				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if(get_sub_field( 'button_type' ) == 'scroll-to-link') { ?>
            					<a class="scroll-to-button std-button <?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                            <?php } else { ?>
                                <a class="std-button <?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                            <?php } ?>
                            <?php $counter++; ?>
        				<?php endwhile; ?>
                    </span>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </div>

        </div>
    </div>
</section>
