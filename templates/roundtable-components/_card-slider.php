<section class="roundtable-card-slider-module <?php echo get_sub_field( 'background_colour' ); ?>">
	<div class="container">
		<div class="top-content">
			<span class="title h2-style"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
		<div class="roundtable-card-slider">
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php $counter=1;?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="slide">
						<div class="slide-inner">
                            <span class="slide-tag-container">
                                <span class="tag"><?php echo get_sub_field( 'tag' ); ?></span>
                            </span>
							<div class="slide-image-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
												'alt'     => $image['alt'],
												'loading' => 'lazy',
											) ); ?>
										<?php } ?>
									</span>
								</span>
							</div>
							<span class="slide-text-container">
								<span class="slide-text"><?php echo get_sub_field( 'card_text' ); ?></span>
							</span>
						</div>
					</div>
					<?php $counter ++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
		<div class="cards-progress-container">
			<?php $slideCount = $counter - 1; ?>
            <?php $slidePercent = 100 / $slideCount; ?>
            <div class="cards-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $slidePercent;?>" style="background-size:<?php echo $slidePercent;?>%">
                <span class="slider__label sr-only">
            </div>
		</div>
	</div>
</section>
