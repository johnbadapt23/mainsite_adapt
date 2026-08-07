<section class="two-column-services landing-video-intro background-white landing-intro-block">
    <div class="container">
        <div class="landing-video-intro-columns">
            <div class="column one-half text-column">
                <div class="text-content-inner">                    
                    <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                    <span class="buttons-container">
                        <?php if ( have_rows( 'buttons' ) ) : ?>
                            <?php $counter = 1;?>
                            <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                <?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                                    <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                        <?php echo get_sub_field( 'button_text' ); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                        <?php echo get_sub_field( 'button_text' ); ?>
                                    </a>
                                <?php } ?>
                                <?php $counter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="column one-half video-column image-column">
                <div class="video-container image-container">
                    <span class="frame"></span>
                    <div class="bg-container">
                        <?php $image = get_sub_field('image'); ?>
                        <?php if ( $image ) { ?>
                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        <?php } ?>
                    </div>
                </div>                 
            </div>
        </div>
    </div>
</section>
