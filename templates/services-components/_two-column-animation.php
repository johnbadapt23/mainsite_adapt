<section class="animation-icon-module">
	<div class="container">
		<div class="column-container">
			<?php if ( get_sub_field( 'animation_or_text' ) == 'text') { ?>
				<div class="column animation-column large-text-column background-pink">
					<span class="v-wrap">
						<span class="v-box left-align">
							<h2 class="black-text"><?php echo get_sub_field( 'text' ); ?></h2>
						</span>
					</span>
				</div>
			<?php } ?>
			<div class="column icon-column <?php if ( get_sub_field( 'animation_or_text' ) == 'text') { ?>left-border<?php } ?>">
				<h3 class="text-black"><?php echo get_sub_field( 'title' ); ?></h3>
				<?php if ( have_rows( 'icons' ) ) : ?>
					<div class="icon-column-container">
						<?php while ( have_rows( 'icons' ) ) : the_row(); ?>
							<div class="icon-column">
								<span class="icon-container">
									<span class="image-container">
										<span class="bg-container contained-image">
											<?php $icon = get_sub_field( 'icon' ); ?>
											<?php if ( $icon ) { ?>
												<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
													'alt'     => $icon['alt'],
													'loading' => 'lazy',
												) ); ?>
											<?php } ?>
										</span>
									</span>
								</span>
								<span class="title-container">
									<span class="labelMedium text-black"><?php echo get_sub_field( 'title' ); ?></span>
								</span>
							</div>
						<?php endwhile; ?>
					</div>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
			<?php if ( get_sub_field( 'animation_or_text' ) == 'animation') { ?>
				<div class="column animation-column background-pink">
					<?php $animation_json = get_sub_field( 'animation' ); ?>
					<?php $animation_id = get_sub_field( 'animation_id' ); ?>
					<?php if ( $animation_json ) { ?>
						<div class="v-wrap">
	                        <div class="v-box">
								<span class="animation-container">
									<span class="animator-player">
										<lottie-player speed="1" id="<?php echo $animation_id; ?>" src="<?php echo $animation_json['url']; ?>" background="transparent" style="width: 100%; height: auto"></lottie-player>
									</span>
								</span>
								<script>
									LottieInteractivity.create({
										player:'#<?php echo $animation_id; ?>',
										mode:"scroll",
										actions: [
											{
											visibility: [0.25, 1.0],
											type: "play"
											}
										]
									});
								</script>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
