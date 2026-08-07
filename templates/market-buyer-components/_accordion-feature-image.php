<section class="accordion-with-image">
    <div class="container">
        <div class="title-container">
            <h2 class="text-black"><?php the_sub_field( 'title' ); ?></h2>
        </div>

        <div class="column-container">
            <div class="column one-half accordion-block">
                <?php if ( have_rows( 'accordion_group' ) ) : ?>
                    <span class="accordion-wrapper">
                        <?php while ( have_rows( 'accordion_group' ) ) : the_row(); ?>
                            <?php if ( have_rows( 'item' ) ) : ?>
                                <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                    <div class="faq-item">
                                        <?php $red_icon = get_sub_field( 'red_icon' ); ?>
                                        <div class="question">
                                            <span class="icon-container">
                                                <?php if ( $red_icon ) { ?>
                                                    <?php echo wp_get_attachment_image( $red_icon['ID'], 'full', false, array(
                                                        'class'   => 'red-icon',
                                                        'alt'     => $red_icon['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <div class="accordion-title labelXLarge text-black"><?php echo get_sub_field( 'title' ); ?></div>
                                        </div>
                                        <div class="answer accordion-content p-medium text-black">
                                            <div class="inner">
                                                <?php echo get_sub_field( 'text' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="column one-half image-block">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <span class="image-container">
                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                            'alt'     => $image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    </span>
                <?php } ?>
            </div>
        </div>
    </div>
</section>