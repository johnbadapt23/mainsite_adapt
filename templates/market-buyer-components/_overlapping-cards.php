<section class="overlapping-cards market-sticky-cards background-radial">
    <div class="container">
        
        <div class="title-container">
            <span class="column">
                <h2 class="bold-red text-white"><?php echo get_sub_field('title'); ?></h2>
            </span>
            <span class="column">
                <span class="text text-white"><?php echo get_sub_field('text'); ?></span>
            </span>
        </div>

        <div class="market-sticky-cards-container">

            <!-- SIDEBAR NAVIGATION -->
            <div class="side-bar-navigation">
                <?php if (have_rows('cards')): ?>
                    <ul>
                        <?php 
                        $counter = 1;
                        while (have_rows('cards')): the_row(); 
                        ?>
                            <li class="market-sticky-cards-nav-item" data-card="<?php echo $counter; ?>">
                                <span class="label"><?php ; ?><?php echo get_sub_field('side_label'); ?></span>
                               
                                <?php if ( have_rows( 'link' ) ) : ?>
                                    <?php $buttonCounter = 1;?>
                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                            <a class="red-text red-underline-link red-arrow text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                            <?php $file = get_sub_field( 'file' ); ?>
                                            <a class="red-text text-link red-underline-link download-text-link" href="<?php echo $file['url']; ?>" target="_blank"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                            <a class="formPopupHubspot red-text text-link red-underline-link download-text-link" href="#formPopup<?php echo $counter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                            <div style="display: none;">         
                                                <div class="preview-cta-form login-form-container" id="formPopup<?php echo $counter; ?>">
                                                    <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                                </div>
                                            </div> 
                                        <?php } else { ?> 
                                                <a class="formPopupHubspot red-text text-link red-underline-link download-text-link" href="#formPopup<?php echo $counter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                            <div style="display: none;">         
                                                <div class="preview-cta-form login-form-container" id="formPopup<?php echo $counter; ?>">
                                                    <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                                </div>
                                            </div> 
                                        <?php } ?>                     	
                                        <?php $buttonCounter++; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>                
                            </li>
                        <?php 
                        $counter++;
                        endwhile; 
                        ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- CARD CONTENT -->
            <?php if (have_rows('cards')): ?>
                <div class="market-sticky-cards-wrapper">
                <?php 
                $counter = 1;
                while (have_rows('cards')): the_row(); 
                ?>
                    <div class="market-sticky-card" data-card="<?php echo $counter; ?>">
                        <div class="column-container">
                            <div class="column text-column">
                                <div class="text-inner">
                                    <h2 class="bold-red text-white"><?php echo get_sub_field('card_title'); ?></h2>
                                    <span class="text p-large text-white">
                                        <?php echo get_sub_field('card_text'); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="column image-column">
                                <div class="image-container square">
                                    <?php $image = get_sub_field('image'); ?>
                                    <?php if ($image): ?>
                                        <img src="<?php echo $image['url']; ?>"
                                                alt="<?php echo $image['alt']; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                $counter++;
                endwhile; 
                ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>