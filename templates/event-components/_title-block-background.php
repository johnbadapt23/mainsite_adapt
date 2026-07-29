<section class="events-title-block events-title-block-background background-black">
	<?php $background_image = get_sub_field( 'background_image' ); ?>
	<div class="background-image-container" style="background-image:url(<?php echo $background_image['url']; ?>);">
	</div>
	<div class="container">
		<div class="content-container center-align">
			<span class="pre-title text-medium-grey"><?php echo get_sub_field( 'pre_title' ); ?></span>
			<h1 class="title text-white"><?php echo get_sub_field( 'title' ); ?></h1>
			<span class="button-container">
                <?php if ( have_rows( 'buttons' ) ) : ?>
                    <?php $counter = 1;?>
    				<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                    	<?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                            <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
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
