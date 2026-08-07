<section class="partners-cards-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
	<div class="container">
		<div class="top-content">
			<h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
		</div>
		<div class="card-container">
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="card background-grey">
						<span class="icon-container">
							<span class="image-container">
								<span class="bg-container first-bg">
									<?php $icon = get_sub_field( 'icon' ); ?>
									<?php if ( $icon ) { ?>
										<?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
											'alt'     => $icon['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</span>
								<span class="bg-container hover-bg">
									<?php $hover_icon = get_sub_field( 'hover_icon' ); ?>
									<?php if ( $hover_icon ) { ?>
										<?php echo wp_get_attachment_image( $hover_icon['ID'], 'adapt-optimized', false, array(
											'alt'     => $hover_icon['alt'],
											'loading' => 'lazy',
										) ); ?>
									<?php } ?>
								</span>
							</span>
						</span>
						<span class="content-container">
							<span class="content-container-inner">
								<h4 role="heading" aria-level="3" class="card-title"><?php echo get_sub_field( 'card_title' ); ?></h4>
								<span class="card-text"><?php echo get_sub_field( 'card_text' ); ?></span>
							</span>
						</span>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
		<?php if ( have_rows( 'button' ) ) : ?>
			<div class="button-container">
				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
				 <?php if(get_sub_field( 'link_type' ) == 'scrollto') { ?>
					<a class="scroll-to-button std-button  red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
				<?php } else { ?>
					<a class="link std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
				<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
	</div>
</section>
