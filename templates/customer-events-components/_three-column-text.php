<?php $textColour = 'white-text'; 
      $greyText = 'text-medium-grey';     
?>
<?php if(get_sub_field( 'background_colour' )){ 
    if(get_sub_field( 'background_colour') != 'background-black'){
        $textColour = 'black-text';
        $greyText = 'text-dark-grey'; 
    }
 } ?>

<section class="comparison-three-column-text three-column-text <?php if(get_sub_field( 'background_colour' )){ ?><?php echo get_sub_field( 'background_colour' ); ?><?php } ?>">
    <span class="background-gradient-line mobile"></span>
    <div class="container">
        <div class="title-container">
            <h2 class="<?php echo $textColour; ?> bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="column-container">
            <span class="background-gradient-line desktop"></span>
            <?php if ( have_rows( 'column' ) ) : ?> 
                <?php $counter=1;?>               
				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-third">
                        <div class="column-inner">
                            <span class="white-text counter-text labelSmall"><?php echo $counter; ?></span>
                            <span>
                                <h4 class="headerSmall <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h4>
                                <p class="p-medium <?php echo $greyText ; ?>"><?php echo get_sub_field( 'text' ); ?></p>
                            </span>
                        </div>
                    </div>
                <?php $counter++;?>  
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
        <?php if ( have_rows( 'button' ) ) : ?>
            <div class="link-container">
                <?php $buttonCounter = 1;?>
                <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                        <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                        <a class="stdBtn std-button scroll-to-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                        <?php $file = get_sub_field( 'file' ); ?>
                        <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                        <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopupThreeColumn"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="formPopupThreeColumn">
                                <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                            </div>
                        </div> 
                    <?php } else { ?>                                 
                        <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopupThreeColumn"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="formPopupThreeColumn">
                                <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                            </div>
                        </div> 
                    <?php } ?>                     	
                    <?php $buttonCounter++; ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>       
    </div>
</section>