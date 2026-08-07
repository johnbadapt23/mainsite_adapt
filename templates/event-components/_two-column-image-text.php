<section class="two-column-image-text-events <?php echo get_sub_field( 'background_colour' ); ?>" <?php if(get_sub_field('id')){ ?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php if ( get_sub_field( 'title' )) { ?>
			<h2 class="quote-slider-title"><?php echo get_sub_field( 'title' ); ?></h2>
		<?php } ?>
        <?php if ( have_rows( 'module' ) ) : ?>
        	<?php while ( have_rows( 'module' ) ) : the_row(); ?>
                <div class="two-column-image-text-outer <?php echo get_sub_field( 'column_types' ); ?>">
                    <div class="image-column one-half column">
                        <div class="image-container">
                            <div class="bg-container">
                                <?php $image = get_sub_field( 'image' ); ?>
                        		<?php if ( $image ) { ?>
                        			<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                        				'alt'     => $image['alt'],
                        				'loading' => 'lazy',
                        			) ); ?>
                        		<?php } ?>
                            </div>
                            <?php $small_overlay_image = get_sub_field( 'small_overlay_image' ); ?>
                    		<?php if ( $small_overlay_image ) { ?>
                                <span class="absolute-overlay-image">
                                    <span class="image-container">
                                        <span class="bg-container">
                                			<?php echo wp_get_attachment_image( $small_overlay_image['ID'], 'adapt-optimized', false, array(
                                				'alt'     => $small_overlay_image['alt'],
                                				'loading' => 'lazy',
                                			) ); ?>
                                        </span>
                                    </span>
                                </span>
                    		<?php } ?>
                        </div>
                    </div>
                    <div class="text-column one-half column">
                        <?php if(get_sub_field( 'column_types' ) == 'text-only'){ ?>
                            <h4 role="heading" aria-level="3" class="black-text"><?php echo get_sub_field( 'title' ); ?></h4>
                        <?php } else { ?>
                            <h2 class="black-text"><?php echo get_sub_field( 'title' ); ?></h2>
                        <?php } ?>
                        <span class="text black-text"><?php echo get_sub_field( 'text' ); ?></span>
                        <?php if ( have_rows( 'list_item' ) ) : ?>
                            <span class="list-container">
                                <?php while ( have_rows( 'list_item' ) ) : the_row(); ?>
                                    <span class="list-item text-black labelXLarge"><?php echo get_sub_field( 'list_text' ); ?></span>
                                <?php endwhile; ?>
                            </span>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                		<?php if ( have_rows( 'button' ) ) : ?>
                            <span class="button-container">
                    			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    				<a class="std-button red-button button-with-arrow white-arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                    			<?php endwhile; ?>
                            </span>
                		<?php else : ?>
                			<?php // no rows found ?>
                		<?php endif; ?>
                    </div>
                </div>
        	<?php endwhile; ?>
        <?php else : ?>
        	<?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
