<section class="download-block resources-block background-lightgrey" <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php if(get_sub_field( 'title' )){ ?>
            <span class="download-block-title"><?php echo get_sub_field( 'title' ); ?></span>
        <?php } ?>
		<?php
		$count = 0;
		$resources = get_sub_field('resource');
		if (is_array($resources)) {
			$count = count($resources);
		}

		if ( $count < 3){
			$columns = 'two-column';
		} else {
			$columns = 'resources-slider';
		}

		?>

		<div class="resources-container <?php echo $columns; ?> desktop">
	        <?php if ( have_rows( 'resource' ) ) : ?>
	            <?php $counter = 0; ?>
					<?php while ( have_rows( 'resource' ) ) : the_row(); ?>
	                    <div class="column <?php echo $counter; ?>">
                            <?php if ( get_sub_field( 'resource_type' ) == 'video') { ?>
                                <?php $type = 'watch'; ?>
                            <?php } else if ( get_sub_field( 'resource_type' ) == 'download'){ ?>
                                <?php $type = 'download'; ?>
                            <?php } else { ?>
                                <?php $type = 'link'; ?>
                            <?php } ?>
	                        <?php if ( get_sub_field( 'resource_type' ) == 'video') { ?>
	                            <a class="popup-vimeo resources-video-popup" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>">
	                                <span class="resources-image-container">
	                                    <span class="bg-container">
	                                        <?php $image = get_sub_field( 'image' ); ?>
	                    					<?php if ( $image ) { ?>
	                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
	                    							'alt'     => $image['alt'],
	                    							'loading' => 'lazy',
	                    						) ); ?>
	                    					<?php } ?>
                                            <span class="opacity-overlay">
                                                <span class="video-overlay-button"></span>
                                            </span>

	                                    </span>
	                                </span>
                            <?php } else if ( get_sub_field( 'resource_type' ) == 'download') { ?>
                                <a class="resources-popup-button" href="#resourcesPopup<?php echo $counter; ?>">
                                   <span class="resources-image-container">
                                       <span class="bg-container">
                                           <?php $image = get_sub_field( 'image' ); ?>
                                           <?php if ( $image ) { ?>
                                               <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                               	'alt'     => $image['alt'],
                                               	'loading' => 'lazy',
                                               ) ); ?>
                                           <?php } ?>
                                            <span class="opacity-overlay">
                                                <span class="download-overlay-button"></span>
                                            </span>
                                       </span>
                                   </span>
                            <?php } else { ?>
                                <?php if ( have_rows( 'link' ) ) : ?>
            						<?php while ( have_rows( 'link' ) ) : the_row(); ?>
            							<?php $linkText = get_sub_field( 'link_text' ); ?>
            							<?php $linkLink = get_sub_field( 'link' ); ?>
            						<?php endwhile; ?>
            					<?php else : ?>
            						<?php // no rows found ?>
            					<?php endif; ?>
	                            <a class="resources-link" href="<?php echo $linkLink; ?>" target="_blank" rel="noopener noreferrer">
	                                <span class="resources-image-container">
	                                    <span class="bg-container">
	                                        <?php $image = get_sub_field( 'image' ); ?>
	                    					<?php if ( $image ) { ?>
	                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
	                    							'alt'     => $image['alt'],
	                    							'loading' => 'lazy',
	                    						) ); ?>
	                    					<?php } ?>
                                            <span class="opacity-overlay">
                                            </span>
	                                    </span>
	                                </span>
	                        <?php } ?>
	                            <?php if(get_sub_field( 'title' )){ ?>
	                                <?php $title = get_sub_field( 'title' ); ?>
	                                <span class="listing-title"><?php echo get_sub_field( 'title' ); ?></span>
	                            <?php } ?>
                                <?php if (!empty($linkText)) { ?>
                                    <span class="type"><?php echo $linkText; ?></span>
                                <?php } else { ?>
                                    <span class="type"><?php echo $type; ?></span>
                                <?php } ?>
	                        </a>
	                        <div class="resourcesPopupContainer" style="display: none;">
	                            <div class="resourcesPopup" id="resourcesPopup<?php echo $counter; ?>">
	                                <div class="container">
                                        <div class="resources-container">
    	                                    <div class="preview-container">
    	                                        <div class="download-image-container">
    	                                            <span class="resources-image-container">
    	                                                <span class="bg-container">
    	                                                    <?php $image = get_sub_field( 'image' ); ?>
    	                                					<?php if ( $image ) { ?>
    	                                						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
    	                                							'alt'     => $image['alt'],
    	                                							'loading' => 'lazy',
    	                                						) ); ?>
    	                                					<?php } ?>
    	                                                </span>
    	                                            </span>
    	                                        </div>
    	                                    </div>
    	                                    <div class="download-container">
    	                                        <span class="download-title">
    	                                            <?php echo $title; ?>
    	                                        </span>
                                                <span class="download-description">
                                                   <?php echo get_sub_field( 'description' ); ?>
                                               </span>
    	                                        <span class="downloads">
                                                    <?php $count = 0;
                                                        $locations = get_sub_field('download');
                                                        if (is_array($locations)) {
                                                          $count = count($locations);
                                                        }
                                                        ?>
    	                                            <?php if ( have_rows( 'download' ) ) : ?>
                                                        <?php $buttonCounter=1; ?>
    	                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
    	                                                    <span class="download-button-container<?php if($buttonCounter == 1){ ?> active<?php }?> <?php echo get_sub_field( 'download_file_type' ); ?>" id="<?php echo get_sub_field( 'download_file_type' ); ?>"><?php echo get_sub_field( 'download' ); ?></span>
                                                            <?php $buttonCounter++; ?>
    	                                                <?php endwhile; ?>
    	                                            <?php else : ?>
    	                                                <?php // no rows found ?>
    	                                            <?php endif; ?>
                                                    <?php if ( have_rows( 'download' ) ) : ?>
                                                        <span class="download-dropdown-container">
                                                            <ul class="download-switch-container<?php if ($count == 1){ ?> no-dropdown<?php } else { ?> dropdown-select<?php } ?>">
                                                                <?php $switchCounter=1; ?>
            	                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
            	                                                    <li class="download-switch <?php if($switchCounter == 1){ ?> active<?php }?>" value="<?php echo get_sub_field( 'download_file_type' ); ?>">
                                                                        <span class="download-switch-span"><?php echo get_sub_field( 'download_file_type' ); ?></span>
                                                                        <a class="download-switcher" href="#<?php echo get_sub_field( 'download_file_type' ); ?>"><?php echo get_sub_field( 'download_file_type' ); ?></a>
                                                                    </li>
                                                                    <?php $switchCounter++; ?>
            	                                                <?php endwhile; ?>
                                                            </ul>
                                                        </span>
    	                                            <?php else : ?>
    	                                                <?php // no rows found ?>
    	                                            <?php endif; ?>
    	                                        </span>
    	                                    </div>
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

        <div class="resources-container mobile">
	        <?php if ( have_rows( 'resource' ) ) : ?>
	            <?php $counter = 0; ?>
					<?php while ( have_rows( 'resource' ) ) : the_row(); ?>
	                    <div class="column <?php echo $counter; ?>">
                            <?php if ( get_sub_field( 'resource_type' ) == 'video') { ?>
                                <?php $type = 'watch'; ?>
                            <?php } else { ?>
                                <?php $type = 'download'; ?>
                            <?php } ?>
	                        <?php if ( get_sub_field( 'resource_type' ) == 'video') { ?>
	                            <a class="popup-vimeo resources-video-popup" href="https://vimeo.com/<?php echo get_sub_field( 'vimeo_code' ); ?>">
	                                <span class="resources-image-container">
	                                    <span class="bg-container">
	                                        <?php $image = get_sub_field( 'image' ); ?>
	                    					<?php if ( $image ) { ?>
	                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
	                    							'alt'     => $image['alt'],
	                    							'loading' => 'lazy',
	                    						) ); ?>
	                    					<?php } ?>
                                            <span class="opacity-overlay">
                                                <span class="video-overlay-button"></span>
                                            </span>

	                                    </span>
	                                </span>
	                        <?php } else { ?>
	                            <a class="resources-popup-button" href="#resourcesPopupMobile<?php echo $counter; ?>">
	                                <span class="resources-image-container">
	                                    <span class="bg-container">
	                                        <?php $image = get_sub_field( 'image' ); ?>
	                    					<?php if ( $image ) { ?>
	                    						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
	                    							'alt'     => $image['alt'],
	                    							'loading' => 'lazy',
	                    						) ); ?>
	                    					<?php } ?>
                                            <span class="opacity-overlay">
                                                <span class="download-overlay-button"></span>
                                            </span>
	                                    </span>
	                                </span>
	                        <?php }?>
	                            <?php if(get_sub_field( 'title' )){ ?>
	                                <?php $title = get_sub_field( 'title' ); ?>
	                                <span class="listing-title"><?php echo get_sub_field( 'title' ); ?></span>
	                            <?php } ?>
                                <span class="type"><?php echo $type; ?></span>
	                        </a>
	                        <div class="resourcesPopupContainer" style="display: none;">
	                            <div class="resourcesPopup" id="resourcesPopupMobile<?php echo $counter; ?>">
	                                <div class="container">
                                        <div class="resources-container">
    	                                    <div class="preview-container">
    	                                        <div class="download-image-container">
    	                                            <span class="resources-image-container">
    	                                                <span class="bg-container">
    	                                                    <?php $image = get_sub_field( 'image' ); ?>
    	                                					<?php if ( $image ) { ?>
    	                                						<?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
    	                                							'alt'     => $image['alt'],
    	                                							'loading' => 'lazy',
    	                                						) ); ?>
    	                                					<?php } ?>
    	                                                </span>
    	                                            </span>
    	                                        </div>
    	                                    </div>
    	                                    <div class="download-container">
    	                                        <span class="download-title">
    	                                            <?php echo $title; ?>
    	                                        </span>
                                                <span class="download-description">
                                                   <?php echo get_sub_field( 'description' ); ?>
                                               </span>
    	                                        <span class="downloads">
                                                    <?php $count = 0;
                                                        $locations = get_sub_field('download');
                                                        if (is_array($locations)) {
                                                          $count = count($locations);
                                                        }
                                                        ?>
    	                                            <?php if ( have_rows( 'download' ) ) : ?>
                                                        <?php $buttonCounter=1; ?>
    	                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
    	                                                    <span class="download-button-container<?php if($buttonCounter == 1){ ?> active<?php }?> <?php echo get_sub_field( 'download_file_type' ); ?>" id="<?php echo get_sub_field( 'download_file_type' ); ?>"><?php echo get_sub_field( 'download' ); ?></span>
                                                            <?php $buttonCounter++; ?>
    	                                                <?php endwhile; ?>
    	                                            <?php else : ?>
    	                                                <?php // no rows found ?>
    	                                            <?php endif; ?>
                                                    <?php if ( have_rows( 'download' ) ) : ?>
                                                        <span class="download-dropdown-container">
                                                            <ul class="download-switch-container <?php if ($count == 1){ ?> <?php } else { ?> dropdown-select<?php } ?>">
                                                                <?php $switchCounter=1; ?>
            	                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
            	                                                    <li class="download-switch <?php if($switchCounter == 1){ ?> active<?php }?>" value="<?php echo get_sub_field( 'download_file_type' ); ?>">
                                                                        <span class="download-switch-span"><?php echo get_sub_field( 'download_file_type' ); ?></span>
                                                                        <a class="download-switcher" href="#<?php echo get_sub_field( 'download_file_type' ); ?>"><?php echo get_sub_field( 'download_file_type' ); ?></a>
                                                                    </li>
                                                                    <?php $switchCounter++; ?>
            	                                                <?php endwhile; ?>
                                                            </ul>
                                                        </span>
    	                                            <?php else : ?>
    	                                                <?php // no rows found ?>
    	                                            <?php endif; ?>
    	                                        </span>
    	                                    </div>
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
    </div>
</section>
