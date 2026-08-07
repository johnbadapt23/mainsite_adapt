<section class="logos-block">
    <div class="logo-block-outer">
        <div class="container">
            <div class="top-block">
                <div class="column one-half">
                    <span class="title">
                        <?php echo get_sub_field( 'title' ); ?>
                    </span>
                </div>
                <div class="column one-half">
                    <span class="text">
                        <?php echo get_sub_field( 'text' ); ?>
                    </span>
                </div>
            </div>
            <div class="logos-block">
                <span class="logos-slider">
                    <?php if ( have_rows( 'logos' ) ) : ?>
        				<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
        					<?php $logo = get_sub_field( 'logo' ); ?>
                            <?php if ( get_sub_field( 'link' )) { ?>
                                <a href="<?php echo get_sub_field( 'link' ); ?>" target="_blank" rel="noopener noreferrer">
                            <?php } ?>
                                <span class="slide">
                					<?php if ( $logo ) { ?>
                						<?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                							'class'   => 'logo',
                							'alt'     => $logo['alt'],
                							'loading' => 'lazy',
                						) ); ?>
                					<?php } ?>
                                </span>
                            <?php if ( get_sub_field( 'link' )) { ?>
                                </a>
                            <?php } ?>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </span>
            </div>
        </div>
    </div>
</section>
