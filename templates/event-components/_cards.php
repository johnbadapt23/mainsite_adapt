<section class="cards-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
	<div class="container">
		<div class="top-content">
			<h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
			<span class="text-black"><?php echo get_sub_field( 'sub_title' ); ?></span>
		</div>
		<div class="card-container">
			<?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<div class="card">
						<span class="icon-container">
							<span class="image-container">
								<span class="bg-container first-bg">
									<?php $icon = get_sub_field( 'icon' ); ?>
									<?php if ( $icon ) { ?>
										<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
									<?php } ?>
								</span>
								<span class="bg-container hover-bg">
									<?php $hover_icon = get_sub_field( 'hover_icon' ); ?>
									<?php if ( $hover_icon ) { ?>
										<img loading="lazy" src="<?php echo $hover_icon['url']; ?>" alt="<?php echo $hover_icon['alt']; ?>" />
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
