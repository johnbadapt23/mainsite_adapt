<section class="map-moving-text background-black">
    <div class="container">
        <div class="map-fixed-scroller-container">
            <div class="title-container">
                <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
            <?php $map_image = get_sub_field( 'map_image' ); ?>
            <div class="map-container" style="background-image: url(<?php echo $map_image['url']; ?>);">
            </div>
            <div class="scrolling-container growing-text-container">
                <div class="column empty-column">
                </div>
                <div class="column empty-column">
                </div>
                <?php if ( have_rows( 'moving_text' ) ) : ?>
                    <?php while ( have_rows( 'moving_text' ) ) : the_row(); ?>
                        <div class="column growing-text-column">
                            <span class="red-text growing-title"><?php echo get_sub_field( 'large_text' ); ?></span>
                            <span class="white-text"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>                
            </div>            
        </div>
    </div>
</section>




        
