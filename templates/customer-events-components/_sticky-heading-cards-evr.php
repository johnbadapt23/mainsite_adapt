<section class="sticky-heading-cards evr-scrolling-content background-white">
    <div class="sticky-heading-scrolling-container">
        <div class="sticky-heading-container">
            <div class="container">
                <div class="inner-text-container">
                    <h2 class="bold-red text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
                </div>
            </div>
        </div>
        <?php $total_rows = 0;
            $cards = get_sub_field('cards');
            if (is_array($cards)) {
                $total_rows = count($cards);
            }
        ?>
        <?php if ( have_rows( 'cards' ) ) : ?>
            <?php $slide_index = 0; ?>
            <div class="cards-container">                
                <?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                    <div class="scrolling-card">
                        <div class="container">
                            <div class="column-container">
                                <div class="column one-half image-column">
                                    <div class="image-container contained">
                                        <div class="bg-container contained">
                                            <?php $image = get_sub_field( 'image' ); ?>
                                            <?php if ( $image ) { ?>
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                    'class'   => 'desktop-image',
                                                    'alt'     => $image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="column one-half text-column">
                                    <span class="counter-container">
                                        <span class="counter"><span class="black-text labelSmall red-underline">0<?php echo $slide_index + 1; ?></span><span class="text-secondary text-medium-grey labelSmall"> / 0<?php echo $total_rows; ?></span>
                                    </span>
                                    <h2 class="card-title"><?php echo get_sub_field( 'title' ); ?></h2>
                                    <span class="mobile-image-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                'class'   => 'desktop-image',
                                                'alt'     => $image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="card-text p-medium text-secondary text-grey"><?php echo get_sub_field( 'text' ); ?></span>
                                </div>
                            </div>                                                       
                        </div>
                    </div>
                    <?php $slide_index++; ?>
                <?php endwhile; ?>                
            </div> 
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>               
    </div>
</section>


