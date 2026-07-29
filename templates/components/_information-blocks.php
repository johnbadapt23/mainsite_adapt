<section class="information-blocks"<?php if (get_sub_field( 'id' )) { ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
		<?php if ( have_rows( 'block' ) ) : ?>
            <div class="column-container">
    			<?php while ( have_rows( 'block' ) ) : the_row(); ?>
                    <div class="column one-half information-column">
        				<h3 class="red-text counter"><?php echo get_sub_field( 'counter' ); ?></h3>
        				<h2 class="information-title"><?php echo get_sub_field( 'title' ); ?></h2>
        				<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
        				<?php if ( have_rows( 'button' ) ) : ?>
                            <span class="button-container">
            					<?php while ( have_rows( 'button' ) ) : the_row(); ?>
            						<a class="std-button red-outline" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
            						<?php if ( have_rows( 'under_button_text' ) ) : ?>
            							<?php while ( have_rows( 'under_button_text' ) ) : the_row(); ?>
                                            <span class="tooltip-container">
                                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                                <span class="tooltip"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
            								</span>
            							<?php endwhile; ?>
            						<?php else : ?>
            							<?php // no rows found ?>
            						<?php endif; ?>
            					<?php endwhile; ?>
                            </span>
        				<?php else : ?>
        					<?php // no rows found ?>
        				<?php endif; ?>
                    </div>
    			<?php endwhile; ?>
            </div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
    </div>
</section>
