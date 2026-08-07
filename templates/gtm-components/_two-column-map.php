<?php if(get_sub_field( 'background_colour' ) == 'background-white'){ ?>
    <?php $textColour = 'text-black'; ?>
<?php } else { ?>
    <?php $textColour = 'white-text'; ?>
<?php }?>
<section class="left-text-links gtm-map-block advisors-centered-text-links left-text-links-image background-black">    
    <div class="container">  
        <div class="column-container">      
            <div class="text-container column text-column one-half">   
                <span class="inner-text">                        
                    <h1 class="h1-style bold-red primary-white"><?php echo get_sub_field( 'title' ); ?></h1>
                    <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
                </span>
                <div class="mobile-image-container">
                    <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
                    <?php if ( $mobile_image ) { ?>
                        <span class="background-container">
                            <?php echo wp_get_attachment_image( $mobile_image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $mobile_image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        </span>
                    <?php } ?>
                     <?php if ( have_rows( 'cards' ) ) : ?>
                        <span class="mobile-gtm-card-slider">
                            <?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                                <span class="slide">
                                    <span class="gtm-card">
                                        <span class="card-inner">
                                            <span class="corner tr"></span>
                                            <span class="corner bl"></span>
                                            <span class="card-top">
                                                <span class="font-ibm primary-white"><?php echo get_sub_field( 'name' ); ?></span>
                                                <span class="font-ibm tertiary-white"><?php echo get_sub_field( 'company_name' ); ?></span>
                                            </span>
                                            <span class="small-details">
                                                <span class="role"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-role.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'role' ); ?></span>
                                                <span class="industry"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-industry.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'industry' ); ?></span>
                                                <span class="level"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-level.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'level' ); ?></span>
                                            </span>
                                            <span class="bottom-details">
                                                <span class="bottom-detail">
                                                    <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-dollar.svg" alt="" width="32"/></span>
                                                    <span class="detail">
                                                        <span class="detail-title font-ibm tertiary-white">Investment Priorty</span>
                                                        <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'investment_priority' ); ?></span>
                                                    </span>
                                                </span>
                                                <span class="bottom-detail">
                                                    <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-alert.svg" alt="" width="32"/></span>
                                                    <span class="detail">
                                                        <span class="detail-title font-ibm tertiary-white">Top Challenge</span>
                                                        <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'top_challenge' ); ?></span>
                                                    </span>
                                                </span>
                                                <span class="bottom-detail">
                                                    <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-ticket.svg" alt="" width="32"/></span>
                                                    <span class="detail">
                                                        <span class="detail-title font-ibm tertiary-white">Attending</span>
                                                        <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'attending' ); ?></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>	  
                                </span>                          
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
                <span class="links-container">
                    <?php if ( have_rows( 'links' ) ) : ?>
                        <?php $buttonCounter = 1;?>
                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                                <a class="stdBtn std-button scroll-to-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                                <?php $file = get_sub_field( 'file' ); ?>
                                <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo $file['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?>                                 
                                <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'form_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } ?>                     	
                            <?php $buttonCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </span>
            </div>
            <div class="column one-half image-column map-column">
                <div class="map-container">
                    <?php $desktop_image = get_sub_field( 'desktop_image' ); ?>
                    <?php if ( $desktop_image ) { ?>
                        <?php echo wp_get_attachment_image( $desktop_image['ID'], 'adapt-optimized', false, array(
                            'alt'     => $desktop_image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                    <?php if ( have_rows( 'cards' ) ) : ?>
                        <?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                        <?php
                            $left = get_sub_field('percentage_from_left');
                            $origin_class = ($left > 50) ? 'origin-right' : 'origin-left';
                            $top = get_sub_field('percentage_from_top');
                            $origin_top = ($top > 60) ? 'origin-bottom' : 'origin-top';
                            ?>
                            <span class="card-hover-container" style="top: <?php echo get_sub_field( 'percentage_from_top' ); ?>%; left:<?php echo get_sub_field( 'percentage_from_left' ); ?>%;">
                                <span class="card-hover-trigger<?php if ( get_sub_field( 'active_on_load' ) == 1 ) { ?> active<?php } ?>"></span>
                                <span class="gtm-card <?php echo $origin_class; ?> <?php echo $origin_top; ?><?php if ( get_sub_field( 'active_on_load' ) == 1 ) { ?> active<?php } ?>">
                                    <span class="card-inner">
                                        <span class="corner tr"></span>
                                        <span class="corner bl"></span>
                                        <span class="card-top">
                                            <span class="font-ibm primary-white"><?php echo get_sub_field( 'name' ); ?></span>
                                            <span class="font-ibm tertiary-white"><?php echo get_sub_field( 'company_name' ); ?></span>
                                        </span>
                                        <span class="small-details">
                                            <span class="role"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-role.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'role' ); ?></span>
                                            <span class="industry"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-industry.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'industry' ); ?></span>
                                            <span class="level"><span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-level.svg" alt="" width="12"/></span><?php  echo get_sub_field( 'level' ); ?></span>
                                        </span>
                                        <span class="bottom-details">
                                            <span class="bottom-detail">
                                                <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-dollar.svg" alt="" width="32"/></span>
                                                <span class="detail">
                                                    <span class="detail-title font-ibm tertiary-white">Investment Priorty</span>
                                                    <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'investment_priority' ); ?></span>
                                                </span>
                                            </span>
                                            <span class="bottom-detail">
                                                <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-alert.svg" alt="" width="32"/></span>
                                                <span class="detail">
                                                    <span class="detail-title font-ibm tertiary-white">Top Challenge</span>
                                                    <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'top_challenge' ); ?></span>
                                                </span>
                                            </span>
                                            <span class="bottom-detail">
                                                <span class="icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/gtm-ticket.svg" alt="" width="32"/></span>
                                                <span class="detail">
                                                    <span class="detail-title font-ibm tertiary-white">Attending</span>
                                                    <span class="detail-text font-ibm primary-white"><?php  echo get_sub_field( 'attending' ); ?></span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </span>	
                            </span>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


			