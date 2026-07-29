<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<section class="centered-text-links advisors-centered-text-links <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="text-container">
            <h2 class="<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>h1-style <?php } else { ?>h1-style <?php } ?><?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
            <span class="links-container">
                <?php if ( have_rows( 'links' ) ) : ?>
                    <?php $buttonCounter = 1;?>
    				<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="scroll-to-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) == 'hubspot-form') { ?> 
                            <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#centerLinkFormPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="centerLinkFormPopup<?php echo $buttonCounter; ?>">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                    </div>
                                </div> 
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
    </div>
</section>