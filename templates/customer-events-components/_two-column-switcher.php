<section class="two-column two-column-image-text-switcher evr-two-column-image-text-switcher">
    <div class="container">
        <div class="title-container">
            <div class="inner-text-container">
                <h2 class="bold-red text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
        </div>
        <div class="column-container">
            <div class="column one-half image-column image-right">
                <div class="image-outer-container">
                    <?php if ( have_rows( 'switch_content' ) ) : ?>
                        <?php $imageCounter=1; ?>
                        <?php while ( have_rows( 'switch_content' ) ) : the_row(); ?>
                            <span class="image-container evr-image-container <?php if($imageCounter==1){ ?> active<?php } ?>" data-counter="<?php echo $imageCounter; ?>">
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
                            <?php $imageCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="column one-half text-column evr-switch-column image-right">
                <?php if ( have_rows( 'switch_content' ) ) : ?>
                    <?php $switchCounter=1; ?>
                    <?php while ( have_rows( 'switch_content' ) ) : the_row(); ?>
                        <span class="switch-container <?php if($switchCounter==1){ ?> active<?php } ?>">
                            <h4 role="heading" aria-level="3" class="switch-title text-black"><?php echo get_sub_field( 'title' ); ?></h4>
                            <span class="text-container bold-red text-black"><?php echo get_sub_field( 'text' ); ?></span>					
                        </span>
                        <?php $switchCounter++; ?>
                    <?php endwhile; ?>                
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

			