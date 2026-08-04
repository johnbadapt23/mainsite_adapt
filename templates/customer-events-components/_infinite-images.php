<section class="infinite-images">
    <div class="infinite-top">
        <?php if ( have_rows( 'top_row' ) ) : ?>
            <?php while ( have_rows( 'top_row' ) ) : the_row(); ?>
                <span class="infinite-image">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => false,
                                ) ); ?>
                            <?php } ?>
                        </span>
                    </span>
                </span>
            <?php endwhile; ?>
        <?php else : ?>
        <?php // no rows found ?>
        <?php endif; ?>
         <?php if ( have_rows( 'top_row' ) ) : ?>
            <?php while ( have_rows( 'top_row' ) ) : the_row(); ?>
                <span class="infinite-image">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                    </span>
                </span>
            <?php endwhile; ?>
        <?php else : ?>
        <?php // no rows found ?>
        <?php endif; ?>
    </div>
    <div class="infinite-bottom">
        <?php if ( have_rows( 'bottom_row' ) ) : ?>
            <?php while ( have_rows( 'bottom_row' ) ) : the_row(); ?>
                <span class="infinite-image">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                    </span>
                </span>
            <?php endwhile; ?>
        <?php else : ?>
        <?php // no rows found ?>
        <?php endif; ?>
        <?php if ( have_rows( 'bottom_row' ) ) : ?>
            <?php while ( have_rows( 'bottom_row' ) ) : the_row(); ?>
                <span class="infinite-image">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                    </span>
                </span>
            <?php endwhile; ?>
        <?php else : ?>
        <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>