<?php $border_top = get_sub_field('add_border_top'); ?>

<?php if ( have_rows( 'global_gtm_full_suite_slider', 'option' ) ) : ?>
	<?php while ( have_rows( 'global_gtm_full_suite_slider', 'option' ) ) : the_row(); ?>
        <section class="full-suite-slider-module background-true-black<?php if ( $border_top == 'yes' ) { ?> add-border-top<?php } ?>">
            <div class="container">
                <div class="title-container">
                    <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="p-small text dark-grey-text"><?php echo get_sub_field( 'text' ); ?></p>
                </div>
                <div class="slider-outer">
                    <?php if ( have_rows( 'slides' ) ) : ?>
                        <span class="slide-link-container">
                            <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                <button type="button" class="slide-link"><?php echo get_sub_field( 'slide_link_title' ); ?></button>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'slides' ) ) : ?>
                        <div class="full-suite-slider">
                            <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                <div class="full-suite-slide">
                                    <div class="column one-half text-column">
                                        <h4 role="heading" aria-level="3" class="white-text labelXXL"><?php echo get_sub_field( 'title' ); ?></h4>
                                        <span class="image-container hide-desktop">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                        'alt'     => $image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                        <p class="p-xsmall"><?php echo get_sub_field( 'text' ); ?></p>
                                        <?php if (get_sub_field( 'link' )) { ?>
                                            <a class="red-text red-underline-link red-arrow external-link text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self" aria-label="Learn more about <?php echo esc_attr( wp_strip_all_tags( get_sub_field( 'title' ) ) ); ?>">Learn more</a>
                                        <?php } ?>
                                    </div>
                                    <div class="column one-half image-column hide-mobile">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                        'alt'     => $image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                    <?php endif; ?>
                    <span class="progress-bar-outer"><span class="progress-bar-form-suite"></span></span>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
<?php endif; ?>