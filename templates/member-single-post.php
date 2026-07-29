

<?php if(current_user_can('mepr_auth')) {?>
    <section class="singlePost print-only<?php if( get_field( 'article_content' )) { ?><?php } else {?> no-padding-bottom<?php } ?>">
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
            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                <?php if ( get_field('featured_video_vimeo_code')) { ?>
                    <div class="featureBlock">
                        <a href="" class="image postPlayBtn" style="background-image: url(<?php the_field( 'video_poster' ); ?>);">
                            <img class="delete-no" style="display: none;" src="<?php the_field( 'video_poster' ); ?>" alt=""/>
                            <span class="icon print-no">
                                <div class="v-wrap">
                                    <div class="v-box">
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="97" />
                                    </div>
                                </div>
                            </span>
                        </a>
                        <?php if ( get_field ( 'hidden_vimeo_embed_for_yoast' )) { ?>
                            <span class="hiddenEmbed" style="display: none;"><?php the_field ( 'hidden_vimeo_embed_for_yoast' );?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if ( get_field('featured_image')) { ?>
                    <div class="featureBlock">
                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                            <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_field( 'featured_image' ); ?>" alt=""/>
                        </div>
                    </div>
                 <?php } ?>
            <?php } ?>
            <div class="videoPlayerContainer print-no">
                <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                <div class="videoWrapper">
                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                        <source type="video/mp4" src="<?php the_field('featured_video_vimeo_code'); ?>" />
                    </video>
                </div>
            </div>
        </div>
        <?php if ( have_rows( 'additional_hidden_video_embed' ) ) : ?>
        	<?php while ( have_rows( 'additional_hidden_video_embed' ) ) : the_row(); ?>
        		<span class="hiddenEmbed print-no" style="display: none;"><?php the_sub_field( 'hidden_vimeo_embed_for_yoast' ); ?></span>
        	<?php endwhile; ?>
        <?php else : ?>
        	<?php // no rows found ?>
        <?php endif; ?>
            <?php if ( get_field ( 'fixed_menu_select' ) == 'yes' ) { ?>
                <?php if ( have_rows( 'fixed_menu' ) ) : ?>
                    <?php get_template_part( 'templates/components/_fixed-menu-block' ); ?>
                <?php endif; ?>
            <?php } ?>
        <div class="container">
            <div class="post-inner inner<?php if ( get_field ( 'fixed_menu_select' ) == 'yes' ) { ?> navPadding<?php } ?>">

                <?php
                    $post_tags = get_the_tags();
                ?>

                <?php if ( $post_tags ) { ?>
                    <div class="tags print-no">
                        <?php foreach( $post_tags as $tag ) { ?>
                            <span>
                                <?php echo '#' . $tag->name  ; ?>
                            </span>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="postDetails">

                    <?php
                    $term_m = 'topic';
                    ?>
                    <?php
                    $terms = get_the_terms( $post->ID, 'topic' );
                    ?>
                    <?php if ( $terms ) { ?>
                        <span class="topics-container">
                            <?php $counterTopic = 0; ?>
                            <?php $len = count($terms); ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                    <?php echo $term -> name; ?>
                                </span>
                                <?php $counterTopic++; ?>
                            <?php } ?>
                        </span>
                    <?php } ?>

                    <span class="post-date">
                        <?php echo get_the_date('d.m.Y'); ?>
                    </span>

                    <span>
                        <?php the_field( 'read_time' ); ?>
                    </span>

                    <?php if ( get_field ( 'podcast_file' ) ) { ?>

                    <?php } ?>
                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>

                    <?php } ?>
                </div>

                <div class="fullWidth scrollPos" <?php if( get_field('article_content_id')){?>id="<?php the_field('article_content_id'); ?>"<?php } ?>>
                    <div class="left">
                        <h1 class="title">
                            <?php echo the_title(); ?>
                        </h1>
                        <hr>
                        <span class="hidden print-no" style="visibility: hidden; opacity: 0; font-size: 1px;"><?php the_field( 'author_search_names' ); ?></span>
                        <?php if ( have_rows( 'contributors' ) ) : ?>
                            <div class="author">
                                <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                    <?php if ( $post_object ): ?>
                                        <?php $post = $post_object; ?>
                                        <?php setup_postdata( $post ); ?>
                                            <a href="<?php the_permalink(); ?>" class="authorSingle">
                                                <span class="authorImage" style="background-image: url(<?php the_field( 'speaker_image' ); ?>);">
                                                    <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_field( 'speaker_image' ); ?>" alt=""/>
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
                        <div class="share print-no">
                            <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" /><span>Share</span></a>
                            <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php echo the_permalink(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/email.svg" alt="Share via Email" /><span>Email</span></a>
                            <?php if(current_user_can('mepr-active','memberships:9811')) { ?>
                                <?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <?php if( get_field( 'article_content' )) { ?>
                    <?php $postID = get_the_ID(); ?>
                    <div class="fullWidth article-content">
                        <div class="articleWrapper">
                            <?php the_field( 'article_content' ); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php if ( have_rows( 'content_blocks' ) ): ?>
    	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

            <?php if ( get_row_layout() == 'article_content' ) : ?>
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
                       <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle members-logged-in">
                           <div class="container">
                               <div class="post-inner">
                                   <div class="fullWidth article-content">
                                       <div class="articleWrapper">
                                           <?php the_sub_field( 'article_content' ); ?>
                                           <?php if( get_sub_field( 'infogram_image' )) { ?>
                                               <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_sub_field( 'infogram_image' ); ?>" alt=""/>
                                           <?php } ?>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </section>
                   <?php else: ?>
                       <?php if( $members =='3829'){ ?>
                       <?php } else { ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php } ?>
                   <?php endif;?>

                <?php else : ?>

                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only repeatableSingle singlePost">
                        <div class="container">
                            <div class="post-inner">
                                <div class="fullWidth article-content">
                                    <div class="articleWrapper">
                                        <?php the_sub_field( 'article_content' ); ?>
                                        <?php if( get_sub_field( 'infogram_image' )) { ?>
                                            <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_sub_field( 'infogram_image' ); ?>" alt=""/>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

            <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
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
                       <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
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
                                    <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_sub_field( 'infogram_image' ); ?>" alt=""/>
                                <?php } ?>
                           </div>
                       </section>
                   <?php else: ?>
                       <?php if( $members =='3829'){ ?>
                       <?php } else { ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php } ?>
                   <?php endif;?>
                <?php else : ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram">
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
                                 <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_sub_field( 'infogram_image' ); ?>" alt=""/>
                             <?php } ?>
                        </div>
                    </section>
                <?php endif; ?>

    		<?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
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
                       <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock standard <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                       <?php if( $members =='3829'){ ?>
                       <?php } else { ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php } ?>
                   <?php endif;?>

                <?php else : ?>

                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only imageGridBlock standard <?php the_sub_field( 'background_colour' ); ?>">
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
    				<section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                         <?php if( $members =='3829'){ ?>
                         <?php } else { ?>
                             <?php get_template_part( 'templates/components/_locked-content' ); ?>
                         <?php } ?>
                      <?php endif; ?>
               <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php the_sub_field( 'background_colour' ); ?>">
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
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
                                        <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php else : ?>
                    <?php if( $members =='3829'){ ?>
                    <?php } else { ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php } ?>
                <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
                                        <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                                    </div>
                                <?php endwhile; ?>
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
            <?php elseif ( get_row_layout() == 'related_articles_taxonomies' ) : ?>
                <?php get_template_part( 'templates/components/_related-articles-taxonomies' ); ?>
            <?php elseif ( get_row_layout() == 'related_articles_taxonomies_grid_block' ) : ?>
                <?php get_template_part( 'templates/components/_related-articles-taxonomies-grid' ); ?>


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
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-no scrollPos relatedArticlesCarousel members-logged-in">
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
                                                        <?php if ( get_field( 'listing_image') ) { ?>
                                                            <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                                    <span class="watchIcon"></span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
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
                                                        <?php } ?>
                                                    </div>

                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>

                                                            <?php } ?>
                                                        </span>

                                                        <span class="articleLink"><?php the_title(); ?></span>

                                                        <?php
    	                                                    $post_tags = get_the_tags();
    														$count=0;
    	                                                ?>
    	                                                <?php if ( $post_tags ) { ?>
    	                                                    <div class="tags print-no">
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
                    <?php else : ?>
                        <?php if( $members =='3829'){ ?>
                        <?php } else { ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                        <?php } ?>
                   <?php endif; ?>
               <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-no scrollPos relatedArticlesCarousel">
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
                                                        <?php if ( get_field( 'listing_image') ) { ?>
                                                            <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                                    <span class="watchIcon"></span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
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
                                                        <?php } ?>
                                                    </div>

                                                    <span class="postDetails">
                                                        <span class="info">
                                                            <?php
                                                            $term_m = 'topic';
                                                            ?>
                                                            <?php
                                                            $terms = get_the_terms( $post, 'topic' );
                                                            ?>
                                                            <?php if ( $terms ) { ?>
                                                                <?php $counterTopic = 0; ?>
                                                                <?php $len = count($terms); ?>
                                                                <?php foreach($terms as $term) { ?>
                                                                    <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                         <?php echo $term -> name; ?>
                                                                    </span>
                                                                    <?php $counterTopic++; ?>
                                                                <?php } ?>

                                                            <?php } ?>
                                                        </span>

                                                        <span class="articleLink"><?php the_title(); ?></span>

                                                        <?php
    	                                                    $post_tags = get_the_tags();
    														$count=0;
    	                                                ?>
    	                                                <?php if ( $post_tags ) { ?>
    	                                                    <div class="tags print-no">
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
                 <?php endif; ?>

             <?php elseif ( get_row_layout() == 'carousel_block' ) : ?>
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
                      <section class="centerModeCarousel print-no scrollPos members-logged-in" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
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
                      <?php else : ?>
                          <?php if( $members =='3829'){ ?>
                          <?php } else { ?>
                              <?php get_template_part( 'templates/components/_locked-content' ); ?>
                          <?php } ?>
                     <?php endif; ?>
                 <?php else: ?>
                     <section class="centerModeCarousel print-no scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
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
                            <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only relatedArticlesThreeColumn members-logged-in">
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
                                                                    <div class="tags print-no">
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
                                                                <div class="tags print-no">
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
                            <?php if( $members =='3829'){ ?>
                            <?php } else { ?>
                                <?php get_template_part( 'templates/components/_locked-content' ); ?>
                            <?php } ?>
                        <?php endif; ?>
                 <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only relatedArticlesThreeColumn">
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
                                                        <div class="tags print-no">
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
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                    <?php if( $members =='3829'){ ?>
                    <?php } else { ?>
                        <?php get_template_part( 'templates/components/_locked-content' ); ?>
                    <?php } ?>
                <?php endif; ?>
              <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                        <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                        <?php if( $members =='3829'){ ?>
                        <?php } else { ?>
                            <?php get_template_part( 'templates/components/_locked-content' ); ?>
                        <?php } ?>
                    <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                          <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                          <?php if( $members =='3829'){ ?>
                          <?php } else { ?>
                              <?php get_template_part( 'templates/components/_locked-content' ); ?>
                          <?php } ?>
                      <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php the_sub_field( 'background_colour' ); ?>">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel members-logged-in">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?> members-logged-in">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage <?php the_sub_field( 'background_colour' ); ?>">
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock standard logos members-logged-in">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section id="<?php the_sub_field( 'id' ); ?>" class="scrollPos imageGridBlock standard logos">
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
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote members-logged-in">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no videoBlock members-logged-in" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);">
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
                                       <span class="videoLink print-no">
                                           <a href="#" class="playBtnVideoBlock">
                                               <span class="icon">
                                                   <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
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
                                   <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                   <div class="videoWrapper">
                                       <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                           <source type="video/mp4" src="<?php the_sub_field('vimeo_code'); ?>" />
                                       </video>
                                   </div>
                               </div>
                           </section>

                	   <?php else : ?>
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>
                	   <?php endif; ?>
                <?php else: ?>
                    <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no videoBlock" style="background-image: url(<?php the_sub_field('video_poster_image'); ?>);">
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
                                <span class="videoLink print-no">
                                    <a href="#" class="playBtnVideoBlock">
                                        <span class="icon">
                                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
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
                           <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor members-logged-in<?php if ( get_sub_field( 'font') ) { ?> <?php the_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php the_sub_field( 'font_colour' ); ?><?php } ?>">
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
                           <?php if( $members =='3829'){ ?>
                           <?php } else { ?>
                               <?php get_template_part( 'templates/components/_locked-content' ); ?>
                           <?php } ?>

                       <?php endif;?>

        			<?php else : ?>
                        <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php the_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php the_sub_field( 'font_colour' ); ?><?php } ?>">
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
                <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
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
            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                <?php if ( get_field('featured_video_vimeo_code')) { ?>
                    <div class="featureBlock">
                        <span class="image postPlayBtn" style="background-image: url(<?php the_field( 'video_poster' ); ?>);">
                            <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_field( 'video_poster' ); ?>" alt=""/>
                            <span class="icon print-no">
                                <div class="v-wrap">
                                    <div class="v-box">
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="97" />
                                    </div>
                                </div>
                            </span>
                        </span>
                        <?php if ( get_field ( 'hidden_vimeo_embed_for_yoast' )) { ?>
                            <span class="hiddenEmbed" style="display: none;"><?php the_field ( 'hidden_vimeo_embed_for_yoast' );?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if ( get_field('featured_image')) { ?>
                    <div class="featureBlock">
                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                            <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_field( 'featured_image' ); ?>" alt=""/>
                        </div>
                    </div>
                 <?php } ?>
            <?php } ?>
            <div class="videoPlayerContainer">
                <span class="closeVideo"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                <div class="videoWrapper">
                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                        <source type="video/mp4" src="<?php the_field('featured_video_vimeo_code'); ?>" />
                    </video>
                </div>
            </div>

            <div class="inner post-inner">

                <div class="postDetails">
                    <?php
                    $term_m = 'topic';
                    ?>
                    <?php
                    $terms = get_the_terms( $post->ID, 'topic' );
                    ?>
                    <?php if ( $terms ) { ?>
                        <span class="topics-container">
                            <?php $counterTopic = 0; ?>
                            <?php $len = count($terms); ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                    <?php echo $term -> name; ?>
                                </span>
                                <?php $counterTopic++; ?>
                            <?php } ?>
                        </span>
                    <?php } ?>

                    <span class="post-date">
                        <?php echo get_the_date('d.m.Y'); ?>
                    </span>

                    <span>
                        <?php the_field( 'read_time' ); ?>
                    </span>

                    <?php if ( get_field ( 'podcast_file' ) ) { ?>

                    <?php } ?>
                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>

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
                                                    <img loading="lazy" class="delete-no" style="display: none;" src="<?php the_field( 'speaker_image' ); ?>" alt=""/>
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
                        <?php if (get_field( 'longer_excerpt' )) { ?>
                            <span class="excerpt"><p><?php the_field( 'longer_excerpt' ); ?></p></span>
                        <?php } else { ?>
                            <span class="excerpt"><p><?php echo the_excerpt(); ?></p></span>
                        <?php } ?>
                    </div>
                    <div class="right">
                        <div class="share print-no">
                            <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" /><span>Share</span></a>
                            <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php echo the_permalink(); ?>" target="_blank"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/email.svg" alt="Share via Email" /><span>Email</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="registrationOverlay">
        <div class="container">
            <div class="inner">
                <div class="titleBlock">
    	            <?php if ( get_field ('member_content_article_page_overlay_title', 'option') ) { ?>
    	                <h3><?php the_field('member_content_article_page_overlay_title', 'option'); ?></h3>
    	            <?php } ?>
    				<?php if ( get_field ('member_content_article_page_overlay_sub_title', 'option') ) { ?>
    	                <p><?php the_field('member_content_article_page_overlay_sub_title', 'option'); ?></p>
    	            <?php } ?>
    	            <?php if ( get_field ('member_content_article_page_overlay_subtitle', 'option') ) { ?>
    	                <p><strong><?php the_field('member_content_article_page_overlay_subtitle', 'option'); ?></strong></p>
    	            <?php } ?>
    				<?php if ( have_rows( 'member_content_article_page_overlay_button', 'option' ) ) : ?>
    					<?php while ( have_rows( 'member_content_article_page_overlay_button', 'option' ) ) : the_row(); ?>
    						<a href="<?php the_sub_field( 'button_link' ); ?>" target="<?php the_sub_field( 'button_target' ); ?>" class="button"><?php the_sub_field( 'button_text' ); ?></a>
    					<?php endwhile; ?>
    				<?php else : ?>
    					<?php // no rows found ?>
    				<?php endif; ?>
    	            <a href="#loginform" class="loginPopupButton textLink">Already a member? Login</a>
    	        </div>
            </div>
        </div>
    </section>
    <?php if ( have_rows( 'content_blocks' ) ): ?>
    	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'related_articles' ) : ?>
                <section <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?> class="scrollPos relatedArticlesCarousel members-logged-in">
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
                                                        <?php
                                                        $term_m = 'topic';
                                                        ?>
                                                        <?php
                                                        $terms = get_the_terms( $post, 'topic' );
                                                        ?>
                                                        <?php if ( $terms ) { ?>
                                                            <?php $counterTopic = 0; ?>
                                                            <?php $len = count($terms); ?>
                                                            <?php foreach($terms as $term) { ?>
                                                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                     <?php echo $term -> name; ?>
                                                                </span>
                                                                <?php $counterTopic++; ?>
                                                            <?php } ?>

                                                        <?php } ?>
                                                    </span>

                                                    <span class="articleLink"><?php the_title(); ?></span>

                                                    <?php
                                                        $post_tags = get_the_tags();
                                                        $count=0;
                                                    ?>
                                                    <?php if ( $post_tags ) { ?>
                                                        <div class="tags print-no">
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
            <?php elseif ( get_row_layout() == 'related_articles_grid_block' ) : ?>
                <?php get_template_part( 'templates/components/_related-articles-grid-block' ); ?>
            <?php elseif ( get_row_layout() == 'related_articles_taxonomies' ) : ?>
                <?php get_template_part( 'templates/components/_related-articles-taxonomies-locked' ); ?>
            <?php endif; ?>
        <?php endwhile; ?>

    <?php endif; ?>
<?php } ?>
