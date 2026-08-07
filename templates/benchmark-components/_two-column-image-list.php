<section class="two-column-image-list">
    <div class="container">
        <div class="title mobile-show">
            <h2 class="headerLarge"><?php the_sub_field( 'title' ); ?></h2>
        </div>
        <div class="column-container <?php the_sub_field( 'image_position' ); ?>">
            <div class="column image-column <?php the_sub_field( 'image_position' ); ?>">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <div class="image-container">
                        <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                            'alt'     => $image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="column text-column <?php the_sub_field( 'image_position' ); ?>">
                <div class="title desktop-show">
                    <h2 class="headerLarge"><?php the_sub_field( 'title' ); ?></h2>
                </div>
                <?php if ( have_rows( 'list_items' ) ) : ?>
                    <div class="list">
                        <?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
                            <span class="item">
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
                                    <h3><?php the_sub_field( 'heading' ); ?></h3>
                                    <p class="text-secondary-grey"><?php the_sub_field( 'text' ); ?></p>
                                </span>
                            </span>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>