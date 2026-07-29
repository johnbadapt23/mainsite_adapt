<?php if(get_sub_field( 'background_colour' ) == 'background-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<section class="centered-text-links <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="text-container">
            <h2 class="<?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
            <span class="links-container">
                <?php if ( have_rows( 'links' ) ) : ?>
                    <?php $counter = 1;?>
    				<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                    	<?php if( $counter == 1 ) { ?>
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button red-button">
                                <?php echo get_sub_field( 'link_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="text-link large-link-text red-text red-underline-link">
                                <?php echo get_sub_field( 'link_text' ); ?>
                            </a>
                        <?php } ?>
                        <?php $counter++; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </span>
        </div>
    </div>
</section>
