<section class="keynote-slider-module">
	<div class="container">
		<div class="top-content">
			<span class="title h2-style"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
		<div class="keynote-slider">
			<?php if ( have_rows( 'slides' ) ) : ?>
				<?php $counter=1;?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
					<div class="slide">
						<div class="slide-inner">
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
									<span class="hover-container background-black">
										<span class="hover-text"><?php echo get_sub_field( 'hover_text' ); ?></span>
										<?php if (get_sub_field( 'vimeo_url' )) { ?>
											<a class="popup-vimeo keynote-button" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_url' ); ?>"><?php echo get_sub_field( 'video_button_text' ); ?></a>
										<?php } ?>
									</span>
								</span>
							</div>
							<span class="slide-details">
								<span class="name text-black"><?php echo get_sub_field( 'title' ); ?></span>
								<span class="name-title text-black"><?php echo get_sub_field( 'sub_title' ); ?></span>
							</span>
						</div>
					</div>
					<?php $counter ++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
		<div class="progress-container">
			<?php $slideCount = $counter - 1; ?>
            <?php $slidePercent = 100 / $slideCount; ?>
            <div class="keynote-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $slidePercent;?>" style="background-size:<?php echo $slidePercent;?>%">
                <span class="slider__label sr-only">
            </div>
		</div>
	</div>
</section>
