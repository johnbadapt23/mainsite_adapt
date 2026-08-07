<section class="subscribe-introduction <?php echo get_sub_field( 'background_colour' ); ?>">
	<span class="absolute-bottom-background"></span>
	<div class="container">
		<div class="content-container">
			<span class="offset-image-one"></span>
			<span class="offset-image-two"></span>
			<span class="offset-image-three"></span>
			<div class="content-inner">
				<span class="pre-title"><?php echo get_sub_field( 'pre_title' ); ?></span>
				<h1 class="title"><?php echo get_sub_field( 'title' ); ?></h1>
				<?php $buttonPost = get_sub_field( 'post_button_text' ); ?>
				<?php $arrow = get_sub_field( 'arrow' ); ?>
				<?php if ( have_rows( 'button' ) ) : ?>
					<span class="button-container">
						<span class="absolute-arrow-container">
							<span class="image-container">
								<span class="bg-container contained-image">
									<?php if ( $arrow ) { ?>
										<?php echo wp_get_attachment_image( $arrow['ID'], 'full', false, array(
											'alt'     => $arrow['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</span>
							</span>
						</span>
						<?php while ( have_rows( 'button' ) ) : the_row(); ?>
	                    	<?php if(get_sub_field( 'button_type' ) == 'scroll-to-button') { ?>
	                            <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button red-button">
	                                <?php echo get_sub_field( 'button_text' ); ?>
	                            </a>
	                        <?php } else if (get_sub_field( 'button_type' ) == 'form-popup') { ?>
								<span class="form-popup-button-container"><?php echo get_sub_field('form_button_code'); ?></span>
							<?php } else if (get_sub_field( 'button_type' ) == 'hubspot-popup') { ?>
								<a href="#subscribeHubspot" class="formPopupHubspotHome stdBtn std-button red-button">
	                                <?php echo get_sub_field( 'button_text' ); ?>
	                            </a>
								<div style="display: none;">         
									<div class="preview-cta-form login-form-container mfp-hide" id="subscribeHubspot">
										<div class="form-container">
											<?php echo get_sub_field('hubspot_embed'); ?>
										</div>
									</div>
								</div>
							<?php } else { ?>
	                            <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button red-button">
	                                <?php echo get_sub_field( 'button_text' ); ?>
	                            </a>
	                        <?php } ?>
	                        
	    				<?php endwhile; ?>
						<span class="post-button-text"><?php echo $buttonPost; ?></span>
					</span>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="image-overlay-container">
			<?php $image = get_sub_field( 'image' ); ?>
			<?php if ( $image ) { ?>
				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
					'alt'     => $image['alt'],
					'loading' => 'lazy',
				) ); ?>
			<?php } ?>
		</div>
	</div>
</section>
