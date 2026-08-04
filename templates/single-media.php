<section class="post-title-block">
    <div class="container">
        <span class="back-container"><a class="back" href="/media/" target="_self">Media</a></span>
        <div class="introduction-hero-module">
            <h1 class="post-title"><?php the_title();?></h1>
            <span class="press-release-introduction h5-style"><?php echo get_field( 'media_introduction_text' ); ?></span>
        </div>
        <div class="sidebar-container">
            <span class="published-details">
                <span class="share-container-mobile">
                    <span class="share-title text-black">Share</span>
                    <span class="share-links-container">
                        <span class="copy-link share">
							<a class="share-button" title="Share this article">
                                 <img src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share.svg" alt="" width="32px"/>
	                        </a>
						</span>
                        <script>
                            const shareButton = document.querySelector('.share-button');
                            const emailButton = document.querySelector('.emailShare');
                            shareButton.addEventListener('click', event => {
                              if (navigator.share) {
                               navigator.share({
                                  title: '<?php the_title(); ?>',
                                  url: '<?php echo the_permalink(); ?>'
                                }).then(() => {
                                  console.log('Thanks for sharing!');
                                })
                                .catch(console.error);
                                } else {
                                    emailButton.click();
                                }
                            });
                        </script>
                        <span class="share-linked-in share">
                            <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="32px"/>
                            </a>
                        </span>
                        <span class="share-twitter share">
                            <a class="twitterShare" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&text=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-black.svg" alt="Tweet" width="32px"/>
                            </a>
                        </span>
                        <span class="share-email share">
                            <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="32px"/>
                            </a>
                        </span>
                    </span>
                </span>
                <span class="published">
                    <?php if (get_field('published_date')){ ?>                         
                       <?php  $date_string = get_field('published_date');

                        // Create DateTime object from value (formats must match).
                        $date = DateTime::createFromFormat('Ymd', $date_string); ?>
                        Published <?php echo $date->format('M j, Y'); ?> in
                    <?php } else { ?> 
                        Published <?php echo get_the_date('M j, Y') ?> in
                    <?php } ?>					
                </span>
                <span class="type-topic">
                    <a href="/media/" class="topic-filter red-text">Media</a>
                </span>               
                <span class="media-contact">
                    <span class="media-contact-title text-black">Media Contact</span>
                        <a class="media-contact text-black" href="mailto:media@adapt.com.au">media@adapt.com.au</a>
                    </span>
                </span>
      
                <?php if ( have_rows( 'publication' ) ) : ?>
                    <span class="media-contact publication">
                    	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
                            <?php $publication_logo = get_sub_field( 'publication_logo' ); ?>
                            <a class="publicaton-link" href="<?php echo get_sub_field( 'publication_link' ); ?>" target="_blank" rel="noopener noreferrer">
                                <?php if ( $publication_logo ) { ?>
                                    <span class="publication-logo-container">
                            			<?php echo wp_get_attachment_image( $publication_logo['ID'], 'full', false, array(
                            				'alt'     => $publication_logo['alt'],
                            				'loading' => 'lazy',
                            			) ); ?>
                                    </span>
                        		<?php } else { ?>
                                    <?php echo get_sub_field( 'publication_name' ); ?>
                                <?php }?>
                            </a>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                <?php // no rows found ?>
                <?php endif; ?>
            </span>
        </div>
    </div>
</section>

<section class="post-article-container media">
    <div class="container">
		<span class="hidden print-no" style="visibility: hidden; opacity: 0; font-size: 1px;"><?php echo get_field( 'author_search_names' ); ?></span>
        <div class="left-column sticky-scroll-to-container">
            <span class="non-beaking-spacer">&nbsp;</span>
        </div>
        <div class="post-content">
			<?php if ( get_field('article_content')){ ?>
				<div class="post-content-inner article-content">
					<div class="post-text text-black post-content-text">
						<?php echo get_field( 'article_content' ); ?>
					</div>
					<div class="sidebar-content">
						<span class="subscribe-sidebar-form background-pink">
							<span class="icon-container">
								<span class="icon-inner">
									<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
								</span>
							</span>
							<h5 class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></h5>
							<p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>

							<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
						</span>
					</div>
				</div>
			<?php } ?>
            <?php if ( have_rows( 'content_blocks' ) ): ?>
            	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                    <?php if ( get_row_layout() == 'article_content' ) : ?>
                        <?php get_template_part( 'templates/post-components/_post-content' ); ?>
                    <?php elseif ( get_row_layout() == 'background_colour_list' ) : ?>
                        <?php get_template_part( 'templates/post-components/_background-colour-list' ); ?>
                    <?php elseif ( get_row_layout() == 'tick_list' ) : ?>
                        <?php get_template_part( 'templates/post-components/_tick-list' ); ?>
                    <?php elseif ( get_row_layout() == 'counter_title_and_text' ) : ?>
                        <?php get_template_part( 'templates/post-components/_counter-title-text' ); ?>
                    <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                        <?php get_template_part( 'templates/post-components/_full-image' ); ?>
                    <?php elseif ( get_row_layout() == 'download' ) : ?>
                        <?php get_template_part( 'templates/post-components/_download' ); ?>
					<?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
						<?php get_template_part( 'templates/post-components/_infogram' ); ?>
					<?php elseif ( get_row_layout() == 'video_block' ) : ?>
						<?php get_template_part( 'templates/post-components/_full-video' ); ?>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <?php // no layouts found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'media_contact' ) ) : ?>
                <span class="media-contact">
                    <span class="media-contact-title text-black">Media Contact</span>
                        <?php while ( have_rows( 'media_contact' ) ) : the_row(); ?>
                            <a class="media-contact text-black" href="mailto:<?php echo get_sub_field( 'contact_link' ); ?>"><?php echo get_sub_field( 'contact_text' ); ?></a>
                        <?php endwhile; ?>
                    </span>
                </span>
            <?php else : ?>
            <?php // no rows found ?>
            <?php endif; ?>
            <div class="mobile-subscribe">
    			<span class="subscribe-sidebar-form background-pink">
					<span class="icon-container">
						<span class="icon-inner">
							<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
						</span>
					</span>
    				<h5 class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></h5>
    				<p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>
					<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
    			</span>
            </div>
        </div>
        <div class="sidebar-container">
        </div>
    </div>
    <?php if ( have_rows( 'featured_news' ) ) : ?>
        <?php get_template_part( 'templates/post-components/_media-related' ); ?>
    <?php endif; ?>


</section>
