<section class="steps-module <?php echo get_sub_field( 'background_colour' ); ?> <?php echo get_sub_field( 'overlay_pattern' ); ?><?php if (get_sub_field( 'title' )) { ?> title-padding<?php } ?> ">
    <div class="container">
        <?php if (get_sub_field( 'title' )) { ?>
            <h2 class="module-title"><?php echo get_sub_field( 'title' ); ?></h2>
        <?php } ?>
        <div class="column-container <?php echo get_sub_field( 'image_orientation' ); ?>">
            <?php if ( get_sub_field( 'image_orientation' ) == 'no-image') { ?>
                <div class="single-column column">
                    <div class="text-column-inner text-column">
                        <?php if ( have_rows( 'content_column' ) ) : ?>
                            <?php while ( have_rows( 'content_column' ) ) : the_row(); ?>
                                <span class="step-number"><?php echo get_sub_field( 'step_number' ); ?></span>
                                <span class="step-detail">
                                    <span class="pre-title"><?php echo get_sub_field( 'pre_title' ); ?></span>
                                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="column one-half image-column <?php echo get_sub_field( 'image_orientation' ); ?> desktop-column">
                    <?php $pre_image_image = get_sub_field( 'pre_image_image' ); ?>
                    <div class="v-wrap">
                        <div class="v-box">
                            <div class="steps-image-container">
                                <div class="image-container">
                                    <?php if ( $pre_image_image ) { ?>
                                        <span class="pre-image-container">
                                            <span class="pre-image-inner">
                                                <img class="pre-image" src="<?php echo $pre_image_image['url']; ?>" alt="<?php echo $pre_image_image['alt']; ?>" />
                                            </span>
                                        </span>
                                    <?php } ?>
                                    <div class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                        <?php } ?>
                                    </div>
                                    <?php $post_image_image = get_sub_field( 'post_image_image' ); ?>
                        			<?php if ( $post_image_image ) { ?>
                        				<img loading="lazy" class="post-image" src="<?php echo $post_image_image['url']; ?>" alt="<?php echo $post_image_image['alt']; ?>" />
                        			<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column one-half text-column <?php echo get_sub_field( 'image_orientation' ); ?>">
                    <?php if ( have_rows( 'content_column' ) ) : ?>
                        <?php while ( have_rows( 'content_column' ) ) : the_row(); ?>
                            <span class="step-number"><?php echo get_sub_field( 'step_number' ); ?></span>
                            <span class="step-detail">
                                <span class="pre-title"><?php echo get_sub_field( 'pre_title' ); ?></span>
                                <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                <div class="mobile-image-container<?php if ( $preText ) { ?> full-width-image<?php } ?>">
                                    <?php if ( $pre_image_image ) { ?>
                                        <span class="pre-image-container">
                                            <span class="pre-image-inner">
                                                <img loading="lazy" class="pre-image" src="<?php echo $pre_image_image['url']; ?>" alt="<?php echo $pre_image_image['alt']; ?>" />
                                            </span>
                                        </span>
                                    <?php } ?>
                                    <?php if ( $image ) { ?>
                                        <img loading="lazy" class="main-image<?php if ( $pre_image_image ) { ?> full-width-image<?php } ?>" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                    <?php } ?>
                                    <?php if ( $post_image_image ) { ?>
                        				<img loading="lazy" class="post-image" src="<?php echo $post_image_image['url']; ?>" alt="<?php echo $post_image_image['alt']; ?>" />
                        			<?php } ?>
                                </div>
                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                            </span>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            <?php }?>
        </div>
    </div>
</section>
