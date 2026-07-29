<?php global $post; ?>
<?php  
    $categories = get_the_category();
    $category_slugs = array_map(function($cat) {
        return $cat->slug;
    }, $categories);
?>
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
<?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
<?php } else { ?>
    <?php setPostViews(get_the_ID()); ?>
<?php } ?>
<?php if($typePost == 'best-practice'){ ?>
    <section class="post-title-block best-practices-title-block background-light-grey">
        <div class="container">
            <span class="back-container"><a class="back" href="/all-resources/" target="_self">Resources</a></span>
            <div class="best-practices-inner">
                <span class="type-topic">
                    <?php if (yoast_get_primary_term_id('topic')) {
                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                        $postTopic = get_term( $primary_term_topic_id );
                    } else {
                        if(get_the_terms( $post->ID, 'topic' )){
                            $terms = get_the_terms( $post->ID, 'topic' );
                            foreach($terms as $term) {
                                $postTopic = $term;
                            }
                        }
                    }?>
                    <?php if (yoast_get_primary_term_id('resource-type')) {
                        $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                        $postType= get_term( $primary_term_type_id );
                    } else {
                        if(get_the_terms( $post->ID, 'resource-type' )){
                            $terms = get_the_terms( $post->ID, 'resource-type' );
                            foreach($terms as $term) {
                                $postType= $term;
                            }
                        }
                    }?>
                    <?php if($postTopic){?>
                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text"><?php echo $postTopic->name; ?></a>
                    <?php } ?>
                    <?php if($postType){?>
                        <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text">/ <?php echo $postType->name; ?> </a>
                    <?php } ?>
                </span>
                <h1 class="post-title"><?php the_title();?></h1>
                <span class="download-button-container">
                    <a class="std-button red-button download-button" href="#downloadGuide"><?php echo get_field( 'download_button_text' ); ?></a>
                </span>
            </div>
        </div>
        <div style="display:none;">
            <div class="download-form" id="downloadGuide">
                <div class="download-form-container">
                    <?php echo get_field( 'best_practices_form_embed' ); ?>
                </div>
            </div>
        </div>
        <div class="best-practices-image-container background-white">
            <div class="container">
                <div class="image-container-inner">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                            <?php if ( $best_practice_listing_image ) { ?>
                                <img src="<?php echo $best_practice_listing_image['url']; ?>" alt="<?php echo $best_practice_listing_image['alt']; ?>" />
                            <?php } ?>
                        </span>
                        <span class="content-container-absolute">
                            <span class="topic-filter">
                                <?php if (yoast_get_primary_term_id('topic')) {
                                    $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                    $postTopic = get_term( $primary_term_topic_id );
                                } else {
                                    if(get_the_terms( $post->ID, 'topic' )){
                                        $terms = get_the_terms( $post->ID, 'topic' );
                                        foreach($terms as $term) {
                                            $postTopic = $term;
                                        }
                                    }
                                }?>
                                <?php if($postTopic){?>
                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                <?php } ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge <?php echo get_field( 'best_practice_listing_text_colour' ); ?>"><?php the_title(); ?></a>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <section class="<?php if (in_array('portal-preview', $category_slugs)) {?>post-title-preview <?php } ?>post-title-block<?php if($typePost == 'insights' || $typePost == 'expert'){ ?> background-black<?php } ?><?php if($typePost == 'press-release'){ ?> press-release<?php } ?>">
        <div class="container">
            <?php if($typePost == 'press-release'){ ?>
                <span class="back-container"><a class="back" href="/resource-type/in-the-news/" target="_self">In the News</a></span>
            <?php } elseif($typePost == 'media'){ ?>
                <span class="back-container"><a class="back" href="/resource-type/media/" target="_self">Media</a></span>
            <?php } else { ?>
                <span class="back-container"><a class="back <?php if($typePost == 'insights' || $typePost == 'expert'){ ?> white-text<?php } ?>" href="/all-resources/" target="_self">Resources</a></span>
            <?php }?>
            <div class="introduction-hero-module">
                <h1 class="post-title"><?php the_title();?></h1>
                <?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
                    <span class="press-release-introduction h5-style"><?php echo get_field( 'press_release_introduction_text' ); ?></span>
                <?php } else { ?>
                    <?php 
                        $excerpt = get_the_excerpt();
                        if ( $excerpt ) { ?> 
                        <span class="excerpt h5-style"><?php echo $excerpt; ?></span>
                    <?php } ?>                    
                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    <?php if ($video_link){ ?>
                    <?php } else { ?>
                        <?php $video_link = get_field( 'vimeo_code' ); ?>
                    <?php } ?>
                    <?php if (in_array('portal-preview', $category_slugs)) {?>
                    <?php } else { ?>
                        <?php if ($video_link){ ?>
                            <div class="featured-video-container mobile">
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                    <span class="video-container">
                                        <span class="bg-container">
                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                            <?php if ( $video_poster_image ) { ?>
                                                <img loading="lazy" src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
                                            <?php } ?>
                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                <span class="opacity-overlay"></span>
                                            <?php } ?>
                                            <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                            <?php if ($video_link){ ?>
                                                <span class="video-button">
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="hero-image-container">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                        <?php if ( $featured_image ) { ?>
                                            <img loading="lazy" src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                        <?php } ?>
                                    </span>
                                </span>
                            </div>
                        <?php } ?> 
                    <?php } ?>                   
                <?php } ?>
            </div>
            <div class="sidebar-container">
                <span class="published-details">
                    <span class="share-container-mobile">
                        <span class="share-title <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>text-white<?php } else { ?>text-black<?php } ?>">Share</span>
                        <span class="share-links-container">
                            <span class="copy-link share">
								<a class="share-button" title="Share this article">
									<?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                         <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share-white.svg" width="32px"/>
                                    <?php } else { ?>
                                         <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share.svg" width="32px"/>
                                    <?php } ?>
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
                                <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank">
                                    <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-white.svg" alt="Share on LinkedIn" width="32px"/>
                                    <?php } else { ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="32px"/>
                                    <?php } ?>
                                </a>
                            </span>
                            <span class="share-twitter share">
                                <a class="twitterShare" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&text=<?php the_excerpt(); ?>" target="_blank">
                                    <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-white.svg" alt="Tweet" width="32px"/>
                                    <?php } else { ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-black.svg" alt="Tweet" width="32px"/>
                                    <?php } ?>
                                </a>
                            </span>
                            <span class="share-email share">
                                <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank">
                                    <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email-white.svg" alt="Share via Email" width="32px"/>
                                    <?php } else { ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="32px"/>
                                    <?php } ?>
                                </a>
                            </span>
                        </span>
                    </span>
                    <span class="published">
						<?php if($typePost == 'press-release'){?>
							Published <?php echo $date->format('M j, Y'); ?> in
						<?php } else { ?>
							Published <?php echo get_the_date('M j, Y') ?> in
						<?php } ?>
                    </span>
                    <span class="type-topic">
                        <?php if (yoast_get_primary_term_id('topic')) {
                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                            $postTopic = get_term( $primary_term_topic_id );
                        } else {
                            if(get_the_terms( $post->ID, 'topic' )){
                                $terms = get_the_terms( $post->ID, 'topic' );
                                foreach($terms as $term) {
                                    $postTopic = $term;
                                }
                            }
                        }?>
                        <?php if (yoast_get_primary_term_id('resource-type')) {
                            $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                            $postType= get_term( $primary_term_type_id );
                        } else {
                            if(get_the_terms( $post->ID, 'resource-type' )){
                                $terms = get_the_terms( $post->ID, 'resource-type' );
                                foreach($terms as $term) {
                                    $postType= $term;
                                }
                            }
                        }?>
                        <?php if($postType){?>
                            <a href="<?php echo get_term_link($postType); ?>" class="topic-filter red-text"><?php echo $postType->name; ?> </a>
                        <?php } ?>
                        <?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
                        <?php } else { ?>
                            <?php if($postTopic){?>
                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter red-text"><?php echo $postTopic->name; ?></a>
                            <?php } ?>
                        <?php } ?>

                    </span>
                    <?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
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
                        <?php if ( have_rows( 'publication' ) ) : ?>
                            <span class="media-contact publication">
                            	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
                                    <?php $publication_logo = get_sub_field( 'publication_logo' ); ?>
                                    <a class="publicaton-link" href="<?php echo get_sub_field( 'publication_link' ); ?>" target="_blank">
                                        <?php if ( $publication_logo ) { ?>
                                            <span class="publication-logo-container">
                                    			<img loading="lazy" src="<?php echo $publication_logo['url']; ?>" alt="<?php echo $publication_logo['alt']; ?>" />
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
                    <?php } else { ?>
                        <?php if ( have_rows( 'contributors' ) ) : ?>
                            <span class="contributor-container">
                                <span class="contributor-title">Contributors</span>
                            	<?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                            		<?php $post_object = get_sub_field( 'contributor' ); ?>
                            		<?php if ( $post_object ): ?>
                            			<?php $post = $post_object; ?>
                            			<?php setup_postdata( $post ); ?>
                            				<a class="contributor scroll-to" href="#<?php echo $post->ID; ?>"><?php the_title(); ?></a>
                            			<?php wp_reset_postdata(); ?>
                            		<?php endif; ?>
                            	<?php endwhile; ?>
                            </span>
                        <?php else : ?>
                        	<?php // no rows found ?>
                        <?php endif; ?>
                    <?php } ?>
                </span>
            </div>            
            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
            <?php if ($video_link){ ?>
            <?php } else { ?>
                <?php $video_link = get_field( 'vimeo_code' ); ?>
            <?php } ?>
            <?php if ($video_link){ ?>
                <div class="featured-video-container desktop">
                    <?php if ( get_field ( 'hidden_vimeo_embed_for_yoast' )) { ?>
                        <span class="hiddenEmbed" style="display: none;"><?php echo get_field ( 'hidden_vimeo_embed_for_yoast' );?></span>
                    <?php } ?>
                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                        <span class="video-container">
                            <span class="bg-container">
                                <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                <?php if ( $video_poster_image ) { ?>
                                    <img loading="lazy" src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
                                <?php } ?>
                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                    <span class="opacity-overlay"></span>
                                <?php } ?>
                                <span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
                                <?php if ($video_link){ ?>
                                    <span class="video-button">
                                    </span>
                                <?php } ?>
                            </span>
                        </span>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<?php if (in_array('portal-preview', $category_slugs)) {?>
    <section class="post-article-container <?php echo $typePost; ?>">
        <div class="container">
            <div class="left-column sticky-scroll-to-container">
                <?php if ( have_rows( 'scroll_to_links' ) ) : ?>
                    <?php while ( have_rows( 'scroll_to_links' ) ) : the_row(); ?>
                        <span class="scroll-to-container">
                            <?php if ( have_rows( 'scroll_to_link' ) ) : ?>
                                <?php while ( have_rows( 'scroll_to_link' ) ) : the_row(); ?>
                                    <a class="scroll-to-link" href="#<?php echo get_sub_field( 'id' ); ?>"><?php echo get_sub_field( 'title' ); ?></a>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    <?php endwhile; ?>
                <?php else : ?>
                    <span class="non-beaking-spacer">&nbsp;</span>
                <?php endif; ?>
            </div>
            <?php $previewContent = false; ?>
            <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                    <?php if( get_sub_field( 'preview_text' )){ ?>
                        <?php $previewContent = true; ?>
                        <?php $previewText = get_sub_field( 'preview_text' ); ?>
                        <?php $image = get_sub_field( 'image' ); ?>  
                    <?php } ?>                                                                             
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
            <div class="post-content">
                <div class="post-content-inner article-content">
                    <div class="post-text text-black post-content-text preview-text">
                         <?php if ($previewContent == false){ ?>
                            <p><?php echo get_the_excerpt(); ?></p>
                         <?php } else { 
                            echo $previewText; 
                         } ?>
                    </div>
                    <?php if ( $image ) { ?>
                        <div class="preview-image-container">
                            <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                        </div>
                    <?php } ?>
                     <?php $portalURL = get_field( 'portal_url' ); ?>
                     <?php $members_only_blurred_text_image = get_field( 'members_only_blurred_text_image', 'options' ); ?>
                     <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                        <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                            <?php if ( have_rows( 'cta' ) ) : ?>
                                <?php while ( have_rows( 'cta' ) ) : the_row(); ?>
                                <div class="blurred-image-cta-container">
                                    <span class="blur-image-container">
                                        <span class="bg-container"> 
                                            <p>                                                
                                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                            </p>
                                            <p>
                                                Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                            </p>
                                            <ul>
                                                <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                            </ul>  
                                            <p>
                                                Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                            </p>                                             
                                        </span>
                                    </span>
                                    <div class="preview-cta-container background-pink">
                                        <div class="preview-cta-inner">
                                            <div class="preview-cta-image-column">
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                        <?php } ?>
                                                    </span>
                                                </span>                                                    
                                            </div>
                                            <div class="preview-cta-content">
                                                <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                                <?php if ( have_rows( 'buttons' ) ) : ?>
                                                    <?php $buttonCounter = 1; ?>
                                                    <span class="button-container">                                                                                                                   
                                                        <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                            <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                            <?php } else { ?> 
                                                                <a class="formPopupHubspot std-button stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#previewCTA<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                <div style="display: none;">         
                                                                    <div class="preview-cta-form login-form-container" id="previewCTA<?php echo $buttonCounter; ?>">
                                                                        <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                            <?php $buttonCounter++; ?>
                                                        <?php endwhile; ?>
                                                    </span>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                            $postID = get_the_ID();
                                            $postURL = get_permalink();
                                        ?>
                                        <span class="preview-cta-bottom-module">Already an ADAPT Research & Advisory Client? <a class="login-link"  href="https://research.adapt.com.au/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $portalURL;?>" target="_blank">Login here</a></span>
                                    </div> 
                                </div>                                       
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>                                       
                </div>
            </div>
           
        </div>
    </section>
    <!-- <section class="registrationOverlay bottom-registration-overlay">
        <div class="container">
            <div class="inner">
                <div class="titleBlock">
                    <h3>Access the full article.</h3>
                    <div class="portal-link-container">
                        <a class="std-button red-button portal-link" href="<?php echo get_field( 'portal_url' ); ?>" target="_blank">Article</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section> -->
<?php } else { ?> 
    <section class="post-article-container <?php echo $typePost; ?>">
        <div class="container">
            <span class="hidden print-no" style="visibility: hidden; opacity: 0; font-size: 1px;"><?php echo get_field( 'author_search_names' ); ?></span>
            <div class="left-column sticky-scroll-to-container">
                <?php if ( have_rows( 'scroll_to_links' ) ) : ?>
                    <?php while ( have_rows( 'scroll_to_links' ) ) : the_row(); ?>
                        <span class="scroll-to-container">
                            <?php if ( have_rows( 'scroll_to_link' ) ) : ?>
                                <?php while ( have_rows( 'scroll_to_link' ) ) : the_row(); ?>
                                    <a class="scroll-to-link" href="#<?php echo get_sub_field( 'id' ); ?>"><?php echo get_sub_field( 'title' ); ?></a>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    <?php endwhile; ?>
                <?php else : ?>
                    <span class="non-beaking-spacer">&nbsp;</span>
                <?php endif; ?>
            </div>
            <div class="post-content">
                <?php if($typePost == 'press-release'){ ?>
                    <span class="published-information-container">
                        <span class="published-information">Originally published in <a class="publication" href="<?php echo $publicationLink; ?>" target="_blank"><?php echo $publicationName; ?></a></span>
                    </span>
                <?php } ?>
                <?php if ( get_field('article_content')){ ?>
                    <div class="post-content-inner article-content">
                        <div class="post-text text-black post-content-text">
                            <?php echo get_field( 'article_content' ); ?>
                        </div>
                        <div class="sidebar-content">
                            <span class="subscribe-sidebar-form background-pink">
                                <span class="icon-container">
                                    <span class="icon-inner">
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg"/>
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
                            <?php get_template_part( 'templates/post-components/_video-block' ); ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <?php // no layouts found ?>
                <?php endif; ?>
                <?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
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
                <?php } else { ?>
                    <?php if($typePost == 'best-practice'){ ?>
                        <span class="download-button-container">
                            <a class="std-button red-button download-button" href="#downloadGuide"><?php echo get_field( 'download_button_text' ); ?></a>
                        </span>
                    <?php } ?>
                    <?php if ( have_rows( 'contributors' ) ) : ?>
                        <div class="authors">
                            <?php if (get_field( 'contributor_title' )) { ?>
                                <span class="labelXLarge text-black"><?php echo get_field( 'contributor_title' ); ?></span>
                            <?php } else { ?>
                                <span class="labelXLarge text-black">Contributors</span>
                            <?php }?>
                            <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                <?php $post_object = get_sub_field( 'contributor' ); ?>
                                <?php $label = get_sub_field( 'contributor_label' ); ?>
                                <?php if ( $post_object ): ?>
                                    <?php $post = $post_object; ?>
                                    <?php setup_postdata( $post ); ?>
                                        <div class="speaker-container-inner" id="<?php echo $post -> ID; ?>">
                                            <span class="speaker-image">
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php $team_member_image = get_field( 'team_member_image' ); ?>
                                                        <img loading="lazy" src="<?php echo $team_member_image['url']; ?>" alt="<?php echo $team_member_image['alt']; ?>" />
                                                    </span>
                                                    <span class="border-offset"></span>
                                                </span>
                                            </span>
                                            <span class="description">
                                                <span class="speaker-name labelLarge text-black"><?php echo the_title(); ?></span>
                                                <span class="speaker-title labelMedium text-black"><?php echo get_field('speaker_description'); ?></span>
                                                <div class="speaker-role text-black">
                                                        <?php
                                                            $text = get_field('small_description');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo get_field('small_description'); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    </div>
                                            </span>
                                        </div>
                                    <?php wp_reset_postdata(); ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'meta_tags' ) ) : ?>
                        <div class="meta-tag-container">
                            <?php while ( have_rows( 'meta_tags' ) ) : the_row(); ?>
                                <span class="tags meta-tags"><?php echo get_sub_field('meta_tag'); ?></span>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>

                <?php } ?>
                <div class="mobile-subscribe">
                    <span class="subscribe-sidebar-form background-pink">
                        <span class="icon-container">
                            <span class="icon-inner">
                                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg"/>
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
        <?php if($typePost == 'best-practice'){ ?>
            <?php if ( have_rows( 'related_articles' ) ) : ?>
                <?php get_template_part( 'templates/post-components/_post-related-best-practices' ); ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        <?php } else { ?>
            <?php if ( have_rows( 'related_articles' ) ) : ?>
                <?php get_template_part( 'templates/post-components/_post-related' ); ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        <?php } ?>

        <?php if ( have_rows( 'featured_news' ) ) : ?>
            <?php if($typePost == 'press-release') { ?>
                <?php get_template_part( 'templates/post-components/_press-related' ); ?>
            <?php } ?>
            <?php if($typePost == 'media') { ?>
                <?php get_template_part( 'templates/post-components/_media-related' ); ?>
            <?php } ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </section>
<?php } ?>
    
