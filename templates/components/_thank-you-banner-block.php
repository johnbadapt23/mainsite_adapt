<section class="thank-you-banner">
    <div class="container">
        <div class="column one-half image-column">
            <img class="overlay-image-top-left" src="<?php echo get_template_directory_uri(); ?>/assets/images/overlay-top-left.svg" alt="" width="50"/>
            <img loading="lazy" class="overlay-image-bottom-left" src="<?php echo get_template_directory_uri(); ?>/assets/images/overlay-bottom-left.svg" alt="" width="50"/>
            <div class="image-container">
                <div class="bg-container">
                    <?php $image = get_sub_field( 'image' ); ?>
            		<?php if ( $image ) { ?>
            			<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
            				'alt'     => $image['alt'],
            				'loading' => 'lazy',
            			) ); ?>
            		<?php } ?>
                </div>
            </div>
            <img loading="lazy" class="overlay-image-bottom-right" src="<?php echo get_template_directory_uri(); ?>/assets/images/overlay-bottom-right.svg" alt="" width="50"/>
        </div>
        <div class="column one-half text-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <span class="text-inner">
                        <h3 class="subtitle text-red"><?php echo get_sub_field( 'sub_title' ); ?></h3>
                		<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                		<?php if ( have_rows( 'button' ) ) : ?>
                            <span class="button-container">
                    			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                    <?php if( get_sub_field( 'scroll_to_or_link' ) == 'scroll-to') { ?>
                                        <a class="std-button scroll-to-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php } else { ?>
                                        <a class="std-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php }?>
                    			<?php endwhile; ?>
                            </span>
                		<?php else : ?>
                			<?php // no rows found ?>
                		<?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
