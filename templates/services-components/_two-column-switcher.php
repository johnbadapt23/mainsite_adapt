<section class="two-column-switch-module background-white">
	<div class="container">
		<div class="title-switch-container">
			<span class="title-container">
				<h2 class="black-text"><?php echo get_sub_field( 'title' ); ?></h2>
			</span>
			<span class="switcher-container">
				<?php if ( have_rows( 'modules' ) ) : ?>
					<?php $switchCounter=1; ?>
					<?php while ( have_rows( 'modules' ) ) : the_row(); ?>
						<button type="button" class="module-switcher<?php if($switchCounter == 1){?> active<?php } ?>">
							<?php if (get_sub_field('switch_label')){ ?>
								<?php echo get_sub_field( 'switch_label' ); ?>
							<?php } else { ?>
								<?php echo get_sub_field( 'title' ); ?>
							<?php }?>

						</button>
					<?php $switchCounter++; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</span>
		</div>
		<div class="module-container">
			<?php if ( have_rows( 'modules' ) ) : ?>
				<?php $moduleCounter=1; ?>
				<?php while ( have_rows( 'modules' ) ) : the_row(); ?>
					<div class="switch-module <?php if($moduleCounter == 1){?> active<?php } ?> background-light-grey">
						<div class="column-container">
							<div class="column text-list-column">
								<h2 class="black-text"><?php echo get_sub_field( 'title' ); ?></h2>
								<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
								<div class="mobile-image">
									<?php $image = get_sub_field( 'image' ); ?>
									<?php if ( $image ) { ?>
										<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
											'alt'     => $image['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</div>
								<?php if ( have_rows( 'list_items' ) ) : ?>
									<span class="list-container">
				                        <?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
				                            <span class="list-item text-black labelXLarge">
				                                <?php echo get_sub_field( 'list_text' ); ?>
				                            </span>
				                        <?php endwhile; ?>
									</span>
			                    <?php else : ?>
			                        <?php // no rows found ?>
			                    <?php endif; ?>
								<?php if ( have_rows( 'button' ) ) : ?>
									<span class="button-container">
										<?php while ( have_rows( 'button' ) ) : the_row(); ?>
											<a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
										<?php endwhile; ?>
									</span>
								<?php else : ?>
									<?php // no rows found ?>
								<?php endif; ?>
							</div>
							<div class="column image-column">
								<?php $image = get_sub_field( 'image' ); ?>
								<?php if ( $image ) { ?>
									<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
										'alt'     => $image['alt'],
										'loading' => 'lazy',
									) ); ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php $moduleCounter++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</section>
