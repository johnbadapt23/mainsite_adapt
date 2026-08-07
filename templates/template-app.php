<?php
/**
 * Template Name: App Page Template
 */

get_header();

?>

<main class="page flexible app-page" id="main">
<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'text_and_icon_block' ) : ?>
			<section class="app-introduction">
				<div class="container">
					<div class="app-inner-container">
						<?php $top_icon = get_sub_field( 'top_icon' ); ?>
						<?php if ( $top_icon ) { ?>
							<span class="top-icon-container"><?php echo wp_get_attachment_image( $top_icon['ID'], 'adapt-optimized', false, array(
								'width'   => '250',
								'alt'     => $top_icon['alt'],
								'loading' => 'lazy',
							) ); ?></span>
						<?php } ?>
						<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
						<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
						<?php if ( have_rows( 'bottom_icons' ) ) : ?>
							<span class="bottom-icon-container">
								<?php while ( have_rows( 'bottom_icons' ) ) : the_row(); ?>
									<?php $icon = get_sub_field( 'icon' ); ?>
									<?php if (get_sub_field( 'icon_link' )){ ?>
										<a href="<?php echo get_sub_field( 'icon_link' ); ?>" target="_blank" rel="noopener noreferrer">
											<?php if ( $icon ) { ?>
												<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
													'width'   => '130',
													'alt'     => $icon['alt'],
													'loading' => 'lazy',
												) ); ?>
											<?php } ?>
										</a>
									<?php } else { ?>
										<?php if ( $icon ) { ?>
											<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
												'width'   => '130',
												'alt'     => $icon['alt'],
												'loading' => 'lazy',
											) ); ?>
										<?php } ?>
									<?php } ?>
								<?php endwhile; ?>
							</span>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php elseif ( get_row_layout() == 'app_block' ) : ?>
			<section class="app-block">
				<div class="container">
					<div class="main-image-column-container">
						<?php if ( have_rows( 'text' ) ) : ?>
							<?php $textcounter = 1; ?>
							<?php while ( have_rows( 'text' ) ) : the_row(); ?>
								<?php if($textcounter == 5) { ?>
									<span class="bottom-container">
								<?php } ?>
								<span class="text-container column one-half">
									<?php echo get_sub_field( 'text' ); ?>
								</span>
								<?php if($textcounter == 6) { ?>
									</span>
								<?php } ?>
								<?php $textcounter++; ?>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>

						<?php $main_image = get_sub_field( 'main_image' ); ?>
						<?php if ( $main_image ) { ?>
							<span class="main-image-container">
								<?php echo wp_get_attachment_image( $main_image['ID'], 'adapt-optimized', false, array(
									'alt'     => $main_image['alt'],
									'loading' => 'lazy',
								) ); ?>
							</span>
						<?php } ?>
					</div>
				</div>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>

</main>
<?php get_footer(); ?>
