<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<section class="left-text-links advisors-centered-text-links left-text-links-image <?php echo get_sub_field( 'background_colour' ); ?>">    
    <div class="container">  
        <div class="column-container">      
            <div class="text-container text-column one-half">   
                <?php if (get_sub_field( 'sub_title' )) { ?> 
                    <span class="sub-title labelSmall <?php echo $textColour; ?>"><?php echo get_sub_field( 'sub_title' ); ?></span>
                <?php } ?>                         
                <h2 class="h1-style bold-red <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                <div class="mobile-image-container">
                    <div class="image-container">
                        <div class="bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                    'alt'     => $image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <span class="links-container">
                    <?php if ( have_rows( 'links' ) ) : ?>
                        <?php $buttonCounter = 1;?>
                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                <?php $file = get_sub_field( 'file' ); ?>
                                <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else { ?>
                                <span style="display: none"><?php echo get_sub_field( 'form_code' ); ?></span>
                                <span class="form-popup-button-container <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>"><?php echo get_sub_field( 'form_button' ); ?></span>                                                               
                            <?php } ?>                     	
                            <?php $buttonCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </span>
            </div>
            <div class="column one-half image-column">
                <div class="image-container">
                    <div class="bg-container">
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
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