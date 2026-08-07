<section class="cards-module gtm-cards-module background-black static-cards" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
	<div class="container">
		<div class="top-content">
			<h2 class="text-white bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
			<span class="text-white"><?php echo get_sub_field( 'sub_title' ); ?></span>
		</div>
		<div class="card-container">
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="static-card">
						<span class="icon-container">
							<span class="image-container">
								<span class="bg-container first-bg">
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
						<span class="content-container">
							<span class="content-container-inner">
								<span class="card-title header-Xsmall"><?php echo get_sub_field( 'card_title' ); ?></span>
								<span class="card-text"><?php echo get_sub_field( 'card_text' ); ?></span>
							</span>
						</span>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
	</div>
</section>
