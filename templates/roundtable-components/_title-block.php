<?php $headerType = get_field( 'header_type' ); ?>
<section class="events-title-block roundtable-title-block events-title-block-left <?php if($headerType == 'dark-header'){ ?> background-black<?php } ?>">
	<div class="container">
		<div class="content-container left-align">
			<span class="pre-title <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> dark-grey-text<?php } ?>"><?php echo get_sub_field( 'pre_title_text' ); ?></span>
			<h1 class="title <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> black-text<?php } ?>"><?php echo get_sub_field( 'title' ); ?></h1>
			<?php if (get_sub_field( 'text' )) { ?>
				<span class="text <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> black-text<?php } ?>"><?php echo get_sub_field( 'text' ); ?></span>
			<?php } ?>
			<span class="button-container<?php if (get_sub_field( 'text' )) { ?><?php } else { ?> no-margin-top<?php } ?>">
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
			<?php if ( have_rows( 'bottom_text' ) ) : ?>
				<span class="bottom-text">
					<?php while ( have_rows( 'bottom_text' ) ) : the_row(); ?>
						<span class="text arrow-icon <?php if($headerType == 'dark-header'){ ?> white-text<?php } else { ?> black-text<?php } ?>"><?php echo get_sub_field( 'text' ); ?></span>
						<span class="text-link-container">
							<a class="text-link red-text large-link-text red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
						</span>
					<?php endwhile; ?>
				</span>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</section>
