
<section class="video-quote-block background-black">
    <div class="container">
        <div class="video-quote-container">
            <div class="column one-half text-column">
                <h4 class="quote text-white"><?php echo get_sub_field( 'quote' ); ?></h4>
                <span class="quoter text-white"><?php echo get_sub_field( 'name_and_title' ); ?></span>
                <a class="text-link video-popup popup-vimeo video-link red-text red-underline-link" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>"><?php echo get_sub_field( 'play_text' ); ?></a>
            </div>
            <div class="column one-half video-column">
                <div class="video-container image-container">
                    <span class="frame"></span>
                    <div class="bg-container">
                        <?php $poster_image = get_sub_field( 'poster_image' ); ?>
                        <?php if ( $poster_image ) { ?>
                            <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array(
                                'alt'     => $poster_image['alt'],
                                'loading' => false,
                            ) ); ?>
                        <?php } ?>
                        <?php if( get_sub_field( 'vimeo_code' )) { ?>
                            <span class="opacity-overlay"></span>
                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                        <?php } ?>
                    </div>
                </div>                            
            </div>
        </div>
    </div>
</section>