<section class="full-image-text background-white">
    <div class="background-image-container">
        <div class="image-container">
            <div class="bg-container">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                <?php } ?>
            </div>
             <div class="text-container-outer">
                <div class="text-container-inner background-black">
                    <div class="container">
                        <div class="text-content-container">
                            <span class="title text h2-style white-text bold-red"><?php echo get_sub_field( 'title' ); ?></span>
                            <span class="text p-large medium-light-grey-text"><?php echo get_sub_field( 'text' ); ?></span>
                            <?php if ( have_rows( 'button' ) ) : ?>
                                <?php $buttonCounter = 1; ?>
                                <span class="button-container">                                                                                                                   
                                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                            <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } else if( get_sub_field( 'button_type' ) =='scroll-to') { ?> 
                                            <a class="scroll-to-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } else { ?> 
                                            <span class="form-popup-button-container <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>"><?php echo get_sub_field( 'form_button' ); ?></span>                                
                                        <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                        <?php $buttonCounter++; ?>
                                    <?php endwhile; ?>
                                </span>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>    
</section>

