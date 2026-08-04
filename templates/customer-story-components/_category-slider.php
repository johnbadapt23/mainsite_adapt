<section class="story-categories-slider-module <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <?php $category_ids = get_sub_field( 'category' ); ?>
        <?php 
        $args = array(
            'post_type' => 'customer_stories',
            'posts_per_page' => 4,
            'paged' => $paged,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'customer-stories-categories',
                    'field' => 'id',
                    'terms'    => $category_ids
                )
            ),
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'slide_out_story',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => 'slide_out_story',
                    'value' => '1',
                    'compare' => '!='
                )
            )
        ); 
        $termTax = get_term($category_ids[0], 'customer-stories-categories');
        $term_link = get_term_link($termTax);
        ?>
        <div class="related-title-container">
            <span>                                
                <h2 class="black-text"><?php echo $termTax->name; ?> <?php if($termTax->slug == 'event-delegate'){ ?>Feedback<?php } ?></h2>
                <p class="p-large text-dark-grey"><?php echo get_field('sub_title', $termTax ); ?></p>
            </span>
            <span class="link-container"><a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo $term_link; ?>" target="_self">See more</a></span>
        </div>
        <div class="category-slider-container">
            <?php $postloop = new WP_Query( $args ); ?>         
            <?php if ( $postloop->have_posts() ) : ?>
                <?php while ( $postloop->have_posts() ) : $postloop->the_post(); ?>
                <div class="related-item slide">
                    <?php if ( get_field( 'slide_out_story' ) == 1 ) { ?>
                        <a href="#<?php echo get_post_field( 'post_name', get_post() ); ?>" class="slide-out-story-button">
                    <?php } else { ?> 
                        <a href="<?php the_permalink(); ?>">
                    <?php } ?>                        
                        <?php $video_poster = get_field( 'video_poster' ); ?>
                        <span class="related-inner <?php if ( $video_poster ) { ?>image-related<?php } ?>">                                                  
                            <span class="related-top">
                                <?php $company_logo_background = get_field('company_logo_with_background'); ?>
                                <?php $company_logo = get_field( 'company_logo' ); ?>
                                <?php if ( $company_logo_background ) { ?>
                                    <span class="company-logo-container background-company-logo">
                                        <span class="logo-container-background">
                                            <?php echo wp_get_attachment_image( $company_logo_background['ID'], 'full', false, array(
                                                'alt'     => $company_logo_background['alt'],
                                                'loading' => false,
                                            ) ); ?>
                                        </span>
                                    </span>
                                <?php } else { ?>
                                    <?php if ( $company_logo ) { ?>
                                        <span class="company-logo-container">
                                            <span class="logo-container">
                                                <?php echo wp_get_attachment_image( $company_logo['ID'], 'full', false, array(
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
                                 <?php $video_poster = get_field( 'video_poster' ); ?>   
                                <?php if ( have_rows( 'quote_caption' ) ) : ?>
                                    <?php 
                                        $captions = get_field( 'quote_caption' );
                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                    ?>
                                    <?php if ( $video_poster ) { ?>
                                        <span class="caption-container-outer">
                                            <span class="video-preview">
                                                <span class="video-preview-image">                                                    
                                                    <span class="video-button"></span>                                                
                                                </span>
                                            </span>
                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?> with-video">                                                
                                                <?php while ( have_rows( 'quote_caption' ) ) : the_row(); ?>                                                                    
                                                    <span class="name-role">
                                                        <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                        <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                    </span>                                                                    
                                                <?php endwhile; ?>
                                            </span>
                                        </span>
                                    <?php } else { ?> 
                                        <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">                                            
                                            <?php while ( have_rows( 'quote_caption' ) ) : the_row(); ?>                                                                    
                                                <span class="name-role">
                                                    <span class="name labelXsmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                    <span class="role labelXsmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                </span>                                                                    
                                            <?php endwhile; ?>
                                        </span>
                                    <?php } ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>     
                                <?php if ( have_rows( 'quote_caption' ) ) : ?>
                                    <?php 
                                        $captions = get_field( 'quote_caption' );
                                        $caption_count = is_array($captions) ? count($captions) : 0;
                                        $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                    ?>
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
                            </span>
                        </span>
                    </a>                         
                </div>
            <?php endwhile; endif;  ?>
            <?php wp_reset_postdata(); ?>            
            <div class="related-item slide see-all-slide">
                <a href="<?php echo $term_link; ?>">
                    <span class="related-inner background-red">
                        <span class="content-top">
                            <h3><span class="text-white">See All </span><span class="white-text-60">Customer Stories for <?php echo $termTax->name; ?></span></h3>
                        </span>
                        <span class="arrow-container">
                        </span>
                    </span>
                </a>
            </div>
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
                                            <?php echo wp_get_attachment_image( $company_logo['ID'], 'full', false, array(
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
                                                <span class="bio-image-container"><?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
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
        <span class="slide-counter-outer">
            <span class="slide-counter labelXsmall"><span class="slide-count">1</span>/<span class="slide-total"></span></span>
        </span>

    </div>
</section>