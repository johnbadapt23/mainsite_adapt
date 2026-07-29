<section class="fixed-scroller <?php if(get_sub_field( 'background_colour' )) { ?><?php echo get_sub_field( 'background_colour' ); ?><?php } ?>">
    <div class="container">        
        <div class="fixed-scroller-container">
            <div class="title-container sticky-container">
                <div class="column-container bottom-align">
                    <h2 class="bold-red white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="p-medium white-text bottom-align"><?php echo get_sub_field( 'text' ); ?></p>
                </div>
            </div>
            <div class="fixed-scroller-inner">
                <?php if ( have_rows( 'scrolling_content' ) ) : ?>
                    <?php while ( have_rows( 'scrolling_content' ) ) : the_row(); ?>
                        <div class="fixed-scroll-item">
                            <span class="fixed-image-container column image-column">
                                <span class="image-container square-image">
                                    <span class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                        <?php } ?>
                                    </span>
                                </span>
                            </span>
                            <span class="text-column column">
                                <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                                <span class="p-large bold-red grey-text"><?php echo get_sub_field( 'text' ); ?></span>
                            </span>
                        </div>                                                                        
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

