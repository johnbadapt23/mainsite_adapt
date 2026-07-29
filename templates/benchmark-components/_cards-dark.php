<section class="cards-module gtm-cards-module background-black benchmark-card-module" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
	<div class="container">
		<div class="top-content">
			<h2 class="text-white bold-grey"><?php echo get_sub_field( 'title' ); ?></h2>
			<?php if(get_sub_field( 'learn_more_link' )){ ?>
				<span class="link-container">
					<a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'learn_more_link' ); ?>" target="_self">Learn more</a>				
				</span>	 
			<?php } ?>					
		</div>
		<div class="background-card-container">
			<?php $background_image = get_sub_field( 'background_image' ); ?>
			<div class="background-container" style="background-image:url(<?php echo $background_image['url']; ?>)">
			</div>
			<div class="card-container desktop">
				<?php if ( have_rows( 'cards' ) ) : ?>
					<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
						<div class="card">
							<span class="icon-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<?php if ( $icon ) { ?>
											<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
										<?php } ?>
									</span>
								</span>
							</span>
							<span class="content-container">
								<span class="content-container-inner">
									<span class="card-title labelXL"><?php echo get_sub_field( 'card_title' ); ?></span>
									<span class="card-text">
										<span class="inner-text"><?php echo get_sub_field( 'card_text' ); ?></span>
										<?php if ( get_sub_field( 'current_page' ) == 1 ) { ?> 
											<span class="current-page-tag labelXsmall">Currently viewing</span>
										<?php } else { ?>
											<?php if (get_sub_field( 'card_link' )){ ?> 
												<span class="link-container">
													<a class="red-text text-link medium-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'card_link' ); ?>" target="_self">Learn more</a>				
												</span>
											<?php } ?>		 										
										<?php } ?>																			
									</span>
								</span>
							</span>
						</div>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
			<div class="card-slider card-container mobile">
				<?php if ( have_rows( 'cards' ) ) : ?>
					<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
						<div class="card">
							<span class="icon-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<?php if ( $icon ) { ?>
											<img loading="lazy" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
										<?php } ?>
									</span>
								</span>
							</span>
							<span class="content-container">
								<span class="content-container-inner">
									<span class="card-title labelXL"><?php echo get_sub_field( 'card_title' ); ?></span>
									<span class="card-text">
										<span class="inner-text"><?php echo get_sub_field( 'card_text' ); ?></span>
										<?php if ( get_sub_field( 'current_page' ) == 1 ) { ?> 
											<span class="current-page-tag labelXsmall">Currently viewing</span>
										<?php } else { ?>
											<?php if (get_sub_field( 'card_link' )){ ?> 
												<span class="link-container">
													<a class="red-text text-link medium-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'card_link' ); ?>" target="_self">Learn more</a>				
												</span>
											<?php } ?>		 										
										<?php } ?>																			
									</span>
								</span>
							</span>
						</div>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
