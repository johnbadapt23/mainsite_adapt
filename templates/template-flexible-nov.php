<?php
/**
 * Template Name: Flexible Template
 */

get_header();
?>
<main id="main" role="main" class="home<?php if ( get_field ( 'banner_image' ) ) { ?><?php } else{ ?> no-banner-top<?php } ?>">
	<h1 class="pageTitleLine"><?php echo the_title(); ?></h1>
	<?php if ( get_field ( 'banner_image' ) ) { ?>
		<section class="banner">
			<div class="imageContainer">
                <div class="image" style="background-image: url(<?php the_field('banner_image'); ?>);">
                    <div class="container">
						<div class="v-wrap">
							<div class="v-box">
								<?php if ( get_field ( 'banner_title' ) ) { ?>
									<h2 class="pageTitle"><?php the_field('banner_title'); ?></h2>
									<hr>
								<?php } ?>
		                        <?php if ( get_field ( 'banner_subtitle' ) ) { ?>
			                        <h2><?php the_field('banner_subtitle'); ?></h2>
								<?php } ?>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</section>
	<?php } ?>
	<?php if ( get_field ( 'hidden_vimeo_embed_for_yoast' )) { ?>
		<span class="hiddenEmbed" style="display: none;"><?php the_field ( 'hidden_vimeo_embed_for_yoast' );?></span>
	<?php } ?>
	<?php if ( get_field ( 'fixed_menu_select' ) == 'yes' ) { ?>
		<?php if ( have_rows( 'fixed_menu' ) ) : ?>
			<?php get_template_part( 'templates/components/_fixed-menu-block' ); ?>
		<?php endif; ?>
	<?php } ?>
	<?php if ( have_rows( 'content_blocks' ) ): ?>
		<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

			<?php if ( get_row_layout() == 'logo_grid' ) : ?>

				<section class="logoGrid <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="titleBlock">
							<span class="title">
								<h2><?php the_sub_field( 'block_title' ); ?></h2>
							</span>

							<span class="description <?php the_sub_field( 'top_right_text_position' ); ?>">
								<h3><?php the_sub_field( 'top_right_text' ); ?></h3>
							</span>
						</div>

						<?php if ( have_rows( 'logos' ) ) : ?>
							<div class="logoBlock">
								<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
									<div class="logo">
										<span class="logoContainer">
											<div class="image" style="background-image: url(<?php the_sub_field( 'logo' ); ?>);">
											</div>
										</span>
										<span class="logoTitle">
											<?php the_sub_field( 'title' ); ?>
										</span>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>

						<?php if ( get_sub_field ( 'link_url' ) ) { ?>
							<a class="logoBlockLink <?php the_sub_field( 'link_style' ); ?>" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
						<?php } ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'article_content' ) : ?>
				<section class="scrollPos repeatableSingle singlePost" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="post-inner">
							<div class="fullWidth article-content">
								<div class="articleWrapper">
									<?php the_sub_field( 'article_content' ); ?>
								</div>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
				<section class="scrollPos fullImageInfogram" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						 <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
							 <div class="featureBlock">
								 <img class="featureImage" src="<?php the_sub_field( 'image' ); ?>"/>
							 </div>
						 <?php } else { ?>
							 <div class="infogram-container">
								 <?php the_sub_field( 'infogram' ); ?>
							 </div>
						 <?php } ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'related_articles' ) : ?>

	            <section class="relatedArticlesCarousel scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
	                <div class="container">
	                    <div class="inner">
	            			<h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
	            			<?php if ( have_rows( 'related_articles' ) ) : ?>
	                            <div class="owl-carousel articlesCarousel">
	                                <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
	                                    <?php $post_object = get_sub_field( 'article' ); ?>
	                                    <?php if ( $post_object ): ?>
	                                        <?php $post = $post_object; ?>

	                                        <a class="relatedArticle item">
	                                            <?php setup_postdata( $post ); ?>

	                                            <div class="imageContainer">
	                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
	                                                    <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
	                                                        <?php if ( get_field ( 'podcast_file' ) ) { ?>
	                                                            <span class="podcast">
	                                                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
	                                                            </span>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } else { ?>
														<div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
	                                                        <?php if ( get_field ( 'podcast_file' ) ) { ?>
	                                                            <span class="podcast">
	                                                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
	                                                            </span>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
	                                            </div>

	                                            <span class="postDetails">
	                                                <span class="info">
	                                                    <span class="date">
															<?php if( get_field('event_date')) { ?>
 															   <?php the_field('event_date'); ?>
 														   <?php } else { ?>
 															   <?php echo get_the_date('d.m.Y'); ?>
 														   <?php } ?>
	                                                    </span>
	                                                    <span class="readTime">
	                                                        <?php the_field( 'read_time' ); ?>
	                                                    </span>
	                                                </span>

	                                                <span class="articleLink"><?php the_title(); ?></span>

	                                                <?php
	                                                    $post_tags = get_the_tags();
														$count=0;
	                                                ?>
	                                                <?php if ( $post_tags ) { ?>
	                                                    <div class="tags">
	                                                        <?php foreach( $post_tags as $tag ) { $count++; ?>
																<?php if ( $count <= 3 ) { ?>
																	<span>
		                                                                <?php echo '#' . $tag->name  ; ?>
		                                                            </span>
																<?php } ?>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
	                                            </span>

	                                        </a>
	                                        <?php wp_reset_postdata(); ?>
	                                    <?php endif; ?>
	                                <?php endwhile; ?>
	                            </div>
	                        </div>
							<?php if ( have_rows( 'button_block' ) ) : ?>
	                            <div class="buttonBlock">
	                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
	                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
	                                <?php endwhile; ?>
	                            </div>
	                        <?php endif; ?>
	                    </div>
	    			<?php endif; ?>
	            </section>

			<?php elseif ( get_row_layout() == 'related_articles_three_column_block' ) : ?>

				<section class="relatedArticlesThreeColumn scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="column first">
								<h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
								<?php if ( get_sub_field ( 'see_more_link' ) ) { ?>
									<a class="logoBlockLink text" href="<?php the_sub_field( 'see_more_link' ); ?>" target="_self"><?php the_sub_field( 'see_more_link_text' ); ?></a>
								<?php } ?>
							</div>
							<div class="column two">
			                    <?php if ( have_rows( 'related_articles_column_two' ) ) : ?>
			                        <div class="wrapper">
			                        	<?php while ( have_rows( 'related_articles_column_two' ) ) : the_row(); ?>
			                        		<?php $post_object = get_sub_field( 'article' ); ?>
			                        		<?php if ( $post_object ): ?>
			                        			<?php $post = $post_object; ?>

			                                    <div class="relatedArticle">
			                            			<?php setup_postdata( $post ); ?>
													<span class="postDetails<?php if( get_field('read_time')) { ?><?php } else { ?> no-read-time<?php } ?>">
			                                            <span>
															<?php if( get_field('event_date')) { ?>
																<?php the_field('event_date'); ?>
															<?php } else { ?>
																<?php echo get_the_date('d.m.Y'); ?>
															<?php } ?>
			                                            </span>
														<?php if( get_field('read_time')) { ?>
				                                            <span>
				                                                <?php the_field( 'read_time' ); ?>
				                                            </span>
														<?php } ?>
			                                        </span>
			                            			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

													<?php
	                                                    $post_tags = get_the_tags();
	                                                ?>
	                                                <?php if ( $post_tags ) { ?>
	                                                    <div class="tags">
	                                                        <?php foreach( $post_tags as $tag ) { ?>
	                                                            <span>
	                                                                <?php echo '#' . $tag->name  ; ?>
	                                                            </span>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
			                                    </div>
			                                    <?php wp_reset_postdata(); ?>
			                        		<?php endif; ?>
			                        	<?php endwhile; ?>
			                        </div>
			                    <?php endif; ?>
			                </div>
							<?php if ( get_sub_field( 'featured_article_column_three' ) ) { ?>
								<div class="column three">
									<?php $post_object = get_sub_field( 'featured_article_column_three' ); ?>
									<?php if ( $post_object ): ?>
										<?php $post = $post_object; ?>

										<a class="relatedArticle item">
											<?php setup_postdata( $post ); ?>

											<div class="imageContainer">
												<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
													<div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
														<?php if ( get_field ( 'podcast_file' ) ) { ?>
															<span class="podcast">
																<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
															</span>
														<?php } ?>
													</div>
												<?php } else { ?>
													<div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
														<?php if ( get_field ( 'podcast_file' ) ) { ?>
															<span class="podcast">
																<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
															</span>
														<?php } ?>
													</div>
												<?php } ?>
											</div>

											<span class="postDetails">
												<span class="info">
													<span class="date">
														<?php echo get_the_date('d.m.Y'); ?>
													</span>
													<span class="readTime">
														<?php the_field( 'read_time' ); ?>
													</span>
												</span>

												<span class="articleLink"><?php the_title(); ?></span>

												<?php
													$post_tags = get_the_tags();
												?>
												<?php if ( $post_tags ) { ?>
													<div class="tags">
														<?php foreach( $post_tags as $tag ) { ?>
															<span>
																<?php echo '#' . $tag->name  ; ?>
															</span>
														<?php } ?>
													</div>
												<?php } ?>
											</span>
										</a>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
								</div>
							<?php } ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
				<section class="twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<h2><?php the_sub_field( 'title' ); ?></h2>
								<hr>

							</div>
							<div class="textBlock">
								<?php the_sub_field( 'text_block' ); ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'text_image_block' ) : ?>
				<section class="halfHalfBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="textBlock <?php the_sub_field( 'image_position' ); ?>">
						<div class="v-wrap">
							<div class="v-box">
								<h2><?php the_sub_field( 'title' ); ?></h2>
								<hr>
								<?php if ( get_sub_field ( 'text_block' ) ) { ?>
									<span class="desktopText"><?php the_sub_field( 'text_block' ); ?></span>
								<?php } ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink desktop <?php the_sub_field( 'link_style' ); ?>" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="imageBlock <?php the_sub_field( 'image_position' ); ?>">
						<div class="image" style="background-image: url(<?php the_sub_field( 'image' ); ?>);">
						</div>
					</div>
					<div class="textBlock mobile">
						<div class="container">
							<div class="inner">
								<?php if ( get_sub_field ( 'text_block' ) ) { ?>
									<span class="mobileText"><?php the_sub_field( 'text_block' ); ?></span>
								<?php } ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink <?php the_sub_field( 'link_style' ); ?>" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
				<?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
			<?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
				<?php get_template_part( 'templates/components/_full-width-text-editor' ); ?>
			<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

				<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos scrollPos">
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<?php if ( get_sub_field ( 'block_title' ) ) { ?>
									<h2><?php the_sub_field( 'block_title' ); ?></h2>
									<span class="hrWrapper">
										<hr>
									</span>
								<?php } ?>
								<?php if ( get_sub_field ( 'description' ) ) { ?>
									<h3><?php the_sub_field( 'description' ); ?></h3>
								<?php } ?>
							</div>

							<?php if ( have_rows( 'logos' ) ) : ?>
								<div class="gridWrapper">
									<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
										<div class="item">
											<div class="imageContainer">
												<div class="image" style="background-image: url(<?php the_sub_field( 'logo' ); ?>);">
												</div>
											</div>
										</div>
									<?php endwhile; ?>
									<div class="item">
										<div class="v-wrap">
											<div class="v-box">
												<span class="yourLogoHere">Your Company Here</span>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
				<section class="speakerQuoteCarousel scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<h2><?php the_sub_field( 'block_title' ); ?></h2>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php } ?>
							</div>

							<?php if ( have_rows( 'item' ) ) : ?>
								<div class="owl-carousel speaker-gallery">
									<?php while ( have_rows( 'item' ) ) : the_row(); ?>
										<div class="item">
											<div class="imageContainer">
												<div class="image" style="background-image: url(<?php the_sub_field( 'image' ); ?>);">
												</div>
											</div>
											<div class="textBlock">
												<div class="v-wrap">
													<div class="v-box">
														<span class="quoteBlock">
															<?php the_sub_field( 'quote' ); ?>
														</span>
														<span class="quoteAuthor">
															<?php the_sub_field( 'quote_author' ); ?>
														</span>
													</div>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
				<section class="quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">

							<?php if ( have_rows( 'quotes' ) ) : ?>
								<div class="owl-carousel quote">
									<?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
										<div class="item">
											<div class="v-wrap">
												<div class="v-box">
													<span class="quoteBlock">
														<?php the_sub_field( 'quote' ); ?>
													</span>
													<span class="quoteAuthor">
														<?php the_sub_field( 'quote_author' ); ?>
													</span>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
				<section class="twoColumnWithTextAndFeaturedQuote scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="column first">
								<h2>
									<?php the_sub_field( 'title' ); ?>
								</h2>
								<div class="textBlock">
									<?php the_sub_field( 'text_block' ); ?>
								</div>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php } ?>
							</div>

							<div class="column last">
								<div class="item">
									<div class="v-wrap">
										<div class="v-box">
											<span class="quoteBlock">
												<?php the_sub_field( 'quote' ); ?>
											</span>
											<span class="quoteAuthor">
												<?php the_sub_field( 'quote_author' ); ?>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'carousel_block' ) : ?>
				<section class="centerModeCarousel scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<span class="title">
									<h2><?php the_sub_field( 'block_title' ); ?></h2>
									<hr>
								</span>
							</div>
							<?php if ( have_rows( 'items' ) ) : ?>
								<div class="center popup-gallery">
									<?php while ( have_rows( 'items' ) ) : the_row(); ?>

										<?php if ( get_sub_field ( 'image_or_video' ) == 'image' ) { ?>
											<a href="<?php the_sub_field( 'image' ); ?>" class="imageContainer">
												<div class="image" style="background-image: url(<?php the_sub_field( 'image' ); ?>);">
												</div>
											</a>
										<?php } else { ?>
											<a href="<?php the_sub_field('vimeo_code'); ?>" class="video" id="video" playsinline="" webkit-playsinline="" loop="" controls>
				                                <source src="<?php the_sub_field('vimeo_code'); ?>" type="video/mp4"></source>
				                            </a>
										<?php } ?>

									<?php endwhile; ?>
								</div>

							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'speaker_block' ) : ?>

				<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos">
					<div class="container">
						<div class="inner">
							<h2><?php the_sub_field( 'block_title' ); ?></h2>

							<?php if ( have_rows( 'speakers' ) ) : ?>
								<div class="gridWrapper">
									<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

										<?php $post_object = get_sub_field( 'speaker' ); ?>
										<?php if ( $post_object ): ?>
											<?php $post = $post_object; ?>
											<?php setup_postdata( $post ); ?>
												<a href="<?php the_permalink(); ?>" class="item">
													<?php if ( get_field( 'speaker_image') ) { ?>
														<div class="imageContainer">
															<div class="image" style="background-image: url(<?php the_field( 'speaker_image' ); ?>);">
															</div>
														</div>
													<?php } ?>
													<hr>
													<span class="title"><?php the_title(); ?></span>
													<span class="description">
														<?php the_field( 'speaker_description' ); ?>
													</span>
													<?php if ( get_field( 'logo') ) { ?>
														<div class="logoContainer">
															<img loading="lazy" src="<?php the_field( 'logo' ); ?>" alt="Adapt" />
														</div>
													<?php } ?>
												</a>
											<?php wp_reset_postdata(); ?>
										<?php endif; ?>

									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
							<div class="buttonBlock">
								<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
									<a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
				<?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
			<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

			<?php elseif ( get_row_layout() == 'hierarchical_logo_block' ) : ?>

				<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<h2><?php the_sub_field( 'block_title' ); ?></h2>
								<hr>
								<h3><?php the_sub_field( 'description' ); ?></h3>
							</div>

							<?php if ( have_rows( 'logo_group' ) ) : ?>
								<div class="logoGroup">
									<?php while ( have_rows( 'logo_group' ) ) : the_row(); ?>
										<div class="title">
											<h3><?php the_sub_field( 'group_title' ); ?></h3>
										</div>
										<?php if ( have_rows( 'logos' ) ) : ?>
											<div class="gridWrapper heirarchy">
												<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
													<div class="item">
														<div class="imageContainer">
															<div class="image" style="background-image: url(<?php the_sub_field( 'logo' ); ?>);">
															</div>
														</div>
													</div>
												<?php endwhile; ?>
											</div>
										<?php endif; ?>
									<?php endwhile; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>

				<section class="textImageBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="title">
								<h2><?php the_sub_field( 'block_title' ); ?></h2>
								<hr>
							</div>
							<?php if ( have_rows( 'item' ) ) : ?>
								<div class="itemsWrapper">
									<?php while ( have_rows( 'item' ) ) : the_row(); ?>
										<div class="item">
											<?php if ( get_sub_field( 'image') ) { ?>
												<a href="<?php the_sub_field('link_url'); ?>" target="<?php the_sub_field('link_target'); ?>" class="imageContainer">
													<div class="image" style="background-image: url(<?php the_sub_field( 'image' ); ?>);">
													</div>
												</a>
											<?php } ?>
											<span class="title"><?php the_sub_field( 'title' ); ?></span>
											<span class="description">
												<?php the_sub_field( 'text' ); ?>
											</span>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'video_block' ) : ?>
				 <?php get_template_part( 'templates/components/_video-block' ); ?>
			<?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
				 <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>

			<?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>

				<section class="imageGridBlock standard scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<h2><?php the_sub_field( 'block_title' ); ?></h2>
							<?php if ( have_rows( 'item' ) ) : ?>
								<div class="gridWrapper">
									<?php while ( have_rows( 'item' ) ) : the_row(); ?>
										<div class="item">
											<?php if ( get_sub_field( 'image') ) { ?>
												<div class="imageContainer">
													<div class="image" style="background-image: url(<?php the_sub_field( 'image' ); ?>);">
													</div>
												</div>
											<?php } ?>
											<hr>
											<span class="title">
												<?php the_sub_field( 'title' ); ?>
											</span>
											<span class="description">
												<?php the_sub_field( 'description' ); ?>
											</span>
											<?php if ( get_sub_field( 'logo') ) { ?>
												<div class="logoContainer">
													<img loading="lazy" src="<?php the_sub_field( 'logo' ); ?>" alt="Adapt" />
												</div>
											<?php } ?>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'form_block' ) : ?>

				<section class="formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
					<div class="container">
						<div class="inner">
							<div class="formWrapper register">
								<?php if ( get_sub_field ( 'block_title' ) ) { ?>
									<h2><?php the_sub_field('block_title'); ?></h2>
									<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
										<hr>
									<?php } ?>
								<?php } ?>
								<?php if ( get_sub_field ( 'block_description' ) ) { ?>
									<h3><?php the_sub_field('block_description'); ?></h3>
								<?php } ?>
								<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
									<?php the_sub_field('form_shortcode'); ?>
								<?php }?>
								<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
									<a class="button popup-modal" href="#<?php the_sub_field('form_id'); ?>"><?php the_sub_field('button_text'); ?></a>
									<div class="formPopup mfp-hide" id="<?php the_sub_field('form_id'); ?>">
										<a class="popup-modal-dismiss"></a>
										<?php if ( get_sub_field ( 'block_title' ) ) { ?>
											<h2><h2><?php the_sub_field('block_title'); ?></h2></h2>
										<?php } ?>
										<?php if ( get_sub_field ( 'block_description' ) ) { ?>
											<h3><?php the_sub_field('block_description'); ?></h3>
										<?php } ?>
											<div class="formWrapper register"><?php the_sub_field('form_shortcode'); ?></div>
									</div>
								<?php }?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'related_articles_grid_block' ) : ?>
				<?php get_template_part( 'templates/components/_related-articles-grid-block' ); ?>

			<?php elseif ( get_row_layout() == 'counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'membership_block' ) : ?>
				<?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
					<section class="pricingBlock scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
						<div class="container">
							<h2>Membership</h2>
							<?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem first">
									<div class="innerWrapper">
										<?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
											<span class="title">
												<?php the_sub_field( 'title', 'option' ); ?>
												<span class="hrWrapper">
													<hr>
												</span>
											</span>
											<span class="priceBlockWrapper">
												<span class="priceBlock">
													<span class="dollar">$</span><?php the_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
												</span>
												<span class="priceBlock annual">
													<span class="dollar">$</span><?php the_sub_field( 'price_annually', 'option' ); ?><span class="month">/annually</span>
												</span>
											</span>
											<?php if ( have_rows( 'features', 'option' ) ) : ?>
												<div class="features">
													<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
														<span class="feature"><?php the_sub_field( 'feature', 'option' ); ?></span>
													<?php endwhile; ?>
												</div>
											<?php endif; ?>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php the_sub_field( 'button_link', 'option' ); ?>" target="<?php the_sub_field( 'button_target', 'option' ); ?>"><?php the_sub_field( 'button_text', 'option' ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

							<?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem featured">

									<?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
										<div class="innerWrapper">
											<div class="featuredWrapper">
												<span class="title">
													<?php the_sub_field( 'title', 'option' ); ?>
													<span class="hrWrapper">
														<hr>
													</span>
												</span>
												<span class="priceBlockWrapper">
													<span class="priceBlock">
														<span class="dollar">$</span><?php the_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
													</span>
													<span class="priceBlock annual">
														<span class="dollar">$</span><?php the_sub_field( 'price_annually', 'option' ); ?><span class="month">/annually</span>
													</span>
												</span>
												<?php if ( have_rows( 'features', 'option' ) ) : ?>
													<div class="features">
														<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
															<span class="feature"><?php the_sub_field( 'feature', 'option' ); ?></span>
														<?php endwhile; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php the_sub_field( 'button_link', 'option' ); ?>" target="<?php the_sub_field( 'button_target', 'option' ); ?>"><?php the_sub_field( 'button_text', 'option' ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

							<?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem last">
									<?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
										<div class="innerWrapper">
											<span class="title">
												<?php the_sub_field( 'title', 'option' ); ?>
												<span class="hrWrapper">
													<hr>
												</span>
											</span>
											<span class="priceBlockWrapper">
												<span class="priceBlock">
													<span class="dollar">$</span><?php the_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
												</span>
												<span class="priceBlock annual">
													<span class="dollar">$</span><?php the_sub_field( 'price_annually', 'option' ); ?><span class="month">/annually</span>
												</span>
											</span>
											<?php if ( have_rows( 'features', 'option' ) ) : ?>
												<div class="features">
													<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
														<span class="feature"><?php the_sub_field( 'feature', 'option' ); ?></span>
													<?php endwhile; ?>
												</div>
											<?php endif; ?>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php the_sub_field( 'button_link', 'option' ); ?>" target="<?php the_sub_field( 'button_target', 'option' ); ?>"><?php the_sub_field( 'button_text', 'option' ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</section>
				<?php } ?>

			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
