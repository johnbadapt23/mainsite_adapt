<section class="parallax-image-module">
    <div class="parallax-image-container">
        <?php $desktop_image = get_sub_field( 'desktop_image' ); ?>
        <?php if ( $desktop_image ) { ?>
            <img class="desktop-image mobile-hide" src="<?php echo $desktop_image['url']; ?>" alt="<?php echo $desktop_image['alt']; ?>" />
        <?php } ?>
        <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
        <?php if ( $mobile_image ) { ?>
            <img loading="lazy" class="mobile-image desktop-hide" src="<?php echo $mobile_image['url']; ?>" alt="<?php echo $mobile_image['alt']; ?>" />
        <?php } ?>
    </div>
</section>
