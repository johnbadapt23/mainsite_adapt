<section class="download-module background-white">
    <div class="container">
        <div class="column one-half image-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <div class="image-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column one-half text-column">
            <span class="title-container"><?php echo get_sub_field( 'title' ); ?></span>
            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
            <span class="button-container">
                <a class="site-button download-link-button" href="<?php echo get_sub_field( 'download_link' ); ?>" target="_blank" rel="noopener noreferrer">Download</a>
            </span>
        </div>
    </div>
</section>
