<section class="repeatable-image-text background-white">
    <div class="container">
        <div class="inner">
            <div class="title-container">
                <?php if ( get_sub_field ('title') ) { ?>
                    <h2 class="bold-red"><?php echo get_sub_field('title'); ?></h2>
                <?php } ?>
                <?php if ( get_sub_field ('sub_title') ) { ?>
                    <span class="text sub-title p-medium text-dark-grey"><?php echo get_sub_field('sub_title'); ?></span>
                <?php } ?>
            </div>
            <?php if ( have_rows( 'card' ) ): ?>
                <div class="cards">
                    <?php while ( have_rows( 'card' ) ) : the_row(); ?>
                        <div class="column-container <?php the_sub_field('image_position'); ?>">
                            <div class="column image-column <?php the_sub_field('image_position'); ?>">
                                <?php $image = get_sub_field('image'); ?>
                                <div class="image-container">
                                    <?php if ( $image ) { ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                            'alt'     => $image['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ( have_rows( 'text_block' ) ): ?>
                                <div class="column text-column <?php the_sub_field('image_position'); ?>">
                                    <?php while ( have_rows( 'text_block' ) ) : the_row(); ?>
                                        <?php if ( get_sub_field ('heading') ) { ?>
                                            <span class="labelXXL bold-grey"><?php echo get_sub_field('heading'); ?></span>
                                        <?php } ?>
                                        <?php if ( get_sub_field ('text') ) { ?>
                                            <span class="text p-xsmall text-dark-grey"><?php echo get_sub_field('text'); ?></span>
                                        <?php } ?>
                                        <?php if ( have_rows( 'link' ) ): ?>
                                            <span class="button-container">
                                                <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                    <a class="text-link red-text external-link red-underline-link" href="<?php echo get_sub_field('link_url'); ?>" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                <?php endwhile; ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>