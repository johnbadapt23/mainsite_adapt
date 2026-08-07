<?php if(get_sub_field('background_colour') == 'background-true-black'){ ?>
    <?php $textcolor = 'white-text' ?>
    <?php $textSecondary = 'white-text' ?>
<?php } else { ?> 
    <?php $textcolor = 'black-text' ?>
    <?php $textSecondary = 'text-secondary' ?>
<?php } ?>
<section class="two-column two-column-image-text-customer-events two-column-partnered-events <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-white<?php } ?>">
    <div class="container">
        <?php if ( have_rows( 'row' ) ) : ?>
            <?php while ( have_rows( 'row' ) ) : the_row(); ?>
                <div class="column-container <?php echo get_sub_field( 'orientation' ); ?>">
                    <div class="column one-half image-column">
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        <?php } ?>
                    </div>
                    <div class="column one-half text-column">
                        <div class="text-inner">
                            <h2 class="bold-red <?php echo $textcolor; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
                            <p class="p-medium <?php echo $textSecondary; ?>"><?php echo get_sub_field( 'text' ); ?></p>
                            <?php if ( have_rows( 'button' ) ) : ?>
                                <span class="button-container">
                                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                            <a class="stdBtn std-button red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <?php } else if( get_sub_field( 'link_type' ) == 'file') { ?> 
                                            <?php $file = get_sub_field( 'file' ); ?>
                                                <a class="file-button std-button download-icon-button red-outline-button" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <?php } else { ?> 
                                            <span style="display: none"><?php echo get_sub_field( 'form_code' ); ?></span>
                                            <span class="form-popup-button-container red-outline-button"><?php echo get_sub_field( 'form_button' ); ?></span>                                                               
                                        <?php } ?>                     	
                                    <?php endwhile; ?>
                                </span>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
