<section class="staff-slider-module">
    <div class="container">
        <?php if ( have_rows( 'slide' ) ) : ?>
            <div class="staff-slider desktop">
                <?php $counter=1; ?>
				<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                    <?php if ($counter % 2 == 0) { } else { ?>
                        <div class="staff-slide-outer">
                    <?php } ?>
                        <div class="staff-slide">
                            <div class="staff-slide-inner">
                                <div class="content-column">
                                    <div class="staff-details">
                    					<h5 class="staff-name <?php echo get_sub_field( 'name_colour' ); ?>"><?php echo get_sub_field( 'name' ); ?></h5>
                    					<span class="staff-role text-dark-grey"><?php echo get_sub_field( 'role' ); ?></span>
                    					<span class="staff-details-text text-black"><?php echo get_sub_field( 'description' ); ?></span>
                                    </div>
                                </div>
                                <div class="image-arrow-column column">
                                    <?php $arrow = get_sub_field( 'arrow' ); ?>
                                    <span class="arrow-container">
                    					<?php if ( $arrow ) { ?>
                    						<?php echo wp_get_attachment_image( $arrow['ID'], 'adapt-optimized', false, array(
                    							'alt'     => $arrow['alt'],
                    							'loading' => 'lazy',
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                					<?php $image = get_sub_field( 'image' ); ?>
                                    <span class="staff-image-container">
                    					<?php if ( $image ) { ?>
                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                    							'alt'     => $image['alt'],
                    							'loading' => 'lazy',
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php if ($counter % 2 == 0) { ?>
                        </div>
                    <?php } ?>
                    <?php $counter++; ?>
				<?php endwhile; ?>
                <?php if ($counter % 2 == 0) { ?>
                    </div>
                <?php } else { ?>

                <?php } ?>
            </div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
        <?php if ( have_rows( 'slide' ) ) : ?>
            <div class="staff-slider mobile">
                <?php $counter=1; ?>
				<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                    <div class="staff-slide-outer">
                        <div class="staff-slide">
                            <div class="staff-slide-inner">
                                <div class="content-column">
                                    <div class="staff-details">
                    					<h5 class="staff-name <?php echo get_sub_field( 'name_colour' ); ?>"><?php echo get_sub_field( 'name' ); ?></h5>
                    					<span class="staff-role text-dark-grey"><?php echo get_sub_field( 'role' ); ?></span>
                    					<span class="staff-details-text text-black"><?php echo get_sub_field( 'description' ); ?></span>
                                    </div>
                                </div>
                                <div class="image-arrow-column column">
                                    <?php $arrow = get_sub_field( 'mobile_arrow' ); ?>
                                    <span class="arrow-container">
                    					<?php if ( $arrow ) { ?>
                    						<?php echo wp_get_attachment_image( $arrow['ID'], 'adapt-optimized', false, array(
                    							'alt'     => $arrow['alt'],
                    							'loading' => 'lazy',
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                					<?php $image = get_sub_field( 'image' ); ?>
                                    <span class="staff-image-container">
                    					<?php if ( $image ) { ?>
                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                    							'alt'     => $image['alt'],
                    							'loading' => 'lazy',
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $counter++; ?>
				<?php endwhile; ?>
            </div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
        <?php if ( have_rows( 'button' ) ) : ?>
            <div class="button-container">
				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    <?php if(get_sub_field( 'button_type' ) == 'scrollTo') { ?>
                        <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button red-button">
                            <?php echo get_sub_field( 'button_text' ); ?>
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button red-button">
                            <?php echo get_sub_field( 'button_text' ); ?>
                        </a>
                    <?php } ?>
				<?php endwhile; ?>
            </div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
    </div>
</section>
