<section class="slide-stack">
    <div class="container">
        <div class="slide-wrapper background-secondary-light-grey">
            <?php 
            $counter = 1;
            if ( have_rows( 'slide' ) ) : ?>
                <div class="slider-track">
                    <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                        <div class="slide" data-slide-number="<?php echo $counter; ?>">
                            <?php $slide_image = get_sub_field( 'slide_image' ); ?>
                            <?php if ( $slide_image ) { ?>
                                <span class="image-container">
                                    <?php echo wp_get_attachment_image( $slide_image['ID'], 'adapt-optimized', false, array(
                                        'alt'     => $slide_image['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                </span>
                            <?php } ?>
                        </div>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <?php 
            $counter = 1;
            if ( have_rows( 'slide' ) ) : ?>
                <div class="slider-content">
                    <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                        <div class="slide" data-slide-number="<?php echo $counter; ?>">
                            <span class="title-block">
                                <span class="title">
                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                    <?php if ( $icon ) { ?>
                                        <span class="icon">
                                            <?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
                                                'alt'     => $icon['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        </span>
                                    <?php } ?>
                                    <span class="text">
                                        <h3 class="labelMedium"><?php the_sub_field( 'title' ); ?></h3>
                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                            <a href="<?php the_sub_field( 'link_url' ); ?>"
                                            class="red-text text-link red-underline-link external-link"
                                            target="<?php the_sub_field( 'link_target' ); ?>">
                                            <?php the_sub_field( 'link_text' ); ?>
                                            </a>
                                        <?php } ?>
                                    </span>
                                </span>
                            </span>
                        </div>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            <div class="pagination">
                <a class="slide-prev"></a>
                <a class="slide-next"></a>
            </div>
        </div>
    </div>
</section>