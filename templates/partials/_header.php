
<?php if ( get_field( 'hide_header_and_footer' ) == 1 ) { ?>
<?php } else { ?> 
<?php $q = get_queried_object(); ?>
<?php $accessLink = get_field( 'access_the_portal_link', 'options'  ); ?>
<?php $typePost = get_field( 'post_type' ); ?>
<?php $headerType = get_field( 'header_type' ); ?>
<?php $typePost = ''; ?>
<?php if ( is_post_type_archive('media') ) { ?>
	<?php $typePost = 'media-list'; ?>
<?php } ?>
<?php if($q->taxonomy == 'topic'){ ?>
	<header class="header clear" role="banner">
<?php } else { ?>
	<header class="header clear<?php if($typePost == 'insights' || $typePost == 'expert' || $typePost == 'media-list' ){ ?> background-black<?php } ?><?php if(isset($q->slug) && $q->slug == 'in-the-news'){ ?> background-secondary-light-grey<?php } ?><?php if($headerType == 'dark-header'){ ?> background-black<?php } ?><?php if($headerType == 'pink-header'){ ?> background-pink<?php } ?><?php if(isset($q->slug) && $q->slug == 'in-the-news'){ ?> background-black<?php } ?>" role="banner">
<?php }?>
	<div class="container">
		<span class="header-inner">
			<span class="logo-container">
				<span class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logoHeader">
						<?php $imagedark = get_field( 'icon', 'options' ); ?>
						<img class="dark" src="<?php echo $imagedark['url']; ?>" alt="<?php echo $imagedark['alt'] ?: 'Adapt'; ?>" />
						<?php $imageLight = get_field( 'logo_dark_theme', 'options' ); ?>
						<img class="light" src="<?php echo $imageLight['url']; ?>" alt="<?php echo $imageLight['alt'] ?: 'Adapt'; ?>" />
					</a>
				</span>
			</span>
			<span class="headerRight">
				<span class="menu">
					<?php get_template_part( 'templates/partials/_mega-main-menu' ); ?>
				</span>
				<span class="menu-buttons">
					<?php if ( have_rows( 'login_link', 'options' ) ) : ?>
						<?php while ( have_rows( 'login_link', 'options' ) ) : the_row(); ?>
							<a href="<?php echo get_sub_field('link'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="client-login">
								<?php echo get_sub_field('link_text'); ?>
							</a>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
					<?php if ( have_rows( 'discovery_link', 'options' ) ) : ?>
						<?php while ( have_rows( 'discovery_link', 'options' ) ) : the_row(); ?>
							<a href="<?php echo get_sub_field('link'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="booking-button">
								<?php echo get_sub_field('link_text'); ?>
							</a>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</span>
				<span class="buttonWrapper">
		            <a class="nav">
		                <span class="ham"></span>
		            </a>
		    	</span>
			</span>
		</span>
	</div>
	<?php
	$linkedInLink = get_field( 'linked_in', 'options'  );
	$youtubeLink = get_field( 'you_tube', 'options'  );
	?>
	<div class="mobileMenu mobileMenuMain">
		<div class="mobileInner">
			<div class="container">
				<span class="menu">
					<?php get_template_part( 'templates/partials/_mega-main-menu-mobile' ); ?>
				</span>
			</div>
			<div class="menu-bottom">
				<span class="menu-buttons">
					<?php if ( have_rows( 'discovery_link', 'options' ) ) : ?>
						<?php while ( have_rows( 'discovery_link', 'options' ) ) : the_row(); ?>
							<a href="<?php echo get_sub_field('link'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="booking-button std-button red-button">
								<?php echo get_sub_field('link_text'); ?>
							</a>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
					<?php if ( have_rows( 'login_link', 'options' ) ) : ?>
						<?php while ( have_rows( 'login_link', 'options' ) ) : the_row(); ?>
							<a href="<?php echo get_sub_field('link'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="client-login std-button red-outline-button">
								<?php echo get_sub_field('link_text'); ?>
							</a>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</span>
			</div>
		</div>
	</div>
	<div class="mobileMenu mobileMenuResources">
		<div class="resources-menu-top">
			<div class="container">
				<span class="back-container">
					<a class="back resources-lisiting" href="/all-resources/">Resources</a>
				</span>
				<span class="close-container">
					<button type="button" class="close-menu"></button>
				</span>
			</div>
		</div>
		<div class="mobileInner">
			<div class="main-links-container">
				<div class="container">
					<span class="logo">
						<a href="/all-resources" class="logoHeader">
							<?php $imagedarksmall = get_field( 'logo_small', 'options' ); ?>
							<img loading="lazy" class="dark" src="<?php echo $imagedarksmall['url']; ?>" alt="<?php echo $imagedarksmall['alt']; ?>" />
						</a>
						<a class="resources-link-mobile" href="/all-resources" target="_self">Resources</a>
					</span>
					<span class="adapt-link-container">
						<a class="adapt-link" href="/">ADAPT</a>
					</span>
				</div>
			</div>
			<div class="container">
				<form action="/search-results" method="get">
					<input class="searchInput" type="text" name="searchWords" id="mobilesearch" placeholder="Search" value="" />
					<button type="submit" class="search-button-mobile"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify-placeholder.svg" alt="" width="20"/></button>
					<input type="hidden" value="1" name="sentence" />
					<a class="search-clear" onclick="ClearFields();"></a>
				</form>
				<div class="resources-nav">
					<ul>
						<li class="resource-mobile-dropdown topics-menu">
							<a>Topics</a>
							<div class="sub-menu">
	                            <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
	                            	<?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
		                                <div class="sub-menu-inner">
	                                        <?php if ( have_rows( 'group' ) ) : ?>
	                                			<?php while ( have_rows( 'group' ) ) : the_row(); ?>
	                                                <span class="dropDownSection">
	            										<span class="columnTitle">
	                                                        <?php echo get_sub_field( 'title' ); ?>
	                                                    </span>
	                                                    <?php if ( have_rows( 'link' ) ) : ?>
	                                                        <ul>
	                                        					<?php while ( have_rows( 'link' ) ) : the_row(); ?>
	                                        						<?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
	                                        						<?php if ( $topic_link_term ): ?>
	                                                                    <li>
	                                            							<a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
	                                                                    </li>
	                                        						<?php endif; ?>
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
						</li>
						<li class="resource-mobile-dropdown reports-menu">
							<a>Reports</a>
							<div class="sub-menu">
								<div class="sub-menu-inner">
									<ul>
										<li>
											<a href="/resource-type/market-trend-reports/">Market Trend Reports</a>
										</li>
										<!-- <li>
											<a href="/resource-type/best-practices-guides/">Best Practices Guides</a>
										</li> -->
									</ul>
								</div>
							</div>
						</li>
						<li class="single-link">
							<a class="articles-link" href="/resource-type/articles" target="_self">Articles</a>
						</li>
						<li class="single-link">
							<a class="peer-insights-link" href="/resource-type/peer-insights" target="_self">Peer Insights</a>
						</li>
						<li class="single-link">
							<a class="peer-insights-link" href="/resource-type/expert-presentations" target="_self">Expert Presentations</a>
						</li>
						<!-- <li class="single-link">
							<a class="news-link" href="/resource-type/in-the-news" target="_self">In the News</a>
						</li>
						<li class="single-link">
							<a class="podcast-link" href="#" target="_self">Podcast</a>
						</li> -->
					</ul>
				</div>
				<?php if ( have_rows( 'resources_column_two', 'options' ) ) : ?>
					<?php while ( have_rows( 'resources_column_two', 'options' ) ) : the_row(); ?>
						<div class="mobile-menu-bottom">
							<div class="subscribe-sidebar-form mobile-menu-subscribe-form background-pink">
								<span class="icon-container">
									<span class="icon-inner">
										<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
									</span>
								</span>
								<span class="form-content">
									<h5 class="labelMedium text-red"><?php echo get_sub_field( 'title' ); ?></h5>
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
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- Resources Sticky Menu -->
	<?php if ( isset($q->taxonomy) && $q->taxonomy == 'topic' ) { ?>
	<div class="resources-sticky-menu">
	<?php } else { ?>
		<div class="resources-sticky-menu
			<?php if ($typePost == 'insights') { ?> background-black<?php } ?>
			<?php if (isset($q->slug) && $q->slug == 'peer-insights') { ?> background-black<?php } ?>
		">
	<?php } ?>
		<div class="container">
			<div class="resources-sticky-inner">
				<span class="scroll-image-container">
					<span class="logo">
						<a href="/all-resources" class="logoHeader">
							<?php $imagedarksmall = get_field( 'logo_small', 'options' ); ?>
							<img loading="lazy" class="dark" src="<?php echo $imagedarksmall['url']; ?>" alt="<?php echo $imagedarksmall['alt']; ?>" />
							<?php $imageLightSmall = get_field( 'logo_small_dark_theme', 'options' ); ?>
							<img loading="lazy" class="light" src="<?php echo $imageLightSmall['url']; ?>" alt="<?php echo $imageLightSmall['alt']; ?>" />
						</a>
						<a class="resources-link-mobile" href="/all-resources" target="_self">Resources</a>
					</span>
				</span>
				<div class="resources-nav">
					<ul>
						<li class="single-link">
							<a class="resources-link" href="/all-resources" target="_self">Resources</a>
						</li>
						<li class="dropdown topics-menu">
							<a>Topics</a>
							<div class="megaMenu topicsMenu" id="topics">
								<!-- <span class="mobile-menu-title">Research & Advisory</span> -->
								<div class="container">
		                            <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
		                            	<?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
			                                <div class="column one-third first">
		                                        <?php if ( have_rows( 'group' ) ) : ?>
		                                			<?php while ( have_rows( 'group' ) ) : the_row(); ?>
		                                                <span class="dropDownSection">
		            										<span class="columnTitle">
		                                                        <?php echo get_sub_field( 'title' ); ?>
		                                                    </span>
		                                                    <?php if ( have_rows( 'link' ) ) : ?>
		                                                        <ul>
		                                        					<?php while ( have_rows( 'link' ) ) : the_row(); ?>
		                                        						<?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
		                                        						<?php if ( $topic_link_term ): ?>
		                                                                    <li>
		                                            							<a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
		                                                                    </li>
		                                        						<?php endif; ?>
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
		                            <?php if ( have_rows( 'topics_column_two', 'option' ) ) : ?>
		                            	<?php while ( have_rows( 'topics_column_two', 'option' ) ) : the_row(); ?>
		    								<div class="column one-third second">
												<span class="column-title">
													<?php echo get_sub_field( 'title' ); ?>
												</span>
												<?php if ( have_rows( 'featured_posts' ) ) : ?>
													<span class="featured-posts-container">
														<?php while ( have_rows( 'featured_posts' ) ) : the_row(); ?>
															<span class="featured-post">
															<?php $post_object = get_sub_field( 'post' ); ?>
															<?php if ( $post_object ): ?>
																<?php $post = $post_object; ?>
																<?php setup_postdata( $post ); ?>
																	<a href="<?php the_permalink(); ?>">
																		<span class="post-image-container">
																			<span class="image-container">
																				<span class="bg-container">
																					<?php $video_poster_image = get_field( 'video_poster' ); ?>
							                                                        <?php if ( $video_poster_image ) { ?>
							                                                        	<?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
							                                                        		'alt'     => $video_poster_image['alt'],
							                                                        		'loading' => 'lazy',
							                                                        	) ); ?>
							                                                        <?php } else { ?>
																						<?php $featured_image = get_field( 'featured_image' ); ?>
													                                    <?php if ( $featured_image ) { ?>
													                                    	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
													                                    		'alt'     => $featured_image['alt'],
													                                    		'loading' => 'lazy',
													                                    	) ); ?>
													                                    <?php } ?>
																					<?php }?>
																				</span>
																			</span>
																		</span>
																		<span class="post-title-container">
																			<span class="featured-title"><?php the_title(); ?></span>
																		</span>
																	</a>
																<?php wp_reset_postdata(); ?>
																</span>
															<?php endif; ?>
														<?php endwhile; ?>
													</span>
												<?php else : ?>
													<?php // no rows found ?>
												<?php endif; ?>
		    								</div>
		                                <?php endwhile; ?>
		                            <?php else : ?>
		                                <?php // no rows found ?>
		                            <?php endif; ?>
		                            <?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
		                            	<?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
		    								<div class="column one-third third">
												<span class="column-title">
													<?php echo get_sub_field( 'title' ); ?>
												</span>
												<?php if ( have_rows( 'featured_posts' ) ) : ?>
													<span class="featured-posts-container">
														<?php while ( have_rows( 'featured_posts' ) ) : the_row(); ?>
															<span class="featured-post">
															<?php $post_object = get_sub_field( 'post' ); ?>
															<?php if ( $post_object ): ?>
																<?php $post = $post_object; ?>
																<?php setup_postdata( $post ); ?>
																	<a href="<?php the_permalink(); ?>">
																		<span class="post-image-container">
																			<span class="image-container">
																				<span class="bg-container">
																					<?php $video_poster_image = get_field( 'video_poster' ); ?>
							                                                        <?php if ( $video_poster_image ) { ?>
							                                                        	<?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
							                                                        		'alt'     => $video_poster_image['alt'],
							                                                        		'loading' => 'lazy',
							                                                        	) ); ?>
							                                                        <?php } else { ?>
																						<?php $featured_image = get_field( 'featured_image' ); ?>
													                                    <?php if ( $featured_image ) { ?>
													                                    	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
													                                    		'alt'     => $featured_image['alt'],
													                                    		'loading' => 'lazy',
													                                    	) ); ?>
													                                    <?php } ?>
																					<?php }?>
																				</span>
																			</span>
																		</span>
																		<span class="post-title-container">
																			<span class="featured-title"><?php the_title(); ?></span>
																		</span>
																	</a>
																<?php wp_reset_postdata(); ?>
																</span>
															<?php endif; ?>
														<?php endwhile; ?>
													</span>
												<?php else : ?>
													<?php // no rows found ?>
												<?php endif; ?>
		    								</div>
		                                <?php endwhile; ?>
		                            <?php else : ?>
		                                <?php // no rows found ?>
		                            <?php endif; ?>
								</div>
							</div>
						</li>
						<li class="dropdown reports-menu">
							<a>Reports</a>
							<div class="megaMenu reportsMenu" id="reports">
								<!-- <span class="mobile-menu-title">Research & Advisory</span> -->
								<div class="container">
									<?php if ( have_rows( 'reports_column_one', 'option' ) ) : ?>
		                            	<?php while ( have_rows( 'reports_column_one', 'option' ) ) : the_row(); ?>
		    								<div class="column full-column first">
												<span class="column-title">
													<?php echo get_sub_field( 'title' ); ?>
												</span>
												<?php if ( have_rows( 'featured_posts' ) ) : ?>
													<span class="featured-posts-container">
														<?php while ( have_rows( 'featured_posts' ) ) : the_row(); ?>
															<span class="featured-post">
															<?php $post_object = get_sub_field( 'post' ); ?>
															<?php if ( $post_object ): ?>
																<?php $post = $post_object; ?>
																<?php setup_postdata( $post ); ?>
																	<a href="<?php the_permalink(); ?>">
																		<span class="post-image-container">
																			<span class="image-container">
																				<span class="bg-container">
																					<?php $video_poster_image = get_field( 'video_poster' ); ?>
							                                                        <?php if ( $video_poster_image ) { ?>
							                                                        	<?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
							                                                        		'alt'     => $video_poster_image['alt'],
							                                                        		'loading' => 'lazy',
							                                                        	) ); ?>
							                                                        <?php } else { ?>
																						<?php $featured_image = get_field( 'featured_image' ); ?>
													                                    <?php if ( $featured_image ) { ?>
													                                    	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
													                                    		'alt'     => $featured_image['alt'],
													                                    		'loading' => 'lazy',
													                                    	) ); ?>
													                                    <?php } ?>
																					<?php }?>
																				</span>
																			</span>
																		</span>
																		<span class="post-title-container">
																			<span class="featured-title"><?php the_title(); ?></span>
																		</span>
																	</a>
																<?php wp_reset_postdata(); ?>
																</span>
															<?php endif; ?>
														<?php endwhile; ?>
													</span>
												<?php else : ?>
													<?php // no rows found ?>
												<?php endif; ?>
												<span class="all-container">
													<a class="text-link red-text red-link red-underline-link" href="/resource-type/market-trend-reports/" target="_self">All Market Trend Reports</a>
												</span>
		    								</div>
		                                <?php endwhile; ?>
		                            <?php else : ?>
		                                <?php // no rows found ?>
		                            <?php endif; ?>
									<?php if ( have_rows( 'reports_column_two', 'option' ) ) : ?>
		                            	<?php while ( have_rows( 'reports_column_two', 'option' ) ) : the_row(); ?>
		    								<div class="column one-half second">
												<span class="column-title">
													<?php echo get_sub_field( 'title' ); ?>
												</span>
												<?php if ( have_rows( 'featured_posts' ) ) : ?>
													<span class="featured-posts-container">
														<?php while ( have_rows( 'featured_posts' ) ) : the_row(); ?>
															<span class="featured-post">
															<?php $post_object = get_sub_field( 'post' ); ?>
															<?php if ( $post_object ): ?>
																<?php $post = $post_object; ?>
																<?php setup_postdata( $post ); ?>
																	<a href="<?php the_permalink(); ?>">
																		<span class="post-image-container">
																			<span class="image-container">
																				<span class="bg-container">
																					<?php $video_poster_image = get_field( 'video_poster' ); ?>
							                                                        <?php if ( $video_poster_image ) { ?>
							                                                        	<?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
							                                                        		'alt'     => $video_poster_image['alt'],
							                                                        		'loading' => 'lazy',
							                                                        	) ); ?>
							                                                        <?php } else { ?>
																						<?php $featured_image = get_field( 'featured_image' ); ?>
													                                    <?php if ( $featured_image ) { ?>
													                                    	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
													                                    		'alt'     => $featured_image['alt'],
													                                    		'loading' => 'lazy',
													                                    	) ); ?>
													                                    <?php } ?>
																					<?php }?>
																				</span>
																			</span>
																		</span>
																		<span class="post-title-container">
																			<span class="featured-title"><?php the_title(); ?></span>
																		</span>
																	</a>
																<?php wp_reset_postdata(); ?>
																</span>
															<?php endif; ?>
														<?php endwhile; ?>
													</span>
												<?php else : ?>
													<?php // no rows found ?>
												<?php endif; ?>
												<span class="all-container">
													<a class="text-link red-text red-link red-underline-link" href="/resource-type/best-practices-guides/" target="_self">All Best Practices</a>
												</span>
		    								</div>
		                                <?php endwhile; ?>
		                            <?php else : ?>
		                                <?php // no rows found ?>
		                            <?php endif; ?>
								</div>
							</div>
						</li>
						<li class="single-link">
							<a class="articles-link" href="/resource-type/articles" target="_self">Articles</a>
						</li>
						<li class="single-link">
							<a class="peer-insights-link" href="/resource-type/peer-insights" target="_self">Peer Insights</a>
						</li>
						<li class="single-link">
							<a class="peer-insights-link" href="/resource-type/expert-presentations" target="_self">Expert Presentations</a>
						</li>
						<!-- <li class="single-link">
							<a class="news-link" href="/resource-type/in-the-news" target="_self">In the News</a>
						</li> -->
						<li class="single-link">
							<a class="podcast-link" href="/resource-type/podcast/" target="_self">Podcast</a>
						</li>
					</ul>
				</div>
				<div class="sticky-menu-right">
					<span class="search-button-container">
						<?php if($typePost == 'insights' || $typePost == 'expert' ){ ?>
							<a class="search-button" href="#searchDropdown"><img loading="lazy" class="search-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/search-image-white.svg" alt="Search" width="28"/></a>
						<?php } else if($q && ( $q->slug == 'peer-insights' || $q->slug == 'expert-presentations') ){ ?>
							<a class="search-button" href="#searchDropdown"><img loading="lazy" class="search-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/search-image-white.svg" alt="Search" width="28"/></a>
						<?php } else { ?>
							<a class="search-button" href="#searchDropdown"><img loading="lazy" class="search-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/search-image.svg" alt="Search" width="28"/></a>
						<?php } ?>
					</span>
					<span class="subscribe-button-container">
						<span class="form-popup-button-container subscribe-popup-button"><?php echo get_field( 'form_button_subscribe', 'options' ); ?></span>
					</span>
				</div>
			</div>
			<span class="buttonWrapperResources">
				<a class="navResources">
					<span class="ham"></span>
				</a>
			</span>
		</div>
		<?php if ( is_single() ) { ?>
			<div class="single-post-sticky">
				<div class="container">
					<span class="buttonWrapperSingleResources">
						<a class="navSingleResources">
							<span class="ham"></span>
						</a>
					</span>
					<span class="title-container">
						<span class="labelLarge"><?php the_title(); ?></span>
					</span>
					<span class="right-container">
						<span class="share-container">
							<span class="share-title">Share</span>
							<span class="share-links-container">
								<span class="copy-link share">
		                            <input type="text" value="<?php echo the_permalink(); ?>" id="postLink" style="display: none;">
		                            <a onclick="copyJobLink()">
										<span class="image-icon-container">
		                                    <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link.svg" alt="Copy link" width="24px"/>
		                                    <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link-hover.svg" alt="Copy link" width="24px"/>
											<span class="job-link-text"></span>
										</span>
		                            </a>
		                        </span>
		                        <script>
		                            function copyJobLink() {
		                        		// Get the text field
		                        		var copyText = document.getElementById("postLink");

		                        		// Select the text field
		                        		copyText.select();
		                        		copyText.setSelectionRange(0, 99999); // For mobile devices

		                        		// Copy the text inside the text field
		                        		navigator.clipboard.writeText(copyText.value);
		                                jQuery('.copy-link .job-link-text').html('Copied');
		                                jQuery('.copy-link .job-link-text').addClass('text-red');
		                        	}
		                        </script>
								<span class="share-linked-in share">
									<a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
										<span class="image-icon-container">
	                                        <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="24px"/>
											<img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" alt="Share on LinkedIn" width="24px"/>
										</span>
									</a>
								</span>
								<span class="share-twitter share">
									<a class="twitterShare" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&text=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
										<span class="image-icon-container">
	                                        <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-black.svg" alt="Tweet" width="24px"/>
											<img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-hover.svg" alt="Tweet" width="24px"/>
										</span>
									</a>
								</span>
								<span class="share-email share">
									<a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
										<span class="image-icon-container">
	                                        <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="24px"/>
											<img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" alt="Share via Email" width="24px"/>
									</a>
								</span>
							</span>
						</span>
						<span class="subscribe-button-container">
							<span class="form-popup-button-container subscribe-popup-button"><?php echo get_field( 'form_button_subscribe', 'options' ); ?></span>
						</span>
					</span>
				</div>
				<div class="progress-container">
				  <span class="progress-bar"></span>
				</div>
			</div>
		<?php } ?>
	</div>

</header>
<div class="search-dropdown" id="searchDropdown">
	<div class="container">
		<div class="column logo-column">
			<span class="logo-title-container">
				<span class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logoHeader">
						<?php $imagedarksmall = get_field( 'logo_small', 'options' ); ?>
						<img loading="lazy" class="dark" src="<?php echo $imagedarksmall['url']; ?>" alt="<?php echo $imagedarksmall['alt']; ?>" />
					</a>
				</span>
				<span class="search-title">
					<a class="resources-link" href="/all-resources" target="_self">Resources</a>
				</span>
			</span>
		</div>
		<div class="column search-link">
			<span class="search-container">
				<form action="/search-results" method="get">
					<input class="searchInput" type="text" name="searchWords" id="search" placeholder="Search for insights" value="" />
					<input type="hidden" value="1" name="sentence" />
				</form>
			</span>
			<span class="search-column-container">
				<?php if ( have_rows( 'search_link_column', 'options' ) ) : ?>
					<?php while ( have_rows( 'search_link_column', 'options' ) ) : the_row(); ?>
						<span class="column search-link-column one-third">
							<span class="column-title"><?php echo get_sub_field( 'column_title' ); ?></span>
							<?php if ( have_rows( 'links' ) ) : ?>
								<?php while ( have_rows( 'links' ) ) : the_row(); ?>
									<?php if (get_sub_field( 'link_type' ) == 'type'){ ?>
										<?php $link_term = get_sub_field( 'type_link' ); ?>
									<?php } else { ?>
										<?php $link_term = get_sub_field( 'topic_link' ); ?>
									<?php } ?>
									<?php if ( $link_term ): ?>
										<a class="search-link" href="<?php echo get_term_link($link_term); ?>"><?php echo $link_term->name; ?></a>
									<?php endif; ?>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
						</span>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</span>
		</div>
		<script>
		function ClearFields() {
		     document.getElementById("search").value = "";
			 document.getElementById("mobilesearch").value = "";
			 document.getElementById("searchClear").classList.remove("active");
		}
		</script>
		<span class="close-clear-container" id="searchClear">
			<button type="button" class="search-close"></button>
			<a class="search-clear" onclick="ClearFields();">Clear</a>
		</span>
	</div>
</div>
<?php }?>