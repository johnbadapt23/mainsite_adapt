<section class="vendors background-black">
    <div class="container">
        <?php if ( get_sub_field( 'title' )) { ?>
			<h2 class="white-text vendor-title"><?php echo get_sub_field( 'title' ); ?></h2>
		<?php } ?>
        <div class="vendor-container">
            <?php if ( have_rows( 'logos' ) ) : ?>
				<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                    <span class="logo">
                        <span class="image-container">
                            <span class="bg-container contained-image">
            					<?php $logo = get_sub_field( 'logo' ); ?>
            					<?php if ( $logo ) { ?>
            						<?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
            							'alt'     => $logo['alt'],
            							'loading' => 'lazy',
            						) ); ?>
            					<?php } ?>
                            </span>
                        </span>
                    </span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
