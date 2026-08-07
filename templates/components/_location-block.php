<section class="location-block">
    <div class="container">
        <div class="column one-half text-column">
            <span class="location-container">
    			<h2><?php echo get_sub_field( 'title' ); ?></h2>
    			<span class="location-title"><?php echo get_sub_field( 'location_title' ); ?></span>
    			<p class="address"><?php echo get_sub_field( 'address' ); ?></p>
    			<a class="web-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
    			<a class="phone-number" href="tel:<?php echo get_sub_field( 'phone_number' ); ?>"><?php echo get_sub_field( 'phone_number' ); ?></a>
    			<a class="directions-link" href="<?php echo get_sub_field( 'directions_link' ); ?>" target="_blank" rel="noopener noreferrer">Get Directions</a>
            </span>
        </div>
        <div class="column one-half image-column">
            <?php $portrait_image = get_sub_field( 'portrait_image' ); ?>
            <?php if ( $portrait_image ) { ?>
                <span class="portrait-image-container">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php echo wp_get_attachment_image( $portrait_image['ID'], 'full', false, array(
                                'alt'     => $portrait_image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        </span>
                    </span>
                </span>
            <?php } ?>
            <?php $square_image = get_sub_field( 'square_image' ); ?>
            <?php if ( $square_image ) { ?>
                <span class="square-image-container">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php echo wp_get_attachment_image( $square_image['ID'], 'full', false, array(
                                'alt'     => $square_image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        </span>
                    </span>
                </span>
            <?php } ?>
            <span class="absolute-border"></span>
        </div>
    </div>
</section>
