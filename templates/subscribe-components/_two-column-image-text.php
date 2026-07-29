<section class="two-column-image-text-subscribe <?php echo get_sub_field( 'background_colour' ); ?>" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
    <div class="container">
        <?php if ( get_sub_field( 'title' )) { ?>
			<h2 class="quote-slider-title"><?php echo get_sub_field( 'title' ); ?></h2>
		<?php } ?>
        <?php if ( have_rows( 'module' ) ) : ?>
        	<?php while ( have_rows( 'module' ) ) : the_row(); ?>
                <div class="two-column-image-text-outer <?php echo get_sub_field( 'column_types' ); ?>">
                    <div class="image-column one-half column">
                        <?php $image = get_sub_field( 'image' ); ?>
                		<?php if ( $image ) { ?>
                			<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                		<?php } ?>
                    </div>
                    <div class="text-column one-half column">
                        <?php if(get_sub_field( 'column_types' ) == 'text-only'){ ?>
                            <h4 class="black-text"><?php echo get_sub_field( 'title' ); ?></h4>
                        <?php } else { ?>
                            <h2 class="black-text"><?php echo get_sub_field( 'title' ); ?></h2>
                        <?php } ?>
                        <span class="text black-text"><?php echo get_sub_field( 'text' ); ?></span>
                        <?php if ( have_rows( 'list_item' ) ) : ?>
                            <span class="list-container">
                                <?php while ( have_rows( 'list_item' ) ) : the_row(); ?>
                                    <span class="list-item text-black labelXLarge"><?php echo get_sub_field( 'list_text' ); ?></span>
                                <?php endwhile; ?>
                            </span>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                		<?php if ( have_rows( 'link' ) ) : ?>
                            <span class="button-container">
                    			<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                    <?php if(get_sub_field( 'link_type' ) == 'form-popup') { ?>
                                        <span class="form-popup-text-link-container red-text with-red-underline-link with-red-arrow"><?php echo get_sub_field( 'form_button_code' ); ?></span>
                                    <?php } else { ?>
                                        <a class="text-link red-arrow large-link-text red-text red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    <?php } ?>
                    			<?php endwhile; ?>
                            </span>
                		<?php else : ?>
                			<?php // no rows found ?>
                		<?php endif; ?>
                    </div>
                </div>
        	<?php endwhile; ?>
        <?php else : ?>
        	<?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
