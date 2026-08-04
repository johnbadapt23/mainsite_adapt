<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos">
    <div class="container">
        <div class="inner">
            <h2><?php the_sub_field( 'block_title' ); ?></h2>

            <?php if ( have_rows( 'speakers' ) ) : ?>
                <div class="gridWrapper speaker-cta">
                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                        <?php $post_object = get_sub_field( 'speaker' ); ?>
                        <?php if ( $post_object ): ?>
                            <?php $post = $post_object; ?>
                            <?php setup_postdata( $post ); ?>
                                <span class="item">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if ( get_field( 'speaker_image') ) { ?>
                                            <div class="imageContainer">
                                                <div class="image" style="background-image: url(<?php the_field( 'speaker_image' ); ?>);">
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <hr>
                                        <span class="title"><?php the_title(); ?></span>
                                        <span class="description">
                                            <?php the_field( 'speaker_description' ); ?>
                                        </span>
                                    </a>
                                    <?php if ( have_rows( 'speaker_block_button' ) ) : ?>
                                        <?php while ( have_rows( 'speaker_block_button' ) ) : the_row(); ?>
                                            <?php if ( get_sub_field( 'download_file' ) ) { ?>
                                                <a class="button" target="_blank" rel="noopener noreferrer" href="<?php the_sub_field( 'download_file' ); ?>"><?php the_sub_field( 'button_text' ); ?></a>
                                            <?php } ?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>

                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if ( have_rows( 'button_block' ) ) : ?>
            <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                <div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>
