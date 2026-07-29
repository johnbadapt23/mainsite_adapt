<section class="benchmarking-sticky-cards background-light-grey">
    <div class="container">
        <div class="top-content">
            <?php if ( get_sub_field ('title_position') == 'title-left' ) { ?>
                <span class="title-block <?php echo get_sub_field('title_position'); ?>">
                    <?php if ( get_sub_field ( 'title' ) ) { ?><h2 class="text-black bold-red"><?php echo get_sub_field( 'title' ); ?></h2><?php } ?>
                    <?php if ( get_sub_field ( 'sub_title' ) ) { ?><span class="sub-title secondary-dark"><?php echo get_sub_field( 'sub_title' ); ?></span><?php } ?>
                </span>
                <?php if ( get_sub_field ( 'button_position' ) == 'top' ) { ?>
                    <span class="link-container desktop">
                        <?php if ( have_rows( 'button' ) ) : ?>
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                    <a class="stdBtn std-button red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                                    <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                    <?php $file = get_sub_field( 'file' ); ?>
                                    <a class="download-file-button std-button red-outline-button" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                    <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#stickyCardFormPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    <div style="display: none;">         
                                        <div class="preview-cta-form login-form-container" id="stickyCardFormPopup">
                                            <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                        </div>
                                    </div> 
                                <?php } else { ?>                                 
                                    <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#formPopupStickyCards"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    <div style="display: none;">         
                                        <div class="preview-cta-form login-form-container" id="formPopupStickyCards">
                                            <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                        </div>
                                    </div> 
                                <?php } ?>        
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>	
                <?php } ?>    		
            <?php } else { ?>
                <span class="title-block <?php echo get_sub_field('title_position'); ?>">
                    <?php if ( get_sub_field ( 'title' ) ) { ?><h2 class="text-black bold-red"><?php echo get_sub_field( 'title' ); ?></h2><?php } ?>
                    <?php if ( get_sub_field ( 'sub_title' ) ) { ?><span class="sub-title secondary-dark"><?php echo get_sub_field( 'sub_title' ); ?></span><?php } ?>
                </span>                
                <?php if ( get_sub_field ( 'button_position' ) == 'top' ) { ?>
                    <span class="link-container desktop">
                        <?php if ( have_rows( 'button' ) ) : ?>
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                    <a class="stdBtn std-button red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                                    <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                    <?php $file = get_sub_field( 'file' ); ?>
                                    <a class="download-file-button std-button red-outline-button" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                    <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#stickyCardFormPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    <div style="display: none;">         
                                        <div class="preview-cta-form login-form-container" id="stickyCardFormPopup">
                                            <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                        </div>
                                    </div> 
                                <?php } else { ?>                                 
                                    <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#formPopupStickyCards"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    <div style="display: none;">         
                                        <div class="preview-cta-form login-form-container" id="formPopupStickyCards">
                                            <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                        </div>
                                    </div> 
                                <?php } ?>        
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>	
                <?php } ?>    		
            <?php } ?>
		</div>
       <div class="sticky-columns-wrapper desktop">
            <!-- TEXT COLUMN -->
            <div class="text-column">
                <?php if ( have_rows( 'sticky_content' ) ) : ?>
                    <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                        <div class="text-block-wrapper">
                            <div class="text-block">
                                <h4 class="header-medium"><?php echo get_sub_field( 'title' ); ?></h4>
                                <p class="p-large text-dark-grey"><?php the_sub_field( 'text' ); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <!-- IMAGE COLUMN -->
            <div class="image-column">
                <?php if ( have_rows( 'sticky_content' ) ) : ?>
                    <?php $i = 0; ?>
                    <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <div class="sticky-image-container<?php if ( get_sub_field ( 'add_shadow_to_image' ) == 'yes' ) { ?> shadow<?php } ?>" style="--i: <?php echo $i; ?>;">
                                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                            </div>
                        <?php } ?>
                        <?php $i++; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="columns-wrapper mobile">
            <?php if ( have_rows( 'sticky_content' ) ) : ?>
                <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                    <div class="mobile-column">
                        <div class="text-block-wrapper">
                            <div class="text-block">
                                <h4 class="header-medium"><?php echo get_sub_field( 'title' ); ?></h4>
                                <p class="p-medium text-dark-grey"><?php the_sub_field( 'text' ); ?></p>
                            </div>
                        </div>
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <div class="image-container-full">
                                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                            </div>
                        <?php } ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>     
            <span class="link-container">
                <?php if ( have_rows( 'button' ) ) : ?>
                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                            <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="download-file-button std-button red-outline-button" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                            <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="formPopup">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                </div>
                            </div> 
                        <?php } else { ?>                                 
                            <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="formPopup">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                </div>
                            </div> 
                        <?php } ?>        
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
			</span>		                
        </div>   
        <?php if ( get_sub_field ( 'button_position' ) == 'bottom' ) { ?>
                <span class="link-container bottom desktop">
                    <?php if ( have_rows( 'button' ) ) : ?>
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="stdBtn std-button red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                                <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                <?php $file = get_sub_field( 'file' ); ?>
                                <a class="download-file-button std-button red-outline-button" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#stickyCardFormPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="stickyCardFormPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?>                                 
                                <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#formPopupStickyCards"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopupStickyCards">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } ?>        
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </span>	
            <?php } ?>    		
		</div>                    
    </div>
</section>
<script>
function syncTextHeights(attempt = 1) {
    const textWrappers = document.querySelectorAll('.text-block-wrapper');
    const images = document.querySelectorAll('.sticky-image-container');

    let needsRetry = false;

    textWrappers.forEach((wrapper, index) => {
        const img = images[index]?.querySelector('img');
        if (!img) return;

        const h = img.offsetHeight;
        
        if (h === 0) {
            needsRetry = true;
        } else {
            wrapper.style.height = h + 'px';
        }
    });

    // Retry up to 20 times with a short delay
    if (needsRetry && attempt < 20) {
        setTimeout(() => syncTextHeights(attempt + 1), 100);
    }
}

// Run once DOM is ready
document.addEventListener('DOMContentLoaded', () => syncTextHeights());

// Debounced resize
window.addEventListener('resize', () => {
    clearTimeout(window.syncTextTimeout);
    window.syncTextTimeout = setTimeout(() => syncTextHeights(), 100);
});
</script>
