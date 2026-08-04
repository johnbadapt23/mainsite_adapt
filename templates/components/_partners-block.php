<section class="partners-block">
    <div class="container">
        <h2><?php echo get_sub_field( 'title' ); ?></h2>
		<?php if ( have_rows( 'level' ) ) : ?>
			<?php while ( have_rows( 'level' ) ) : the_row(); ?>
                <div class="level-container">
    				<span class="level-title"><?php echo get_sub_field( 'level_title' ); ?></span>
    				<?php if ( have_rows( 'partner' ) ) : ?>
                        <span class="partners-logo-container">
        					<?php while ( have_rows( 'partner' ) ) : the_row(); ?>
                                <?php if ( get_sub_field( 'partner_link' )) { ?>
                                    <a href="<?php echo get_sub_field( 'partner_link' ); ?>" target="_blank">
                                <?php } ?>
                                    <span class="partners-logo">
                                        <?php $logo = get_sub_field( 'partner_logo' ); ?>
                    					<?php if ( $logo ) { ?>
                    						<?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                    							'class'   => 'logo',
                    							'alt'     => $logo['alt'],
                    							'loading' => false,
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                                <?php if ( get_sub_field( 'partner_link' )) { ?>
                                    </a>
                                <?php } ?>
        					<?php endwhile; ?>
                        </span>
    				<?php else : ?>
    					<?php // no rows found ?>
    				<?php endif; ?>
                </div>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
        <?php if( get_sub_field( 'register_button_text' )) { ?>
            <span class="button-container">
                <a class="std-button red-outline register-button" href="#register"><?php echo get_sub_field( 'register_button_text' ); ?></a>
            </span>
        <?php } ?>
    </div>
</section>
