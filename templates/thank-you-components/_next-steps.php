<section class="partners-cards-module next-steps-block">
	<div class="container">
		<div class="top-content">
			<h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
		</div>
		<div class="card-container">
			<?php if ( have_rows( 'card' ) ) : ?>
				<?php $counter=1; ?>
				<?php while ( have_rows( 'card' ) ) : the_row(); ?>
					<div class="card">
						<?php $arrow_image = get_sub_field( 'arrow_image' ); ?>
						<?php if ( $arrow_image ) { ?>
							<span class="arrow-container"><?php echo wp_get_attachment_image( $arrow_image['ID'], 'full', false, array(
								'alt'     => $arrow_image['alt'],
								'loading' => 'lazy',
							) ); ?></span>
						<?php } ?>
						<span class="content-container">
							<div class="counter-title-container">
								<span class="counter-circle-outer">
									<span class="counter circle-counter">
										<?php echo $counter ?>
									</span>
								</span>
							</div>
							<h4 role="heading" aria-level="3" class="black-text"><?php echo get_sub_field( 'title' ); ?></h4>
							<span class="content-container-bottom">
								<span class="card-text"><?php echo get_sub_field( 'text' ); ?></span>
							</span>
						</span>
					</div>
					<?php $counter++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</section>
