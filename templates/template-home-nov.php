<?php
/**
 * Template Name: Home Template (Nov)
 *
 * Renamed so this is distinguishable from templates/template-home.php in the
 * wp-admin page-template dropdown -- both previously declared the identical
 * "Home Template" name, making it impossible to tell them apart when
 * assigning a page template. No functional change.
 */

get_header();
?>
<?php
	$bannerSlides = get_field('banner_slides');
	$bannerButtons = get_field('banner_buttons');
?>

<?php if ( get_field ( 'show_loader' ) == 'yes' ) { ?>
	<span class="loading">
	    <img class="spinner" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logo-white-red.svg" alt="Adapt Logo" />
	</span>
<?php } ?>

<main id="main" role="main" class="home<?php if ( get_field ( 'does_the_page_have_a_banner' ) == 'no' ) { ?> noBanner<?php } ?>">
	<h1 class="pageTitleLine"><?php echo the_title(); ?></h1>
	<?php if ( get_field ( 'back_button' ) == 'yes' ) { ?>
		<section class="backContainer">
			<div class="container">
				<a class="back-button" href="<?php the_field('back_button_link');?>">Back</a>
			</div>
		</section>
	<?php } ?>
	<?php if($bannerSlides) { ?>
		<section class="banner">
			<ul class="slides">
				<?php foreach($bannerSlides as $slide) { ?>
					<li style="background-image:url(<?php echo $slide['image']; ?>);">
						<?php if( $slide['dark_overlay'] == 'yes') { ?>
							<span class="dark-overlay"></span>
						<?php } ?>
						<img loading="lazy" src="<?php echo $slide['image']; ?>" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" alt="Adapt - <?php echo the_title(); ?>" />
						<div class="container">
							<div class="content <?php echo $slide['text_layout']; ?>">
								<?php if($slide['title']) { ?>
									<div class="column title">
										<span class="title"><?php echo $slide['title']; ?></span>
									</div>
								<?php } ?>
								<?php if($slide['text']) { ?>
									<div class="column text">
										<span class="text"><?php echo $slide['text']; ?></span>
									</div>
								<?php } ?>
								<?php if($slide['buttons']) { ?>
									<span class="buttonBlock">
										<?php foreach($slide['buttons'] as $button) { ?>
											<a href="<?php echo $button['link']; ?>" target="<?php echo $button['target']; ?>" class="stdBtn red"><?php echo $button['title']; ?></a>
										<?php } ?>
									</span>
								<?php } ?>
								<?php if($slide['video']) { ?>
									<span class="videoLink">
										<button type="button" class="playBtn">
											<span class="icon">
												<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
											</span>
											<span class="text">
												<span><?php if($slide['video'][0]['video_button_text']) { ?><?php echo $slide['video'][0]['video_button_text']; ?><?php } else { ?>Watch Video<?php } ?></span>
												<span><?php echo $slide['video'][0]['duration']; ?></span>
											</span>
										</button>
									</span>
								<?php } ?>


							</div>
							<?php if($slide['banner_buttons']) { ?>
								<span class="baseButtons">
									<span>
									<?php foreach($slide['banner_buttons'] as $button) { ?>
										<a href="<?php echo $button['link']; ?>" target="<?php echo $button['target']; ?>">
											<span class="text"><?php echo $button['text']; ?></span>
											<span class="title"><?php echo $button['title']; ?></span>
										</a>
									<?php } ?>
									</span>
								</span>
							<?php } ?>
						</div>
					</li>
				<?php } ?>
				<?php foreach($bannerSlides as $slide) { ?>
					<?php if($slide['video']) { ?>
						<div class="videoPlayerContainer">
							<span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
							<div class="videoWrapper">
								<video width="100%" id="popupVideo" controls controlsList="nodownload">
									<source type="video/mp4" src="<?php echo $slide['video'][0]['vimeo_code']; ?>" />
								</video>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</ul>
		</section>
	<?php } ?>
	<?php if ( get_field ( 'hidden_vimeo_embed_for_yoast_home' )) { ?>
		<span class="hiddenEmbed" style="display: none;"><?php the_field ( 'hidden_vimeo_embed_for_yoast_home' );?></span>
	<?php } ?>

	<?php if ( have_rows( 'content_blocks' ) ): ?>
		<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

			<?php if ( get_row_layout() == 'logo_grid' ) : ?>

				<section class="logoGrid <?php the_sub_field( 'background_colour' ); ?>">
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
				<section class="scrollPos repeatableSingle singlePost">
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
				<section class="scrollPos fullImageInfogram">
					<div class="container">
						 <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
							 <div class="featureBlock">
								 <?php
								 $feature_image_url = get_sub_field( 'image' );
								 $feature_image_id = $feature_image_url ? attachment_url_to_postid( $feature_image_url ) : 0;
								 $feature_image_alt = $feature_image_id ? get_post_meta( $feature_image_id, '_wp_attachment_image_alt', true ) : '';
								 ?>
								 <img loading="lazy" class="featureImage" src="<?php echo esc_url( $feature_image_url ); ?>" alt="<?php echo esc_attr( $feature_image_alt ); ?>"/>
							 </div>
						 <?php } else { ?>
							 <div class="infogram-container">
								 <?php the_sub_field( 'infogram' ); ?>
							 </div>
						 <?php } ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'related_articles_grid_block' ) : ?>
				<?php get_template_part( 'templates/components/_related-articles-grid-block' ); ?>

			<?php elseif ( get_row_layout() == 'related_articles' ) : ?>

	            <section class="relatedArticlesCarousel">
	                <div class="container">
	                    <div class="inner">
	            			<h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
	            			<?php if ( have_rows( 'related_articles' ) ) : ?>
	                            <div class="owl-carousel articlesCarousel">
	                                <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
	                                    <?php $post_object = get_sub_field( 'article' ); ?>
	                                    <?php if ( $post_object ): ?>
	                                        <?php $post = $post_object; ?>
											  <?php setup_postdata( $post ); ?>
	                                        <a class="relatedArticle item" href="<?php the_permalink(); ?>">


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

				<section class="relatedArticlesThreeColumn">
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
										<?php setup_postdata( $post ); ?>
										<a class="relatedArticle item" href="<?php the_permalink(); ?>">


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
				<section class="twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?>">
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
				<section class="halfHalfBlock <?php the_sub_field( 'background_colour' ); ?>">
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
			<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

				<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos">
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
				<section class="speakerQuoteCarousel">
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

			<?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
				<?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
			<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

			<?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
				<section class="quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?>">
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

			<?php elseif ( get_row_layout() == 'video_block' ) : ?>
				<section class="videoBlock" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);">
					<?php if( get_sub_field('dark_overlay') == 'yes') { ?>
						<span class="dark-overlay"></span>
					<?php } ?>
					<div class="container">
						<div class="content">
							<?php if( get_sub_field ( 'video_title' ) ) { ?>
								<div class="column title">
									<span class="title"><?php the_sub_field('video_title'); ?></span>
								</div>
								<hr>
							<?php } ?>
							<?php if( get_sub_field ( 'video_description' ) ) { ?>
								<div class="column text">
									<span class="text"><?php the_sub_field('video_description'); ?></span>
								</div>
							<?php } ?>
							<span class="videoLink">
								<button type="button" class="playBtnVideoBlock">
									<span class="icon">
										<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
									</span>
									<span class="text">
										<span><?php if( get_sub_field('video_button_text')) { ?><?php the_sub_field('video_button_text') ?><?php } else { ?>Watch Video<?php } ?></span>
										<span><?php the_sub_field('video_duration') ?></span>
									</span>
								</button>
							</span>
						</div>
					</div>
					<div class="videoPlayerContainer videoBlock">
						<span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
						<div class="videoWrapper">
							<video width="100%" id="popupVideo" controls controlsList="nodownload">
								<source type="video/mp4" src="<?php the_sub_field('vimeo_code'); ?>" />
							</video>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
				 <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>

			<?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
				<section class="twoColumnWithTextAndFeaturedQuote">
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
				<section class="formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
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

			<?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>

				<section class="textImageBlock <?php the_sub_field( 'background_colour' ); ?>">
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

			<?php elseif ( get_row_layout() == 'membership_block' ) : ?>
				<?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
					<section class="pricingBlock">
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
