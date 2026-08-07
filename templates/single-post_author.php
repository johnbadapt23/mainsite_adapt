
<section class="speaker-profile author-section">
    <div class="container">
        <div class="column-container">
            <div class="column image-column">
                <span class="mobile-title-container">
                    <h2 class="title h1-style"><?php echo the_title(); ?></h1>
                    <h3 class="subtitle h1-style"><?php echo get_field('speaker_description'); ?></h3>  
                </span>
                <span class="image-container">
                    <span class="bg-container">
                        <?php $team_member_image = get_field( 'team_member_image' ); ?>
                        <?php if ( $team_member_image ) { ?>
                            <?php echo wp_get_attachment_image( $team_member_image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $team_member_image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        <?php } ?>
                    </span>
                    <span class="border-offset"></span>
                </span>
                 <?php if ( get_field ( 'linked_in_url' ) ) { ?>
                    <span class="button-container">
                        <a class="linkedin-button" href="<?php echo get_field('linked_in_url'); ?>" target="_blank" rel="noopener noreferrer"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-white-new.svg" alt="" width="20"/>Follow on LinkedIn</a>
                    </span>
                <?php } ?>                
            </div>
            <div class="column details-column">
                <h1 class="title"><?php echo the_title(); ?></h1>
                <h3 role="heading" aria-level="2" class="subtitle h1-style"><?php echo get_field('speaker_description'); ?></h3>                
                <div class="textBlock black-text">
                    <?php echo get_field('small_description'); ?>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="author-posts-listing">
    <div class="container">
        <div class="title-container">
            <h2>Articles & Research by <?php the_title(); ?></h2>
        </div>
        <div class="list-container" id="postList">
            <?php
            // Fetch all posts related to the author
            $slug = get_post_field('post_name', get_the_ID());
            $contributor_query = new WP_Query([
                'name' => $slug,
                'post_type' => ['team_member', 'post_author'],
                'posts_per_page' => 1,
            ]);

            if ($contributor_query->have_posts()) :
                $contributor_query->the_post();
                $contributor_id = get_the_ID();

                // Query to get all posts for the contributor
                $args = [
                    'post_type' => 'post',
                    'posts_per_page' => -1, // Fetch all posts
                    'meta_query' => [
                        [
                            'key' => 'contributors_$_contributor',
                            'value' => $contributor_id,
                            'compare' => 'LIKE',
                        ],
                    ],
                ];
                $author_posts = new WP_Query($args);

                if ($author_posts->have_posts()) :
                    $post_count = 0;
                    $group_count = 0;

                    while ($author_posts->have_posts()) : $author_posts->the_post();
                        if ($post_count % 6 == 0) {
                            if ($post_count > 0) {
                                echo '</div>'; // Close the previous group
                            }
                            $group_count++;
                            echo '<div class="post-group" data-group="' . $group_count . '"' . ($group_count > 1 ? ' style="display:none;"' : '') . '>'; // Start a new group
                        }
                        ?>
                        <div class="item one-half articles">
                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                            <?php if ($video_link){ ?>
                            <?php } else { ?>
                                <?php $video_link = get_field( 'vimeo_code' ); ?>
                            <?php } ?>
                            <?php if ($video_link){ ?>
                                <a href="<?php the_permalink(); ?>">
                                    <span class="video-container">
                                        <span class="bg-container">
                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                            <?php if ( $video_poster_image ) { ?>
                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $video_poster_image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } else { ?>
                                                <?php $featured_image = get_field( 'featured_image' ); ?>
                                                <?php if ( $featured_image ) { ?>
                                                    <?php echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $featured_image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
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
                            <?php } else { ?>
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                            <?php if ( $featured_image ) { ?>
                                                <?php echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $featured_image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </a>
                                </span>
                            <?php } ?>
                            <span class="item-content-container">
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
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text mobile-hide"><?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                    <?php if ( !empty( $postType ) ) { ?>
                                        <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text">/ <?php echo $postType->name; ?> </a>
                                    <?php } ?>
                                    
                                </span>
                                <a href="<?php the_permalink(); ?>"  target="_self" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                            </span>
                        </div>
                        <?php
                        $post_count++;
                    endwhile;
                    echo '</div>'; // Close the last group

                    // Generate pagination links
                    if ($group_count > 1) {
                        echo '<div class="pagination-container">';
                        for ($i = 1; $i <= $group_count; $i++) {
                            echo '<button type="button" class="pagination-link" data-page="' . $i . '">' . $i . '</button>';
                        }
                        echo '</div>';
                    }                
                    echo '<p>No posts found.</p>';
                endif;

                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>


<?php
$today = date('Ymd'); // Current date in 'Ymd' format

// Get the current author's terms from the 'author-event' taxonomy
$post_author_id = get_the_ID(); // Assuming you are on a single 'post_author' post
$author_terms = get_the_terms($post_author_id, 'author-event');
$currentEvents = 'no'; 
if ($author_terms && !is_wp_error($author_terms)) {
    // Collect the slugs of the author's terms
    $author_term_slugs = array_map(function($term) {
        return $term->slug;
    }, $author_terms);
    
    // Build a query to get all event posts with a future date
    $args = array(
        'post_type' => 'event',
        'meta_key'  => 'date',
        'posts_per_page' => -1,
        'orderby'   => 'meta_value_num',
        'order'     => 'ASC',
        'meta_query' => array(
            array(
                'key'     => 'date',
                'compare' => '>=',
                'value'   => $today,
            )
        ),
    );

    $query = new WP_Query($args);
    $matching_posts = array(); // Array to store matching posts
    
    if ($query->have_posts()): ?>
        <?php while ($query->have_posts()): $query->the_post();
    // Get the event_link field and parse the path
    $event_link = get_field('event_link');
    $parsed_path = parse_url($event_link, PHP_URL_PATH); // Extracts path only

    $path_segments = explode('/', trim($parsed_path, '/')); // Split path into segments
    $event_slug = end($path_segments); // Get the last segment
    print_r($event_slug); // Debugging output

    // Check if the event_slug matches exactly with any author_slug
    foreach ($author_term_slugs as $author_slug) {
        if ($event_slug === $author_slug) {
            $matching_posts[] = get_the_ID();
            break; // No need to check further for this post
        }
    }
endwhile; ?>
    <?php endif;
        wp_reset_postdata(); 


    if (!empty($matching_posts)) { ?>
        <?php $currentEvents = 'yes'; ?>
                <section class="author-events events-listing-module background-black">
            <div class="container">
                <div class="title-container">
                    <h2>Events featuring <?php the_title(); ?></h2>
                </div>
                <div class="events-listing">

        <?php foreach ($matching_posts as $post_id) {
            $post = get_post($post_id);
            setup_postdata($post); // Set up post data for this post ?>
                <?php // Load field value.
                        $date_string = get_field('date');

                        // Create DateTime object from value (formats must match).

                        $date = DateTime::createFromFormat('Ymd', $date_string); 
                        $year = $date->format('Y');
                    
                        // Check if this is the first event of the current year
                        if (!isset($seen_years[$year])) {
                            $seen_years[$year] = true; // Mark the year as seen
                            $id_attribute = 'id="' . $year . '"';
                            $firstClass = 'first-of-year';
                        } else {
                            $id_attribute = ''; // No ID attribute for subsequent events of the same year
                            $firstClass = 'not-first-of-year';
                        }
                        ?>
                        <?php if(get_field('event_link')){ ?> 
                            <a href="<?php echo get_field( 'event_link' ); ?>" target="_blank" rel="noopener noreferrer">
                            <span class="item event-item background-black <?php echo $firstClass; ?> <?php echo $date->format('Y'); ?>" data-date="<?php echo $date->format('Y'); ?>" <?php echo $id_attribute; ?>>
                                <span class="container">                                    
                                    <span class="date-content-container">
                                        <span class="press-date-container column">
                                            <span class="v-wrap">
                                                <span class="v-box">
                                                    <span class="date-inner">
                                                        <span class="date-day text-medium-grey"><?php echo $date->format('j'); ?></span>
                                                        <span class="mobile-container">
                                                            <span class="date-month text-medium-grey labelMedium"><?php echo $date->format('M'); ?></span>
                                                            <span class="date-year text-medium-grey labelMedium"><?php echo $date->format('Y'); ?></span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                        <span class="item-content-container column">
                                            <span class="content-inner">
                                                <span class="title text-white labelXXLarge"><?php the_title(); ?></span>
                                                <p class="location text-dark-grey"><?php echo get_field( 'location' ); ?></p>
                                                <p class="excerpt text-white"><?php echo get_field( 'listing_description' ); ?></p>
                                                <span class="mobile-link-container"><span class="text-link red-text external-link red-underline-link">Learn More</span></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                            </span>                                       
                            </a>
                        <?php } ?>    
       <?php  }

        wp_reset_postdata(); ?>
         </div>
        </section>
    <?php } else {        
    }
}

?>
<section class="author-roundtables author-events events-listing-module background-black <?php if($currentEvents == 'no'){ ?> padding-top<?php } else { ?> no-padding-top<?php } ?>">
    <div class="container">
        <?php $roundtableCounter=0; ?>
        <?php if($currentEvents == 'no'){ ?> 
            <div class="title-container">
                <h2>Roundtables featuring <?php the_title(); ?></h2>
            </div>
        <?php } ?>
        <div class="list-container events-listing">
            <?php
            // Get the slug of the current author
            $slug = get_post_field('post_name', get_the_ID());
            $contributor_query = new WP_Query([
                'name' => $slug,
                'post_type' => 'speaker',
                'posts_per_page' => 1,
            ]);

            if ($contributor_query->have_posts()) :
                $contributor_query->the_post();
                $contributor_id = get_the_ID(); // Get the current speaker's ID
                $today = date('Ymd'); // Current date in 'Ymd' format
                
                // Query to fetch 'registration' posts
                $args = [
                    'post_type' => 'registration',  // We're looking in the 'registration' post type
                    'posts_per_page' => -1,
                    'meta_key'  => 'event_date',
                    'orderby'   => 'meta_value_num',
                    'order'     => 'ASC',
                    'meta_query' => array(
                        array(
                            'key'     => 'event_date',
                            'compare' => '>=',
                            'value'   => $today,
                        )
                    ), // Get all registration posts                   
                ];
                $author_posts = new WP_Query($args);

                if ($author_posts->have_posts()) :
                    while ($author_posts->have_posts()) : $author_posts->the_post();
                        $date_string = get_field('event_date');                        
                        // Create DateTime object from value (formats must match).

                        $date = DateTime::createFromFormat('Ymd', $date_string); 
                        $year = $date->format('Y'); 
                        // Check if the speakers repeater field exists
                        if (have_rows('speakers')) : // Assuming 'speakers' is the name of the repeater field
                            while (have_rows('speakers')) : the_row();
                                
                                // Now, check inside the nested 'speaker' repeater
                                if (have_rows('speaker')) : // Assuming 'speaker' is the nested repeater field
                                    while (have_rows('speaker')) : the_row();
                                        
                                        // Get the speaker post object
                                        $post_object = get_sub_field('speaker');
                                        if ($post_object) {

                                            // If the current speaker matches the one in the loop, show the roundtable
                                            if ($post_object->ID === $contributor_id) {
                                                $roundtableCounter++;
                                                ?>
                                                <span class="item event-item background-black">
                                                    <span class="container">                                    
                                                        <span class="date-content-container">
                                                            <span class="press-date-container column">
                                                                <span class="v-wrap">
                                                                    <span class="v-box">
                                                                        <span class="date-inner">
                                                                            <span class="date-day text-medium-grey"><?php echo $date->format('j'); ?></span>
                                                                            <span class="mobile-container">
                                                                                <span class="date-month text-medium-grey labelMedium"><?php echo $date->format('M'); ?></span>
                                                                                <span class="date-year text-medium-grey labelMedium"><?php echo $date->format('Y'); ?></span>
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                            <span class="item-content-container column">
                                                                <span class="content-inner">
                                                                    <span class="title text-white labelXXLarge"><?php the_title(); ?></span>
                                                                    <p class="location text-white"><?php the_field( 'tag_text' ); ?></p>
                                                                    <p class="excerpt text-white"><?php the_field( 'sub_title' ); ?></p>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>  
                                            <?php
                                            } else { ?>
                                                
                                            <?php }
                                        }

                                    endwhile;
                                endif;
                            endwhile;
                        endif;
                    endwhile;                   
                endif;

                wp_reset_postdata();  // Reset after custom query
            endif;
            ?>
        </div>
        <?php if ($roundtableCounter > 0){
            
        } else { ?>
        <style>
            section.author-events.events-listing-module {
                padding-bottom: 99px;
            }
            section.author-events.events-listing-module.author-roundtables {
                display: none;
            }
        </style>
        <?php } ?>
    </div>
</section>







