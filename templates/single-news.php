<?php $typePost = get_field( 'post_type' ); ?>
<?php if ( have_rows( 'publication' ) ) : ?>
	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
		<?php $publishedDate = get_sub_field( 'published_date' ); ?>
		<?php $publicationName = get_sub_field( 'publication_name' ); ?>
		<?php $publicationLink = get_sub_field( 'publication_link' ); ?>
	<?php endwhile; ?>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
<?php if($publishedDate){
    // Load field value.
    $date_string = $publishedDate;

    // Create DateTime object from value (formats must match).
    $date = DateTime::createFromFormat('Ymd', $date_string);
} ?>

<section class="post-title-block press-release">
    <div class="container">
        <span class="back-container"><a class="back" href="/news/" target="_self">In the News</a></span>
        <div class="introduction-hero-module">
            <h1 class="post-title"><?php the_title();?></h1>

            <span class="press-release-introduction h5-style"><?php echo get_field( 'press_release_introduction_text' ); ?></span>
        </div>
        <div class="sidebar-container">
            <span class="published-details">
                <?php if($publishedDate){ ?>
                    <span class="published">
                        Published <?php echo $date->format('M j, Y'); ?> in
                    </span>
                    <span class="type-topic">
                        <a href="/news/" class="topic-filter red-text">In The News </a>
                    </span>
                <?php } ?>
                <?php if ( have_rows( 'publication' ) ) : ?>
                    <span class="media-contact publication">
                    	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
                            <?php $publication_logo = get_sub_field( 'publication_logo' ); ?>
                            <a class="publicaton-link" href="<?php echo get_sub_field( 'publication_link' ); ?>" target="_blank">
                                <?php if ( $publication_logo ) { ?>
                                    <span class="publication-logo-container">
                            			<img src="<?php echo $publication_logo['url']; ?>" alt="<?php echo $publication_logo['alt']; ?>" />
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

<section class="post-article-container press-release">
    <div class="container">
        <div class="left-column sticky-scroll-to-container">
        	<span class="non-beaking-spacer">&nbsp;</span>
        </div>
        <div class="post-content">
			<span class="published-information-container">
				<span class="published-information">Originally published in <a class="publication" href="<?php echo $publicationLink; ?>" target="_blank"><?php echo $publicationName; ?></a></span>
			</span>
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
        <?php get_template_part( 'templates/post-components/_press-related' ); ?>
    <?php endif; ?>	
</section>
