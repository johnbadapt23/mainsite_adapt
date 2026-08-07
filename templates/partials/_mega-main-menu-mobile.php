<div class="main-nav-mobile">
    <ul>
        <div class="services-container">
            <span class="mobile-services-dropdown">
                Our Services
            </span>
            <div class="services-inner-mobile">
                <?php if ( have_rows( 'it_leaders', 'options' ) ) : ?>
                    <?php while ( have_rows( 'it_leaders', 'options' ) ) : the_row(); ?>
                        <?php 
                            $itTitle = get_sub_field( 'title' );
                            $itIcon = get_sub_field( 'icon' ); 
                            $itText = get_sub_field( 'text' );
                            $itOverview = get_sub_field( 'overview_link' );                    
                            $itOverviewTitle = get_sub_field( 'overview_title' );                           
                            $itOverviewImage = get_sub_field( 'overview_image' );
                        ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'technology_vendors', 'options' ) ) : ?>
                    <?php while ( have_rows( 'technology_vendors', 'options' ) ) : the_row(); ?>
                        <?php 
                            $techTitle = get_sub_field( 'title' );
                            $techIcon = get_sub_field( 'icon' ); 
                            $techText = get_sub_field( 'text' );
                            $techOverview = get_sub_field( 'overview_link' );
                            $techOverviewTitle = get_sub_field( 'overview_title' );                            
                            $techOverviewImage = get_sub_field( 'overview_image' );
                        ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'it_leaders', 'options' ) ) : ?>
                    <?php while ( have_rows( 'it_leaders', 'options' ) ) : the_row(); ?>
                        <li class="main-dropdown services-menu it-leaders-menu">
                            <a><span class="icon-container">
                                    <?php if ( $itIcon ) { ?>
                                        <?php echo wp_get_attachment_image( $itIcon['ID'], 'adapt-optimized', false, array(
                                            'alt'     => $itIcon['alt'],
                                            'loading' => false,
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                                <span class="services-inner-title">
                                    <span><?php echo $itTitle; ?></span>
                                    <span class="services-inner-text labelSmall text-dark-grey"><?php echo $itText; ?></span>
                                </span>
                            </a>
                            <div class="mobile-sub-menu servicesMenu">
                                <div class="sub-menu-top">
                                    <div class="container">
                                        <span class="back-container">
                                            <span class="mobile-menu-title"><?php echo $itTitle; ?></span>
                                        </span>
                                    </div>
                                </div>
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <div class="overview-container">
                                        <a href="<?php echo $itOverview; ?>" target="_self">
                                            <span class="background-pink overview-container-inner">
                                                <?php if ( $itOverviewImage ) { ?>
                                                    <span class="overview-image">
                                                        <?php echo wp_get_attachment_image( $itOverviewImage['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $itOverviewImage['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    </span>
                                                    <span class="overview-content">
                                                        <span class="text-black labelLarge"><?php echo $itOverviewTitle; ?></span>
                                                        <span class="link-container">
                                                            <span class="text-link red-underline-link medium-text-link red-text">Learn more</span>
                                                        </span>
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        </a>
                                    </div>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <?php $compare = 'no-compare'; ?>
                                        <?php if ( get_sub_field( 'see_how_we_compare' ) == 1 ) { ?> 
                                            <?php $compare = 'compare'; ?>
                                        <?php } ?>
                                        <span class="dropDownSection small-text-section <?php echo $compare; ?>">
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                            <?php } ?>
                                                <?php if($compare == 'compare'){ ?> 
                                                    <span class="compare-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                    <span class="columnTitle">
                                                        <?php echo get_sub_field( 'title' ); ?>
                                                    </span>
                                                <?php } else { ?> 
                                                    <span class="columnTitle small-margin">
                                                        <?php echo get_sub_field( 'title' ); ?>
                                                    </span>
                                                    <span class="columnText">
                                                        <?php echo get_sub_field( 'text' ); ?>
                                                    </span>
                                                <?php } ?>                                                 
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'technology_vendors', 'options' ) ) : ?>
                    <?php while ( have_rows( 'technology_vendors', 'options' ) ) : the_row(); ?>
                        <li class="main-dropdown services-menu tech-vendors-menu">
                            <a><span class="icon-container">
                                    <?php if ( $techIcon ) { ?>
                                        <?php echo wp_get_attachment_image( $techIcon['ID'], 'adapt-optimized', false, array(
                                            'alt'     => $techIcon['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                                <span class="services-inner-title">
                                    <span><?php echo $techTitle; ?></span>
                                    <span class="services-inner-text labelSmall text-dark-grey"><?php echo $techText; ?></span>
                                </span>
                            </a>
                            <div class="mobile-sub-menu servicesMenu">
                                <div class="sub-menu-top">
                                    <div class="container">
                                        <span class="back-container">
                                            <span class="mobile-menu-title"><?php echo $techTitle; ?></span>
                                        </span>
                                    </div>
                                </div>
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <div class="overview-container">
                                        <a href="<?php echo $techOverview; ?>" target="_self">
                                            <span class="background-pink overview-container-inner">
                                                <?php if ( $techOverviewImage ) { ?>
                                                    <span class="overview-image">
                                                        <?php echo wp_get_attachment_image( $techOverviewImage['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $techOverviewImage['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    </span>
                                                    <span class="overview-content">
                                                        <span class="text-black labelLarge"><?php echo $techOverviewTitle; ?></span>
                                                        <span class="link-container">
                                                            <span class="text-link red-underline-link medium-text-link red-text">Learn more</span>
                                                        </span>
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        </a>
                                    </div>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <span class="dropDownSection small-text-section">
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                            <?php } ?>
                                                <span class="columnTitle small-margin">
                                                    <?php echo get_sub_field( 'title' ); ?>
                                                </span>
                                                <span class="columnText">
                                                    <?php echo get_sub_field( 'text' ); ?>
                                                </span>
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
        <?php $helpText = get_field( 'how_we_help_text', 'options' ); ?>
        <?php if ( have_rows( 'help_menu_column', 'options' ) ) : ?>
            <li class="main-dropdown how-help-menu">
                <a>How We Help</a>
                <div class="mobile-sub-menu  howHelpMenu">
                    <div class="sub-menu-top">
            			<div class="container">
            				<span class="back-container">
                                <span class="mobile-menu-title">How We Help</span>
            				</span>
            			</div>
            		</div>
                    <span class="intro-text"><?php echo $helpText; ?>
                    <?php while ( have_rows( 'help_menu_column', 'options' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'group' ) ) : ?>
                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                <span class="dropDownSection">
                                    <span class="columnTitle">
                                        <?php echo get_sub_field( 'title' ); ?>
                                    </span>
                                    <?php if ( have_rows( 'links' ) ) : ?>
                                        <ul>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'all_link' ) ) : ?>
                                        <span class="all-link-container">
                        					<?php while ( have_rows( 'all_link' ) ) : the_row(); ?>
                        						<a class="all-link text-link red-text red-link red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                        					<?php endwhile; ?>
                                        </span>
                    				<?php else : ?>
                    					<?php // no rows found ?>
                    				<?php endif; ?>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <?php if ( have_rows( 'who_we_help_menu_column', 'options' ) ) : ?>
            <li class="main-dropdown who-help-menu">
                <a>Who We Help</a>
                <div class="mobile-sub-menu whoHelpMenu">
                    <div class="sub-menu-top">
            			<div class="container">
            				<span class="back-container">
                                <span class="mobile-menu-title">Who We Help</span>
            				</span>
            			</div>
            		</div>
                    <?php while ( have_rows( 'who_we_help_menu_column', 'options' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'group' ) ) : ?>
                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                <span class="dropDownSection">
                                    <span class="columnTitle">
                                        <?php echo get_sub_field( 'title' ); ?>
                                    </span>
                                    <?php if ( have_rows( 'links' ) ) : ?>
                                        <ul>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <li class="main-dropdown events-menu">
            <a>Events</a>
            <div class="mobile-sub-menu eventsMenu">
                <div class="sub-menu-top">
                    <div class="container">
                        <span class="back-container">
                            <span class="mobile-menu-title">Events</span>
                        </span>
                    </div>
                </div>
                <?php if ( have_rows( 'events_column_one', 'options' ) ) : ?>
                    <?php while ( have_rows( 'events_column_one', 'options' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'group' ) ) : ?>
                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                <span class="dropDownSection small-text-section">
                                    <?php if (get_sub_field( 'link' )) { ?>
                                        <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                    <?php } ?>
                                        <span class="columnTitle small-margin">
                                            <?php echo get_sub_field( 'title' ); ?>
                                        </span>
                                        <span class="columnText">
                                            <?php echo get_sub_field( 'text' ); ?>
                                        </span>
                                    <?php if (get_sub_field( 'link' )) { ?>
                                        </a>
                                    <?php } ?>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'events_column_two', 'options' ) ) : ?>
                        <?php while ( have_rows( 'events_column_two', 'options' ) ) : the_row(); ?>
                            <?php if ( have_rows( 'group' ) ) : ?>
                                <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                    <span class="dropDownSection links-dropdown-section">
                                        <span class="columnTitle">
                                            <?php echo get_sub_field( 'title' ); ?>
                                        </span>
                                        <?php if ( have_rows( 'links' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                    <li>
                                                        <a class="events-links" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank" rel="noopener noreferrer">
                                                            <?php echo get_sub_field( 'link_text' ); ?>
                                                            <?php if(get_sub_field('link_month')){ ?><span class="event-month text-red"><?php echo get_sub_field('link_month'); ?></span><?php } ?>
                                                        </a>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'events_bottom', 'options' ) ) : ?>
                        <div class="sub-menu-bottom">
                            <div class="menu-bottom-inner">
                                <?php while ( have_rows( 'events_bottom', 'options' ) ) : the_row(); ?>
                                    <span class="events-bottom-text"><?php echo get_sub_field( 'text' ); ?></span>
                            		<?php if ( have_rows( 'link' ) ) : ?>
                                        <span class="events-bottom-link-container">
                                			<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                				<a class="text-link external-link red-text red-link red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                			<?php endwhile; ?>
                                        </span>
                            		<?php else : ?>
                            			<?php // no rows found ?>
                            		<?php endif; ?>
                                <?php endwhile; ?>
                            </div>
                        </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </li>
        <li class="main-dropdown resources-menu">
            <a>Resources</a>
            <div class="mobile-sub-menu resourcesMenu">
                <div class="sub-menu-top">
                    <div class="container">
                        <span class="back-container">
                            <span class="mobile-menu-title">Resources</span>
                        </span>
                    </div>
                </div>
                <?php if ( have_rows( 'resources_column_one', 'options' ) ) : ?>
                    <?php while ( have_rows( 'resources_column_one', 'options' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'group' ) ) : ?>
                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                <span class="dropDownSection">
                                    <?php if (get_sub_field( 'title_link' )) { ?>
                                        <a class="title-link black-text" href="<?php echo get_sub_field( 'title_link' ); ?>" target="_self">
                                    <?php } ?>
                                        <span class="columnTitle small-margin">
                                            <?php echo get_sub_field( 'title' ); ?>
                                        </span>
                                        <span class="columnText large-margin">
                                            <?php echo get_sub_field( 'text' ); ?>
                                        </span>
                                    <?php if (get_sub_field( 'title_link' )) { ?>
                                        </a>
                                    <?php } ?>
                                    <?php if (get_sub_field( 'sub_title' )) { ?>
                                        <span class="columnTitle small-margin subTitle">
                                            <?php echo get_sub_field( 'sub_title' ); ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ( have_rows( 'links' ) ) : ?>
                                        <ul>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <?php $linkText = get_sub_field( 'link_text' );?>
                                                <?php $topic_link_term = get_sub_field( 'type' );?>
                                                <?php if ( $topic_link_term ): ?>
                                                    <li>
                                                        <a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $linkText; ?></a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'all_link' ) ) : ?>
                                        <span class="all-link-container">
                        					<?php while ( have_rows( 'all_link' ) ) : the_row(); ?>
                        						<a class="all-link text-link red-text red-link red-underline-link underlined-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                        					<?php endwhile; ?>
                                        </span>
                    				<?php else : ?>
                    					<?php // no rows found ?>
                    				<?php endif; ?>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'resources_column_two', 'options' ) ) : ?>
                	<?php while ( have_rows( 'resources_column_two', 'options' ) ) : the_row(); ?>
                        <div class="full-width">
                            <div class="form-container-inner">
                                <div class="subscribe-sidebar-form mobile-menu-subscribe-form background-pink">
                                    <span class="icon-container">
                                        <span class="icon-inner">
                                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                        </span>
                                    </span>
                                    <span class="form-content">
                    					<span class="labelMedium text-red"><?php echo get_sub_field( 'title' ); ?></span>
                    					<p class="text-black"><?php echo get_sub_field( 'text' ); ?></p>
                                        <?php if ( have_rows( 'button' ) ) : ?>
                                			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    							<a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                    						<?php endwhile; ?>
                    					<?php else : ?>
                    						<?php // no rows found ?>
                    					<?php endif; ?>
                                    </span>
                				</div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </li>
         <li class="main-dropdown customer-menu">
            <a>Customers</a>
            <div class="mobile-sub-menu customerMenu">
                <div class="sub-menu-top">
                    <div class="container">
                        <span class="back-container">
                            <span class="mobile-menu-title">Customers</span>
                        </span>
                    </div>
                </div>
                <?php if ( have_rows( 'customer_stories_menu_column', 'options' ) ) : ?>
                    <?php while ( have_rows( 'customer_stories_menu_column', 'options' ) ) : the_row(); ?>
                        <span class="dropDownSection">
                            <?php if ( have_rows( 'group' ) ) : ?>
                                <?php while ( have_rows( 'group' ) ) : the_row(); ?>                                
                                    <?php if (get_sub_field( 'link' )) { ?>
                                        <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                    <?php } ?>
                                        <span class="columnTitle small-margin">
                                            <?php echo get_sub_field( 'title' ); ?>
                                        </span>
                                        <span class="columnText">
                                            <?php echo get_sub_field( 'text' ); ?>
                                        </span>
                                    <?php if (get_sub_field( 'link' )) { ?>
                                        </a>
                                    <?php } ?>                                                                                   
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( get_sub_field( 'overview_link' )) { ?> 
                                <span class="all-link-container">
                                    <a class="all-link text-link red-text red-link red-underline-link underlined-link" href="<?php echo get_sub_field( 'overview_link'); ?>" target="_self">All Stories</a>
                                </span>
                            <?php } ?>                                    
                        </span>
                    <?php endwhile; ?>   
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>                     
                <?php if ( have_rows( 'customer_stories_menu_column_two', 'options' ) ) : ?>
                    <?php while ( have_rows( 'customer_stories_menu_column_two', 'options' ) ) : the_row(); ?>
                        <span class="dropDownSection">
                            <?php if ( have_rows( 'group' ) ) : ?>
                                <?php while ( have_rows( 'group' ) ) : the_row(); ?>  
                                    <span class="logo-link-column-container column-container test">                                                                      
                                        <span class="link-logo-column column">
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                <?php // Decorative duplicate of the title-link below (same href) --
                                                      // hidden from assistive tech and the tab order so it isn't
                                                      // announced twice with no name of its own. ?>
                                                <a href="<?php echo get_sub_field( 'link' ); ?>" target="_self" aria-hidden="true" tabindex="-1">
                                            <?php } ?>
                                            <?php $logo = get_sub_field( 'logo' ); ?>
                                            <span class="logo-tile" style="background-color: <?php echo get_sub_field( 'tile_background_colour' ); ?>">
                                                <?php if ( $logo ) { ?>
                                                    <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $logo['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                        <span class="link-text-column column">
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                            <?php } ?>
                                            <span class="columnTitle small-margin">
                                                <?php echo get_sub_field( 'title' ); ?>
                                            </span>
                                            <span class="columnText">
                                                <?php echo get_sub_field( 'text' ); ?>
                                            </span>
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                </a>
                                            <?php } ?>   
                                        </span>
                                    </span>                                                                            
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>                                                                
                        </span>                            
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </li>   
        <?php if ( have_rows( 'adapt_column_one', 'options' ) ) : ?>
            <li class="main-dropdown adapt-menu">
                <a>ADAPT</a>
                <div class="mobile-sub-menu adaptMenu">
                    <div class="sub-menu-top">
            			<div class="container">
            				<span class="back-container">
                                <span class="mobile-menu-title">ADAPT</span>
            				</span>
            			</div>
            		</div>
                    <?php while ( have_rows( 'adapt_column_one', 'options' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'group' ) ) : ?>
                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                <span class="dropDownSection">
                                    <a href="<?php echo get_sub_field( 'title_link' ); ?>" target="_self">
                                        <span class="columnTitle small-margin">
                                            <?php echo get_sub_field( 'title' ); ?>
                                        </span>
                                    </a>
                                    <span class="columnText large-margin">
                                        <?php echo get_sub_field( 'text' ); ?>
                                    </span>
                                    <?php if ( have_rows( 'links' ) ) : ?>
                                        <ul>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </ul>
</div>
