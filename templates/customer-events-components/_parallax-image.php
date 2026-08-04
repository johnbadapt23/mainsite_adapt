<section class="parallax-image-module">
    <div class="parallax-image-container">
        <?php $desktop_image = get_sub_field( 'desktop_image' ); ?>
        <?php if ( $desktop_image ) { ?>
            <?php echo wp_get_attachment_image( $desktop_image['ID'], 'full', false, array(
                'class'   => 'desktop-image mobile-hide',
                'alt'     => $desktop_image['alt'],
                // Matches the previous plain <img> exactly (no loading attribute --
                // this is the above-the-fold desktop hero image). Passing false
                // stops WP core's own auto-lazy-loading heuristic (which counts
                // images globally across the page) from adding one on its own.
                'loading' => false,
            ) ); ?>
        <?php } ?>
        <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
        <?php if ( $mobile_image ) { ?>
            <?php echo wp_get_attachment_image( $mobile_image['ID'], 'full', false, array(
                'class'   => 'mobile-image desktop-hide',
                'alt'     => $mobile_image['alt'],
                'loading' => 'lazy',
            ) ); ?>
        <?php } ?>
    </div>
</section>
