<section class="background-image-stats">
	<div class="background-image-container">
		<div class="container">
			<?php $background_image = get_sub_field( 'background_image' ); ?>
			<?php if ( $background_image ) { ?>
				<?php echo wp_get_attachment_image( $background_image['ID'], 'full', false, array(
					'alt'     => $background_image['alt'],
					'loading' => 'lazy',
				) ); ?>
			<?php } ?>
		</div>
	</div>
	<?php if ( get_sub_field( 'number_of_stats' ) == 'three') { ?>
		<div class="container three-stats">
			<div class="title-container">
				<h3 class="black-text"><?php echo get_sub_field( 'title' ); ?></h3>
			</div>
			<div class="stats-column-container">
				<?php if ( have_rows( 'stats' ) ) : ?>
					<?php $statCounter = 1; ?>
					<?php while ( have_rows( 'stats' ) ) : the_row(); ?>
						<span class="stat-column one-third" data-aos-anchor-placement="top-center" data-aos="fade-up" <?php if ($statCounter == 1){?>data-aos-duration="600"<?php } ?><?php if ($statCounter == 2){?>data-aos-duration="1200"<?php } ?><?php if ($statCounter == 3){?>data-aos-duration="1800"<?php } ?>>
							<span class="stat-container <?php echo get_sub_field( 'circle_size' ); ?>">
								<span class="v-wrap">
									<span class="v-box">
										<h2 class="red-text"><?php echo get_sub_field( 'stat' ); ?></h3>
										<span class="black-text stat-subtitle"><?php echo get_sub_field( 'stat_sub_title' ); ?></span>
									</span>
								</span>
							</span>
						</span>
						<?php $statCounter++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	<?php }  else { ?>
		<div class="container four-stats">
	        <div class="four-column-container">
	        <?php if (get_sub_field( 'title' )) { ?>
	            <span class="absolute-title-container">
	               <h3 class="black-text"><?php echo get_sub_field( 'title' ); ?></h3>
	            </span>
	        <?php } ?>
	            <?php if ( have_rows( 'stats' ) ) : ?>
	                <?php $counter=1;?>
					<?php while ( have_rows( 'stats' ) ) : the_row(); ?>
	                    <?php $fadetime = $counter * 600; ?>
	                    <div class="column one-quarter" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="<?php echo $fadetime; ?>">
	                        <div class="stat-container">
								<span class="v-wrap">
									<span class="v-box">
										<h2 class="red-text"><?php echo get_sub_field( 'stat' ); ?></h3>
										<span class="black-text stat-subtitle"><?php echo get_sub_field( 'stat_sub_title' ); ?></span>
									</span>
								</span>
	                        </div>
	                    </div>
	                    <?php $counter++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
	        </div>
	    </div>
	<?php } ?>
</section>
