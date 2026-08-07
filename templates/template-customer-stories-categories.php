
<?php global $displayed_posts;
$displayed_posts = array ();

?>
<?php

$q = get_queried_object();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$filterType = isset($_GET['sub-category']) ? sanitize_text_field($_GET['sub-category']) : '';
$keyword = isset($_GET['searchWords']) ? sanitize_text_field($_GET['searchWords']) : '';

if ($q && $q->parent != 0) {
    // Redirect child category to its parent archive
    $parent_term = get_term($q->parent, 'customer-stories-categories');
    if ($parent_term) {
        wp_redirect(get_term_link($parent_term), 301);
        exit;
    }
}

?>
<?php if ( $filterType || $keyword ) : ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const target = document.querySelector('#storiesListing');
      if (target) {
        target.scrollIntoView({ behavior: 'auto' });
      }
    });
  </script>
<?php endif; ?>
<section class="categories-title-block">
    <div class="container">
        <div class="related-title-container">
            <h2><span class="black-text"><?php echo $q->name; ?><span><br>
                <?php if($q->slug != 'event-delegate'){ ?> 
                    <span class="text-medium-grey">Customer Stories</span>
                <?php } else { ?> 
                    <span class="text-medium-grey">Feedback</span>
                <?php } ?>
                
            </h2>
            <p class="p-large text-dark-grey"><?php echo get_field('sub_title', $q ); ?></p>
        </div>
    </div>
</section>
<?php if ( have_rows( 'featured_stories', $q ) ) : ?>
    <section class="featured-stories">        
        <?php while ( have_rows( 'featured_stories', $q ) ) : the_row(); ?>   
            <div class="container">
                <div class="featured-stories-container">         
                    <?php if ( have_rows( 'stories' ) ) : ?>
                        <?php $featuredCounter = 1;?>
                        <?php while ( have_rows( 'stories' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'story' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?>                                     
                                    <div class="featured-stories-item<?php if($featuredCounter == 1){ ?> first-featured<?php } ?>">
                                        <?php if ( get_field( 'slide_out_story' ) == 1 ) { ?>
                                            <a href="#<?php echo get_post_field( 'post_name', get_post() ); ?>" class="slide-out-story-button">
                                        <?php } else { ?> 
                                            <a href="<?php the_permalink(); ?>">
                                        <?php } ?>    
                                            <?php $video_poster = get_field( 'video_poster' ); ?>
                                            <span class="related-inner <?php if ( $video_poster ) { ?>image-related<?php } ?>">                                                  
                                                <span class="related-top">
                                                    <?php if ( $video_poster ) { ?>
                                                    <span class="background-image-container bg-container">
                                                            <?php echo wp_get_attachment_image( $video_poster['ID'], 'adapt-optimized', false, array(
                                                                'alt'     => $video_poster['alt'],
                                                                'loading' => 'lazy',
                                                            ) ); ?>
                                                        </span>
                                                    <?php } ?>                                                                                                                                                       
                                                </span> 
                                                <span class="related-bottom<?php if($featuredCounter != 1){ ?> not-first<?php } ?>">
                                                    <?php if($featuredCounter == 1){ ?>  
                                                        <span class="top-section">
                                                            <?php $company_logo = get_field( 'company_logo' ); ?>
                                                            <?php if ( $company_logo ) { ?>
                                                                <span class="company-logo-container">
                                                                    <span class="logo-container">
                                                                        <?php echo wp_get_attachment_image( $company_logo['ID'], 'adapt-optimized', false, array(
                                                                            'alt'     => $company_logo['alt'],
                                                                            'loading' => 'lazy',
                                                                        ) ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                        </span>
                                                        <span class="bottom-section">
                                                            <?php
                                                            $terms = get_the_terms(get_the_ID(), 'customer-stories-categories');
                                                            $subcategory = null;

                                                            if (!empty($terms) && !is_wp_error($terms)) {
                                                                foreach ($terms as $term) {
                                                                    if ($term->parent != 0) {
                                                                        $subcategory = $term;
                                                                        break; // just grab the first subcategory
                                                                    }
                                                                }

                                                                if (!$subcategory && isset($terms[0])) {
                                                                    // fallback to first term if no child found
                                                                    $subcategory = $terms[0];
                                                                }
                                                            }
                                                            ?>
                                                            <span class="featured-title labelXsmall">Featured Story</span>                
                                                            <span class="title headerMedium"><?php the_title(); ?></span>                                                            
                                                            <?php $caption = 'false'; ?>
                                                            <?php if ( have_rows( 'video_caption' ) ) : ?>
                                                                <?php $caption = 'true'; ?>
                                                                <?php 
                                                                    $captions = get_field( 'video_caption' );
                                                                    $caption_count = is_array($captions) ? count($captions) : 0;
                                                                    $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                ?>
                                                                <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                    <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>                                                                    
                                                                        <span class="name-role">
                                                                            <span class="name labelXsmall text-white"><?php echo get_sub_field( 'name' ); ?></span>
                                                                            <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                        </span>                                                                    
                                                                    <?php endwhile; ?>
                                                                </span>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>   
                                                            <?php if($caption == 'false'){ ?>  
                                                                <?php if ( have_rows( 'quote_caption' ) ) : ?>
                                                                    <?php 
                                                                        $captions = get_field( 'quote_caption' );
                                                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                    ?>
                                                                    <?php $caption == 'true'; ?>
                                                                    <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                        <?php while ( have_rows( 'quote_caption' ) ) : the_row(); ?>                                                                    
                                                                            <span class="name-role">
                                                                                <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                                                <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                            </span>                                                                    
                                                                        <?php endwhile; ?>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?> 
                                                            <?php } ?>
                                                            <?php if($caption == 'false'){ ?> 
                                                                <?php if ( have_rows( 'bio' ) ) : ?>
                                                                    <?php $caption == 'true'; ?>
                                                                    <?php 
                                                                        $captions = get_field( 'bio' );
                                                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                    ?>
                                                                    <?php $caption = 'true'; ?>
                                                                    <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                        <?php while ( have_rows( 'bio' ) ) : the_row(); ?>                                                                    
                                                                            <span class="name-role">
                                                                                <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                                                <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                            </span>                                                                    
                                                                        <?php endwhile; ?>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?> 
                                                            <?php } ?>                                                                                          
                                                        </span>
                                                    <?php } else { ?>
                                                         <?php
                                                            $terms = get_the_terms(get_the_ID(), 'customer-stories-categories');
                                                            $subcategory = null;

                                                            if (!empty($terms) && !is_wp_error($terms)) {
                                                                foreach ($terms as $term) {
                                                                    if ($term->parent != 0) {
                                                                        $subcategory = $term;
                                                                        break; // just grab the first subcategory
                                                                    }
                                                                }

                                                                if (!$subcategory && isset($terms[0])) {
                                                                    // fallback to first term if no child found
                                                                    $subcategory = $terms[0];
                                                                }
                                                            }
                                                            ?>
                                                            <?php if($subcategory){ ?> 
                                                                <span class="sub-cat label-XSmall">/ <?php echo $subcategory->name; ?></span>
                                                            <?php } else { ?> 
                                                                <span class="sub-cat label-XSmall">/ <?php echo $q->name; ?></span>
                                                            <?php } ?> 
                                                            <?php if ( get_field( 'show_quote_in_listing' ) == 1 ) { ?>
                                                                <?php if ( have_rows( 'content' ) ): ?>
                                                                    <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                                                        <?php if ( get_row_layout() == 'quote' ) : ?>
                                                                            <span class="title labelXL">"<?php echo get_sub_field( 'quote' ); ?>"</span>                                            
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                <?php else: ?>
                                                                    <?php // no layouts found ?>
                                                                <?php endif; ?>                                                                           
                                                            <?php } else { ?>
                                                                <span class="title labelXL"><?php the_title(); ?></span> 
                                                            <?php } ?> 
                                                            <?php $caption = 'false'; ?>
                                                            <?php if ( have_rows( 'video_caption' ) ) : ?>
                                                                <?php $caption = 'true'; ?>
                                                                <?php 
                                                                    $captions = get_field( 'video_caption' );
                                                                    $caption_count = is_array($captions) ? count($captions) : 0;
                                                                    $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                ?>
                                                                <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                    <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>                                                                    
                                                                        <span class="name-role">
                                                                            <span class="name labelXsmall text-white"><?php echo get_sub_field( 'name' ); ?></span>
                                                                            <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                        </span>                                                                    
                                                                    <?php endwhile; ?>
                                                                </span>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>   
                                                            <?php if($caption == 'false'){ ?>  
                                                                <?php if ( have_rows( 'quote_caption' ) ) : ?>
                                                                    <?php 
                                                                        $captions = get_field( 'quote_caption' );
                                                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                    ?>
                                                                    <?php $caption == 'true'; ?>
                                                                    <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                        <?php while ( have_rows( 'quote_caption' ) ) : the_row(); ?>                                                                    
                                                                            <span class="name-role">
                                                                                <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                                                <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                            </span>                                                                    
                                                                        <?php endwhile; ?>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?> 
                                                            <?php } ?>
                                                            <?php if($caption == 'false'){ ?> 
                                                                <?php if ( have_rows( 'bio' ) ) : ?>
                                                                    <?php $caption == 'true'; ?>
                                                                    <?php 
                                                                        $captions = get_field( 'bio' );
                                                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                                    ?>
                                                                    <?php $caption = 'true'; ?>
                                                                    <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                        <?php while ( have_rows( 'bio' ) ) : the_row(); ?>                                                                    
                                                                            <span class="name-role">
                                                                                <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                                                <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                            </span>                                                                    
                                                                        <?php endwhile; ?>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?> 
                                                            <?php } ?>
                                                                                                                
                                                    <?php } ?>                                                    
                                                </span>
                                            </span>
                                        </a>                        
                                    </div>                                    
                                <?php wp_reset_postdata(); ?>
                                 <?php wp_reset_query(); ?>
                            <?php endif; ?>
                             <?php $featuredCounter++;?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
                <div class="slide-out-items">
                    
                     <?php if ( have_rows( 'stories' ) ) : ?>
                        <?php while ( have_rows( 'stories' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'story' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?> 
                                <?php if ( get_field( 'slide_out_story' ) == 1 ) { ?> 
                                    <?php $slug = get_post_field( 'post_name', get_post() ); ?>
                                    <div id="<?php echo $slug; ?>" class="slide-out-story-item">
                                        <span class="close-story"></span>
                                        <span class="story-top">
                                        <?php $company_logo = get_field( 'company_logo' ); ?>
                                            <?php if ( $company_logo ) { ?>
                                                <span class="company-logo-container">
                                                    <span class="logo-container">
                                                        <?php echo wp_get_attachment_image( $company_logo['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $company_logo['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    </span>
                                                </span>
                                            <?php } ?>
                                            <?php if(get_field( 'event_name' )) { ?>
                                                <span class="event-name text-red"><?php echo get_field( 'event_name' ); ?></span>
                                            <?php } ?>
                                            <span class="quote-container">
                                                <span class="h2-size text-black">"<?php echo get_field( 'slide_out_quote' ); ?>"</span>
                                            </span>
                                            <?php if ( have_rows( 'bio' ) ) : ?>
                                                <div class="bio-container">
                                                    <?php while ( have_rows( 'bio' ) ) : the_row(); ?>
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <span class="bio-image-container"><?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                                'alt'     => $image['alt'],
                                                                'loading' => 'lazy',
                                                            ) ); ?></span>
                                                        <?php } ?>
                                                        <span class="bio-text">
                                                            <span class="labelLarge text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                            <span class="labelLarge text-dark-grey"><?php echo get_sub_field( 'role' ); ?> at <?php echo get_sub_field( 'company' ); ?></span>
                                                            <?php if (get_sub_field( 'bio_text' )) {?>
                                                                <p class="p-xsmall text-dark-grey"><?php echo get_sub_field( 'bio_text' ); ?></p>
                                                            <?php } ?> 
                                                            <span class="share-container">
                                                        <span class="share-title labelMedium black-text">Share this</span>
                                                        <span class="share-links-container">
                                                            <span class="copy-link share">
                                                                <input type="text" value="<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>" id="postLink" style="display: none;">
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
                                                                <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                                                    <span class="image-icon-container">
                                                                        <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="24px"/>
                                                                        <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" alt="Share on LinkedIn" width="24px"/>
                                                                    </span>
                                                                </a>
                                                            </span>
                                                            <span class="share-email share">
                                                                <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>" target="_blank" rel="noopener noreferrer">
                                                                    <span class="image-icon-container">
                                                                        <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="24px"/>
                                                                        <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" alt="Share via Email" width="24px"/>
                                                                </a>
                                                            </span>
                                                        </span>
                                                    </span>                                                   
                                                        </span>
                                                    <?php endwhile; ?>                                            
                                                </div>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </span>
                                        <span class="story-bottom">
                                            <span class="events-cta background-red">
                                                <h3 class="white-text">Explore our upcoming executive events</h3>
                                                <a class="text-link large-link-text white-underline-link white-arrow white-text" href="/edge-events/#calendar">View events calendar</a>

                                            </span>
                                        </span>
                                    </div>                  
                                <?php } ?>
                            <?php endif; ?>
                    <?php endwhile; endif;  ?>
                    <?php wp_reset_postdata(); ?>
                    <div class="click-overlay"></div>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
<?php if ( have_rows( 'logo_scroller', $q ) ) : ?>
	<?php while ( have_rows( 'logo_scroller', $q ) ) : the_row(); ?>
        <section class="logo-scroller logo-ticker-tape">
            <div class="container">
                <?php if ( have_rows( 'top_row' ) ) : ?>
                    <?php while ( have_rows( 'top_row' ) ) : the_row(); ?>
                        <div class="band-container-backwards">
                            <span class="moving-text play">
                                <?php if ( have_rows( 'company' ) ) : ?>
                                    <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                        <?php $logo = get_sub_field( 'logo' ); ?>
                                        <?php if ( $logo ) { ?>
                                            <span class="ticker-logo-container">
                                                <span class="bg-container">
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                                    <?php } ?>
                                                        <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                                            'class'   => 'colour-image',
                                                            'alt'     => $logo['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </span>
                            <span class="moving-text play">
                                <?php if ( have_rows( 'company' ) ) : ?>
                                    <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                        <?php $logo = get_sub_field( 'logo' ); ?>
                                        <?php if ( $logo ) { ?>
                                            <span class="ticker-logo-container">
                                                <span class="bg-container">
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                                    <?php } ?>
                                                        <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                                            'class'   => 'colour-image',
                                                            'alt'     => $logo['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'bottom_row' ) ) : ?>
                    <?php while ( have_rows( 'bottom_row' ) ) : the_row(); ?>
                        <div class="band-container-forwards">
                            <span class="moving-text">
                                <?php if ( have_rows( 'company' ) ) : ?>
                                    <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                        <?php $logo = get_sub_field( 'logo' ); ?>
                                        <?php if ( $logo ) { ?>
                                            <span class="ticker-logo-container">
                                                <span class="bg-container">
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                                    <?php } ?>
                                                        <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                                            'class'   => 'colour-image',
                                                            'alt'     => $logo['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </span>
                             <span class="moving-text">
                                <?php if ( have_rows( 'company' ) ) : ?>
                                    <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                        <?php $logo = get_sub_field( 'logo' ); ?>
                                        <?php if ( $logo ) { ?>
                                            <span class="ticker-logo-container">
                                                <span class="bg-container">
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                                    <?php } ?>
                                                        <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                                            'class'   => 'colour-image',
                                                            'alt'     => $logo['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php if(get_sub_field( 'company_link' )) { ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </section>
	<?php endwhile; ?>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
<section class="stories-listing" id="storiesListing">
    <div class="container">
        <div class="filter-container">
            <div class="topic-button-container-outer <?php if($q->slug == 'event-delegate'){ ?>showing-buttons<?php } ?>">
                <div class="title-container">
                    <span class="labelXL text-black">All <?php echo $q -> name; ?> 
                    <?php if($q->slug != 'event-delegate'){ ?> 
                        Stories
                    <?php } else { ?> 
                        Feedback
                    <?php } ?>
                    </span>
                </div>
                <div class="topic-button-container<?php if($q->slug == 'event-delegate'){ ?> show<?php } ?> filter-button-container">
                    <?php 
                        $args = array(
                            'taxonomy'   => 'customer-stories-categories',
                            'parent'     => $q->term_id, // Parent term ID (getting child terms)
                            'hide_empty' => true // Only show terms that have posts associated
                        );

                        // Get child terms
                        $child_terms = get_terms( $args );
                    ?>
                    <a href="<?php echo get_term_link( $q ); ?>?sub-category=all" value="all" class="filter-button-all filter-button<?php if( $filterType === '' || $filterType === 'all' ) echo ' selected'; ?>">All</a>
                    <?php foreach($child_terms as $term) { ?>
                        <a href="<?php echo get_term_link( $q );?>?sub-category=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterType == '') { } else { if ($term -> slug == $filterType ) { ?> selected<?php }}?><?php if ($q->slug == 'peer-insights'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
                    <?php } ?>
                </div>
                <div class="topic-select-container filter-select-container">
                    <?php 
                        $args = array(
                            'taxonomy'   => 'customer-stories-categories',
                            'parent'     => $q->term_id,
                            'hide_empty' => true
                        );
                        $child_terms = get_terms($args);
                    ?>
                    <select id="subCategorySelect" class="filter-select">
                        <option value="<?php echo get_term_link($q); ?>" <?php selected($filterType, ''); ?>>All</option>
                        <?php foreach($child_terms as $term) {
                            $url = get_term_link($q) . '?sub-category=' . $term->slug;
                            $selected = ($filterType === $term->slug) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_url($url); ?>" <?php echo $selected; ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <span class="search-button-container<?php if($keyword != '') { ?> search-open<?php } ?>">
                    <span class="search-button-stories labelSmall">Search </span>
                    <span class="search-button-stories-close labelSmall">Clear </span>
                </span>
            </div>
            <div class="search-container" <?php if($keyword != '') { ?> style="display:block;"<?php } ?>>
                <form method="get">
					<input class="searchInputStories" type="text" name="searchWords" id="search" placeholder="Search for company or topics..." value="<?php echo isset($_GET['searchWords']) ? esc_attr($_GET['searchWords']) : ''; ?>" />
					<input type="hidden" value="1" name="sentence" />
				</form>
            </div>            
        </div>
        <div class="listing-container-outer">  
            <?php 
            $args = array(
            'post_type'      => 'customer_stories',
            'posts_per_page' => 90,
            'paged'          => $paged,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC', // default order
            'tax_query'      => array(
                array(
                    'taxonomy' => 'customer-stories-categories',
                    'field'    => 'slug',
                    'terms'    => $q->slug
                )
            )
        );

        // if there's a keyword, add it
        if ($keyword != '') {
            $args['s'] = $keyword;
        }

        // if a filterType is set and not 'all', add to tax_query
        if (!empty($filterType) && $filterType !== 'all') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'customer-stories-categories',
                'field'    => 'slug',
                'terms'    => $filterType,
                'operator' => 'IN'
            ));

        }
            ?>
            <?php $postloop = new WP_Query( $args ); ?>  
            <?php if ($keyword): ?>
            <div class="search-results-header">
                <?php if ($postloop->have_posts()) : ?>
                    <span class="h2-style"><?php echo $postloop->found_posts; ?> result<?php echo $postloop->found_posts == 1 ? '' : 's'; ?> found</span>
                <?php else : ?>
                    <span class="h2-style">No results found.<br><span style="color:#9A9A9A;">Please try different keywords.</span></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>  
            <div class="listing-container column-container">      
                <?php if ( $postloop->have_posts() ) : ?>
                    <?php while ( $postloop->have_posts() ) : $postloop->the_post(); ?>                    
                    <div class="related-item one-third">
                        <?php if ( get_field( 'slide_out_story' ) == 1 ) { ?>
                            <a href="#<?php echo get_post_field( 'post_name', get_post() ); ?>" class="slide-out-story-button">
                        <?php } else { ?> 
                            <a href="<?php the_permalink(); ?>">
                        <?php } ?>                        
                            <?php $video_poster = get_field( 'video_poster' ); ?>
                            <span class="related-inner">                                                  
                                <span class="related-top">
                                    <?php $company_logo_background = get_field('company_logo_with_background'); ?>
                                    <?php $company_logo = get_field( 'company_logo' ); ?>
                                    <?php if ( $company_logo_background ) { ?>
                                        <span class="company-logo-container background-company-logo">
                                            <span class="logo-container-background">
                                                <?php echo wp_get_attachment_image( $company_logo_background['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $company_logo_background['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            </span>
                                        </span>
                                    <?php } else { ?>
                                        <?php if ( $company_logo ) { ?>
                                            <span class="company-logo-container">
                                                <span class="logo-container">
                                                    <?php echo wp_get_attachment_image( $company_logo['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $company_logo['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                </span>
                                            </span>
                                        <?php } ?> 
                                    <?php } ?>                                                                            
                                </span> 
                                <span class="related-bottom">
                                    <?php
                                    $terms = get_the_terms(get_the_ID(), 'customer-stories-categories');
                                    $subcategory = null;

                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            if ($term->parent != 0) {
                                                $subcategory = $term;
                                                break; // just grab the first subcategory
                                            }
                                        }

                                        if (!$subcategory && isset($terms[0])) {
                                            // fallback to first term if no child found
                                            $subcategory = $terms[0];
                                        }
                                    }
                                    ?>
                                    <?php if($subcategory){ ?> 
                                        <span class="sub-cat label-XSmall">/ <?php echo $subcategory->name; ?></span>
                                    <?php } else { ?> 
                                        <span class="sub-cat label-XSmall">/ <?php echo $q->name; ?></span>
                                    <?php } ?> 
                                    <?php if ( get_field( 'show_quote_in_listing' ) == 1 ) { ?>
                                        <?php if ( have_rows( 'content' ) ): ?>
                                            <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                                <?php if ( get_row_layout() == 'quote' ) : ?>
                                                    <span class="title labelXL">"<?php echo get_sub_field( 'quote' ); ?>"</span>                                            
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <?php // no layouts found ?>
                                        <?php endif; ?>                                                                           
                                    <?php } else { ?>
                                        <span class="title labelXL"><?php the_title(); ?></span> 
                                    <?php } ?> 
                                    <?php $caption = 'false'; ?>                                    
                                    <?php $video_poster = get_field( 'video_poster' ); ?>   
                                    <?php if ( have_rows( 'video_caption' ) ) : ?>
                                        <?php $caption = 'true'; ?>
                                        <?php 
                                            $captions = get_field( 'video_caption' );
                                            $caption_count = is_array($captions) ? count($captions) : 0;
                                            $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                        ?>
                                        <span class="caption-container-outer">
                                            <span class="video-preview">
                                                <span class="video-preview-image">                                                    
                                                    <span class="video-button"></span>                                                
                                                </span>
                                            </span>
                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?> with-video">                                                
                                                <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>                                                                    
                                                    <span class="name-role">
                                                        <span class="name labelXsmall text-white"><?php echo get_sub_field( 'name' ); ?></span>
                                                        <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                    </span>                                                                    
                                                <?php endwhile; ?>
                                            </span>
                                        </span>                                   
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>                                    
                                    <?php if($caption == 'false'){ ?>  
                                        <?php if ( have_rows( 'quote_caption' ) ) : ?>
                                            <?php 
                                                $captions = get_field( 'quote_caption' );
                                                $caption_count = is_array($captions) ? count($captions) : 0;
                                                $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                            ?>
                                            <?php $caption == 'true'; ?>
                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                <?php while ( have_rows( 'quote_caption' ) ) : the_row(); ?>                                                                    
                                                    <span class="name-role">
                                                        <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                        <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                    </span>                                                                    
                                                <?php endwhile; ?>
                                            </span>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?> 
                                    <?php } ?>
                                    <?php if($caption == 'false'){ ?> 
                                        <?php if ( have_rows( 'bio' ) ) : ?>
                                            <?php $caption == 'true'; ?>
                                            <?php 
                                                $captions = get_field( 'bio' );
                                                $caption_count = is_array($captions) ? count($captions) : 0;
                                                $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                            ?>
                                            <?php $caption = 'true'; ?>
                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                <?php while ( have_rows( 'bio' ) ) : the_row(); ?>                                                                    
                                                    <span class="name-role">
                                                        <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                        <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                    </span>                                                                    
                                                <?php endwhile; ?>
                                            </span>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?> 
                                    <?php } ?>
                                   
                                </span>
                            </span>
                        </a>                        
                    </div>
                <?php endwhile; 
                endif;  ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <div class="slide-out-items">
                
                <?php if ( $postloop->have_posts() ) : ?>
                    <?php while ( $postloop->have_posts() ) : $postloop->the_post(); ?> 
                        <?php if ( get_field( 'slide_out_story' ) == 1 ) { ?> 
                            <?php $slug = get_post_field( 'post_name', get_post() ); ?>
                            <div id="<?php echo $slug; ?>" class="slide-out-story-item">
                                <span class="close-story"></span>
                                <span class="story-top">
                                   <?php $company_logo = get_field( 'company_logo' ); ?>
                                    <?php if ( $company_logo ) { ?>
                                        <span class="company-logo-container">
                                            <span class="logo-container">
                                                <?php echo wp_get_attachment_image( $company_logo['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $company_logo['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            </span>
                                        </span>
                                    <?php } ?>
                                    <?php if(get_field( 'event_name' )) { ?>
                                        <span class="event-name text-red"><?php echo get_field( 'event_name' ); ?></span>
                                    <?php } ?>
                                    <span class="quote-container">
                                        <span class="h2-size text-black">"<?php echo get_field( 'slide_out_quote' ); ?>"</span>
                                    </span>
                                    <?php if ( have_rows( 'bio' ) ) : ?>
                                        <div class="bio-container">
                                            <?php while ( have_rows( 'bio' ) ) : the_row(); ?>
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <span class="bio-image-container"><?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?></span>
                                                <?php } ?>
                                                <span class="bio-text">
                                                    <span class="labelLarge text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                    <span class="labelLarge text-dark-grey"><?php echo get_sub_field( 'role' ); ?> at <?php echo get_sub_field( 'company' ); ?></span>
                                                    <?php if (get_sub_field( 'bio_text' )) {?>
                                                        <p class="p-xsmall text-dark-grey"><?php echo get_sub_field( 'bio_text' ); ?></p>
                                                    <?php } ?> 
                                                    <span class="share-container">
                                                <span class="share-title labelMedium black-text">Share this</span>
                                                <span class="share-links-container">
                                                    <span class="copy-link share">
                                                        <input type="text" value="<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>" id="postLink" style="display: none;">
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
                                                        <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank" rel="noopener noreferrer">
                                                            <span class="image-icon-container">
                                                                <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="24px"/>
                                                                <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" alt="Share on LinkedIn" width="24px"/>
                                                            </span>
                                                        </a>
                                                    </span>
                                                    <span class="share-email share">
                                                        <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo get_term_link( $q );?>?story=<?php echo $slug; ?>" target="_blank" rel="noopener noreferrer">
                                                            <span class="image-icon-container">
                                                                <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="24px"/>
                                                                <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" alt="Share via Email" width="24px"/>
                                                        </a>
                                                    </span>
                                                </span>
                                            </span>                                                   
                                                </span>
                                            <?php endwhile; ?>                                            
                                        </div>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                                <span class="story-bottom">
                                    <span class="events-cta background-red">
                                        <h3 class="white-text">Explore our upcoming executive events</h3>
                                        <a class="text-link large-link-text white-underline-link white-arrow white-text" href="/edge-events/#calendar">View events calendar</a>

                                    </span>
                                </span>
                            </div>                  
                        <?php } ?>
                <?php endwhile; endif;  ?>
                <?php wp_reset_postdata(); ?>
                <div class="click-overlay"></div>
            </div>
        </div?>
    </div>
</section>
<?php if($q->slug == 'event-delegate'){ ?> 
    <?php get_template_part( 'templates/event-components/_events-listing' ); ?>
<?php } else { ?>
    <?php if ( have_rows( 'suite_slider', $q) ) : ?>
        <?php while ( have_rows( 'suite_slider', $q ) ) : the_row(); ?>
            <section class="full-suite-slider-module background-true-black">
                <div class="container">
                    <div class="title-container">
                        <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                        <span class="p-small text dark-grey-text"><?php echo get_sub_field( 'text' ); ?></p>
                    </div>
                    <div class="slider-outer">
                        <?php if ( have_rows( 'slides' ) ) : ?>
                            <span class="slide-link-container">
                                <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                    <button type="button" class="slide-link"><?php echo get_sub_field( 'slide_link_title' ); ?></button>
                                <?php endwhile; ?>
                            </span>
                        <?php else : ?>
                        <?php endif; ?>
                        <?php if ( have_rows( 'slides' ) ) : ?>
                            <div class="full-suite-slider">
                                <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                    <div class="full-suite-slide">
                                        <div class="column one-half text-column">
                                            <h4 role="heading" aria-level="3" class="white-text"><?php echo get_sub_field( 'title' ); ?></h4>
                                            <span class="image-container hide-desktop">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $image['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                            <p class="p-xsmall"><?php echo get_sub_field( 'text' ); ?></p>
                                            <a class="red-text red-underline-link red-arrow text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self" aria-label="Learn more about <?php echo esc_attr( wp_strip_all_tags( get_sub_field( 'title' ) ) ); ?>">Learn more</a>
                                        </div>
                                        <div class="column one-half image-column hide-mobile">
                                            <span class="image-container">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $image['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                        <span class="progress-bar-outer"><span class="progress-bar-form-suite"></span></span>
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?>    
<?php } ?>