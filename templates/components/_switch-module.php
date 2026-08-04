<?php $backgroundColour = 'background-white'; ?>
<?php if ( have_rows( 'module' ) ) : ?>
    <?php $colourCounter=1;?>
    <?php while ( have_rows( 'module' ) ) : the_row(); ?>
        <?php if($colourCounter==1){ ?>
            <?php $backgroundColour = get_sub_field( 'background_colour' );?>
        <?php } ?>
        <?php $colourCounter++;?>
    <?php endwhile; ?>
<?php else : ?>
    <?php // no rows found ?>
<?php endif; ?>
<section class="switcher-module <?php echo $backgroundColour; ?>">
    <div class="container">
        <div class="top-block">
            <span class="title-switcher-container">
                <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if ( have_rows( 'module' ) ) : ?>
                    <span class="module-switcher">
                        <?php $buttonCounter=1;?>
                    	<?php while ( have_rows( 'module' ) ) : the_row(); ?>
                            <button type="button" class="module-switch-button std-button <?php if($buttonCounter==1){ ?>active<?php } ?>" data-background="<?php echo get_sub_field( 'background_colour' );?>"><?php echo get_sub_field( 'title' ); ?></button>
                            <?php $buttonCounter++;?>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </span>
        </div>

        <?php if ( have_rows( 'module' ) ) : ?>
            <?php $counter=1;?>
            <?php while ( have_rows( 'module' ) ) : the_row(); ?>
                <div class="switch-content-container <?php if($counter==1){ ?>active<?php } ?>">
                    <div class="icon-text-column-container">
            			<?php if ( have_rows( 'columns' ) ) : ?>
            				<?php while ( have_rows( 'columns' ) ) : the_row(); ?>
                                <div class="column one-half icon-text-column">
                                    <div class="icon-container">
                                        <span class="image-container">
                                            <span class="bg-container contained-image">
                                                <?php $icon = get_sub_field( 'icon' ); ?>
                            					<?php if ( $icon ) { ?>
                            						<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                            							'alt'     => $icon['alt'],
                            							'loading' => false,
                            						) ); ?>
                            					<?php } ?>
                                            </span>
                                        </span>
                					</div>
                                    <div class="text-container">
                                        <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                        <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                    </div>
                                </div>
            				<?php endwhile; ?>
            			<?php else : ?>
            				<?php // no rows found ?>
            			<?php endif; ?>
                    </div>
                    <?php if ( have_rows( 'bottom_link' ) ) : ?>
                        <span class="bottom-link-container">
                            <?php while ( have_rows( 'bottom_link' ) ) : the_row(); ?>
                                <a class="text-link red-text red-underline-link large-link-text red-arrow" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php $counter++;?>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
