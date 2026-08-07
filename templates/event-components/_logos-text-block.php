<?php $headerType = get_field( 'header_type' ); ?>
<section class="events-logos-text-block <?php if($headerType == 'dark-header'){ ?> background-black<?php } ?>">
    <div class="events-logos-text-block-outer background-white">
        <div class="container">
            <div class="logo-container">
                <span class="logo-text"><?php echo get_sub_field( 'logo_title' ); ?></span>
                <?php if ( have_rows( 'logos' ) ) : ?>
                	<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                		<?php $logo = get_sub_field( 'logo' ); ?>
                		<?php if ( $logo ) { ?>
                            <span class="logo-column">
                                <span class="image-container">
                                    <span class="bg-container">
                            			<?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
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
            <div class="text-content-container">
                <div class="text-inner">
                    <span class="icon-container">
                        <?php $icon = get_sub_field( 'icon' ); ?>
            			<?php if ( $icon ) { ?>
            				<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
            					'alt'     => $icon['alt'],
            					'loading' => 'lazy',
            				) ); ?>
            			<?php } ?>
                    </span>
                    <span class="text">
                        <h3 class="black-text"><?php echo get_sub_field( 'text' ); ?></h3>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
