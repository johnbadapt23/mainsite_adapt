<section class="sneak-peak-module">
	<div class="container">
		<div class="sneak-title-container">
			<div class="column one-half">
				<h2 class="sneak-title text-black">
					<?php echo get_sub_field( 'title' ); ?>
				</h2>
				<span class="arrow-container">
					<span class="image-container">
						<span class="bg-container">
							<?php $arrow_image = get_sub_field( 'arrow_image' ); ?>
							<?php if ( $arrow_image ) { ?>
								<img src="<?php echo $arrow_image['url']; ?>" alt="<?php echo $arrow_image['alt']; ?>" />
							<?php } ?>
						</span>
					</span>
				</span>
			</div>
		</div>
		<div class="sneak-peak-container">
			<div class="sneak-image-container desktop">
				<div class="sneak-image-inner">
					<?php if ( have_rows( 'research' ) ) : ?>
						<?php $imagecounter=1;?>
						<?php while ( have_rows( 'research' ) ) : the_row(); ?>
							<span class="sneak-image<?php if($imagecounter == 1){ ?> active<?php } ?>">
								<?php $image = get_sub_field( 'image' ); ?>
								<a class="image-popup" href="<?php echo $image['url']; ?>">
									<span class="image-container">
										<span class="bg-container">
											<?php if ( $image ) { ?>
												<img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											<?php } ?>
										</span>
										<span class="enlarge-image"></span>
									</span>
								</a>
							</span>
							<?php $imagecounter++;?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="sneak-text-container">
				<?php if ( have_rows( 'research' ) ) : ?>
					<?php $counter=1;?>
					<?php while ( have_rows( 'research' ) ) : the_row(); ?>
						<span class="sneak-peak-text-continer">
							<span class="sneak-title<?php if($counter == 1){ ?> active<?php } ?>"><?php echo get_sub_field( 'title' ); ?></span>
							<span class="text-black sneak-peak-text" <?php if($counter == 1){ ?>style="display: block;"<?php } ?>>
								<span class="sneak-image-mobile">
									<?php $image = get_sub_field( 'image' ); ?>
									<a class="image-popup" href="<?php echo $image['url']; ?>">
										<span class="image-container">
											<span class="bg-container">
												<?php if ( $image ) { ?>
													<img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
												<?php } ?>
											</span>
											<span class="enlarge-image"></span>
										</span>
									</a>
								</span>
								<span class="text-inner"><?php echo get_sub_field( 'text' ); ?></span>
							</span>
						</span>
						<?php $counter++;?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
