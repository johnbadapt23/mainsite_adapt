<section class="resources-cta-block"<?php if (get_sub_field( 'id' )) { ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="cta-content">
            <div class="column text-column one-half">
        		<span class="cta-title"><?php echo get_sub_field( 'title' ); ?></span>
        		<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
        		<?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container">
            			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <a class="std-button arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
            			<?php endwhile; ?>
                    </span>
        		<?php else : ?>
        			<?php // no rows found ?>
        		<?php endif; ?>
            </div>
            <div class="column image-column one-half">
                <div class="bottom-image-container full-width-image">
            		<?php $image = get_sub_field( 'image' ); ?>
                    <div class="main-image-container">
                		<?php if ( $image ) { ?>
                			<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                				'alt'     => $image['alt'],
                				'loading' => 'lazy',
                			) ); ?>
                		<?php } ?>
                    </div>
                    <span class="overlay-image-container">
                        <?php $overlay_image = get_sub_field( 'overlay_image' ); ?>
            			<?php if ( $overlay_image ) { ?>
            				<?php echo wp_get_attachment_image( $overlay_image['ID'], 'adapt-optimized', false, array(
            					'alt'     => $overlay_image['alt'],
            					'loading' => 'lazy',
            				) ); ?>
            			<?php } ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
