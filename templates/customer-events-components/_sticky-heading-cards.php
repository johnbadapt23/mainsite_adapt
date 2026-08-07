<section class="sticky-heading-cards background-white">
    <div class="sticky-heading-scrolling-container">
        <div class="sticky-heading-container">
            <div class="container">
                <div class="inner-text-container">
                    <h2 class="bold-red text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
                </div>
            </div>
        </div>
        <?php if ( have_rows( 'cards' ) ) : ?>
            <div class="cards-container">
                <div class="container">
                    <?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                        <div class="scrolling-card">
                            <h4 role="heading" aria-level="3" class="card-title"><?php the_sub_field( 'card_title' ); ?></h4>
                            <div class="card-image-container">
                                <div class="image-container contained">
                                    <div class="bg-container contained">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                'class'   => 'desktop-image',
                                                'alt'     => $image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                        <?php $image = get_sub_field( 'mobile_image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                'class'   => 'mobile-image',
                                                'alt'     => $image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div> 
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>               
    </div>
</section>


