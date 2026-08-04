<section class="lifestyle-slider-block">
    <div class="container">
        <div class="content-top">
            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="text-black sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
        </div>
    </div>
    <div class="content-slider-container">
        <span class="leftSlideCover"></span>
        <div class="container">
            <?php if ( have_rows( 'slides' ) ) : ?>
                <div class="lifestyle-slider">
                    <?php $counter=1;?>
                	<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                        <div class="slide lifestyle-slide-outer">
                            <div class="lifestyle-slide-inner">
                                <div class="image-container">
                                    <span class="hover-border"></span>
                                    <div class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                    					<?php if ( $image ) { ?>
                    						<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                    							'alt'     => $image['alt'],
                    							'loading' => false,
                    						) ); ?>
                    					<?php } ?>
                                    </div>
                                </div>
                                <span class="hover-container">
                                    <span class="hover-text-container">
                                        <?php echo get_sub_field( 'hover_text' ); ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <?php $counter ++; ?>
                	<?php endwhile; ?>
                </div>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
