<section class="three-column-icon-text <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
		<div class="title-container">
			<h2 class="title"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
        <div class="icon-text-column-container">
			<?php if ( have_rows( 'columns' ) ) : ?>
				<?php while ( have_rows( 'columns' ) ) : the_row(); ?>
                    <div class="column one-third icon-text-column">
                        <div class="icon-container">
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
    					</div>
                        <div class="text-container">
                            <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
