<section class="market-two-column <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <span class="title-container">
            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>            
        </span>
        <div class="two-column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-half image-text-column">
                        <div class="inner-column image-column one-half">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $image = get_sub_field( 'image' ); ?>
                                    <?php if ( $image ) { ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                            'alt'     => $image['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                            </span>
                        </div>
                        <div class="inner-column one-half text-column">
                            <span class="title text-black"><?php echo get_sub_field( 'title' ); ?></span>
                            <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>