<section class="partner-with-us-block">
    <div class="block-container">
        <div class="container">
            <div class="column one-half text-column">
                <span class="text-container">
                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                    <?php if ( have_rows( 'button' ) ) : ?>
	                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <span class="button-container">
                                <?php if ( get_sub_field( 'button_type' ) == 'register-link') { ?>
                                    <a class="std-button white-button register-button" href="#register"><?php echo get_sub_field( 'button_text' ); ?></a>
                                <?php } else { ?>
                                    <a class="std-button white-button register-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                <?php } ?>
                            </span>
                        <?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </span>
            </div>
            <div class="column one-half image-column">
                <?php $image = get_sub_field( 'image' ); ?>
    			<?php if ( $image ) { ?>
                    <span class="image-container">
                        <span class="bg-container">
                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                        </span>
                    </span>
    			<?php } ?>
            </div>
        </div>
    </div>
</section>
