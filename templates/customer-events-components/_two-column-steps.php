<?php if(get_sub_field('background_colour') == 'background-true-black'){ ?>
    <?php $textcolor = 'white-text' ?>
    <?php $textSecondary = 'text-medium-grey' ?>
<?php } else { ?> 
    <?php $textcolor = 'black-text' ?>
    <?php $textSecondary = 'text-medium-grey' ?>
<?php } ?>
<section class="two-column two-column-steps <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-true-black<?php } ?>">
    <div class="container">
        <div class="title-container">
            <div class="inner-text-container">
                <h2 class="bold-red <?php echo $textcolor; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
        </div>
        <div class="steps-container">
            <span class="tracking-line-container">
                <span class="tracking-line"></span>
            </span>
            <?php if ( have_rows( 'steps' ) ) : ?>
                <?php $stepCounter = 1; ?>
				<?php while ( have_rows( 'steps' ) ) : the_row(); ?>
                    <div class="column-container step"> 
                        <span class="step-counter labelXsmall"><?php echo $stepCounter; ?></span>
                        <div class="column one-half image-column">
                            <div class="image-container contained">
                                <div class="bg-container contained">
                                    <?php $image = get_sub_field( 'image' ); ?>
                                    <?php if ( $image ) { ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                            'alt'     => $image['alt'],
                                            'loading' => false,
                                        ) ); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="column one-half text-column">
                            <h4 role="heading" aria-level="3" class="title red-text"><?php echo get_sub_field( 'title' ); ?></h4>
                            <h4 class="sub-title <?php echo $textcolor; ?>"><?php echo get_sub_field( 'sub_title' ); ?></h4>
                            <span class="mobile-image-container">
                                <?php $image = get_sub_field( 'image' ); ?>
                                <?php if ( $image ) { ?>
                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                        'alt'     => $image['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                <?php } ?>
                            </span>
                            <p class="p-medium <?php echo $textSecondary; ?>"><?php echo get_sub_field( 'text' ); ?></p>                            
                        </div>
                    </div>
                    <?php $stepCounter++; ?>
                <?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
        <?php if ( have_rows( 'button' ) ) : ?>
            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                <div class="button-container">
                    <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                        <a class="stdBtn std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                    <?php } else if( get_sub_field( 'link_type' ) == 'scroll_to_id') { ?> 
                        <?php $file = get_sub_field( 'file' ); ?>
                        <a class="scroll-to std-button red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'button_text' ); ?></a>
                    <?php } else { ?> 
                        <span style="display: none"><?php echo get_sub_field( 'form_embed' ); ?></span>
                        <span class="form-popup-button-container red-button"><?php echo get_sub_field( 'form_button' ); ?></a>                                                               
                    <?php } ?>             
                </div>                
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>