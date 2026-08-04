<section class="customise-cards background-black">
    <div class="container">
        <div class="cards-container">
            <?php if ( have_rows( 'title_card' ) ) : ?>
				<?php while ( have_rows( 'title_card' ) ) : the_row(); ?>
                    <div class="card title-card transparent">
                        <span class="v-wrap">
                            <span class="v-box">
                                <span class="card-content">
                					<h2 class="card-text">
                                        <?php echo get_sub_field( 'text' ); ?>
                                    </h2>
                					<?php if ( have_rows( 'button' ) ) : ?>
                                        <span class="button-container">
                    						<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                                <?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                                                    <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button red-outline-button">
                                                        <?php echo get_sub_field( 'button_text' ); ?>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button red-outline-button">
                                                        <?php echo get_sub_field( 'button_text' ); ?>
                                                    </a>
                                                <?php } ?>
                    						<?php endwhile; ?>
                                        </span>
                					<?php else : ?>
                						<?php // no rows found ?>
                					<?php endif; ?>
                                </span>
                            </span>
                        </span>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
            <?php if ( have_rows( 'content_card' ) ) : ?>
                <?php $counter=1;?>
				<?php while ( have_rows( 'content_card' ) ) : the_row(); ?>
                    <div class="card content-card" <?php if ($counter == 1){?>data-aos="fade-up"<?php } else { ?>data-aos="fade-down"<?php } ?> data-aos-anchor-placement="center-bottom" data-aos-duration="800">
    					<?php $image = get_sub_field( 'image' ); ?>
                        <span class="card-top-image">
                            <span class="image-container">
                                <span class="bg-container contained-image">
                                    <?php if ( $image ) { ?>
                						<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                							'alt'     => $image['alt'],
                							'loading' => false,
                						) ); ?>
                					<?php } ?>
                                </span>
                            </span>
                        </span>
    					<span class="card-text-container">
                            <span class="title">
                                <?php echo get_sub_field( 'title' ); ?>
                            </span>
                            <span class="text">
                                <?php echo get_sub_field( 'text' ); ?>
                            </span>
                        </span>
                    </div>
                    <?php $counter++;?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
