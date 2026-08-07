<section class="position-icon-slider">
    <div class="container">
        <span class="title-text-container">
            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            <p class="text-black"><?php echo get_sub_field( 'text' ); ?></p>
        </span>
    </div>
    <?php if ( have_rows( 'slides' ) ) : ?>
        <div class="icon-slider-container">
            <span class="leftSlideCover"></span>
            <div class="container">
                <div class="icon-slider">
                    <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                        <div class="icon-slide background-light-grey">
                            <div class="icon-slide-inner">
                                <?php $slide_icon = get_sub_field( 'slide_icon' ); ?>
                                <span class="slide-icon-container">
                                    <span class="image-container">
                                        <span class="bg-container contained-image">
                                            <?php if ( $slide_icon ) { ?>
                                                <?php echo wp_get_attachment_image( $slide_icon['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $slide_icon['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </span>
                                </span>
                                <h4 role="heading" aria-level="3" class="slide-title text-black"><?php echo get_sub_field( 'slide_title' ); ?></h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <span class="progress"></span>
            </div>
        </div>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?>
</section>
