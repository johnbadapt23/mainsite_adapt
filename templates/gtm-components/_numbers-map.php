<section class="map-with-numbers background-black">
    <div class="container">
        <span class="title-container">
            <span class="labelXXL primary-white"><?php echo get_sub_field( 'text' ); ?></span>
        </span>
        <span class="map-outer">
            <span class="background-container">
                <?php $background_image = get_sub_field( 'background_image' ); ?>
                <?php if ( $background_image ) { ?>
                    <?php echo wp_get_attachment_image( $background_image['ID'], 'full', false, array(
                        'alt'     => $background_image['alt'],
                        'loading' => false,
                    ) ); ?>
                <?php } ?>
            </span>
            <span class="column-container">
                <?php if ( have_rows( 'numbers' ) ) : ?>
                    <?php while ( have_rows( 'numbers' ) ) : the_row(); ?>
                        <span class="column numbers-column one-third">
                            <span class="numbers-text text-red" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="600"><?php echo get_sub_field( 'number' ); ?></span>
                            <span class="labelMedium medium-grey" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="600"><?php echo get_sub_field( 'text' ); ?></span>
                        </span>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </span>
        </span>
    </div>
</section>
