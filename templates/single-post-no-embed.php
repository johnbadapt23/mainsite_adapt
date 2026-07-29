<?php if(current_user_can('mepr_auth')) {?>
    <section class="singlePost">
        <div class="container">
            <?php
            $allowed_host = 'adapt.com.au';
            $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                <script>

                function goBack() {
                    window.history.back()
                }
                </script>
                <a class="back-button" onclick="goBack()">Back</a>
            <?php } else { ?>
                <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/adapt-insights/">Back</a>
             <?php } ?>
            <div class="featureBlock">
                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                    <a href="" class="image postPlayBtn" style="background-image: url(<?php the_field( 'video_poster' ); ?>);">
                        <span class="icon">
                            <div class="v-wrap">
                                <div class="v-box">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="97" />
                                </div>
                            </div>
                        </span>
                    </a>
                <?php } else { ?>
                    <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                    </div>
                <?php } ?>
            </div>
            <div class="videoPlayerContainer">
                <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                <div class="videoWrapper">
                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                        <source type="video/mp4" src="<?php the_field('featured_video_vimeo_code'); ?>" />
                    </video>
                </div>
            </div>

            <div class="inner">

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

                <div class="postDetails">
                    <span>
                        <?php echo get_the_date('d.m.Y'); ?>
                    </span>

                    <span>
                        <?php the_field( 'read_time' ); ?>
                    </span>

                    <?php if ( get_field ( 'podcast_file' ) ) { ?>
                        <span class="podcast">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast.svg" alt="Podcast Available" />
                        </span>
                    <?php } ?>
                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                        <span class="watchIcon">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/video.svg" alt="Watch Video" />
                        </span>
                    <?php } ?>
                </div>

                <div class="fullWidth">
                    <div class="left">
                        <h1 class="title">
                            <?php echo the_title(); ?>
                        </h1>
                        <hr>

                        <?php if ( have_rows( 'contributors' ) ) : ?>
                            <div class="author">
                                <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                    <?php if ( $post_object ): ?>
                                        <?php $post = $post_object; ?>
                                        <?php setup_postdata( $post ); ?>
                                            <a href="<?php the_permalink(); ?>" class="authorSingle">
                                                <span class="authorImage" style="background-image: url(<?php the_field( 'speaker_image' ); ?>);">
                                                </span>
                                                <span class="authorText">
                                                    <span class="label">
                                                        <?php the_sub_field( 'contributor_label' ); ?>
                                                    </span>
                                                    <hr>
                                                    <span class="authorName">
                                                        <?php the_title(); ?>
                                                    </span>
                                                    <span class="authorDescription">
                                                        <?php the_field( 'speaker_description' ); ?>
                                                    </span>
                                                    <?php if ( get_field ( 'logo' ) ) { ?>
                                                        <div class="logoWrapper">
                                                            <div class="logoContainer">
                                                                <div class="logo" style="background-image: url(<?php the_field('logo'); ?>);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    <?php wp_reset_postdata(); ?>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>

                        <div class="podcastPlayer">
                            <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                <span class="podcast">
                                    <a class="button audioReveal" href="#audio">Listen to the Podcast</a>
                                    <span class="audioWrapper">

                                        <audio class="audio mejs__player" id="audio" controls>
                                            <source src="<?php the_field( 'podcast_file' ); ?>" type="audio/mp3"></source>
                                        </audio>
                                    </span>
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="right">
                        <div class="share">
                            <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" /><span>Share</span></a>
                            <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php echo the_permalink(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/email.svg" alt="Share via Email" /><span>Email</span></a>
                        </div>
                    </div>
                </div>
                <?php $postID = get_the_ID(); ?>
                <div class="fullWidth">
                    <div class="left">
                        <div class="articleWrapper">
                            <?php the_field( 'article_content' ); ?>
                        </div>
                    </div>
                    <div class="right">
                        <?php if ( have_rows( 'side_related_articles' ) ) : ?>
                            <div class="sideArticles">
                                <?php if(get_field('related_articles_heading','options')) { ?>
                                    <h2 class="relatedTitle"><?php the_field('related_articles_heading','options') ?></h2>
                                <?php } else { ?>
                                    <h2 class="relatedTitle">Related</h2>
                                <?php } ?>
                            	<?php while ( have_rows( 'side_related_articles' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'article' ); ?>
                            		<?php if ( $post_object ): ?>
                            			<?php $post = $post_object; ?>

                                        <div class="relatedArticle">
                                			<?php setup_postdata( $post ); ?>
                                            <span class="postDetails">
                                                <span>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                </span>
                                                <span>
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                            </span>
                                			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                        <?php wp_reset_postdata(); ?>
                            		<?php endif; ?>
                            	<?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <div class="sideArticles">
                                <?php if(get_field('related_articles_heading','options')) { ?>
                                    <h2 class="relatedTitle"><?php the_field('related_articles_heading','options') ?></h2>
                                <?php } else { ?>
                                    <h2 class="relatedTitle">Related</h2>
                                <?php } ?>
                                <?php
                				global $post;
                				$args=array(
                		        	'post_type' => 'post',
                		        	'post_status' => 'publish',
                		        	'posts_per_page' => 6,
                                    'post__not_in' => array( $postID )
                	        	);
                				$my_query = null;
                		      	$my_query = new WP_Query($args);
                				if( $my_query->have_posts() ) {
                					$counter=0;
                					while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                    <div class="relatedArticle">
                                        <?php setup_postdata( $post ); ?>
                                        <span class="postDetails">
                                            <span>
                                                <?php echo get_the_date('d.m.Y'); ?>
                                            </span>
                                            <span>
                                                <?php the_field( 'read_time' ); ?>
                                            </span>
                                        </span>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                    <?php
                                    $counter++;
                                    endwhile;
                                    ?>
                                <?php } wp_reset_postdata(); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( have_rows( 'side_related_events' ) ) : ?>
                            <div class="sideArticles">
                            	<?php while ( have_rows( 'side_related_events' ) ) : the_row(); ?>
                                    <h2 class="relatedTitle">Related</h2>
                            		<?php $post_object = get_sub_field( 'event' ); ?>
                            		<?php if ( $post_object ): ?>
                            			<?php $post = $post_object; ?>

                                        <div class="relatedArticle">
                                			<?php setup_postdata( $post ); ?>
                                            <span class="postDetails">
                                                <span>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                </span>
                                                <span>
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                            </span>
                                			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                        <?php wp_reset_postdata(); ?>
                            		<?php endif; ?>
                            	<?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ( have_rows( 'content_blocks' ) ): ?>
    	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

    		<?php if ( get_row_layout() == 'image_grid_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                   <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                       <section id="<?php the_sub_field('id'); ?>" class="imageGridBlock standard <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                   <?php else: ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                   <?php endif;?>

                <?php else : ?>

                    <section id="<?php the_sub_field('id'); ?>" class="imageGridBlock standard <?php the_sub_field( 'background_colour' ); ?>">
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
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                   <?php $counter = 0; ?>
                   <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                       <?php if ( $counter == 0 ) {
                           $members = $members . get_sub_field( 'membership_id' );
                       } else {
                           $members = $members . ',' . get_sub_field( 'membership_id' );
                       } ?>
                      <?php $counter++; ?>
                  <?php endwhile; ?>
                  <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
    				<section id="<?php the_sub_field('id'); ?>" class="logoGrid <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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

                     <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                      <?php endif; ?>
               <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="logoGrid <?php the_sub_field( 'background_colour' ); ?>">
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
                <?php endif; ?>
    		<?php elseif ( get_row_layout() == 'speaker_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                   <?php $counter = 0; ?>
                   <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                       <?php if ( $counter == 0 ) {
                           $members = $members . get_sub_field( 'membership_id' );
                       } else {
                           $members = $members . ',' . get_sub_field( 'membership_id' );
                       } ?>
                      <?php $counter++; ?>
                  <?php endwhile; ?>
                  <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                    <section id="<?php the_sub_field('id'); ?>" class="imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
                        <div class="container">
                            <div class="inner">
                                <h2><?php the_sub_field( 'block_title' ); ?></h2>

                    			<?php if ( have_rows( 'speakers' ) ) : ?>
                                    <div class="gridWrapper">
                        				<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                            <?php $post_object = get_sub_field( 'speaker' ); ?>
                        					<?php if ( $post_object ): ?>
                                                <a href="<?php the_permalink(); ?>" class="item">
                            						<?php $post = $post_object; ?>
                            						<?php setup_postdata( $post ); ?>
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
                            						<?php wp_reset_postdata(); ?>
                                                </a>
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
                <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?>">
                        <div class="container">
                            <div class="inner">
                                <h2><?php the_sub_field( 'block_title' ); ?></h2>

                    			<?php if ( have_rows( 'speakers' ) ) : ?>
                                    <div class="gridWrapper">
                        				<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                            <?php $post_object = get_sub_field( 'speaker' ); ?>
                        					<?php if ( $post_object ): ?>
                                                <a href="<?php the_permalink(); ?>" class="item">
                            						<?php $post = $post_object; ?>
                            						<?php setup_postdata( $post ); ?>
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
                            						<?php wp_reset_postdata(); ?>
                                                </a>
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
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                   <?php $counter = 0; ?>
                   <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                       <?php if ( $counter == 0 ) {
                           $members = $members . get_sub_field( 'membership_id' );
                       } else {
                           $members = $members . ',' . get_sub_field( 'membership_id' );
                       } ?>
                      <?php $counter++; ?>
                  <?php endwhile; ?>
                  <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                       <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                <?php endif; ?>
                <?php else: ?>
                     <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                <?php endif; ?>

    		<?php elseif ( get_row_layout() == 'related_articles' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                <?php $counter = 0; ?>
                <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                    <?php if ( $counter == 0 ) {
                       $members = $members . get_sub_field( 'membership_id' );
                    } else {
                       $members = $members . ',' . get_sub_field( 'membership_id' );
                    } ?>
                    <?php $counter++; ?>
                <?php endwhile; ?>
                <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                    <section id="<?php the_sub_field('id'); ?>" class="relatedArticlesCarousel members-logged-in">
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
                                                            <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                                                    <span class="podcast">
                                                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                                    </span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
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
                    <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                   <?php endif; ?>
               <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="relatedArticlesCarousel">
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
                                                            <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                                                    <span class="podcast">
                                                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                                    </span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
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
                 <?php endif; ?>

            <?php elseif ( get_row_layout() == 'related_articles_three_column_block' ) : ?>

                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                            <section id="<?php the_sub_field('id'); ?>" class="relatedArticlesThreeColumn members-logged-in">
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
                                                                <span class="postDetails">
                                                                    <span>
                                                                        <?php echo get_the_date('d.m.Y'); ?>
                                                                    </span>
                                                                    <span>
                                                                        <?php the_field( 'read_time' ); ?>
                                                                    </span>
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
                        <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                        <?php endif; ?>
                 <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="relatedArticlesThreeColumn">
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
                                                        <span class="postDetails">
                                                            <span>
                                                                <?php echo get_the_date('d.m.Y'); ?>
                                                            </span>
                                                            <span>
                                                                <?php the_field( 'read_time' ); ?>
                                                            </span>
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
                 <?php endif; ?>

            <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                <?php $counter = 0; ?>
                <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                   <?php if ( $counter == 0 ) {
                       $members = $members . get_sub_field( 'membership_id' );
                   } else {
                       $members = $members . ',' . get_sub_field( 'membership_id' );
                   } ?>
                  <?php $counter++; ?>
                <?php endwhile; ?>
                <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                    <section id="<?php the_sub_field('id'); ?>" class="twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                <?php endif; ?>
              <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                <?php endif; ?>

    		<?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                   <?php $counter = 0; ?>
                   <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                       <?php if ( $counter == 0 ) {
                           $members = $members . get_sub_field( 'membership_id' );
                       } else {
                           $members = $members . ',' . get_sub_field( 'membership_id' );
                       } ?>
                      <?php $counter++; ?>
                  <?php endwhile; ?>
       	           <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                        <section id="<?php the_sub_field('id'); ?>" class="textImageBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                    <?php else : ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="textImageBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                   <?php $counter = 0; ?>
                   <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                       <?php if ( $counter == 0 ) {
                           $members = $members . get_sub_field( 'membership_id' );
                       } else {
                           $members = $members . ',' . get_sub_field( 'membership_id' );
                       } ?>
                      <?php $counter++; ?>
                  <?php endwhile; ?>
                      <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                          <section id="<?php the_sub_field('id'); ?>" class="halfHalfBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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

                      <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                      <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="halfHalfBlock <?php the_sub_field( 'background_colour' ); ?>">
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

                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                            <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" class="speakerQuoteCarousel members-logged-in">
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

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="speakerQuoteCarousel">
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

                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" class="quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?>">
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

                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" id="<?php the_sub_field( 'id' ); ?>" class="imageGridBlock standard logos members-logged-in">
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

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
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

                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'related_articles_grid_block' ) : ?>
                <?php get_template_part( 'templates/components/_related-articles-grid-block' ); ?>

            <?php elseif ( get_row_layout() == 'counter_block' ) : ?>
                <?php get_template_part( 'templates/components/_counter-block' ); ?>

            <?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
                <?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>

            <?php elseif ( get_row_layout() == 'membership_block' ) : ?>
                <?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
                    <section id="<?php the_sub_field('id'); ?>" class="pricingBlock">
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

            <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" class="twoColumnWithTextAndFeaturedQuote members-logged-in">
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

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="twoColumnWithTextAndFeaturedQuote">
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
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                <?php if ( have_rows( 'membership_content' ) ) : ?>
                    <?php $counter = 0; ?>
                    <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                        <?php if ( $counter == 0 ) {
                            $members = $members . get_sub_field( 'membership_id' );
                        } else {
                            $members = $members . ',' . get_sub_field( 'membership_id' );
                        } ?>
                       <?php $counter++; ?>
                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" class="videoBlock members-logged-in" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);">
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
                                                   <span>Watch Video</span>
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

                	   <?php else : ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field('id'); ?>" class="videoBlock" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);">
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
                                            <span>Watch Video</span>
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

                <?php endif; ?>

                <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                    <?php if ( have_rows( 'membership_content' ) ) : ?>
                        <?php $counter = 0; ?>
                        <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                            <?php if ( $counter == 0 ) {
                                $members = $members . get_sub_field( 'membership_id' );
                            } else {
                                $members = $members . ',' . get_sub_field( 'membership_id' );
                            } ?>
                           <?php $counter++; ?>
	                   <?php endwhile; ?>
                           <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                               <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                           <?php else: ?>
                                <?php get_template_part( 'templates/components/_locked-content' ); ?>

                           <?php endif;?>
                     <?php else: ?>
                          <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                  	<?php endif; ?>

                <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                    <?php if ( have_rows( 'membership_content' ) ) : ?>
                        <?php $counter = 0; ?>
                        <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
                            <?php if ( $counter == 0 ) {
                                $members = $members . get_sub_field( 'membership_id' );
                            } else {
                                $members = $members . ',' . get_sub_field( 'membership_id' );
                            } ?>
                           <?php $counter++; ?>
	                   <?php endwhile; ?>
                       <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
                           <section id="<?php the_sub_field('id'); ?>" class="fullWidthTextEditor members-logged-in">
                               <div class="container">
                                   <?php the_sub_field( 'text_editor' ); ?>
                                   <?php if ( have_rows( 'button_block' ) ) : ?>
                                       <div class="buttonBlock">
                                           <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                               <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                           <?php endwhile; ?>
                                       </div>
                                   <?php endif; ?>
                               </div>
                           </section>
                       <?php else: ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>

                       <?php endif;?>

        			<?php else : ?>
                        <section id="<?php the_sub_field('id'); ?>" class="fullWidthTextEditor">
                            <div class="container">
                                <?php the_sub_field( 'text_editor' ); ?>
                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                    <div class="buttonBlock">
                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                            <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                        <?php endwhile; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
        			<?php endif; ?>

            <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                <section id="<?php the_sub_field('id'); ?>" class="formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
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

    		<?php endif; ?>
    	<?php endwhile; ?>

    <?php endif; ?>
<?php } else { ?>

    <section class="singlePost locked">
        <span class="lockOverlay"></span>
        <div class="container">

            <div class="inner">

                <div class="postDetails">
                    <span>
                        <?php echo get_the_date('d.m.Y'); ?>
                    </span>

                    <span>
                        <?php the_field( 'read_time' ); ?> read
                    </span>

                    <?php if ( get_field ( 'podcast_file' ) ) { ?>
                        <span class="podcast">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast.svg" alt="Podcast Available" />
                        </span>
                    <?php } ?>
                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                        <span class="watchIcon">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/video.svg" alt=""/>
                        </span>
                    <?php } ?>
                </div>

                <div class="fullWidth">
                    <div class="left">
                        <h2 class="title">
                            <?php echo the_title(); ?>
                        </h2>
                        <hr>
                        <span class="excerpt"><?php echo the_excerpt(); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="registrationOverlay">
        <div class="container">
            <div class="inner">
                <hr class="fullwidth">
                <div class="titleBlock">
                    <?php if ( get_field ('member_content_article_page_overlay_title', 'option') ) { ?>
                        <h2><?php the_field('member_content_article_page_overlay_title', 'option'); ?></h2>
                        <hr>
                    <?php } ?>
                    <?php if ( get_field ('member_content_article_page_overlay_subtitle', 'option') ) { ?>
                        <h3><?php the_field('member_content_article_page_overlay_subtitle', 'option'); ?></h2>
                    <?php } ?>
                    <a href="/members" class="button">Register</a>
                    <a href="/members-login" class="textLink">Login</a>
                </div>
                <?php
                $allowed_host = 'adapt.com.au';
                $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                    <script>

                    function goBack() {
                        window.history.back()
                    }
                    </script>
                    <a class="back-button" onclick="goBack()">Back</a>
                <?php } else { ?>
                    <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/adapt-insights/">Back</a>
                 <?php } ?>
            </div>
        </div>
    </section>

<?php } ?>
