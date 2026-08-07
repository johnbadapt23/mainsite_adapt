<section class="two-column-thank-you-module background-black">
	<div class="container">
		<span class="title-container">
			<h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
		</span>
		<div class="column-container">
			<div class="column text-list-column">
				<span class="white-text text"><?php echo get_sub_field( 'text' ); ?></span>
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
							<span class="list-item white-text labelXLarge">
								<?php echo get_sub_field( 'list_item' ); ?>
							</span>
						<?php endwhile; ?>
					</span>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
				<?php if ( have_rows( 'button' ) ) : ?>
					<span class="button-container">
						<?php while ( have_rows( 'button' ) ) : the_row(); ?>
							<a class="std-button button-with-arrow red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
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
</section>
