<section class="animated-text animated-text-logos <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-true-black<?php }?>" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
    <div class="container">
        <div class="inner">
            <div class="animated-text-container">
                <span id="animatedText"><?php echo get_sub_field( 'text' ); ?></span>
            </div>
            <div class="logo-container-inner logo-ticker-tape">                
                <div class="band-container-backwards">
                    <span class="moving-text play">
                        <?php if ( have_rows( 'logos' ) ) : ?>
                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                'alt'     => $logo['alt'],
                                                'loading' => false,
                                            ) ); ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>

                        <?php if ( have_rows( 'logos' ) ) : ?>
                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                'alt'     => $logo['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>

                        <?php if ( have_rows( 'logos' ) ) : ?>
                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                'alt'     => $logo['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                </div>
                <span class="pre-mask"></span>
                <span class="post-mask"></span>
            </div>                       
        </div>
    </div>
</section>