<?php
$terms = get_the_terms($post->ID, 'customer-stories-categories');

if (!empty($terms) && !is_wp_error($terms)) {
    $parent_category = null;

    // Find the term that is a parent (parent ID is 0)
    foreach ($terms as $term) {
        if ($term->parent == 0) {
            $parent_category = $term;
            break;
        }
    }

    // If no parent found, check if the term itself has a parent
    if (!$parent_category && isset($terms[0]->parent) && $terms[0]->parent != 0) {
        $parent_category = get_term($terms[0]->parent, 'customer-stories-categories');
    }

    // Assign category details if a valid parent category was found
    if ($parent_category) {
        $category_name = $parent_category->name;
        $category_slug = $parent_category->slug;
        $category_link = get_term_link($parent_category);

        // Get child terms (sub-categories) of the parent category
        $child_terms = get_terms([
            'taxonomy'   => 'customer-stories-categories',
            'parent'     => $parent_category->term_id,
            'hide_empty' => false,
            'number'     => 1, // just get the first one
        ]);

        if (!empty($child_terms) && !is_wp_error($child_terms)) {
            $subcategory_name = $child_terms[0]->name;
            $subcategory_slug = $child_terms[0]->slug;
        }
    }
}

?>

<?php 
global $displayed_posts;
if (!isset($displayed_posts)) {
    $displayed_posts = array();
}

// Add the current post ID to the array
$displayed_posts[] = get_the_ID();
?>

<section class="customer-stories">
    <div class="container">
        <div class="container-inner">
            <span class="back-container"><a class="back" href="<?php echo $category_link; ?>" target="_self">All <?php echo $category_name; ?><?php if($category_slug == 'event-delegate'){ ?> Feedback<?php } ?></a></span>
            <div class="column-container">
                <div class="column sidebar-column">
                    <?php $company_logo_background = get_field('company_logo_with_background'); ?>
                    <?php $company_logo = get_field( 'company_logo' ); ?>
                    <?php if ( $company_logo_background ) { ?>
                        <span class="company-logo-container background-company-logo">
                            <span class="logo-container-background">
                                <img src="<?php echo $company_logo_background['url']; ?>" alt="<?php echo $company_logo_background['alt']; ?>" />
                            </span>
                        </span>
                    <?php } else { ?>
                        <?php if ( $company_logo ) { ?>
                            <span class="company-logo-container">
                                <span class="logo-container">
                                    <img loading="lazy" src="<?php echo $company_logo['url']; ?>" alt="<?php echo $company_logo['alt']; ?>" />
                                </span>
                            </span>
                        <?php } ?> 
                    <?php } ?>     
                    <span class="labelMedium text-black"><?php echo get_field( 'company_name' ); ?></span>
                    <span class="sector-container">
                        <span class="sector-icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/sector.svg" width="20"/></span>
                        <span class="sector-name labelMedium text-dark-grey"><?php echo get_field( 'sector' ); ?></span>
                    </span>
                    <span class="divider-line"></span>
                    <span class="labelMedium text-black">Company Size</span>  
                    <span class="size-text labelMedium text-dark-grey"><?php echo get_field( 'company_size' ); ?></span>
                    <span class="divider-line"></span>
                    <span class="labelMedium text-black">About the Company</span>  
                    <span class="size-text labelMedium text-dark-grey"><?php echo get_field( 'about_company' ); ?></span>                   
                    <span class="divider-line"></span>
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
                        <span class="labelMedium text-black">Service</span>  
                    <span class="size-text labelMedium text-dark-grey"><?php echo $subcategory->name; ?></span>                   
                    <span class="divider-line"></span>
                    <?php } ?>                    
                    <span class="sticky-container">
                        <span class="share-container">
                            <span class="share-title labelMedium black-text">Share this</span>
                            <span class="share-links-container">
                                <span class="copy-link share">
                                    <input type="text" value="<?php echo the_permalink(); ?>" id="postLink" style="display: none;">
                                    <a onclick="copyJobLink()">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link.svg" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link-hover.svg" width="24px"/>
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
                                    <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" alt="Share on LinkedIn" width="24px"/>
                                        </span>
                                    </a>
                                </span>								
                                <span class="share-email share">
                                    <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" width="24px"/>
                                    </a>
                                </span>
                            </span>
                        </span>
                        <span class="divider-line"></span>
                        <span class="side-cta-container">
                            <?php if ( have_rows( 'post_sidebar_content', $parent_category ) ) : ?>
                                <?php while ( have_rows( 'post_sidebar_content', $parent_category ) ) : the_row(); ?>
                                    <span class="subscribe-sidebar-form background-pink">
                                        <span class="icon-container">
                                            <span class="icon-inner">
                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                <?php if ( $icon ) { ?>
                                                    <img loading="lazy" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                <?php } ?>                                    
                                            </span>
                                        </span>
                                        <span class="labelMedium"><?php echo get_sub_field( 'title' ); ?></span>
                                        <?php if ( have_rows( 'links' ) ) : ?>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <span class="link-outer">
                                                    <a class="text-link dark-grey-text arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </span>                                                                                                                                    
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                        <?php if(get_sub_field( 'text' )) { ?>
                                            <p class="labelMedium dark-grey-text"><?php echo get_sub_field( 'text' ); ?></p> 
                                        <?php } ?>
                                        <?php if(get_sub_field( 'form_embed' )) { ?>
                                            <span style="display: none"><?php echo get_sub_field( 'form_embed' ); ?></span>
                                            <span class="form-popup-text-link-container red-text with-red-underline-link medium-link-text with-arrow"><?php echo get_sub_field( 'form_button_embed' ); ?></span>
                                        <?php } ?>
                                    </span>                                                                                              
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
                <div class="column content-column">
                    <?php $company_logo_background = get_field('company_logo_with_background'); ?>
                    <?php $company_logo = get_field( 'company_logo' ); ?>
                    <?php if ( $company_logo_background ) { ?>
                        <span class="company-logo-container background-company-logo desktop-hide">
                            <span class="logo-container-background">
                                <img loading="lazy" src="<?php echo $company_logo_background['url']; ?>" alt="<?php echo $company_logo_background['alt']; ?>" />
                            </span>
                        </span>
                    <?php } else { ?>
                        <?php if ( $company_logo ) { ?>
                            <span class="company-logo-container desktop-hide">
                                <span class="logo-container">
                                    <img loading="lazy" src="<?php echo $company_logo['url']; ?>" alt="<?php echo $company_logo['alt']; ?>" />
                                </span>
                            </span>
                        <?php } ?> 
                    <?php } ?>                         
                    <h1 class="story-title text-black"><?php the_title(); ?></h1>
                    <span class="desktop-hide company-info">
                        <span class="divider-line"></span>
                        <span class="labelMedium text-black"><?php echo get_field( 'company_name' ); ?></span>
                        <span class="sector-container">
                            <span class="sector-icon"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/sector.svg" width="20"/></span>
                            <span class="sector-name labelMedium text-dark-grey"><?php echo get_field( 'sector' ); ?></span>
                        </span>
                        <span class="divider-line"></span>
                        <span class="labelMedium text-black">Company Size</span>  
                        <span class="size-text labelMedium text-dark-grey"><?php echo get_field( 'company_size' ); ?></span>
                        <span class="divider-line"></span>
                        <span class="labelMedium text-black">About the Company</span>  
                        <span class="size-text labelMedium text-dark-grey"><?php echo get_field( 'about_company' ); ?></span>                   
                        <span class="divider-line"></span>
                        <?php if (!empty($terms) && !is_wp_error($terms)) {
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
                            <span class="labelMedium text-black">Service</span>  
                        <span class="size-text labelMedium text-dark-grey"><?php echo $subcategory->name; ?></span>                   
                        <span class="divider-line"></span>
                        <?php } ?>   

                    </span>                    
                    <h5><?php the_excerpt(); ?></h5>
                    <?php if(get_field( 'video_url' )){ ?> 
                        <span class="video-content-outer">
                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_field('video_url'); ?>">
                                <span class="video-image-container">
                                    <span class="bg-container">
                                        <?php $video_poster = get_field( 'video_poster' ); ?>
                                        <?php if ( $video_poster ) { ?>
                                            <img loading="lazy" src="<?php echo $video_poster['url']; ?>" alt="<?php echo $video_poster['alt']; ?>" />
                                        <?php } ?>
                                        <span class="gradient-overlay"></span>
                                        <span class="video-button"></span>
                                        <span class="play-time labelSmall text-white"><?php echo get_field( 'video_play_time' ); ?></span>
                                    </span>
                                </span>
                            </a>
                            <?php if ( have_rows( 'video_caption' ) ) : ?>
                                <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>
                                    <span class="caption-container">
                                        <span class="name-role">
                                            <span class="name labelSmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                            <span class="role labelSmall text-dark-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                        </span>
                                        <span class="company labelSmall text-dark-grey"><?php echo get_sub_field( 'company' ); ?></span>
                                    </span>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    <?php } ?>
                    <div class="story-content">
                        <?php if ( have_rows( 'content' ) ): ?>
                            <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                <?php if ( get_row_layout() == 'text_content' ) : ?>
                                    <span class="text-content"><?php echo get_sub_field( 'text' ); ?></span>
                                <?php elseif ( get_row_layout() == 'quote' ) : ?>
                                    <span class="quote red-text">"<?php echo get_sub_field( 'quote' ); ?>"</span>
                                <?php elseif ( get_row_layout() == 'image' ) : ?>
                                    <span class="image-content-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                        <?php } ?>
                                        <?php if( get_sub_field( 'image_caption' )){ ?> 
                                            <span class="caption"><?php echo get_sub_field( 'image_caption' ); ?></span>
                                        <?php } ?>                                        
                                    </span>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <?php // no layouts found ?>
                        <?php endif; ?>
                    </div>
                    <span class="desktop-hide">
                        <span class="divider-line"></span>
                        <span class="share-container">
                            <span class="share-title labelMedium black-text">Share this</span>
                            <span class="share-links-container">
                                <span class="copy-link share">
                                    <input type="text" value="<?php echo the_permalink(); ?>" id="postLink" style="display: none;">
                                    <a onclick="copyJobLink()">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link.svg" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link-hover.svg" width="24px"/>
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
                                    <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" alt="Share on LinkedIn" width="24px"/>
                                        </span>
                                    </a>
                                </span>								
                                <span class="share-email share">
                                    <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank">
                                        <span class="image-icon-container">
                                            <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="Share via Email" width="24px"/>
                                            <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" width="24px"/>
                                    </a>
                                </span>
                            </span>
                        </span>
                        <span class="divider-line"></span>
                        <span class="side-cta-container">
                            <?php if ( have_rows( 'post_sidebar_content', $parent_category ) ) : ?>
                                <?php while ( have_rows( 'post_sidebar_content', $parent_category ) ) : the_row(); ?>
                                    <span class="subscribe-sidebar-form background-pink">
                                        <span class="icon-container">
                                            <span class="icon-inner">
                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                <?php if ( $icon ) { ?>
                                                    <img loading="lazy" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                <?php } ?>                                    
                                            </span>
                                        </span>
                                        <span class="labelMedium"><?php echo get_sub_field( 'title' ); ?></span>
                                        <?php if ( have_rows( 'links' ) ) : ?>
                                            <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                <span class="link-outer">
                                                    <a class="text-link dark-grey-text arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </span>                                                                                                                                    
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                        <?php if(get_sub_field( 'text' )) { ?>
                                            <p class="labelMedium dark-grey-text"><?php echo get_sub_field( 'text' ); ?></p> 
                                        <?php } ?>
                                        <?php if(get_sub_field( 'form_embed' )) { ?>
                                            <span style="display: none"><?php echo get_sub_field( 'form_embed' ); ?></span>
                                            <span class="form-popup-text-link-container red-text with-red-underline-link medium-link-text with-arrow"><?php echo get_sub_field( 'form_button_embed' ); ?></span>
                                        <?php } ?>
                                    </span>                                                                                              
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    </span>                    
                </div>
            </div>
        </div>
    </div>
</section>
<section class="related-stories">
    <div class="container">
        <div class="related-title-container">
            <h2><span class="black-text"><?php echo $category_name; ?><span><br>
                <?php if($category_slug != 'event-delegate'){ ?> 
                    <span class="text-medium-grey">Customer Stories</span>
                <?php } else { ?> 
                    <span class="text-medium-grey">Feedback</span>
                <?php } ?>
            </h2>
            <span class="link-container"><a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo $category_link; ?>" target="_self">See more</a></span>
        </div>
        <div class="column-container">
            
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
                $current_post_id = get_the_ID(); 
            ?>
            <?php
                $args = array(
                    'post_type' => 'customer_stories',
                    'posts_per_page' => 3,
                    'paged'=> $paged,
                    'post__not_in' => array($current_post_id),
                    'tax_query' => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'customer-stories-categories',
                            'field' => 'slug',
                            'terms'    => $category_slug
                        ),
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
                )
            ?>
            <?php $posts = new WP_Query( $args );
            if( $posts->have_posts() ): ?>
                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <div class="related-item one-third">
                        <a href="<?php the_permalink(); ?>">
                            <?php $video_poster = get_field( 'video_poster' ); ?>
                            <?php $video_poster = get_field( 'video_poster' ); ?>
                            <span class="related-inner">                                                  
                                <span class="related-top">
                                    <?php $company_logo_background = get_field('company_logo_with_background'); ?>
                                    <?php $company_logo = get_field( 'company_logo' ); ?>
                                    <?php if ( $company_logo_background ) { ?>
                                        <span class="company-logo-container background-company-logo">
                                            <span class="logo-container-background">
                                                <img loading="lazy" src="<?php echo $company_logo_background['url']; ?>" alt="<?php echo $company_logo_background['alt']; ?>" />
                                            </span>
                                        </span>
                                    <?php } else { ?>
                                        <?php if ( $company_logo ) { ?>
                                            <span class="company-logo-container">
                                                <span class="logo-container">
                                                    <img loading="lazy" src="<?php echo $company_logo['url']; ?>" alt="<?php echo $company_logo['alt']; ?>" />
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
                                </span>
                            </span>
                        </a>                        
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if($category_slug == 'event-delegate'){ ?> 
    <?php get_template_part( 'templates/event-components/_events-listing' ); ?>
<?php } else { ?>
    <?php if ( have_rows( 'suite_slider', $parent_category) ) : ?>
        <?php while ( have_rows( 'suite_slider', $parent_category ) ) : the_row(); ?>
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
                                    <a class="slide-link" href="#"><?php echo get_sub_field( 'slide_link_title' ); ?></a>
                                <?php endwhile; ?>
                            </span>
                        <?php else : ?>
                        <?php endif; ?>
                        <?php if ( have_rows( 'slides' ) ) : ?>
                            <div class="full-suite-slider">
                                <?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                                    <div class="full-suite-slide">
                                        <div class="column one-half text-column">
                                            <h4 class="white-text"><?php echo get_sub_field( 'title' ); ?></h4>
                                            <span class="image-container hide-desktop">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                    <?php } ?>
                                                </span>
                                            </span>
                                            <p class="p-xsmall"><?php echo get_sub_field( 'text' ); ?></p>
                                            <a class="red-text red-underline-link red-arrow text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Learn more</a>
                                        </div>
                                        <div class="column one-half image-column hide-mobile">
                                            <span class="image-container">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?>

    <?php if ( have_rows( 'form_module', $parent_category ) ) : ?>
        <?php while ( have_rows( 'form_module', $parent_category ) ) : the_row(); ?>
            <?php
            $linkedInLink = get_field( 'linked_in', 'options'  );
            $youtubeLink = get_field( 'you_tube', 'options'  );
            ?>
            <section class="expanding-form-module background-true-black" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
                <div class="container">        
                    <div class="column-container background-red">
                        <span class="grow-container"></span>
                        <div class="column info-column">
                        <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                            <?php if(get_sub_field( 'main_text' )){ ?> 
                                <span class="text white-text hide-mobile p-medium"><?php echo get_sub_field( 'main_text' ); ?></span>
                            <?php } ?>
                            <span class="text white-text hide-mobile p-small"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="socials-container hide-mobile">
                                <span class="text white-text p-small"><?php echo get_sub_field( 'socials_text' ); ?></span>
                                <span class="social-links">
                                    <?php if ($linkedInLink) {?>
                                        <a class="social-link linkedin" href="<?php echo $linkedInLink;?>" target="_blank">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
                                            <style type="text/css">
                                                .st0{fill:#ffffff;}
                                            </style>
                                            <path class="st0" d="M19.3,2V18c0,0.4-0.1,0.7-0.4,1c-0.3,0.3-0.6,0.4-1,0.4H2c-0.4,0-0.7-0.1-1-0.4c-0.3-0.3-0.4-0.6-0.4-1V2
                                                c0-0.4,0.1-0.7,0.4-1c0.3-0.3,0.6-0.4,1-0.4H18c0.4,0,0.7,0.1,1,0.4C19.2,1.3,19.3,1.7,19.3,2z M6.2,7.8H3.4v8.8h2.7V7.8z M6.4,4.8
                                                c0-0.2,0-0.4-0.1-0.6C6.2,4,6.1,3.8,5.9,3.7C5.8,3.5,5.6,3.4,5.4,3.3C5.2,3.2,5,3.2,4.8,3.2h0C4.4,3.2,4,3.4,3.7,3.7
                                                C3.4,4,3.2,4.4,3.2,4.8c0,0.4,0.2,0.8,0.5,1.1C4,6.2,4.4,6.4,4.8,6.4c0.2,0,0.4,0,0.6-0.1c0.2-0.1,0.4-0.2,0.5-0.3
                                                c0.2-0.1,0.3-0.3,0.4-0.5C6.4,5.2,6.4,5,6.4,4.8L6.4,4.8z M16.6,11.3c0-2.6-1.7-3.7-3.3-3.7c-0.5,0-1.1,0.1-1.6,0.3
                                                c-0.5,0.2-0.9,0.6-1.2,1.1h-0.1V7.8H7.8v8.8h2.7v-4.7c0-0.5,0.1-1,0.4-1.3s0.7-0.6,1.2-0.6h0.1c0.9,0,1.5,0.5,1.5,1.9v4.7h2.7
                                                L16.6,11.3z"/>
                                            </svg>

                                        </a>
                                    <?php } ?>
                                    <?php if ($youtubeLink) {?>
                                    <a class="social-link youtube" href="<?php echo $youtubeLink;?>" target="_blank">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 23 15" style="enable-background:new 0 0 23 15;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:#ffffff;}
                                        </style>
                                        <path class="st0" d="M22.2,2.3c-0.1-0.4-0.4-0.9-0.7-1.2c-0.3-0.3-0.8-0.6-1.2-0.7C18.5,0,11.6,0,11.6,0C8.7,0,5.8,0.1,2.9,0.4
                                            C2.4,0.6,2,0.8,1.7,1.1C1.3,1.5,1.1,1.9,1,2.3C0.6,4,0.5,5.7,0.5,7.4c0,1.7,0.1,3.4,0.5,5.1c0.1,0.4,0.4,0.8,0.7,1.2
                                            C2,14,2.4,14.2,2.9,14.3c1.8,0.5,8.7,0.5,8.7,0.5c2.9,0,5.8-0.1,8.7-0.4c0.5-0.1,0.9-0.4,1.2-0.7c0.3-0.3,0.6-0.7,0.7-1.2
                                            c0.3-1.7,0.5-3.4,0.5-5.1C22.7,5.7,22.5,4,22.2,2.3L22.2,2.3z M9.4,10.5V4.2l5.8,3.2L9.4,10.5z"/>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                </span>
                            </span>
                        </div>
                        <div class="column form-column">
                            <span class="form-embed">
                                <?php echo get_sub_field( 'form_embed' ); ?>
                            </span>
                            <span class="text white-text hide-desktop p-small"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="socials-container hide-desktop">
                                <span class="text white-text"><?php echo get_sub_field( 'socials_text' ); ?></span>
                                <span class="social-links">
                                    <?php if ($linkedInLink) {?>
                                        <a class="social-link linkedin" href="<?php echo $linkedInLink;?>" target="_blank">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
                                            <style type="text/css">
                                                .st0{fill:#ffffff;}
                                            </style>
                                            <path class="st0" d="M19.3,2V18c0,0.4-0.1,0.7-0.4,1c-0.3,0.3-0.6,0.4-1,0.4H2c-0.4,0-0.7-0.1-1-0.4c-0.3-0.3-0.4-0.6-0.4-1V2
                                                c0-0.4,0.1-0.7,0.4-1c0.3-0.3,0.6-0.4,1-0.4H18c0.4,0,0.7,0.1,1,0.4C19.2,1.3,19.3,1.7,19.3,2z M6.2,7.8H3.4v8.8h2.7V7.8z M6.4,4.8
                                                c0-0.2,0-0.4-0.1-0.6C6.2,4,6.1,3.8,5.9,3.7C5.8,3.5,5.6,3.4,5.4,3.3C5.2,3.2,5,3.2,4.8,3.2h0C4.4,3.2,4,3.4,3.7,3.7
                                                C3.4,4,3.2,4.4,3.2,4.8c0,0.4,0.2,0.8,0.5,1.1C4,6.2,4.4,6.4,4.8,6.4c0.2,0,0.4,0,0.6-0.1c0.2-0.1,0.4-0.2,0.5-0.3
                                                c0.2-0.1,0.3-0.3,0.4-0.5C6.4,5.2,6.4,5,6.4,4.8L6.4,4.8z M16.6,11.3c0-2.6-1.7-3.7-3.3-3.7c-0.5,0-1.1,0.1-1.6,0.3
                                                c-0.5,0.2-0.9,0.6-1.2,1.1h-0.1V7.8H7.8v8.8h2.7v-4.7c0-0.5,0.1-1,0.4-1.3s0.7-0.6,1.2-0.6h0.1c0.9,0,1.5,0.5,1.5,1.9v4.7h2.7
                                                L16.6,11.3z"/>
                                            </svg>

                                        </a>
                                    <?php } ?>
                                    <?php if ($youtubeLink) {?>
                                    <a class="social-link youtube" href="<?php echo $youtubeLink;?>" target="_blank">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 23 15" style="enable-background:new 0 0 23 15;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:#ffffff;}
                                        </style>
                                        <path class="st0" d="M22.2,2.3c-0.1-0.4-0.4-0.9-0.7-1.2c-0.3-0.3-0.8-0.6-1.2-0.7C18.5,0,11.6,0,11.6,0C8.7,0,5.8,0.1,2.9,0.4
                                            C2.4,0.6,2,0.8,1.7,1.1C1.3,1.5,1.1,1.9,1,2.3C0.6,4,0.5,5.7,0.5,7.4c0,1.7,0.1,3.4,0.5,5.1c0.1,0.4,0.4,0.8,0.7,1.2
                                            C2,14,2.4,14.2,2.9,14.3c1.8,0.5,8.7,0.5,8.7,0.5c2.9,0,5.8-0.1,8.7-0.4c0.5-0.1,0.9-0.4,1.2-0.7c0.3-0.3,0.6-0.7,0.7-1.2
                                            c0.3-1.7,0.5-3.4,0.5-5.1C22.7,5.7,22.5,4,22.2,2.3L22.2,2.3z M9.4,10.5V4.2l5.8,3.2L9.4,10.5z"/>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                </span>
                            </span>
                        </div>			
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?> 
<?php } ?>


