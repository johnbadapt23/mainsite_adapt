<section class="registration-two-column-block">
    <div class="container">
        <?php $count = 0;
            $columns = get_sub_field('column');
            if (is_array($columns)) {
              $count = count($columns);
            }
        ?>
        <?php if ( have_rows( 'column' ) ) : ?>
            <?php $columnCount = 1; ?>
            <div class="column-container">
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column<?php if($count == '3'){?> one-third<?php } else { ?> one-half<?php } ?>">
                        <?php if ($columnCount == 2){ ?>
                            <?php if( get_sub_field('column_title')){ ?>
                                <span class="sub-column-title"><?php echo get_sub_field('column_title'); ?></span>
                            <?php } else { ?>
                                <span class="sub-column-title">In partnership with</span>
                            <?php } ?>	
                        <?php } ?>
                        <div class="logo-container" <?php if( get_sub_field( 'logo_height' )){ ?>style="height: <?php echo get_sub_field( 'logo_height' ); ?>px;"<?php } ?>>
                            <?php $image_logo = get_sub_field( 'image_logo' ); ?>
                            <?php if ( $image_logo ) { ?>
                                <?php echo wp_get_attachment_image( $image_logo['ID'], 'adapt-optimized', false, array(
                                    'alt'     => $image_logo['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </div>
                        <?php if ($columnCount == 1){ ?>
                            <div class="text-container">
                                <?php echo get_sub_field( 'text' ); ?>
                            </div>
                        <?php } else { ?>
                            <div class="text-container text-excerpt">
                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                <span class="read-more">More</span>
                            </div>
                        <?php } ?>
                    </div>
                    <?php $columnCount++; ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
