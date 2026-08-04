<section class="two-column two-column-logo-carousel">
    <div class="container">
        <div class="column-container">
            <div class="column one-half text-column">
                <span class="text-inner">
                    <h2 class="h1-style"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="p-large"><?php echo get_sub_field( 'text' ); ?></p>               
                    <?php if ( have_rows( 'links' ) ) : ?>
                        <span class="button-container">
                            <?php $buttonCounter = 1;?>
                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                    <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                    <?php $file = get_sub_field( 'file' ); ?>
                                    <a class="scroll-to-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                                 <?php } else if( get_sub_field( 'link_type' ) == 'link_type') { ?>
                                        <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#twoColumnCarousel<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <div style="display: none;">         
                                            <div class="preview-cta-form login-form-container" id="twoColumnCarousel<?php echo $buttonCounter; ?>">
                                                <div class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></div>
                                            </div>
                                        </div>                         
                                    <?php } else { ?> 
                                    <span style="display: none"><?php echo get_sub_field( 'form_code' ); ?></span>
                                    <span class="form-popup-button-container <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>"><?php echo get_sub_field( 'form_button' ); ?></span>                                                               
                                <?php } ?>                     	
                                <?php $buttonCounter++; ?>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </span>
            </div>
            <div class="column one-half carousel-column">
                <div class="carousel-outer">
                    <?php 
                    // Get all carousel rows
                    $logos = get_sub_field( 'carousel' ); 
                    
                    if (is_array($logos)) : 
                        $logo_count = count($logos);
                        $animation_duration = $logo_count * 14; // Adjust this multiplier as needed

                        // Shuffle the logos three times for each column
                        $logos_up1 = $logos;
                        $logos_down = $logos;
                        $logos_up2 = $logos;
                        
                        shuffle($logos_up1); // Shuffle for first "moving-logos-up"
                        shuffle($logos_down); // Shuffle for "moving-logos-down"
                        shuffle($logos_up2); // Shuffle for second "moving-logos-up"
                    ?>

                    <!-- First carousel-column-inner (moving-logos-up) -->
                    <div class="carousel-column-inner">
                        <div class="moving-logos-up" style="animation-duration: <?php echo $animation_duration; ?>s;">
                            <?php foreach ($logos_up1 as $logo_up1) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_up1['logo']; ?>
                                            <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>

                            <!-- Duplicate for seamless scrolling -->
                            <?php foreach ($logos_up1 as $logo_up1) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_up1['logo']; ?>
                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Second carousel-column-inner (moving-logos-down) -->
                    <div class="carousel-column-inner">
                        <div class="moving-logos-down" style="animation-duration: <?php echo $animation_duration; ?>s;">
                            <?php foreach ($logos_down as $logo_down) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_down['logo']; ?>
                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>

                            <!-- Duplicate for seamless scrolling -->
                            <?php foreach ($logos_down as $logo_down) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_down['logo']; ?>
                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Third carousel-column-inner (moving-logos-up) -->
                    <div class="carousel-column-inner">
                        <div class="moving-logos-up" style="animation-duration: <?php echo $animation_duration; ?>s;">
                            <?php foreach ($logos_up2 as $logo_up2) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_up2['logo']; ?>
                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>

                            <!-- Duplicate for seamless scrolling -->
                            <?php foreach ($logos_up2 as $logo_up2) : ?>
                                <span class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $logo = $logo_up2['logo']; ?>
                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                        </span>
                                    </span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php else : ?>        
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>
