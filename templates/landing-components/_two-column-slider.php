<section class="two-column-services landing-two-column-slider background-white">
    <div class="container">
        <div class="landing-video-intro-columns">
            <div class="column one-half text-column">
                <div class="text-content-inner">
                    <h2 class="title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>                    
                </div>
            </div>
            <div class="column one-half slider-column">
                <?php if ( have_rows( 'slider' ) ) : ?>
                    <div class="column-slider-container">
                        <?php while ( have_rows( 'slider' ) ) : the_row(); ?>
                            <?php if ( have_rows( 'slide' ) ) : ?>                                
                                <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                                    <div class="slide image-slide">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <span class="slider-inner">
                                            <span class="image-container">
                                                <span class="bg-container">
                                                    <?php if ( $image ) { ?>
                                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                    <?php } ?>
                                                </span>
                                            </span>
                                            <span class="caption-container">
                                                <span class="caption"><?php echo get_sub_field( 'caption' ); ?></span>
                                            </span>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>                          
            </div>
        </div>
    </div>
</section>