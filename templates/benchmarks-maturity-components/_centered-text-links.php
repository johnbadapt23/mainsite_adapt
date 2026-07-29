<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<?php 
    $max_width = get_sub_field('heading_max_width');
    $heading_style = get_sub_field('heading_size');
    $id = get_sub_field('id');
?>

<section class="centered-text-links advisors-centered-text-links <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div style="max-width: <?php echo $max_width; ?>;" class="text-container">
            <?php if ( get_sub_field('title') ) { ?>
                <h2 class="<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?><?php echo $heading_style;?> <?php } else { ?><?php echo $heading_style;?> <?php } ?><?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
            <?php } ?>
            <?php if ( get_sub_field('text') ) { ?>
                <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
            <?php } ?>
            <span class="links-container">
                <?php if ( have_rows( 'links' ) ) : ?>
                    <?php $buttonCounter = 1;?>
                    <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                            <a class="formPopupHubspot download-file-button with-icon stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo $id; ?>_formPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="<?php echo $id; ?>_formPopup<?php echo $buttonCounter; ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                </div>
                            </div> 
                        <?php } else { ?> 
                            <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo $id; ?>_formPopup<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="<?php echo $id; ?>_formPopup<?php echo $buttonCounter; ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                </div>
                            </div> 
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