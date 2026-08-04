<section class="experience-module">
	<div class="container">
		<div class="experience-title-container">
			<div class="column one-half">
				<h2 class="sneak-title text-black">
					<?php echo get_sub_field( 'title' ); ?>
				</h2>
			</div>
		</div>
		<div class="experience-container">
			<div class="experience-image-container">
				<div class="experience-image-inner">
					<?php if ( have_rows( 'switch_content' ) ) : ?>
						<?php $imagecounter=1;?>
						<?php while ( have_rows( 'switch_content' ) ) : the_row(); ?>
							<span class="experience-image<?php if($imagecounter == 1){ ?> active<?php } ?>">
								<?php $image = get_sub_field( 'image' ); ?>
								<span class="image-container">
									<span class="bg-container">
										<?php if ( $image ) { ?>
											<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
											'alt'     => $image['alt'],
											'loading' => false,
										) ); ?>
										<?php } ?>
									</span>
								</span>
							</span>
							<?php $imagecounter++;?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="experience-text-column">
				<?php if ( have_rows( 'switch_content' ) ) : ?>
					<?php $counter=1;?>
					<?php while ( have_rows( 'switch_content' ) ) : the_row(); ?>
						<span class="experience-text-container <?php if($counter == 1){ ?> active<?php } ?>">
							<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
							<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
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
