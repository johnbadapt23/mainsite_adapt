<section class="video-grid-block" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php if(get_sub_field( 'block_title' )){ ?>
            <span class="video-block-title"><?php the_sub_field( 'block_title' ); ?></span>
        <?php } ?>
        <?php if ( have_rows( 'video_columns' ) ) : ?>
            <div class="video-grid-block-container">
                <?php $counter = 0; ?>
				<?php while ( have_rows( 'video_columns' ) ) : the_row(); ?>
                    <div class="column three-column <?php echo $counter; ?>">
                        <div class="video-image-container">
                            <div class="image" style="background-image: url(<?php the_sub_field( 'listing_image' ); ?>);"></div>
                            <span class="videoLink">
                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                    <a href="https://vimeo.com/<?php the_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                                <?php } else { ?>
                                    <button type="button" class="playBtnGrid">
                                <?php } ?>
                                    <span class="icon">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
                                    </span>
                                    <span class="text">
                                        <span><?php the_sub_field( 'button_text' ); ?></span>
                                        <span><?php the_sub_field( 'duration' ); ?></span>
                                    </span>
                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                    </a>
                                <?php } else { ?>
                                    </button>
                                <?php } ?>
                            </span>
                        </div>
                        <?php if(get_sub_field( 'listing_title' )){ ?>
                            <span class="listing-title"><?php the_sub_field( 'listing_title' ); ?></span>
                        <?php } ?>
                        <?php if(get_sub_field( 'listing_text' )){ ?>
                            <span class="listing-details"><?php the_sub_field( 'listing_text' ); ?></span>
                        <?php } ?>
                    </div>
                    <?php $counter++; ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
    <?php if ( have_rows( 'video_columns' ) ) : ?>
        <?php $counter = 0; ?>
            <?php while ( have_rows( 'video_columns' ) ) : the_row(); ?>
                <div class="videoPlayerContainerGrid">
                    <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                    <div class="videoWrapper">
                        <video width="100%" id="popupVideo" controls controlsList="nodownload">
                            <source type="video/mp4" src="<?php the_sub_field( 'vimeo_code' ); ?>" />
                        </video>
                    </div>
                </div>
                <?php $counter++; ?>
            <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?>
</section>
