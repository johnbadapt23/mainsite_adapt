<?php global $post;?>
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
<?php if ( isset($publishedDate) && $publishedDate ) {
    // Load field value.
    $date_string = $publishedDate;

    // Create DateTime object from value (formats must match).
    $date = DateTime::createFromFormat('Ymd', $date_string);
} ?>
<?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
<?php } else { ?>
    <?php setPostViews(get_the_ID()); ?>
<?php } ?>

<?php
$theTerms1 = get_the_terms( $post->ID, 'resource-type' );
$isPodcast1 = false;

foreach( $theTerms1 as $theTerm ){
    if( $theTerm->slug == 'podcast' ){
        $isPodcast1 = true;
        break;
    }
}

?>
                        


<?php if($typePost == 'best-practice'){ ?>
    <section class="post-title-block best-practices-title-block background-light-grey">
        <div class="container">
            <span class="back-container">
                <?php if( $isPodcast1 ) : ?>
                <a class="back" href="/resource-type/podcast/" target="_self">All podcast episodes</a>
                <?php else : ?>
                <a class="back" href="/all-resources/" target="_self">Resources</a>
                <?php endif; ?>
            </span>
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
                    <?php if ( !empty( $postTopic ) ) { ?>
                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text"><?php echo $postTopic->name; ?></a>
                    <?php } ?>
                    <?php if ( !empty( $postType ) ) { ?>
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
                                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'full', false, array(
                                    'alt'     => $best_practice_listing_image['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
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
                                <?php if ( !empty( $postTopic ) ) { ?>
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
                <span class="back-container">
                    <?php if( $isPodcast1 ) : ?>
                    <a class="back white-tex" href="/resource-type/podcast/" target="_self">All podcast episodes</a>
                    <?php else : ?>
                    <a class="back <?php if($typePost == 'insights' || $typePost == 'expert'){ ?> white-text<?php } ?>" href="/all-resources/" target="_self">Resources</a>
                    <?php endif; ?>
                </span>
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
                    
                    <?php if( $isPodcast1 && get_field('podcast_available') == 'yes' && ( get_field('podcast_spotify') || get_field('podcast_itunes') || get_field('podcast_youtube') ) ) : ?>
                    <div class="podcast-listen-on-container">
                        <div class="podcast-listen-on-wrapper">
                            <div style="display: none;">Listen on:</div>
                            <div class="podcast-links">
                                <?php if( get_field('podcast_spotify') ) : ?>
                                <a href="<?= get_field('podcast_spotify'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Spotify Link">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_21501_12855)">
                                            <path d="M15.625 3.08325C8.49483 3.08325 2.70837 8.86971 2.70837 15.9999C2.70837 23.1301 8.49483 28.9166 15.625 28.9166C22.7552 28.9166 28.5417 23.1301 28.5417 15.9999C28.5417 8.86971 22.7552 3.08325 15.625 3.08325Z" fill="white"/>
                                            <path d="M23.886 14.7025C23.6152 14.7025 23.4485 14.6348 23.2141 14.4993C19.5058 12.2858 12.8756 11.7546 8.5839 12.9525C8.3964 13.0046 8.16203 13.0879 7.91203 13.0879C7.22453 13.0879 6.69849 12.5514 6.69849 11.8587C6.69849 11.1504 7.13599 10.7493 7.60474 10.6139C9.43807 10.0775 11.4902 9.82227 13.7245 9.82227C17.5266 9.82227 21.511 10.6139 24.4224 12.3118C24.8287 12.5462 25.0943 12.8691 25.0943 13.4889C25.0943 14.1973 24.5214 14.7025 23.886 14.7025ZM22.2714 18.6712C22.0006 18.6712 21.8183 18.5514 21.6308 18.4525C18.3756 16.5254 13.5214 15.7493 9.20369 16.9212C8.95369 16.9889 8.81828 17.0566 8.5839 17.0566C8.02661 17.0566 7.57349 16.6035 7.57349 16.0462C7.57349 15.4889 7.84432 15.1191 8.38078 14.9681C9.8287 14.5618 11.3079 14.2598 13.4745 14.2598C16.8547 14.2598 20.1204 15.0983 22.6933 16.6296C23.1152 16.8796 23.2818 17.2025 23.2818 17.6556C23.2766 18.2181 22.8391 18.6712 22.2714 18.6712ZM20.8704 22.0879C20.6516 22.0879 20.5162 22.0202 20.3131 21.9004C17.0631 19.9421 13.2818 19.8587 9.54745 20.6243C9.34432 20.6764 9.0787 20.7598 8.92765 20.7598C8.42245 20.7598 8.10474 20.3587 8.10474 19.9368C8.10474 19.4004 8.42244 19.1452 8.81307 19.0618C13.0787 18.1191 17.4381 18.2025 21.1568 20.4264C21.4745 20.6296 21.662 20.8118 21.662 21.2858C21.662 21.7598 21.2922 22.0879 20.8704 22.0879Z" fill="#222222"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_21501_12855">
                                                <rect width="25.8333" height="26.6667" fill="white" transform="translate(2.70837 2.66663)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                                <?php endif; ?>

                                <?php if( get_field('podcast_itunes') ) : ?>
                                <a href="<?= get_field('podcast_itunes'); ?>" target="_blank" rel="noopener noreferrer" aria-label="iTunes Link">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.931 2.66638C21.2484 2.66638 21.5658 2.66686 21.8831 2.66833C22.1503 2.66981 22.4177 2.67268 22.6849 2.68005C23.2673 2.69556 23.8552 2.73004 24.431 2.83337C25.0149 2.93894 25.5594 3.11005 26.0902 3.38025C26.6121 3.64603 27.0901 3.99342 27.5042 4.40759C27.9183 4.82171 28.2649 5.29882 28.5306 5.82068C28.8008 6.35218 28.9726 6.89619 29.0775 7.48083C29.1808 8.05661 29.2163 8.64353 29.2318 9.22595C29.2392 9.49312 29.242 9.76054 29.2435 10.0277L29.2445 10.9799V20.9301C29.2445 21.2474 29.2447 21.5648 29.2425 21.8822C29.241 22.1494 29.2382 22.4168 29.2308 22.684C29.2153 23.2656 29.1808 23.8534 29.0775 24.4291C28.9726 25.0138 28.7998 25.5587 28.5296 26.0902C28.2639 26.612 27.9173 27.0892 27.5033 27.5033C27.0891 27.9174 26.6111 28.2639 26.0892 28.5297C25.5584 28.7999 25.0147 28.9717 24.43 29.0765C23.8544 29.1798 23.2671 29.2153 22.6849 29.2308C22.4178 29.2382 22.1502 29.2411 21.8831 29.2426C21.5657 29.244 21.2475 29.2435 20.93 29.2435H10.9808C10.6635 29.2435 10.3459 29.2448 10.0286 29.2426C9.76155 29.2411 9.49398 29.2382 9.22689 29.2308C8.6445 29.2153 8.05654 29.1799 7.48079 29.0765C6.89683 28.971 6.35243 28.7999 5.82161 28.5297C5.2997 28.2639 4.8217 27.9174 4.40755 27.5033C3.9935 27.0892 3.64693 26.612 3.38118 26.0902C3.11098 25.5587 2.93817 25.0138 2.83333 24.4291C2.73004 23.8534 2.69551 23.2663 2.68001 22.684C2.67264 22.4168 2.66977 22.1494 2.66829 21.8822C2.66682 21.5648 2.66732 21.2474 2.66732 20.9301V10.9799C2.66732 10.6626 2.66682 10.3452 2.66829 10.0287C2.66977 9.76144 2.67263 9.49418 2.68001 9.22693C2.69551 8.64524 2.73 8.0576 2.83333 7.48181C2.93816 6.89714 3.11101 6.35317 3.38118 5.82166C3.64691 5.2998 3.99349 4.82269 4.40755 4.40857C4.82172 3.9944 5.29965 3.647 5.82161 3.38123C6.35239 3.11105 6.89613 2.93918 7.48079 2.83435C8.05653 2.73031 8.64452 2.69629 9.22689 2.68005C9.49398 2.67268 9.76155 2.66981 10.0286 2.66833C10.3459 2.66686 10.6635 2.66638 10.9808 2.66638H20.931ZM20.7513 6.84802L12.8519 8.44177L12.849 8.44275C12.6431 8.48631 12.4815 8.55963 12.3568 8.66443C12.2063 8.79058 12.1229 8.96913 12.0911 9.17712C12.0845 9.22141 12.0736 9.31189 12.0736 9.4447C12.0736 9.4447 12.0736 17.5145 12.0736 19.3314C12.0736 19.5625 12.0553 19.7877 11.8988 19.9789C11.7422 20.1701 11.5483 20.2271 11.3216 20.2728C11.1497 20.3075 10.9779 20.3426 10.806 20.3773C10.1534 20.5087 9.7287 20.5983 9.34408 20.7474C8.97657 20.8899 8.70099 21.0709 8.48177 21.3011C8.047 21.7566 7.87054 22.3748 7.93099 22.9535C7.98265 23.4473 8.20477 23.9197 8.58626 24.2689C8.84388 24.5051 9.16585 24.685 9.54525 24.7611C9.93874 24.8401 10.3583 24.8129 10.971 24.6888C11.2973 24.6231 11.603 24.52 11.8939 24.348C12.1817 24.1782 12.4288 23.9522 12.6214 23.6761C12.8148 23.3993 12.9395 23.0911 13.0081 22.764C13.079 22.4268 13.096 22.122 13.096 21.7855V13.2103C13.096 12.7512 13.2256 12.6295 13.596 12.5394C13.596 12.5394 20.1627 11.2154 20.4691 11.1556C20.8963 11.0738 21.0978 11.1952 21.098 11.6429V17.4974C21.098 17.7291 21.0957 17.9638 20.9378 18.1556C20.7813 18.3469 20.5873 18.4048 20.3607 18.4506C20.1888 18.4852 20.0169 18.5194 19.8451 18.5541C19.1924 18.6855 18.7678 18.7751 18.3831 18.9242C18.0155 19.0667 17.7401 19.2486 17.5208 19.4789C17.0861 19.9344 16.8939 20.5525 16.9544 21.1312C17.0061 21.6251 17.2447 22.0975 17.6263 22.4467C17.8839 22.6828 18.2059 22.8572 18.5853 22.934C18.9786 23.0128 19.3978 22.9849 20.0101 22.8617C20.3363 22.796 20.6422 22.6976 20.9329 22.5258C21.2209 22.356 21.4678 22.129 21.6605 21.8529C21.8539 21.5761 21.9785 21.2678 22.0472 20.9408C22.118 20.6036 22.1214 20.2988 22.1214 19.9623V7.42517C22.1227 6.97073 21.8826 6.6901 21.4554 6.72693C21.3912 6.73283 20.82 6.834 20.7513 6.84802Z" fill="white"/>
                                    </svg>
                                </a>
                                <?php endif; ?>

                                <?php if( get_field('podcast_youtube') ) : ?>
                                <a href="<?= get_field('podcast_youtube'); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube Link">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_21524_9963)">
                                        <g clip-path="url(#clip1_21524_9963)">
                                            <path d="M16.2852 5.99023C16.3282 5.99024 25.2255 5.99197 27.4473 6.58789C28.677 6.91726 29.6435 7.88353 29.9727 9.11328C30.5677 11.3405 30.5703 15.9902 30.5703 15.9902C30.5703 15.9902 30.5701 20.64 29.9727 22.8672C29.6435 24.0969 28.677 25.0633 27.4473 25.3926C25.2255 25.9885 16.3282 25.9902 16.2852 25.9902C16.2852 25.9902 7.35024 25.99 5.12305 25.3926C3.89323 25.0633 2.92695 24.097 2.59766 22.8672C2.00029 20.64 2 15.9902 2 15.9902C2 15.9902 2.00029 11.3405 2.59766 9.11328C2.92696 7.88347 3.89325 6.91719 5.12305 6.58789C7.35024 5.99052 16.2852 5.99023 16.2852 5.99023ZM13.4258 20.2754L20.8477 15.9902L13.4258 11.7061V20.2754Z" fill="white"/>
                                        </g>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_21524_9963">
                                                <rect width="28.57" height="20" fill="white" transform="translate(2 5.99023)"/>
                                            </clipPath>
                                            <clipPath id="clip1_21524_9963">
                                                <rect width="28.57" height="20" fill="white" transform="translate(2 5.99023)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    <?php if ($video_link){ ?>
                    <?php } else { ?>
                        <?php $video_link = get_field( 'vimeo_code' ); ?>
                    <?php } ?>
                    <?php if (in_array('portal-preview', $category_slugs)) {?>
                        <?php 
                        $featured_image = get_field( 'featured_image' ); 
                        $featured_image_id = attachment_url_to_postid( $featured_image['url'] );
                        ?>
                            <?php if ( $featured_image_id ) { ?>
                                <div class="hero-image-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?= wp_get_attachment_image($featured_image_id, 'full', false, array('loading' => 'eager', 'fetchpriority' => 'high')); ?>
                                        </span>
                                    </span>
                                </div>
                            <?php } ?>
                    <?php } else { ?>
                        <?php if ($video_link){ ?>
                            <div class="featured-video-container mobile">
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                    <span class="video-container">
                                        <span class="bg-container">
                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                            <?php if ( $video_poster_image ) { ?>
                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                    'alt'          => $video_poster_image['alt'],
                                                    'loading'      => 'eager',
                                                    'fetchpriority' => 'high',
                                                ) ); ?>
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
                                        <?php 
                                        $featured_image = get_field( 'featured_image' ); 
                                        $featured_image_id = attachment_url_to_postid( $featured_image['url'] );
                                        ?>
                                        <?php if ( $featured_image_id ) { ?>
                                            <?= wp_get_attachment_image($featured_image_id, 'full', false, array('loading' => 'eager', 'fetchpriority' => 'high')); ?>
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
                                         <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share-white.svg" alt="" width="32px"/>
                                    <?php } else { ?>
                                         <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share.svg" alt="" width="32px"/>
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
                                <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-white.svg" alt="Share on LinkedIn" width="32px"/>
                                    <?php } else { ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="32px"/>
                                    <?php } ?>
                                </a>
                            </span>
                            <span class="share-twitter share">
                                <a class="twitterShare" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&text=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if($typePost == 'insights' || $typePost == 'expert'){ ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-white.svg" alt="Tweet" width="32px"/>
                                    <?php } else { ?>
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-black.svg" alt="Tweet" width="32px"/>
                                    <?php } ?>
                                </a>
                            </span>
                            <span class="share-email share">
                                <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
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
                        <?php 
                        $theTerms = get_the_terms( $post->ID, 'resource-type' );
                        $isPodcast = false;

                        foreach( $theTerms as $theTerm ){
                            if( $theTerm->slug == 'podcast' ){
                                $isPodcast = true;
                                $postType = $theTerm;
                                break;
                            }
                        }

                        if( !$isPodcast ){
                            if (yoast_get_primary_term_id('resource-type')) {
                                $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                $postType= get_term( $primary_term_type_id );
                            } else {
                                if(get_the_terms( $post->ID, 'resource-type' )){
                                    $terms = get_the_terms( $post->ID, 'resource-type' );
                                    foreach($terms as $term) {
                                        $postType= $term;
                                    }
                                }
                            }
                        }
                        ?>
                        <?php if ( !empty( $postType ) ) { ?>
                            <a href="<?php echo get_term_link($postType); ?>" class="topic-filter red-text"><?php echo $postType->name; ?> </a>
                        <?php } ?>
                        <?php if($typePost == 'press-release' || $typePost == 'media'){ ?>
                        <?php } else { ?>
                            <?php if ( !empty( $postTopic ) ) { ?>
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
                                    <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                        'alt'          => $video_poster_image['alt'],
                                        'loading'      => 'eager',
                                        'fetchpriority' => 'high',
                                    ) ); ?>
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
                <style>
                    .featured-video-audio-container iframe{ display: none; }
                    .audio-player {
                        display: flex;
                        align-items: center;
                        gap: 14px;
                        background: #141414;
                        border: 1px solid #2a2a2a;
                        border-radius: 999px;
                        padding: 10px 22px;
                        margin: 20px auto 0;
                        font-family: inherit;
                    }

                    .audio-player .play-pause {
                        flex: 0 0 38px;
                        width: 38px;
                        height: 38px;
                        border-radius: 50%;
                        background: #fff;
                        color: #111;
                        border: none;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        line-height: 1;
                        padding: 0;
                        padding-left: 2px; /* optical centering for ▶ */
                        transition: all .15s ease;
                    }
                    .audio-player .play-pause:hover { 
                        transform: scale(1.06); 
                        background: #e7534f;
                        color: #fff;
                    }

                    .audio-player .seek-bar {
                        flex: 1;
                        -webkit-appearance: none;
                        appearance: none;
                        height: 4px;
                        border-radius: 2px;
                        background: #3a3a3a;
                        outline: none;
                        cursor: pointer;
                    }
                    .audio-player .seek-bar::-webkit-slider-thumb {
                        -webkit-appearance: none;
                        width: 13px;
                        height: 13px;
                        border-radius: 50%;
                        background: #fff;
                        box-shadow: 0 0 0 4px rgba(255,255,255,.12);
                        cursor: pointer;
                        margin-top: 0;
                    }
                    .audio-player .seek-bar::-moz-range-thumb {
                        width: 13px;
                        height: 13px;
                        border-radius: 50%;
                        background: #fff;
                        border: none;
                        cursor: pointer;
                    }
                    .audio-player .seek-bar::-moz-range-progress {
                        background: #E4002B;
                        height: 4px;
                        border-radius: 2px;
                    }

                    .audio-player .current-time,
                    .audio-player .duration {
                        flex: 0 0 auto;
                        font-size: 12px;
                        color: #a8a8a8;
                        font-variant-numeric: tabular-nums;
                        letter-spacing: .2px;
                    }
                </style>
                <div class="featured-video-audio-container" style="float: left; width: 100%; padding: 0 100px;">
                    <div class="audio-player" data-vimeo-id="<?php echo $video_link; ?>">
                        <div class="vimeo-hidden" aria-hidden="true"></div>
                        <button class="play-pause" type="button" aria-label="Play">▶</button>
                        <input type="range" class="seek-bar" min="0" max="100" value="0" aria-label="Seek audio position">
                        <span class="current-time">0:00</span> / <span class="duration">0:00</span>
                    </div>

                    <script>
                    (function () {
                        function initPlayers() {
                            document.querySelectorAll('.audio-player:not([data-player-initialized])').forEach(function (el) {
                                el.setAttribute('data-player-initialized', 'true');

                                var iframe = document.createElement('iframe');
                                iframe.src = 'https://player.vimeo.com/video/' + el.dataset.vimeoId + '?controls=0';
                                iframe.allow = 'autoplay';
                                iframe.tabIndex = -1;
                                iframe.setAttribute('aria-hidden', 'true');
                                iframe.style.cssText = 'position:fixed!important;top:-9999px!important;left:-9999px!important;width:1px!important;height:1px!important;padding:0!important;margin:0!important;border:0!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;';
                                el.querySelector('.vimeo-hidden').appendChild(iframe);

                                var player = new Vimeo.Player(iframe);
                                var playBtn = el.querySelector('.play-pause');
                                var seekBar = el.querySelector('.seek-bar');
                                seekBar.step = 'any'; // allow fractional seconds, smoother motion

                                var fmt = function (s) {
                                    s = Math.max(0, s || 0);
                                    return Math.floor(s / 60) + ':' + String(Math.floor(s % 60)).padStart(2, '0');
                                };

                                function paintProgress() {
                                    var pct = (seekBar.value / (seekBar.max || 1)) * 100;
                                    seekBar.style.background = 'linear-gradient(to right, #E4002B 0%, #E4002B ' + pct + '%, #3a3a3a ' + pct + '%, #3a3a3a 100%)';
                                }

                                playBtn.addEventListener('click', function () {
                                    player.getPaused().then(function (paused) {
                                        paused ? player.play() : player.pause();
                                    }).catch(function (err) { console.error('Vimeo play error:', err); });
                                });
                                player.on('play', function () { playBtn.textContent = '⏸'; playBtn.setAttribute('aria-label', 'Pause'); });
                                player.on('pause', function () { playBtn.textContent = '▶'; playBtn.setAttribute('aria-label', 'Play'); });

                                player.on('timeupdate', function (d) {
                                    seekBar.max = d.duration;
                                    seekBar.value = d.seconds;
                                    el.querySelector('.current-time').textContent = fmt(d.seconds);
                                    el.querySelector('.duration').textContent = fmt(d.duration);
                                    paintProgress();
                                });

                                seekBar.addEventListener('input', function () {
                                    paintProgress();
                                    player.setCurrentTime(seekBar.value); // seekBar.value is already in seconds, matches max=duration
                                });
                            });
                        }

                        function loadVimeoSDK(cb) {
                            if (window.Vimeo && window.Vimeo.Player) { cb(); return; }
                            var existing = document.querySelector('script[src*="player.vimeo.com/api/player.js"]');
                            if (existing) {
                                existing.addEventListener('load', cb);
                                if (window.Vimeo && window.Vimeo.Player) cb();
                                return;
                            }
                            var script = document.createElement('script');
                            script.src = 'https://player.vimeo.com/api/player.js';
                            script.onload = cb;
                            document.head.appendChild(script);
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', function () { loadVimeoSDK(initPlayers); });
                        } else {
                            loadVimeoSDK(initPlayers);
                        }
                    })();
                    </script>
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
                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                'alt'     => $image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
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
                                           <?php if ($previewContent == false){ ?>
                                                <p><?php echo get_the_excerpt(); ?></p>
                                            <?php } else { 
                                                echo $previewText; 
                                            } ?>                                         
                                        </span>
                                    </span>
                                    <div class="preview-cta-container background-pink">
                                        <div class="preview-cta-inner">
                                            <div class="preview-cta-image-column">
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                                                                'alt'     => $image['alt'],
                                                                'loading' => 'lazy',
                                                            ) ); ?>
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
                                        <span class="preview-cta-bottom-module">Already an ADAPT Research & Advisory Client? <a class="login-link"  href="https://research.adapt.com.au/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $portalURL;?>" target="_blank" rel="noopener noreferrer">Login here</a></span>
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
                    <h3 role="heading" aria-level="2">Access the full article.</h3>
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
            <div class="post-column-container">
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
                            <span class="published-information">Originally published in <a class="publication" href="<?php echo $publicationLink; ?>" target="_blank" rel="noopener noreferrer"><?php echo $publicationName; ?></a></span>
                        </span>
                    <?php } ?>
                    <?php if ( get_field('article_content')){ ?>
                        <div class="post-content-inner article-content">
                            <div class="post-text text-black post-content-text">
                                <?php echo get_field( 'article_content' ); ?>
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
                                                            <?php if ( $team_member_image ) { ?>
                                                                <?php echo wp_get_attachment_image( $team_member_image['ID'], 'full', false, array(
                                                                    'alt'     => $team_member_image['alt'],
                                                                    'loading' => 'lazy',
                                                                ) ); ?>
                                                            <?php } ?>
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
                                    <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                </span>
                            </span>
                            <span class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></span>
                            <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>    				
                            <span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
                        </span>
                    </div>
                </div>
                <div class="sidebar-container">
                    <?php if ( get_field('article_content')){ ?>
                        <span class="subscribe-sidebar-form background-pink position-sticky">
                            <span class="icon-container">
                                <span class="icon-inner">
                                    <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                </span>
                            </span>
                            <span class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></span>
                            <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>

                            <span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
                        </span>                       
                    <?php } ?>
                </div>
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
    
