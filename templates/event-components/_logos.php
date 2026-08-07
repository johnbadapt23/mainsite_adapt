<section class="events-logos-block background-black">
    <div class="container">
        <div class="logo-container">
            <?php if ( have_rows( 'logos' ) ) : ?>
            	<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
            		<?php $logo = get_sub_field( 'logo' ); ?>
            		<?php if ( $logo ) { ?>
                        <span class="logo-column" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                            <span class="image-container">
                                <span class="bg-container">
                        			<?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                        				'alt'     => $logo['alt'],
                        				'loading' => 'lazy',
                        			) ); ?>
                                </span>
                            </span>
                        </span>
            		<?php } ?>
            	<?php endwhile; ?>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
