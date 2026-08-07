<?php if(get_sub_field( 'background_colour' ) == 'background-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>

<section class="three-column-icon-services <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <span class="title-container">
            <span class="title-inner">
                <h2 class="<?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if (get_sub_field( 'text' )) { ?>
                    <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                <?php } ?>
            </span>
        </span>
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
            	<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="icon-text-item one-third column">
                        <span class="icon-container">
                            <?php $icon = get_sub_field( 'icon' ); ?>
                    		<?php if ( $icon ) { ?>
                    			<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                    				'alt'     => $icon['alt'],
                    				'loading' => 'lazy',
                    			) ); ?>
                    		<?php } ?>
                        </span>
                        <span class="text-container">
                            <span class="icon-text-title <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></span>
                            <span class="icon-text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                        </span>
                    </div>
            	<?php endwhile; ?>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'cta_module' ) ) : ?>
				<?php while ( have_rows( 'cta_module' ) ) : the_row(); ?>
                    <div class="one-third column cta-column">
                        <div class="cta-inner">
                            <span class="red-text"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="button-container">
                                <?php if ( have_rows( 'button' ) ) : ?>
            						<?php while ( have_rows( 'button' ) ) : the_row(); ?>
            							<a class="std-button small-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
            						<?php endwhile; ?>
            					<?php else : ?>
            						<?php // no rows found ?>
            					<?php endif; ?>
                            </span>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
