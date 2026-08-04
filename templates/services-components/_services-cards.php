<section class="services-cards-module background-black">
	<div class="container">
		<div class="card-container">
            <div class="title-card card">
    			<h2 class="text-white"><?php echo get_sub_field( 'title' ); ?></h2>
    		</div>
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="card content-card">
						<span class="icon-container">
							<span class="image-container">
								<span class="bg-container">
									<?php $icon = get_sub_field( 'icon' ); ?>
									<?php if ( $icon ) { ?>
										<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
											'alt'     => $icon['alt'],
											'loading' => false,
										) ); ?>
									<?php } ?>
								</span>
							</span>
						</span>
						<span class="content-container">
							<span class="content-container-inner">
								<h4 class="card-title"><?php echo get_sub_field( 'card_title' ); ?></h4>
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
