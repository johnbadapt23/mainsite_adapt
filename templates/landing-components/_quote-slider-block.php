<?php $slideAmount = get_sub_field( 'slide_amount' ); ?>
<?php if($slideAmount){
} else {
	$slideAmount = 'five-slides';
} ?>
<section class="quote-slider">
	<div class="container">
		<?php if ( get_sub_field( 'title' )) { ?>
			<h2 class="quote-slider-title"><?php echo get_sub_field( 'title' ); ?></h2>
		<?php } ?>
		<div class="quote-slider-module">
			<?php if ( have_rows( 'slides' ) ) : ?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
					<div class="quote-slide">
						<div class="quote-slider-inner">
							<h4 role="heading" aria-level="3" class="quote text-black"><?php echo get_sub_field( 'quote' ); ?></h4>
							<span class="quote-title text-black"><?php echo get_sub_field( 'title' ); ?></span>
							<span class="quote-business red-text"><?php echo get_sub_field( 'business_name' ); ?></span>
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
</section>
