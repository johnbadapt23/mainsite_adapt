<section class="three-column-text-image-cards <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="column-container-outer">
            <div class="column-container card-container">
                <div class="column one-third title-column">
                    <h2 class="title bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
                    <?php if ( have_rows( 'button' ) ) : ?>
                        <div class="button-container" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                    <a class="stdBtn std-button red-button" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                <?php } else if( get_sub_field( 'button_type' ) =='scroll-to') { ?> 
                                    <a class="scroll-to-button std-button red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                <?php } else { ?> 
                                    <span class="form-popup-button-container red-button"><?php echo get_sub_field( 'form_button' ); ?></span>                                                               
                                <?php } ?>                     
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
                <?php if ( have_rows( 'cards' ) ) : ?>
                    <?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                        <div class="column one-third image-text-card background-light-grey" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                            <span class="card-image-container image-container">
                                <span class="bg-container contained-image">
                                    <?php $image = get_sub_field( 'image' ); ?>
                                    <?php if ( $image ) { ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                            'alt'     => $image['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                            </span>
                            <span class="text-container">
                                <span class="black-text labelXL"><?php echo get_sub_field( 'title' ); ?></span>
                                <span class="text text-dark-grey"><?php echo get_sub_field( 'text' ); ?></span>
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

