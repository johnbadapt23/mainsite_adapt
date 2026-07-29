<?php $headerType = get_field( 'header_type' ); ?>
<section class="events-title-block events-title-block-left <?php if($headerType == 'dark-header'){ ?> background-black<?php } ?>">
	<div class="container">
		<div class="content-container left-align">
			<h1 class="title <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> black-text<?php } ?>"><?php echo get_sub_field( 'title' ); ?></h1>
			<span class="line <?php if($headerType == 'dark-header'){ ?> white-line<?php } else { ?> black-line<?php } ?>"></span>
			<span class="text <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> black-text<?php } ?>"><?php echo get_sub_field( 'text' ); ?></span>
			<span class="button-container">
                <?php if ( have_rows( 'buttons' ) ) : ?>
                    <?php $counter = 1;?>
    				<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                    	<?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                            <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
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
