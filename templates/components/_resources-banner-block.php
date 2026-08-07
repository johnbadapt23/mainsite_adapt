<section class="thank-you-banner resources-banner">
    <div class="container">
        <div class="column one-half image-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <div class="image-container">
                        <div class="screen-bg-container">
                            <?php $image = get_sub_field( 'image' ); ?>
                    		<?php if ( $image ) { ?>
                    			<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                    				'alt'     => $image['alt'],
                    				'loading' => 'lazy',
                    			) ); ?>
                    		<?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column one-half text-column">
            <div class="v-wrap">
                <div class="v-box left-align">
                    <span class="text-inner">
                		<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                        <span class="text-details-container">
                            <span class="subtitle"><?php echo get_sub_field( 'sub_title' ); ?></span>
                            <?php
                            $date_string = get_sub_field('event_date');
                            $date = DateTime::createFromFormat('Ymd', $date_string);
                            ?>
                            <span class="date"><?php echo $date->format('j F, Y'); ?></span>
                        </span>
                		<?php if ( have_rows( 'button' ) ) : ?>
                            <span class="button-container">
                    			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                    <?php if( get_sub_field( 'scroll_to_link_or_video' ) == 'scroll-to') { ?>
                                        <a class="std-button scroll-to-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php } ?>
                                    <?php if( get_sub_field( 'scroll_to_link_or_video' ) == 'link') { ?>
                                        <a class="std-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <?php }?>
                                    <?php if( get_sub_field( 'scroll_to_link_or_video' ) == 'video') { ?>
                                        <?php if (get_sub_field( 'vimeo_code' )) { ?>
                                            <a class="std-button popup-vimeo video-button black-outline" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } else { ?>
                                            <a class="std-button video-button black-outline" href="" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } ?>
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
