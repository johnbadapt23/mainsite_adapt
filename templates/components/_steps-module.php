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
                                                <?php echo wp_get_attachment_image( $pre_image_image['ID'], 'full', false, array(
                                                    'class'   => 'pre-image',
                                                    'alt'     => $pre_image_image['alt'],
                                                    'loading' => false,
                                                ) ); ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                    <div class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                'alt'     => $image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </div>
                                    <?php $post_image_image = get_sub_field( 'post_image_image' ); ?>
                        			<?php if ( $post_image_image ) { ?>
                        				<?php echo wp_get_attachment_image( $post_image_image['ID'], 'full', false, array(
                        					'class'   => 'post-image',
                        					'alt'     => $post_image_image['alt'],
                        					'loading' => 'lazy',
                        				) ); ?>
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
                                                <?php echo wp_get_attachment_image( $pre_image_image['ID'], 'full', false, array(
                                                    'class'   => 'pre-image',
                                                    'alt'     => $pre_image_image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                    <?php if ( $image ) { ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                            'class'   => 'main-image' . ( $pre_image_image ? ' full-width-image' : '' ),
                                            'alt'     => $image['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                    <?php if ( $post_image_image ) { ?>
                        				<?php echo wp_get_attachment_image( $post_image_image['ID'], 'full', false, array(
                        					'class'   => 'post-image',
                        					'alt'     => $post_image_image['alt'],
                        					'loading' => 'lazy',
                        				) ); ?>
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
