<?php

// Includes
require('includes/_hooks.php');
require('includes/_setup.php');
require('includes/_head.php');
require('includes/_menu.php');
require('includes/_widgets.php');
require('includes/_shortcodes.php');
require('includes/_functions.php');
require('includes/_customisations.php');
// includes/_instagram.php archived: the Instagram class it defined was never
// instantiated anywhere in the live templates (templates/partials/_instagram.php
// which used it was likewise unused). Both moved to _archive/.


function cc_mime_types($mimes) {
$mimes['json'] = 'text/plain';
$mimes['svg'] = 'image/svg+xml';
return $mimes;
}

function yasglobal_exclude_post_types( $post_type ) {
  if ( $post_type == 'registration' || $post_type == 'resources' ) {
    return '__true';
  }
  return '__false';
}
add_filter( 'permalinks_customizer_exclude_post_type', 'yasglobal_exclude_post_types');

add_filter('upload_mimes', 'cc_mime_types');

// ACF Google Maps API key is set once via custom_acf_init() in includes/_customisations.php
// (this used to be duplicated here as my_acf_init(), registered a second time on acf/init).

function adapt_admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri(). '/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'adapt_admin_style');

/**
 * Join posts and postmeta tables
 *
 * Front-end search only. Without the is_admin() guard this LEFT JOIN against
 * the full postmeta table ran on every is_search() query — including the
 * wp-admin post list search box, media library search, and ACF/Select2 AJAX
 * lookups — which is what was making wp-admin search slow. The front-end
 * "search post meta too" behavior is unchanged.
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join, $query ) {
    global $wpdb;

    if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join', 10, 2 );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where, $query ) {
    global $wpdb;

    if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where', 10, 2 );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where, $query ) {
    global $wpdb;

    if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct', 10, 2 );

// Don't show duplicate posts in loops
add_filter('post_link', 'track_displayed_posts');
add_action('pre_get_posts','remove_already_displayed_posts');

$displayed_posts = [];

function track_displayed_posts($url) {
  global $displayed_posts;
  $displayed_posts[] = get_the_ID();
  return $url; // don't mess with the url
}

function remove_already_displayed_posts($query) {
 // Only applies to the front-end "avoid duplicate posts in a loop" use case.
 // Without this guard it ran on every WP_Query anywhere, including wp-admin
 // list tables, media queries, and ACF/Select2 AJAX lookups.
 if ( is_admin() || ! $query->is_main_query() ) {
     return;
 }

 global $displayed_posts;
 $query->set('post__not_in', $displayed_posts);
}

/*
 * Set post views count using post meta
 */
function setPostViews($postID) {
    $countKey = 'post_views_count';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '0');
    }else{
        $count++;
        update_post_meta($postID, $countKey, $count);
    }
}

 //To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    setPostViews($post_id);
}
add_action( 'wp_head', 'wpb_track_post_views');

// password protected posts
function gettext_pp( $translation, $text ) {
    if ( $text == 'There is no excerpt because this is a protected post.' ) {
        $post = get_post();
        $translation = $post->post_excerpt;
    }
    return $translation;
}
add_filter( 'gettext', 'gettext_pp', 10, 2 );

function protect_my_privates($text){
  $text="For ADAPT's Research and Advisory Clients Only: %s";
  return $text;
}
add_filter('private_title_format','protect_my_privates');
add_filter('protected_title_format', 'protect_my_privates');

add_action('template_redirect', function() {
    if ( is_singular() ) { // Any single post/page/custom post type
        global $post;

        if ( post_password_required( $post ) ) {
            // Optionally, add a custom message above the form
            echo '<p style="font-weight:bold;">For ADAPT\'s Research and Advisory Clients Only</p>';
            // Show the password form
            echo get_the_password_form();
            exit; // Stop template from rendering until password entered
        }
    }
});


function my_enqueue_scripts() {
    wp_enqueue_style(
        'main-styles',
        get_template_directory_uri(). '/assets/css/main.min.css',
        [],
        filemtime(get_template_directory_uri(). '/assets/css/main.min.css')
    );
    wp_enqueue_script('gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js', array(), null, true);
    wp_enqueue_script('scrolltrigger-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/ScrollTrigger.min.js', array(), null, true);
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/main.min.js',
        array('jquery'),
        filemtime(get_template_directory_uri(). '/assets/js/main.min.js'),
        true
    );

    // Localize the script with AJAX URL
    wp_localize_script('main-js', 'ajaxobject', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

// Speaker Ajax filtering
add_action('wp_ajax_filter_speakers', 'filter_speakers_callback');
add_action('wp_ajax_nopriv_filter_speakers', 'filter_speakers_callback');

function filter_speakers_callback() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $expertise_slugs = isset($_POST['expertise']) ? array_map('sanitize_text_field', $_POST['expertise']) : array();
    $posts_per_page = 12; // Number of posts per page
    $offset = ($paged - 1) * $posts_per_page; 

    $referer = wp_get_referer();

    $post_type = (
        $referer &&
        stripos($referer, '/executive-advisor') !== false
    )
        ? 'executive_advisor'
        : 'speaker';

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'offset' => $offset,
        'tax_query' => array(
            array(
                'taxonomy' => 'expertise',
                'field'    => 'slug',
                'terms'    => $expertise_slugs,
                'operator' => 'IN',
            ),
        ),
        'ignore_custom_sort' => true,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => 'adapt_analyst',
                'compare' => 'EXISTS',
            ),
            array(
                'key'     => 'adapt_analyst',
                'compare' => 'NOT EXISTS',
            ),
            
        ),                        
        'orderby'     => array( 'meta_value' => 'DESC', 'menu_order' => 'ASC' ),
    );

    $speakers_query = new WP_Query($args);

    ob_start();

    if ($speakers_query->have_posts()) {
        while ($speakers_query->have_posts()) {
            $speakers_query->the_post();
            $post_slug = get_post_field('post_name', get_post());
            $term_slugs = wp_get_post_terms(get_the_ID(), 'expertise', array('fields' => 'slugs'));
            $filter_slugs = implode(' ', $term_slugs);
            ?>
            <div class="one-third speaker-item one-third column" data-filter="<?php echo esc_attr($filter_slugs); ?>">
                <a class="slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $team_member_image = get_field('speaker_image'); ?>
                            <img src="<?php echo esc_url($team_member_image); ?>" alt="<?php the_title(); ?>" />
                        </span>
                        <span class="text-container">
                            <h5><?php the_title(); ?></h5>
                            <span class="label-Xsmall white-text"><?php echo esc_html(get_field('speaker_description')); ?></span>
                            <span class="learn-more red-text text-link red-underline-link">Learn More</span>
                        </span>
                    </span>
                    <span class="text-container desktop-hide">
                        <span class="p-small black-text"><?php the_title(); ?></span>
                        <span class="label-Xsmall dakr-grey-text"><?php echo get_field('speaker_description'); ?></span>
                        <span class="learn-more red-text text-link external-link">Learn More</span>
                    </span>
                </a>
                <div id="<?php echo $post_slug; ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'speaker_image' ); ?>
                                    <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>
                                <h3><?php echo get_field('speaker_description'); ?></h3>
                                <a class="linkedin" href="<?php echo get_field('linked_in_url'); ?>"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-new.svg" width="28" /></a>
                            </span>
                        </span>
                        <span class="bio-bottom">
                            <?php echo get_field('speaker_details'); ?>                                                
                        </span>                                               
                    </div>
                    <span class="speaker-button-container">
                        <span class="std-button form-popup-button-container red-button"><?php echo get_field( 'speaker_form_button', 'options' ); ?></span>
                        <span style="display:none"><?php echo get_field( 'speaker_form_script', 'options' ); ?></span>
                    </span>
                </div>
                <div class="click-overlay"></div>
            </div>
            <?php
        }
    } else {
        echo '<p>No speakers found.</p>';
    }

    $response['speakers'] = ob_get_clean();

    ob_start();
?>
    <div class="container">
        <div class="wp-pagenavi" role="navigation">
        <?php 
        echo paginate_links(array(
            'total' => $speakers_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => __('Previous'),
            'next_text' => __('Next'),
        ));
        ?>
        </div>
    </div>
<?php
    $response['pagination'] = ob_get_clean();

    wp_reset_postdata();

    echo json_encode($response);
    wp_die();
}

// Partner Ajax filtering
add_action('wp_ajax_filter_partners', 'filter_partners_callback');
add_action('wp_ajax_nopriv_filter_partners', 'filter_partners_callback');

function filter_partners_callback() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $expertise_slugs = isset($_POST['expertise']) ? array_map('sanitize_text_field', $_POST['expertise']) : array();
    $posts_per_page = 12; // Number of posts per page
    $offset = ($paged - 1) * $posts_per_page; 

    $args = array(
        'post_type' => 'partners',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'offset' => $offset,
        'tax_query' => array(
            array(
                'taxonomy' => 'partner-category',
                'field'    => 'slug',
                'terms'    => $expertise_slugs,
                'operator' => 'IN',
            ),
        )                            
    );

    $partners_query = new WP_Query($args);

    ob_start();

    if ($partners_query->have_posts()) {
        while ($partners_query->have_posts()) {
            $partners_query->the_post();
            $post_slug = get_post_field('post_name', get_post());
            $term_slugs = wp_get_post_terms(get_the_ID(), 'partner-category', array('fields' => 'slugs'));
            $filter_slugs = implode(' ', $term_slugs);
            ?>
            <div class="one-third speaker-item one-third column" data-filter="<?php echo esc_attr( $filter_slugs ); ?>">
                <a class="slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $team_member_image = get_field( 'logo' ); ?>
                            <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                        </span>
                        <span class="text-container mobile-hide">
                            <h5 class="labelMedium"><?php the_title(); ?></h5>                                                    
                        </span>
                    </span>
                    <span class="text-container desktop-hide">
                        <span class="p-small"><?php the_title(); ?></span> 
                        <span class="text-link red-text external-link red-underline-link">Learn More</span>                                                   
                    </span>                                                                             
                </a>
                <div id="<?php echo $post_slug; ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'logo' ); ?>
                                    <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>                                                    
                                <a class="website" href="<?php echo get_field('website_url'); ?>" target="_blank"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/website.svg" width="28" /></a>
                            </span>
                        </span>
                        <span class="bio-bottom">
                            <?php echo get_field('partner_details'); ?>                                               
                        </span>
                        
                    </div>
                        <span class="speaker-button-container">
                            <span class="std-button form-popup-button-container red-button"><?php echo get_field( 'consulting_partners_form_button', 'options' ); ?></span>
                            <span style="display:none"><?php echo get_field( 'consulting_partners_form_script', 'options' ); ?></span>
                        </span>
                </div>
                <div class="click-overlay"></div>
            </div>
            <?php
        }
    } else {
        echo '<p>No partners found.</p>';
    }

    $response['partners'] = ob_get_clean();

    ob_start();
?>
    <div class="container">
        <div class="wp-pagenavi" role="navigation">
        <?php 
        echo paginate_links(array(
            'total' => $partners_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => __('Previous'),
            'next_text' => __('Next'),
        ));
        ?>
        </div>
    </div>
<?php
    $response['pagination'] = ob_get_clean();

    wp_reset_postdata();

    echo json_encode($response);
    wp_die();
}

// Edge Partner Ajax filtering
add_action('wp_ajax_edge_filter_partners', 'edge_filter_partners_callback');
add_action('wp_ajax_nopriv_edge_filter_partners', 'edge_filter_partners_callback');

function edge_filter_partners_callback() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $expertise_slugs = isset($_POST['expertise']) ? array_map('sanitize_text_field', $_POST['expertise']) : array();
    $posts_per_page = 12; // Number of posts per page
    $offset = ($paged - 1) * $posts_per_page; 

    $args = array(
        'post_type' => 'edge_partners',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'offset' => $offset,
        'tax_query' => array(
            array(
                'taxonomy' => 'edge-partner-categories',
                'field'    => 'slug',
                'terms'    => $expertise_slugs,
                'operator' => 'IN',
            ),
        )                            
    );

    $partners_query = new WP_Query($args);

    ob_start();

    if ($partners_query->have_posts()) {
        while ($partners_query->have_posts()) {
            $partners_query->the_post();
            $post_slug = get_post_field('post_name', get_post());
            $term_slugs = wp_get_post_terms(get_the_ID(), 'edge-partner-categories', array('fields' => 'slugs'));
            $filter_slugs = implode(' ', $term_slugs);
            ?>
            <div class="one-third speaker-item one-third column" data-filter="<?php echo esc_attr( $filter_slugs ); ?>">
                <a class="slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $team_member_image = get_field( 'logo' ); ?>
                            <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                        </span>
                        <span class="text-container mobile-hide">
                            <h5 class="labelMedium"><?php the_title(); ?></h5>                                                    
                        </span>
                    </span>  
                    <span class="text-container desktop-hide">
                        <span class="p-small"><?php the_title(); ?></span> 
                        <span class="text-link red-text external-link red-underline-link">Learn More</span>                                                   
                    </span>                                                                           
                </a>
                <div id="<?php echo $post_slug; ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'logo' ); ?>
                                    <img src="<?php echo $team_member_image; ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>                                                    
                                <a class="website" href="<?php echo get_field('website_url'); ?>" target="_blank"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/website.svg" width="28" /></a>
                            </span>
                        </span>
                        <span class="bio-bottom">
                            <?php echo get_field('partner_details'); ?>                                               
                        </span>                                               
                    </div>
                        <span class="speaker-button-container">
                        <a href="#"                                                        
                            data-company="<?php the_title(); ?>" 
                            class="open-form std-button red-button white-text text-white" style="color: #fff;">Request an Introduction
                            </a>                                                                                                        
                        </span>
                    </span>
                </div>
                <div class="click-overlay"></div>
            </div>
            <?php
        }
    } else {
        echo '<p>No partners found.</p>';
    }

    $response['partners'] = ob_get_clean();

    ob_start();
?>
    <div class="container">
        <div class="wp-pagenavi" role="navigation">
        <?php 
        echo paginate_links(array(
            'total' => $partners_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => __('Previous'),
            'next_text' => __('Next'),
        ));
        ?>
        </div>
    </div>
<?php
    $response['pagination'] = ob_get_clean();

    wp_reset_postdata();

    echo json_encode($response);
    wp_die();
}

function wpza_replace_repeater_field( $where ) {
    $where = str_replace( "meta_key = 'contributors_$", "meta_key LIKE 'contributors_%", $where );
    return $where;
}
add_filter( 'posts_where', 'wpza_replace_repeater_field' );
remove_all_actions('wp_mail_failed');





/**
 * Exclude specific JS files from WP Rocket Delay JS only on the homepage.
 */
add_filter( 'rocket_delay_js_exclusions', function( $exclusions ) {

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : '';

    // Homepage only.
    if ( $request_uri === '/' ) {
        $exclusions[] = 'jquery.min.js';
        $exclusions[] = 'gsap.min.js';
        $exclusions[] = 'mediaelement-and-player.min.js';
        $exclusions[] = '/themes/adapt/assets/js/main.min.js';
    }

    return $exclusions;
} );




add_filter('wpseo_use_page_analysis', '__return_false');

add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

add_filter(
    'post_search_columns',
    function ($columns, $search, $query) {
        if (
            is_admin()
            && $query->is_main_query()
            && $query->is_search()
            && $query->get('post_type') === 'post'
        ) {
            return array('post_title');
        }

        return $columns;
    },
    10,
    3
);

?>