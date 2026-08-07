<section class="cards-module gtm-cards-module background-black" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
	<div class="container">
		<div class="top-content">
			<h2 class="text-white bold-grey"><?php echo get_sub_field( 'title' ); ?></h2>
			<span class="text-white"><?php echo get_sub_field( 'sub_title' ); ?></span>
		</div>
		<div class="card-container">
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="card">
						<span class="icon-container">
							<span class="image-container">
								<span class="bg-container first-bg">
									<?php $icon = get_sub_field( 'icon' ); ?>
									<?php if ( $icon ) { ?>
										<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
											'alt'     => $icon['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</span>
								<span class="bg-container hover-bg">
									<?php $hover_icon = get_sub_field( 'hover_icon' ); ?>
									<?php if ( $hover_icon ) { ?>
										<?php echo wp_get_attachment_image( $hover_icon['ID'], 'adapt-optimized', false, array(
											'alt'     => $hover_icon['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</span>
							</span>
						</span>
						<span class="content-container">
							<span class="content-container-inner">
								<span class="card-title labelXL"><?php echo get_sub_field( 'card_title' ); ?></span>
								<span class="card-text"><?php echo get_sub_field( 'card_text' ); ?></span>
							</span>
						</span>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
		<div class="icon-slider-outer mobile-hide">
            <?php if ( have_rows( 'icon_slider' ) ) : ?>
                <span class="icon-slide-link-container">
                    <?php while ( have_rows( 'icon_slider' ) ) : the_row(); ?>
                        <button type="button" class="icon-slide-link labelSmall"><?php echo get_sub_field( 'slide_title' ); ?></button>
                    <?php endwhile; ?>
                </span>
			<?php else : ?>
            <?php endif; ?>
            <?php if ( have_rows( 'icon_slider' ) ) : ?>
                <div class="gtm-icon-slider">
                    <?php while ( have_rows( 'icon_slider' ) ) : the_row(); ?>
                        <div class="gtm-icon-slide">
                            <?php if ( have_rows( 'slide_icons' ) ) : ?>
								<?php while ( have_rows( 'slide_icons' ) ) : the_row(); ?>
									<span class="gtm-icon-column column one-quarter">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<?php if ( $icon ) { ?>
											<span class="icon-container">
												<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
													'alt'     => $icon['alt'],
													'loading' => 'lazy',
												) ); ?>
											</span>
										<?php } ?>
										<span class="labelLarge primary-white"><?php echo get_sub_field( 'title' ); ?></span>
									</span>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
			<?php else : ?>
            <?php endif; ?>
        </div>
		<div class="icon-accordion desktop-hide">
	<?php if ( have_rows( 'icon_slider' ) ) : ?>
		<div class="gtm-icon-accordion">
			<?php 
			$first = true; // flag for the first item
			while ( have_rows( 'icon_slider' ) ) : the_row(); 
			?>
				<div class="gtm-icon-accordion-item">
					<span class="gtm-icon-title labelMedium <?php echo $first ? 'active' : ''; ?>">
						<?php echo get_sub_field( 'slide_title' ); ?>
					</span>

					<?php if ( have_rows( 'slide_icons' ) ) : ?>
						<span class="gtm-icon-answer icon-container-outer" <?php echo $first ? 'style="display:inline;"' : 'style="display:none;"'; ?>>
							<span class="icon-column-container">
								<?php while ( have_rows( 'slide_icons' ) ) : the_row(); ?>
									<span class="gtm-icon-column">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<?php if ( $icon ) { ?>
											<span class="icon-container">
												<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
													'alt'     => $icon['alt'],
													'loading' => 'lazy',
												) ); ?>
											</span>
										<?php } ?>
										<span class="labelMedium primary-white"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
									</span>
								<?php endwhile; ?>
							</span>
						</span>
					<?php endif; ?>
				</div>
				<?php $first = false; // reset after first loop ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</div>

	</div>
</section>
