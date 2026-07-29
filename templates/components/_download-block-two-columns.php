<section class="download-block" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php if(get_sub_field( 'block_title' )){ ?>
            <span class="download-block-title"><?php the_sub_field( 'block_title' ); ?></span>
        <?php } ?>
        <?php if ( have_rows( 'download_columns' ) ) : ?>
            <?php $counter = 0; ?>
				<?php while ( have_rows( 'download_columns' ) ) : the_row(); ?>
                    <div class="column two-column <?php echo $counter; ?>">
                        <a class="download-popup-button-multi" href="#downloadPopupDouble<?php echo $counter; ?>">
                            <span class="download-image-container">
                                <span class="image" style="background-image: url(<?php the_sub_field( 'listing_image' ); ?>);"></span>
                            </span>
                        </a>
                        <?php if(get_sub_field( 'listing_title' )){ ?>
                            <span class="listing-title"><?php the_sub_field( 'listing_title' ); ?></span>
                        <?php } ?>
                        <?php if(get_sub_field( 'listing_text' )){ ?>
                            <span class="listing-details"><?php the_sub_field( 'listing_text' ); ?></span>
                        <?php } ?>
                        <a class="download-popup-button-multi" href="#downloadPopupDouble<?php echo $counter; ?>"><?php the_sub_field( 'button_text' ); ?></a>
                        <div class="downloadPopupContainer" style="display: none;">
                            <div class="downloadPopup" id="downloadPopupDouble<?php echo $counter; ?>">
                                <div class="container">
                                    <div class="preview-container">
                                        <?php if (get_sub_field('pdf_flip_embed')) { ?>
                                            <?php the_sub_field('pdf_flip_embed'); ?>
                                        <?php } else { ?>
                                            <div class="download-image-container">
                                                <div class="image" style="background-image: url(<?php the_sub_field( 'listing_image' ); ?>);"></div>
                                            </div>
                                        <?php }?>
                                        <div class="description-container desktop">
                                            <?php if(get_sub_field( 'listing_title' )){ ?>
                                                <span class="listing-title"><?php the_sub_field( 'listing_title' ); ?></span>
                                            <?php } ?>
                                            <?php if(get_sub_field( 'listing_text' )){ ?>
                                                <span class="listing-details"><?php the_sub_field( 'listing_text' ); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="download-container">
                                        <span class="download-title">
                                            Download options
                                        </span>
                                        <span class="downloads">
                                            <?php if ( have_rows( 'download' ) ) : ?>
                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
                                                    <?php the_sub_field( 'download' ); ?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="description-container mobile">
                                        <?php if(get_sub_field( 'listing_title' )){ ?>
                                            <span class="listing-title"><?php the_sub_field( 'listing_title' ); ?></span>
                                        <?php } ?>
                                        <?php if(get_sub_field( 'listing_text' )){ ?>
                                            <span class="listing-details"><?php the_sub_field( 'listing_text' ); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $counter++; ?>
                <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
