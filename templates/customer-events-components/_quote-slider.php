<?php $slideAmount = get_sub_field( 'slide_amount' ); ?>
<?php if($slideAmount){
} else {
	$slideAmount = 'five-slides';
} ?>
<?php $textColour = 'text-black'; ?>
<?php if(get_sub_field( 'background_colour' ) == 'background-true-black') { ?>
	<?php $textColour = 'text-white'; ?>
	<style>
		section.quote-slider.customer-events-slider {
			background-color: #121212;
		}
	</style>
<?php } ?>
<section class="quote-slider customer-events-slider <?php if(get_sub_field( 'background_colour' )) { ?><?php echo get_sub_field( 'background_colour' ); ?><?php } ?>">
	<div class="container">
		<div class="quote-slider-outer <?php if(get_sub_field( 'background_colour' ) == 'background-true-black') { ?>background-black<?php } else { ?>background-white<?php } ?>">
			<div class="quote-slider-module">
				<?php if ( have_rows( 'slides' ) ) : ?>
					<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
						<div class="quote-slide">
							<div class="customer-quote-slider-inner">
								<?php if (get_sub_field( 'large_quote_text' )) { ?> 
									<h2 class="quote <?php echo $textColour; ?>"><?php echo get_sub_field( 'large_quote_text' ); ?></h2>
								<?php } ?>	
								<?php if (get_sub_field( 'quote_text' )) { ?>
									<span class="small-quote-text p-small"><?php echo get_sub_field( 'quote_text' ); ?></span>
								<?php } ?>						
								<span class="quote-title <?php echo $textColour; ?> labelMedium"><?php echo get_sub_field( 'name' ); ?></span>
								<span class="quote-business grey-text labelMedium"><?php echo get_sub_field( 'role' ); ?></span>
							</div>
						</div>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
			<span class="progress-container <?php echo $slideAmount; ?>">
				<span class="progress-bar">
					<?php if ( have_rows( 'slides' ) ) : ?>
						<?php $counter = 0; ?>
						<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
							<span class="progress-inner <?php if($counter == 0){ ?> animate<?php } ?>" data-count="<?php echo $counter;?>" class="progress-bar-inner"></span>
							<?php $counter++; ?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</span>
				<span class="active-bar"></span>
			</span>
			<div class="quote-slider-thumbnails <?php echo $slideAmount; ?>">
				<?php if ( have_rows( 'slides' ) ) : ?>
					<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
						<div class="quote-thumbnail">
							<div class="thumbnail-container">
								<?php $logo = get_sub_field( 'logo' ); ?>
								<?php if ( $logo ) { ?>
									<?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
										'alt'     => $logo['alt'],
										'loading' => 'lazy',
									) ); ?>
								<?php } ?>
							</div>
						</div>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>