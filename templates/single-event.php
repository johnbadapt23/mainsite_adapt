<h1 class="pageTitleLine"><?php echo the_title(); ?></h1>
<?php
	$bannerSlides = get_field('banner_slides');
	$bannerButtons = get_field('banner_buttons');
?>
<?php if($bannerSlides) { ?>
	<section class="banner">
		<ul class="slides">
			<?php foreach($bannerSlides as $slide) { ?>
				<li style="background-image:url(<?php echo $slide['image']; ?>);">
					<?php if( $slide['dark_overlay'] == 'yes') { ?>
						<span class="dark-overlay"></span>
					<?php } ?>
					<img src="<?php echo $slide['image']; ?>" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" alt="Adapt - <?php echo the_title(); ?>" />
					<div class="container">
						<div class="content">
							<?php if($slide['inset_image']) { ?>
								<div class="insetImage">
									<div class="image" style="background-image:url(<?php echo $slide['inset_image']; ?>);">
									</div>
									<img src="<?php echo $slide['inset_image']; ?>" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" alt="Adapt - <?php echo the_title(); ?>" />
								</div>
							<?php } ?>
							<?php if($slide['title']) { ?>
								<div class="column title">
									<span class="title"><?php echo $slide['title']; ?></span>
								</div>
								<hr>
							<?php } ?>
							<?php if($slide['text']) { ?>
								<div class="column text">
									<span class="text"><?php echo $slide['text']; ?></span>
								</div>
							<?php } ?>
							<?php if($slide['button_block']) { ?>
								<span class="buttonBlock">
									<?php foreach($slide['button_block'] as $button) { ?>
										<a href="<?php echo $button['link_url']; ?>" target="<?php echo $button['link_target']; ?>" class="stdBtn red"><?php echo $button['link_text']; ?></a>
									<?php } ?>
								</span>
							<?php } ?>
							<?php if($slide['video']) { ?>
								<span class="videoLink">
									<?php if ($slide['video'][0]['vimeo_code_popup']){ ?>
										<a href="https://vimeo.com/<?php echo $slide['video'][0]['vimeo_code_popup']; ?>" class="image popup-vimeo">
									<?php } else { ?>
										<a href="#" class="playBtn">
									<?php }?>
										<span class="icon">
											<img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
										</span>
										<span class="text">
											<span><?php if($slide['video'][0]['video_button_text']) { ?><?php echo $slide['video'][0]['video_button_text']; ?><?php } else { ?>Watch Video<?php } ?></span>
											<span><?php echo $slide['video'][0]['duration']; ?></span>
										</span>
									</a>
								</span>
							<?php } ?>
						</div>
					</div>
				</li>
			<?php } ?>
			<?php foreach($bannerSlides as $slide) { ?>
				<div class="videoPlayerContainer">
					<?php if($slide['video']) { ?>
						<span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
						<div class="videoWrapper">
							<video width="100%" id="popupVideo" controls controlsList="nodownload">
								<source type="video/mp4" src="<?php echo $slide['video'][0]['vimeo_code']; ?>" />
							</video>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</ul>
	</section>
<?php } ?>
<?php if ( get_field ( 'hidden_vimeo_embed_for_yoast' )) { ?>
	<span class="hiddenEmbed" style="display: none;"><?php the_field ( 'hidden_vimeo_embed_for_yoast' );?></span>
<?php } ?>

<?php if ( have_rows( 'navigation_items' ) ) : ?>
	<section class="navigation event <?php the_field( 'hide_main_menu' ); ?>">
		<div class="container">
			<ul>
				<?php while ( have_rows( 'navigation_items' ) ) : the_row(); ?>
					<li>
						<a class="scroll-button" <?php if( get_sub_field('section_id')) {?>href="#<?php the_sub_field( 'section_id' ); ?>"<?php } else {?>href="#<?php the_sub_field( 'section_name' ); ?>"<?php }?>><?php the_sub_field( 'section_name' ); ?></a>
					</li>
				<?php endwhile; ?>
				<?php if (get_field( 'agenda_link' )) { ?>
					<li>
						<a href="<?php the_field( 'agenda_link' ); ?>" tagret="_self">Agenda</a>
					</li>
				<?php } ?>
				<?php if( get_field( 'show_register_button' ) == 'no' ) { ?>
				<?php } else { ?>
					<li class="register">
						<a class="popup-modal" href="#form"><?php if( get_field('register_button_text')) {?><?php the_field( 'register_button_text' ); ?><?php } else { ?>Register Interest<?php } ?></a>
					</li>
				<?php } ?>
			</ul>
			<div class="formPopup mfp-hide" id="form">
				<a class="popup-modal-dismiss"></a>
				<?php if ( get_field ( 'form_title', 'option' ) ) { ?>
					<h2><?php the_field( 'form_title', 'option' ); ?></h2>
				<?php } ?>
				<?php if ( get_field ( 'form_subtitle', 'option' ) ) { ?>
					<h3><?php the_field( 'form_subtitle', 'option' ); ?></h3>
				<?php } ?>
				<?php if ( get_field ( 'form_shortcode', 'option' ) ) { ?>
					<div class="formWrapper register"><?php the_field( 'form_shortcode', 'option' ); ?></div>
				<?php } ?>
			</div>
			<div class="fixedButtonWrapper">
				<a class="fixednav">
					<span class="ham"></span>
				</a>
			</div>
		</div>
	</section>
<?php endif; ?>
<?php if ( get_field('show_share_buttons') == 'no'){ ?>
<?php } else { ?>
	<section class="eventShare">
		<div class="container">
			<div class="share">
				<a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php echo the_permalink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/email.svg" alt="Share via Email" /><span>Email</span></a>
				<a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_field( 'share_title' ); ?>&summary=<?php the_field('event_short_description_for_listing'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" /><span>Share</span></a>
			</div>
		</div>
	</section>
<?php }?>


<?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

	<?php if ( get_row_layout() == 'event_theme' ) : ?>
		<section class="fullWidthTextEditor" id="<?php the_sub_field( 'id' ); ?>">
			<div class="container">
				<div class="inner">
					<div class="content">
						<?php the_sub_field( 'event_content' ); ?>
					</div>
					<?php if ( get_sub_field( 'pdf_download_file' ) ) { ?>
						<div class="pdfDownload">
							<a class="button pdf" href="<?php the_sub_field( 'pdf_download_file' ); ?>">Download PDF</a>
						</div>
					<?php } ?>
					<?php
						$post_tags = get_the_terms( $post->ID, 'events-tag');
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
					<?php if ( have_rows( 'button_block' ) ) : ?>
						<div class="buttonBlock">
							<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
								<a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

	<?php elseif ( get_row_layout() == 'carousel_block' ) : ?>
		<section class="centerModeCarousel" id="<?php the_sub_field( 'id' ); ?>">
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
	<?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
		<?php get_template_part( 'templates/components/_download-block' ); ?>
	<?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
		<?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
	<?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
		<?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
	<?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
		<?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
	<?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
		<?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
	<?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
		<?php get_template_part( 'templates/components/_two-column-card' ); ?>
	<?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
		<?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
	<?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
		<?php get_template_part( 'templates/components/_full-width-text-editor' ); ?>

	<?php elseif ( get_row_layout() == 'agenda_highlights' ) : ?>

		<section class="agendaHighlightsBlock" id="<?php the_sub_field( 'id' ); ?>">

			<div class="container">
				<div class="inner">
					<?php if ( get_sub_field('title')) { ?>
						<h2 class="title"><?php the_sub_field( 'title' ); ?></h2>
					<?php } else { ?>
						<h2 class="title">Agenda Highlights</h2>
					<?php }?>
				</div>
			</div>

			<?php if ( have_rows( 'itinerary_item' ) ) : ?>
				<div class="agendaBlock">
					<?php while ( have_rows( 'itinerary_item' ) ) : the_row(); ?>
						<div class="item">
							<div class="container">
								<div class="inner">
									<?php if ( get_sub_field ( 'time' ) ) { ?>
										<span class="time">
											<?php the_sub_field( 'time' ); ?>
										</span>
									<?php } ?>
									<span class="eventOverview">
										<span class="title">
											<?php the_sub_field( 'title' ); ?>
										</span>
										<?php if ( get_sub_field ( 'description' ) ) { ?>
											<span class="description">
												<?php the_sub_field( 'description' ); ?>
											</span>
										<?php } ?>
									</span>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<?php if ( get_sub_field('see_more_link')) { ?>
				<div class="seeMore">
					<div class="container">
						<div class="inner">
							<a class="button" href="<?php the_sub_field('see_more_link'); ?>" target="<?php the_sub_field('link_target'); ?>"><?php if( get_sub_field('link_text')) { ?><?php the_sub_field('link_text'); ?><?php } else { ?>See More<?php } ?></a>
						</div>
					</div>
				</div>
			<?php } ?>
		</section>

	<?php elseif ( get_row_layout() == 'counter_block' ) : ?>

		<section class="logoGrid counter <?php the_sub_field( 'background_colour' ); ?>">
			<div class="container">
				<div class="titleBlock">
					<span class="title">
						<h2><?php the_sub_field( 'block_title' ); ?></h2>
					</span>

					<span class="description <?php the_sub_field( 'top_right_text_position' ); ?>">
						<h3><?php the_sub_field( 'top_right_text' ); ?></h3>
					</span>
				</div>

				<?php if ( have_rows( 'numbers' ) ) : ?>
					<div class="logoBlock">
						<?php while ( have_rows( 'numbers' ) ) : the_row(); ?>
							<div class="logo">
								<span class="number"><?php the_sub_field( 'number' ); ?></span>

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

	<?php elseif ( get_row_layout() == 'speaker_block' ) : ?>

		<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?>">
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
													<img src="<?php the_field( 'logo' ); ?>" alt="Adapt" />
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
					<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
						<div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
							<a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</section>
	<?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
	    <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
	<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

		<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos">
			<div class="container">
				<div class="inner">
					<div class="titleBlock">
						<h2><?php the_sub_field( 'block_title' ); ?></h2>
						<hr>
						<h3><?php the_sub_field( 'description' ); ?></h3>
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

	<?php elseif ( get_row_layout() == 'video_block' ) : ?>
		<section class="videoBlock" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
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
						<?php if( get_field('vimeo_code_popup')){ ?>
		                    <a href="https://vimeo.com/<?php the_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
		                <?php } else { ?>
							<a href="#" class="playBtnVideoBlock">
		                <?php } ?>
							<span class="icon">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
							</span>
							<span class="text">
								<span><?php if( get_sub_field('video_button_text')) { ?><?php the_sub_field('video_button_text') ?><?php } else { ?>Watch Video<?php } ?></span>
								<span><?php the_sub_field('video_duration') ?></span>
							</span>
						</a>
					</span>
				</div>
			</div>
			<div class="videoPlayerContainer videoBlock">
				<span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
				<div class="videoWrapper">
					<video width="100%" id="popupVideo" controls controlsList="nodownload">
						<source type="video/mp4" src="<?php the_sub_field('vimeo_code'); ?>" />
					</video>
				</div>
			</div>
		</section>
	<?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
		 <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
	 <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
		 <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
	<?php elseif ( get_row_layout() == 'hierarchical_logo_block' ) : ?>

		<section id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos">
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


		<?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>

            <section class="imageGridBlock standard logos">
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
                                                <img src="<?php the_sub_field( 'logo' ); ?>" alt="Adapt" />
                                            </div>
                                        <?php } ?>
                                    </div>
                				<?php endwhile; ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

		<?php elseif ( get_row_layout() == 'related_articles_taxonomies' ) : ?>
			<?php get_template_part( 'templates/components/_related-articles-taxonomies' ); ?>
		<?php elseif ( get_row_layout() == 'related_articles_taxonomies_grid_block' ) : ?>
			<?php get_template_part( 'templates/components/_related-articles-taxonomies-grid' ); ?>

		<?php elseif ( get_row_layout() == 'related_articles' ) : ?>

            <section id="<?php the_sub_field( 'id' ); ?>" class="relatedArticlesCarousel">
                <div class="container">
                    <div class="inner">
            			<h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
            			<?php if ( have_rows( 'related_articles' ) ) : ?>
                            <div class="owl-carousel articlesCarousel">
                                <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'article' ); ?>
                                    <?php if ( $post_object ): ?>
                                        <?php $post = $post_object; ?>

                                        <a href="<?php echo the_permalink(); ?>" class="relatedArticle item">
                                            <?php setup_postdata( $post ); ?>

                                            <div class="imageContainer">
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <div class="image video" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                                            <span class="podcast">
                                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else if ( get_field ( 'featured_image_or_video' ) == 'featured_image' ) { ?>
													<div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                                            <span class="podcast">
                                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                            </span>
                                                        <?php } ?>
                                                    </div>
												<?php } else if ( get_field ( 'featured_image_or_video' ) == 'image' ) { ?>
													<div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
														<?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
															<span class="podcast">
																<img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
															</span>
														<?php } ?>
													</div>
                                                <?php } else { ?>
													<div class="image event" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
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
							<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
								<div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
									<a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
								</div>
							<?php endwhile; ?>
						<?php endif; ?>
                    </div>
    			<?php endif; ?>
            </section>

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

		<?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
			<section class="speakerQuoteCarousel" id="<?php the_sub_field( 'id' ); ?>">
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

		<?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
			<section id="<?php the_sub_field( 'id' ); ?>"  class="halfHalfBlock <?php the_sub_field( 'background_colour' ); ?>">
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

		<?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>

            <section id="<?php the_sub_field( 'id' ); ?>" class="textImageBlock <?php the_sub_field( 'background_colour' ); ?>">
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

		<?php elseif ( get_row_layout() == 'form_block' ) : ?>
			<section id="<?php the_sub_field( 'id' ); ?>" class="formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
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

		<?php elseif ( get_row_layout() == 'counter_block' ) : ?>
			<?php get_template_part( 'templates/components/_counter-block' ); ?>

		<?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
			<?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>

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
