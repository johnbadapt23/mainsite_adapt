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
                                <a class="slide-link" href="#"><?php echo get_sub_field( 'slide_link_title' ); ?></a>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'slides' ) ) : ?>
                        <div class="full-suite-slider">
                            <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                <div class="full-suite-slide">
                                    <div class="column one-half text-column">
                                        <h4 class="white-text labelXXL"><?php echo get_sub_field( 'title' ); ?></h4>
                                        <span class="image-container hide-desktop">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                <?php } ?>
                                            </span>
                                        </span>
                                        <p class="p-xsmall"><?php echo get_sub_field( 'text' ); ?></p>
                                        <?php if (get_sub_field( 'link' )) { ?>
                                            <a class="red-text red-underline-link red-arrow external-link text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Learn more</a>
                                        <?php } ?>
                                    </div>
                                    <div class="column one-half image-column hide-mobile">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
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