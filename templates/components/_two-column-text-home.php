<section class="flex-two-column-text home-two-column-text <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <?php if (get_sub_field( 'title' )) { ?>
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
        <?php } ?>
            <div class="column-container">
                <?php if ( have_rows( 'column' ) ) : ?>
    				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                        <div class="column one-half <?php echo get_sub_field( 'text_size' ); ?>">
                            <span class="text <?php echo get_sub_field( 'text_size' ); ?>"><?php echo get_sub_field( 'text' ); ?></span>
                            <?php if ( have_rows( 'link' ) ) : ?>
                                <span class="link-container">
            						<?php while ( have_rows( 'link' ) ) : the_row(); ?>
            							<a class="text-link large-link-text red-underline-link red-text red-arrow" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
            						<?php endwhile; ?>
                                </span>
        					<?php else : ?>
        						<?php // no rows found ?>
					<?php endif; ?>
                        </div>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </div>
        </div>
    </div>
</section>
