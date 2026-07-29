<?php 
	$slideAmount = get_sub_field( 'slide_amount' );
	$link_text = get_sub_field('link_text');
	$link_url = get_sub_field('link_url');
	$link_target = get_sub_field('link_target');
?>
<?php if($slideAmount){
} else {
	$slideAmount = 'five-slides';
} ?>
<section class="quote-slider market-buyer-quotes background-black">
	<div class="container">
		<?php if ( get_sub_field( 'title' )) { ?>
			<h2 class="quote-slider-title"><?php echo get_sub_field( 'title' ); ?></h2>
		<?php } ?>
		<div class="quote-slider-module">
			<?php if ( have_rows( 'slides' ) ) : ?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
					<div class="quote-slide">
						<div class="quote-slider-inner">
							<h4 class="quote text-white"><?php echo get_sub_field( 'quote' ); ?></h4>
							<span class="bottom">
								<span class="credits">
									<span class="quote-title text-white labelMedium"><?php echo get_sub_field( 'title' ); ?></span>
									<span class="quote-business text-secondary labelMedium"><?php echo get_sub_field( 'business_name' ); ?></span>
								</span>
								<span class="link mobile-hide">
									<a href="<?php echo $link_url ?>" target="<?php echo $link_target; ?>" class="red-text red-underline-link red-arrow text-link"><?php echo $link_text; ?></a>
								</span>
							</span>
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
								<img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
							<?php } ?>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
			
		</div>
		<span class="link desktop-hide">
			<a href="<?php echo $link_url ?>" target="<?php echo $link_target; ?>" class="red-text red-underline-link red-arrow text-link"><?php echo $link_text; ?></a>
		</span>
	</div>
</section>