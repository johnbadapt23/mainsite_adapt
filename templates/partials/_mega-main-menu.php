<div class="main-nav">
    <ul>
        <?php if ( have_rows( 'it_leaders', 'options' ) ) : ?>
            <?php while ( have_rows( 'it_leaders', 'options' ) ) : the_row(); ?>
                <?php 
                    $itTitle = get_sub_field( 'title' );
                    $itLink = get_sub_field( 'title_link' );
                    $itText = get_sub_field( 'text' );
                    $itIcon = get_sub_field( 'icon' ); 
                    $itOverview = get_sub_field( 'overview_link' );                    
                    $itOverviewTitle = get_sub_field( 'overview_title' );
                    $itOverviewText = get_sub_field( 'overview_text' );
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
                    $techLink = get_sub_field( 'title_link' );
                    $techText = get_sub_field( 'text' );
                    $techIcon = get_sub_field( 'icon' ); 
                    $techOverview = get_sub_field( 'overview_link' );
                    $techOverviewTitle = get_sub_field( 'overview_title' );
                    $techOverviewText = get_sub_field( 'overview_text' );
                    $techOverviewImage = get_sub_field( 'overview_image' );
                ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <?php if($techTitle ||  $itTitle ) { ?>
            <li class="dropdown services-menu">
                <a>Our Services</a>
                <div class="megaMenu servicesMenu" id="servicesMenu">
                    <div class="container">
                        <div class="column one-half title-column">
                            <span class="it-leaders-switch services-hover active">
                                <span class="icon-container">
                                    <?php if ( $itIcon ) { ?>
                                        <img src="<?php echo $itIcon['url']; ?>" alt="<?php echo $itIcon['alt']; ?>" />
                                    <?php } ?>
                                </span>
                                <span class="title-container">
                                    <?php if ( $itLink ) { ?>
                                        <a href="<?php echo $itLink; ?>" target="_self">
                                    <?php } ?>
                                    <span class="hover-title">
                                        <?php echo $itTitle; ?>
                                    </span>
                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                    <?php if ( $itLink ) { ?>
                                        </a>
                                    <?php } ?>
                                </span>
                                <span class="switch-text labelSmall"><?php echo $itText; ?></span>
                            </span>
                            <span class="tech-leaders-switch services-hover">
                                <span class="icon-container">
                                    <?php if ( $techIcon ) { ?>
                                        <img loading="lazy" src="<?php echo $techIcon['url']; ?>" alt="<?php echo $techIcon['alt']; ?>" />
                                    <?php } ?>
                                </span>
                                <span class="title-container">
                                    <?php if ( $techLink ) { ?>
                                        <a href="<?php echo $techLink; ?>" target="_self">
                                    <?php } ?>
                                    <span class="hover-title">
                                        <?php echo $techTitle; ?>
                                    </span>
                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                     <?php if ( $techLink ) { ?>
                                        </a>
                                    <?php } ?>
                                </span>
                                <span class="switch-text labelSmall"><?php echo $techText; ?></span>
                            </span>
                        </div>
                        <div class="column one-half content-column">
                            <div class="services-content-inner">
                                <?php if ( have_rows( 'it_leaders', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'it_leaders', 'options' ) ) : the_row(); ?>
                                        <div class="it-content services-content active" id="itLeaders">
                                            <div class="overview-container">
                                                <a href="<?php echo $itOverview; ?>" target="_self">
                                                    <span class="background-pink overview-container-inner">
                                                        <?php if ( $itOverviewImage ) { ?>
                                                            <span class="overview-image">
                                                                <img loading="lazy" src="<?php echo $itOverviewImage['url']; ?>" alt="<?php echo $itOverviewImage['alt']; ?>" />
                                                            </span>
                                                            <span class="overview-content">
                                                                <span class="text-black labelLarge"><?php echo $itOverviewTitle; ?></span>
                                                                <span class="text-dark-grey labelSmall"><?php echo $itOverviewText; ?></span>
                                                                <span class="link-container">
                                                                    <span class="text-link red-underline-link medium-text-link red-text">Learn more</span>
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="links-container">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <?php $compare = 'no-compare'; ?>
                                                        <?php if ( get_sub_field( 'see_how_we_compare' ) == 1 ) { ?> 
                                                            <?php $compare = 'compare'; ?>
                                                        <?php } ?>
                                                        <span class="dropDownSection <?php echo $compare; ?>">
                                                            <?php if (get_sub_field( 'link' )) { ?>
                                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                                            <?php } ?>                                                                
                                                                <?php if($compare == 'compare'){ ?> 
                                                                    <span class="compare-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                    <span class="columnTitle">
                                                                        <?php echo get_sub_field( 'title' ); ?>
                                                                        <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                                    </span>
                                                                <?php } else { ?> 
                                                                    <span class="columnTitle small-margin">
                                                                        <?php echo get_sub_field( 'title' ); ?>
                                                                        <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                                    </span>
                                                                    <span class="columnText no-margin">
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
                                            
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'technology_vendors', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'technology_vendors', 'options' ) ) : the_row(); ?>
                                        <div class="tech-content services-content" id="techVendors">
                                            <div class="overview-container">
                                                <a href="<?php echo $techOverview; ?>" target="_self">
                                                    <span class="background-pink overview-container-inner">
                                                        <?php if ( $techOverviewImage ) { ?>
                                                            <span class="overview-image">
                                                                <img loading="lazy" src="<?php echo $techOverviewImage['url']; ?>" alt="<?php echo $techOverviewImage['alt']; ?>" />
                                                            </span>
                                                            <span class="overview-content">
                                                                <span class="text-black labelLarge"><?php echo $techOverviewTitle; ?></span>
                                                                <span class="text-dark-grey labelSmall"><?php echo $techOverviewText; ?></span>
                                                                <span class="link-container">
                                                                    <span class="text-link red-underline-link medium-text-link red-text">Learn more</span>
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="links-container">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <span class="dropDownSection">
                                                            <?php if (get_sub_field( 'link' )) { ?>
                                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                                            <?php } ?>
                                                                <span class="columnTitle small-margin">
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                                </span>
                                                                <span class="columnText no-margin">
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
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
        <?php $helpText = get_field( 'how_we_help_text', 'options' ); ?>
        <?php if ( have_rows( 'help_menu_column', 'options' ) ) : ?>
            <li class="dropdown how-help-menu">
                <a>How We Help</a>
                <div class="megaMenu howHelpMenu" id="howHelpMenu">
                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                    <div class="container">
                        <div class="column one-third text-column">
                            <span class="intro-text"><?php echo $helpText; ?>
                        </div>
                        <?php while ( have_rows( 'help_menu_column', 'options' ) ) : the_row(); ?>
                            <div class="column one-third">
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
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <?php if ( have_rows( 'who_we_help_menu_column', 'options' ) ) : ?>
            <li class="dropdown who-help-menu">
                <a>Who We Help</a>
                <div class="megaMenu whoHelpMenu" id="whoHelpMenu">
                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                    <div class="container">
                        <?php while ( have_rows( 'who_we_help_menu_column', 'options' ) ) : the_row(); ?>
                            <div class="column one-half">
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
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <li class="dropdown events-menu">
            <a>Events</a>
            <div class="megaMenu eventsMenu" id="eventsMenu">
                <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                <div class="container">
                    <?php if ( have_rows( 'events_column_one', 'options' ) ) : ?>
                        <?php while ( have_rows( 'events_column_one', 'options' ) ) : the_row(); ?>
                            <div class="column one-half">
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <span class="dropDownSection">
                                            <?php if (get_sub_field( 'link' )) { ?>
                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                            <?php } ?>
                                                <span class="columnTitle small-margin">
                                                    <?php echo get_sub_field( 'title' ); ?>
                                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                </span>
                                                <span class="columnText no-margin">
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
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'events_column_two', 'options' ) ) : ?>
                        <?php while ( have_rows( 'events_column_two', 'options' ) ) : the_row(); ?>
                            <div class="column one-half">
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <span class="dropDownSection">
                                            <span class="columnTitle">
                                                <?php echo get_sub_field( 'title' ); ?>
                                            </span>
                                            <?php if ( have_rows( 'links' ) ) : ?>
                                                <ul>
                                                    <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                        <li class="events-links-container">
                                                            <a class="external-link-event" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank">
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
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
                <?php if ( have_rows( 'events_bottom', 'options' ) ) : ?>
                    <div class="menu-bottom">
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
         <li class="dropdown resources-menu">
            <a>Resources</a>
            <div class="megaMenu resourcesMenu" id="resourcesMenu">
                <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                <div class="container">
                    <?php if ( have_rows( 'resources_column_one', 'options' ) ) : ?>
                        <?php while ( have_rows( 'resources_column_one', 'options' ) ) : the_row(); ?>
                            <div class="column one-half">
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <span class="dropDownSection">
                                            <?php if (get_sub_field( 'title_link' )) { ?>
                                                <a class="title-link black-text" href="<?php echo get_sub_field( 'title_link' ); ?>" target="_self">
                                            <?php } ?>
                                                <span class="columnTitle small-margin">
                                                    <?php echo get_sub_field( 'title' ); ?>
                                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                </span>
                                                <span class="columnText">
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
                                                        <?php $topic_link_term = get_sub_field( 'type' );?>
                                                        <?php $linkText = get_sub_field( 'link_text' );?>
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
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'resources_column_two', 'options' ) ) : ?>
                    	<?php while ( have_rows( 'resources_column_two', 'options' ) ) : the_row(); ?>
                            <div class="column one-half">
                                <div class="subscribe-sidebar-form background-pink">
                                    <span class="icon-container">
                                        <span class="icon-inner">
                                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                        </span>
                                    </span>
                					<h5 class="labelMedium text-red"><?php echo get_sub_field( 'title' ); ?></h5>
                					<p class="text-black"><?php echo get_sub_field( 'text' ); ?></p>
                                    <?php if ( have_rows( 'button' ) ) : ?>
                            			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                							<a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                						<?php endwhile; ?>
                					<?php else : ?>
                						<?php // no rows found ?>
                					<?php endif; ?>
                				</div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <li class="dropdown customer-menu">
            <a>Customers</a>
            <div class="megaMenu customerMenu" id="customerMenu">
                <div class="container">
                    <div class="column one-half">
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
                                                <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
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
                    </div>
                    <div class="column one-half">
                        <?php if ( have_rows( 'customer_stories_menu_column_two', 'options' ) ) : ?>
                            <?php while ( have_rows( 'customer_stories_menu_column_two', 'options' ) ) : the_row(); ?>
                                <span class="dropDownSection">
                                    <?php if ( have_rows( 'group' ) ) : ?>
                                        <?php while ( have_rows( 'group' ) ) : the_row(); ?>  
                                            <span class="logo-link-column-container column-container test">                                                                      
                                                <span class="link-logo-column column">
                                                    <?php if (get_sub_field( 'link' )) { ?>
                                                        <a href="<?php echo get_sub_field( 'link' ); ?>" target="_self">
                                                    <?php } ?>
                                                    <?php $logo = get_sub_field( 'logo' ); ?>
                                                    <span class="logo-tile" style="background-color: <?php echo get_sub_field( 'tile_background_colour' ); ?>">
                                                        <?php if ( $logo ) { ?>
                                                            <img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
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
                </div>
            </div>
        </li>   
       
        <?php if ( have_rows( 'adapt_column_one', 'options' ) ) : ?>
            <li class="dropdown adapt-menu">
                <a>ADAPT</a>
                <div class="megaMenu adaptMenu" id="adaptMenu">
                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                    <div class="container">
                        <?php while ( have_rows( 'adapt_column_one', 'options' ) ) : the_row(); ?>
                            <div class="column full-width">
                                <?php if ( have_rows( 'group' ) ) : ?>
                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                        <span class="dropDownSection">
                                            <a href="<?php echo get_sub_field( 'title_link' ); ?>" target="_self">
                                                <span class="columnTitle small-margin">
                                                    <?php echo get_sub_field( 'title' ); ?>
                                                    <span class="hover-icon-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-arrow-red.svg" alt=""/></span>
                                                </span>
                                            </a>
                                            <span class="columnText">
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
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </li>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </ul>
</div>
