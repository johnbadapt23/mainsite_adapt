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

// ACF Local JSON: field groups are version-controlled as one file per group
// in acf-json/ (named by group key), instead of living only in the DB. ACF
// reads this folder automatically once load_json points at it, and offers
// a "Sync available" action in Custom Fields whenever a group's DB copy and
// its JSON file differ -- nothing here overwrites the DB on its own.
function adapt_acf_json_save_point( $path ) {
	return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'adapt_acf_json_save_point' );

function adapt_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'adapt_acf_json_load_point' );

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


// Lottie player is a ~150-200KB third-party bundle (lottie-player +
// lottie-interactivity, loaded from unpkg) that was previously enqueued on
// every single page regardless of whether that page actually used it -- the
// same waste GSAP/ScrollTrigger had before it was scoped below. Unlike GSAP
// though, every component that renders a <lottie-player> is dispatched
// through an ACF flexible-content field (a different field name and set of
// layouts per template), so a static template whitelist isn't enough on its
// own -- we also need to know whether the *current page's* content actually
// includes one of those layouts.
//
// This only checks whether the layout is present, not whether its own
// animation sub-field is filled in (a couple of these layouts fall back to a
// static image when the animation field is left empty). Loading lottie
// unnecessarily in that rare case is a much smaller cost than risking a
// false negative that silently drops a real animation on a live page.
function adapt_page_needs_lottie() {
    // template-home.php used to have an unconditional loading-screen
    // <lottie-player> at the top of the template regardless of ACF content,
    // which is why this function special-cased it to always return true.
    // That block was commented out directly in the template (commit
    // 0c04931, "disalbed lottie animation") -- template-home.php now only
    // renders a <lottie-player> the same way template-flexible.php does,
    // through its own content_blocks flexible-content field, so it's
    // handled by the same table below instead of a hardcoded special case.
    // IMPORTANT: this runs on wp_enqueue_scripts, i.e. BEFORE the page
    // template's own body/loop executes -- and every field below
    // ('content_blocks' or 'content') is the SAME field each of these
    // templates' own body loops later iterate via
    // have_rows($field)/the_row() to actually render the page. This used
    // to call have_rows()/the_row() here too and return early (`return
    // true` mid-loop) the instant it found a matching row, without
    // exhausting or resetting ACF's internal cursor for that field/post --
    // desyncing it before the real render loop ran, which then started
    // mid-way and could silently skip rows (see the near-identical bug
    // this caused in adapt_page_needs_hubspot_forms_embed(), which dropped
    // an entire flexible-content section from a live page). get_field()
    // returns the raw row array and never touches that shared loop state,
    // so it can't have this side effect.
    $field_by_template = array(
        'templates/template-flexible.php'      => array( 'content_blocks', array( 'introduction_with_animation', 'values_full_screen_blocks' ) ),
        'templates/template-home.php'          => array( 'content_blocks', array( 'introduction_with_animation', 'values_full_screen_blocks' ) ),
        'templates/template-gtm.php'           => array( 'content', array( 'stats_card' ) ),
        'templates/template-market-buyer.php'  => array( 'content_blocks', array( 'two_column_animation_and_icons' ) ),
        'templates/template-services.php'      => array( 'content_blocks', array( 'two_column_animation_and_icons' ) ),
        'templates/template-thank-you-new.php' => array( 'content_blocks', array( 'thank_you_introduction' ) ),
    );

    foreach ( $field_by_template as $template => $config ) {
        if ( ! is_page_template( $template ) ) {
            continue;
        }
        [ $field_name, $layouts ] = $config;
        $rows = get_field( $field_name );
        if ( ! is_array( $rows ) ) {
            return false;
        }
        foreach ( $rows as $row ) {
            if ( isset( $row['acf_fc_layout'] ) && in_array( $row['acf_fc_layout'], $layouts, true ) ) {
                return true;
            }
        }
        return false;
    }

    return false;
}

// GSAP/ScrollTrigger are only actually used by main.js's fixed-scroller /
// sticky-slider-cards animations, which only these 8 templates can ever
// render (each get_template_part() call for those ACF flexible-content
// layouts is hardcoded inside these specific templates' dispatch code --
// confirmed no other template references them). Pulled out of
// my_enqueue_scripts() into its own function so header.php's preconnect
// hint (cdnjs.cloudflare.com) can check the same condition instead of
// preconnecting to an origin most pages never actually contact.
function adapt_page_needs_gsap() {
    return is_page_template( array(
        'templates/template-benchmarking.php',
        'templates/template-comparison.php',
        'templates/template-customer-events.php',
        'templates/template-ecosystem-advisors.php',
        'templates/template-ecosystem-consulting.php',
        'templates/template-edge-consulting.php',
        'templates/template-evr.php',
        'templates/template-partnered-research.php',
    ) );
}

// The speaker/advisor "Submit an Enquiry" popups (_speaker-module.php,
// _advisor-module.php, both dispatched from the 'speaker_module' ACF
// layout) embed HubSpot forms via <div class="hs-form-html"> -- see
// adapt_page_needs_hubspot_forms_embed() below for the loader script this
// gates.
//
// IMPORTANT: this runs on wp_enqueue_scripts, i.e. BEFORE the page
// template's own body/loop executes. It deliberately uses get_field()
// (returns the raw array of rows) instead of have_rows()/the_row() --
// those maintain ACF's internal per-field loop cursor, and this function
// used to call them and return early (via `return true` mid-loop) the
// moment it found a matching row, without exhausting or resetting that
// cursor. Since it checks the SAME 'content' field the page templates
// below loop over later via their own have_rows('content')/the_row() to
// actually render the page, that left the cursor desynced by the time the
// real render loop ran -- causing it to start mid-way and skip the
// speaker_module row entirely, silently dropping the whole section from
// the page. get_field() never touches that shared loop state, so it can't
// have this side effect.
function adapt_page_needs_hubspot_forms_embed() {
    $field_by_template = array(
        'templates/template-customer-events.php'    => array( 'content', array( 'speaker_module' ) ),
        'templates/template-ecosystem-advisors.php'  => array( 'content', array( 'speaker_module' ) ),
    );

    foreach ( $field_by_template as $template => $config ) {
        if ( ! is_page_template( $template ) ) {
            continue;
        }
        [ $field_name, $layouts ] = $config;
        $rows = get_field( $field_name );
        if ( ! is_array( $rows ) ) {
            return false;
        }
        foreach ( $rows as $row ) {
            if ( isset( $row['acf_fc_layout'] ) && in_array( $row['acf_fc_layout'], $layouts, true ) ) {
                return true;
            }
        }
        return false;
    }

    return false;
}

function my_enqueue_scripts() {
    // filemtime() needs a filesystem path, not the public URL. Passing
    // get_template_directory_uri() here silently failed (filemtime() can't
    // stat a remote http:// URL), so $ver was always false and WP fell back
    // to the generic WP-core version string as the cache-busting query arg --
    // meaning CSS/JS changes never busted browser/proxy caches after a
    // deploy. get_template_directory() (filesystem path) fixes that; the
    // enqueued src URL is unchanged.
    // Footer CSS split (promoted to default 2026-09-02, was ?dev=true-gated
    // -- see SESSION-HANDOFF.md #70/#9). partials/_footer.scss is
    // structurally guaranteed to never be above-the-fold (the footer is
    // always the last thing WordPress renders), so its compiled CSS is
    // deferred with no visual risk -- verified rule-for-rule against the
    // old single-bundle main.min.css before this was gated, then confirmed
    // on staging under the gate before promoting (main-nofooter.min.css +
    // footer.min.css together resolve to the exact same selector/property/
    // value set the old main.min.css did; see source/scss/main-nofooter.scss
    // and source/scss/footer-only.scss for the build side). main.min.css
    // itself is still built by build:styles (kept for a rollback path) but
    // is no longer enqueued anywhere.
    wp_enqueue_style(
        'main-styles',
        get_template_directory_uri(). '/assets/css/main-nofooter.min.css',
        [],
        filemtime(get_template_directory(). '/assets/css/main-nofooter.min.css')
    );
    wp_enqueue_style(
        'footer-styles',
        get_template_directory_uri(). '/assets/css/footer.min.css',
        [ 'main-styles' ],
        filemtime(get_template_directory(). '/assets/css/footer.min.css')
    );
    // Loading ~2 CDN scripts on the 60+ templates that never touch GSAP was
    // pure waste -- see adapt_page_needs_gsap().
    if ( adapt_page_needs_gsap() ) {
        wp_enqueue_script('gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js', array(), null, true);
        wp_enqueue_script('scrolltrigger-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/ScrollTrigger.min.js', array(), null, true);
    }

    // Previously loaded as raw <script> tags in header.php ahead of wp_head().
    // Moved into the enqueue system and pinned to the exact version "@latest"
    // was resolving to at time of writing, so behavior today is unchanged but
    // the site no longer silently picks up a new, unreviewed release the next
    // time unpkg's @latest alias moves. Now also scoped to only the pages
    // that actually render a <lottie-player> -- see adapt_page_needs_lottie().
    if ( adapt_page_needs_lottie() ) {
        wp_enqueue_script('lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@2.0.12/dist/lottie-player.js', array(), '2.0.12', false);
        wp_enqueue_script('lottie-interactivity', 'https://unpkg.com/@lottiefiles/lottie-interactivity@1.6.2/dist/lottie-interactivity.min.js', array(), '1.6.2', false);
    }

    // Was previously duplicated as a raw <script src="js.hsforms.net/...">
    // tag inside each speaker/advisor's ACF embed HTML (once per bio card,
    // so up to 12x per page) -- centralised here so it only loads once.
    // The script itself finds every .hs-form-html div on the page (including
    // ones added later, e.g. when a popup opens) and renders a form into it.
    if ( adapt_page_needs_hubspot_forms_embed() ) {
        wp_enqueue_script('hubspot-forms-embed', 'https://js.hsforms.net/forms/embed/developer/8336221.js', array(), null, true);
    }

    // Prefills hidden UTM fields on embedded HubSpot forms (.hs-form-html)
    // from the current page's query string, and hides the row once filled.
    // No-op on pages without a HubSpot form embed, so safe to load site-wide.
    wp_enqueue_script(
        'utm-form-fields',
        get_template_directory_uri() . '/assets/js/utm-form-fields.js',
        array(),
        filemtime(get_template_directory(). '/assets/js/utm-form-fields.js'),
        true
    );

    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/main.min.js',
        array('jquery'),
        filemtime(get_template_directory(). '/assets/js/main.min.js'),
        true
    );

    // Localize the script with AJAX URL. The nonce is checked by the
    // filter_speakers / filter_partners / edge_filter_partners AJAX
    // handlers in functions.php (check_ajax_referer) -- all 3 read-only
    // filter endpoints, but WordPress's own recommended practice for
    // every AJAX handler regardless.
    wp_localize_script('main-js', 'ajaxobject', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('adapt_filter_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

// WP-PageNavi's stylesheet (registered handle 'wp-pagenavi' -- WP's
// style_loader_tag filter is passed the raw handle, NOT the "{handle}-css"
// id WordPress auto-generates for the <link> tag, which is what actually
// shows up in the DOM/devtools) is enqueued by the plugin itself on every
// page, but pagination only actually renders on a handful of listing
// templates (template-insights.php, template-news.php, etc. -- anywhere
// wp_pagenavi() is called). On every other page (including the homepage)
// it was flagged by Lighthouse as a render-blocking request for a
// stylesheet nothing on the page uses. Rather than track down and dequeue
// it per-template (it's also pulled in by a few shared partials used
// across different top-level templates), this defers it everywhere via
// the standard preload+onload swap -- on the pages that DO use it, the
// pagination controls are below the initial viewport, so a few
// milliseconds of async load has no visible effect; on every other page
// it simply stops blocking render. The <noscript> tag preserves the
// original enqueued tag as a fallback with JS disabled.
function adapt_defer_pagenavi_css( $html, $handle ) {
    if ( 'wp-pagenavi' !== $handle ) {
        return $html;
    }
    // Matches rel="stylesheet" or rel='stylesheet' (WP's own output uses
    // single quotes) without reusing the captured quote character in the
    // replacement -- reusing it would break if $html happens to use single
    // quotes, since the replacement's own onload JS string also needs a
    // quote character.
    $preload = preg_replace(
        '/rel=([\'"])stylesheet\1/',
        'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
        $html,
        1
    );
    if ( null === $preload || $preload === $html ) {
        return $html;
    }
    return $preload . '<noscript>' . $html . '</noscript>';
}
add_filter( 'style_loader_tag', 'adapt_defer_pagenavi_css', 10, 2 );

// Same preload+onload deferred-load technique as adapt_defer_pagenavi_css()
// above, applied to the 'footer-styles' handle registered in
// my_enqueue_scripts() -- see the comment there and SESSION-HANDOFF.md
// #70/#9.
function adapt_defer_footer_css( $html, $handle ) {
    if ( 'footer-styles' !== $handle ) {
        return $html;
    }
    $preload = preg_replace(
        '/rel=([\'"])stylesheet\1/',
        'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
        $html,
        1
    );
    if ( null === $preload || $preload === $html ) {
        return $html;
    }
    return $preload . '<noscript>' . $html . '</noscript>';
}
add_filter( 'style_loader_tag', 'adapt_defer_footer_css', 10, 2 );

// WordPress core enqueues wp-block-library CSS (~18KB, render-blocking) on
// every single page regardless of whether Gutenberg block markup is actually
// present. This theme's pages are built entirely through ACF flexible
// content (get_template_part() dispatch, no the_content() call at all on
// any of them) -- only templates/template-default.php and
// templates/template-register.php call the_content(), so those are the only
// two places block CSS could ever matter. Rather than assuming zero posts
// anywhere use the block editor, this checks has_blocks() per-page and only
// dequeues when there's actually nothing to style, so a post that *does*
// contain block markup keeps working exactly as before.
function adapt_maybe_dequeue_block_library_css() {
    if ( is_singular() && has_blocks( get_post() ) ) {
        return;
    }
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'adapt_maybe_dequeue_block_library_css', 100 );

// Imagify's "Next-Gen format" delivery (Settings > Imagify > Optimization)
// works by rewriting <img> tags into <picture> elements with a WebP
// <source> -- it never touches raw attribute values like <video poster="">,
// even though Imagify still generates the matching sibling .webp file on
// disk right next to the original (e.g. photo.jpg.webp). The video-poster
// markup across this theme (introduction block, video blocks, thank-you
// banner) prints $image['url'] straight into poster="", so those posters
// were silently stuck serving the full JPG/PNG. This checks for that
// already-generated .webp sibling on disk and points the poster at it when
// present, falling back to the original URL untouched for any image
// Imagify hasn't processed yet (or if Imagify/WebP generation is ever
// switched off) -- so this can never point at a file that doesn't exist.
function adapt_webp_poster_url( $url ) {
    if ( empty( $url ) ) {
        return $url;
    }
    $upload_dir = wp_get_upload_dir();
    if ( ! str_starts_with( $url, $upload_dir['baseurl'] ) ) {
        return $url;
    }
    $webp_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url ) . '.webp';
    if ( file_exists( $webp_path ) ) {
        return $url . '.webp';
    }
    return $url;
}

// Gate for in-progress CSS work (currently: the sitewide float->flexbox
// modernization pass) that hasn't been visually verified on every page yet.
// Visiting any URL with ?dev=true adds a body class that the gated rules in
// source/scss/sections/_dev-float-refactor.scss target; without it, the
// site renders with the original (untouched) float-based CSS exactly as
// before. This lets that pass be reviewed page-by-page on this exact
// environment before any of it becomes the default for real visitors --
// once a batch is confirmed safe, its rules move out of the gated file and
// the matching original float declarations are removed for real, same as
// was already done for the list-card/flip-card/static-cards modules.
function adapt_dev_gate_body_class( $classes ) {
    if ( isset( $_GET['dev'] ) && $_GET['dev'] === 'true' ) {
        $classes[] = 'dev-float-refactor';
    }
    return $classes;
}
add_filter( 'body_class', 'adapt_dev_gate_body_class' );

// Shared helpers for the 3 AJAX filter callbacks below (speakers,
// partners, edge partners). Their query-building and HTML render loops
// differ enough (different taxonomies/post types/ACF fields, and
// edge_filter_partners_callback even has a structurally different CTA
// button than filter_partners_callback) that merging them into one
// parameterized function isn't worth the regression risk -- but the
// request-param setup and the pagination markup were byte-for-byte
// identical across all 3, so those are pulled out here instead.

/**
 * Pulls the paged/expertise/posts-per-page/offset params every one of
 * the 3 AJAX filter callbacks reads off $_POST the same way.
 */
function adapt_get_ajax_filter_request_params() {
    $paged = intval( $_POST['paged'] ?? 1 );
    $expertise_slugs = array_map( 'sanitize_text_field', $_POST['expertise'] ?? array() );
    $posts_per_page = 12; // Number of posts per page
    $offset = ($paged - 1) * $posts_per_page;

    return array( $paged, $expertise_slugs, $posts_per_page, $offset );
}

/**
 * Renders the "pagination" fragment of the AJAX filter responses --
 * identical markup in all 3 callbacks, just driven by whichever
 * WP_Query ran.
 */
function adapt_render_ajax_filter_pagination( $query, $paged ) {
    ob_start();
    ?>
    <div class="container">
        <div class="wp-pagenavi" role="navigation">
        <?php
        echo paginate_links(array(
            'total' => $query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => __('Previous'),
            'next_text' => __('Next'),
        ));
        ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Speaker Ajax filtering
add_action('wp_ajax_filter_speakers', 'filter_speakers_callback');
add_action('wp_ajax_nopriv_filter_speakers', 'filter_speakers_callback');

function filter_speakers_callback() {
    // Dies with -1 on failure (default check_ajax_referer behavior) --
    // matches the nonce main.js sends via ajaxobject.nonce.
    check_ajax_referer( 'adapt_filter_nonce', 'nonce' );

    [ $paged, $expertise_slugs, $posts_per_page, $offset ] = adapt_get_ajax_filter_request_params();
    // Set by main.js: true when the user actually checked at least one
    // expertise box; false when nothing's checked and $expertise_slugs is
    // instead every checkbox shown on the page (these are ACF-configured
    // per module instance, so we can't infer "no selection" just by
    // comparing $expertise_slugs against some fixed list here).
    $has_selection = ! empty( $_POST['hasSelection'] );

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
        // BUGFIX 2026-09-03 (round 9): a previous round (3) deliberately
        // left 'orderby' unset here on the theory that setting it
        // ourselves would pre-empt Advanced Post Types Order's own
        // reordering. That was backwards -- APTO's own documentation
        // (nsp-code.com "Sample Usage") shows every working example
        // explicitly setting 'orderby' => 'menu_order' so the plugin
        // knows this is a query it should inject its saved order into.
        // Confirmed via APTO's own docs, not just theme-side guessing.
        'orderby' => 'menu_order',
        'order'   => 'ASC',
    );

    // $expertise_slugs is always non-empty in normal operation (either the
    // user's real selection, or every checkbox shown on the page when
    // nothing's checked -- see getSelectedFilters() in main.js). The
    // operator is what actually distinguishes the two cases:
    //  - real selection ($has_selection true): post must have ALL of the
    //    selected terms (operator AND).
    //  - no selection ($has_selection false): post just needs ANY of the
    //    terms shown on this page (operator IN) -- shows everything
    //    relevant to this module instance's ACF-configured expertise list
    //    while still excluding posts with none of those terms.
    if ( ! empty( $has_selection ) ) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'expertise',
                'field'    => 'slug',
                'terms'    => $expertise_slugs,
                'operator' => $has_selection ? 'AND' : 'IN',
            ),
            array(
                'taxonomy' => 'expertise',
                'field'    => 'term_id',
                'terms'    => array( 15788, 15789 ), // adapt-analysts, adapt-advisors
                'operator' => 'IN',
            ),
        );
    } else {
        // Safety net: no slugs at all (e.g. this page renders zero
        // expertise checkboxes). Still exclude untagged posts rather than
        // skipping tax_query entirely.
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'expertise',
                'field'    => 'term_id',
                'terms'    => array( 15788, 15789 ), // adapt-analysts, adapt-advisors
                'operator' => 'IN',
            ),
        );
    }

    // 2026-09-03 (round 9): APTO's own docs note that when more than one
    // configured Sort matches a query's shape, the first one created wins
    // unless you disambiguate with an explicit 'sort_id' -- this is almost
    // certainly why the site's broad "Speakers (Archive)" sort (#66399,
    // created first, no taxonomy Query Rule so it matches every speaker
    // query) kept winning over the dedicated per-term Advanced Sort
    // (#66404, expertise = ADAPT Analysts) we configured for this exact
    // case. Map each expertise term with its own configured Sort here as
    // more get added; terms without an entry just fall through to
    // whatever Sort would otherwise match.
    if ( $has_selection ) {
        $expertise_sort_ids = array(
            'adapt-analysts' => 66404, // "Speakers - Expertise: ADAPT Analysts" Advanced Sort
        );
        foreach ( $expertise_slugs as $slug ) {
            if ( isset( $expertise_sort_ids[ $slug ] ) ) {
                $args['sort_id'] = $expertise_sort_ids[ $slug ];
                break;
            }
        }
    }

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
                <a class="slide-out-bio" href="#<?php echo esc_attr( $post_slug ); ?>" id="<?php echo esc_attr( $post_slug ); ?>">
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
                <div id="<?php echo esc_attr( $post_slug ); ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'speaker_image' ); ?>
                                    <img src="<?php echo esc_url( $team_member_image ); ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>
                                <h3><?php echo get_field('speaker_description'); ?></h3>
                                <a class="linkedin" href="<?php echo get_field('linked_in_url'); ?>"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-new.svg" alt="LinkedIn" width="28" /></a>
                            </span>
                        </span>
                        <span class="bio-bottom">
                            <?php echo get_field('speaker_details'); ?>                                                
                        </span>                                               
                    </div>
                    <span class="speaker-button-container">
                        <?php
                            // Kept in sync with the same HubSpot-embed pattern in
                            // _speaker-module.php / _advisor-module.php -- this AJAX
                            // callback renders the paginated/filtered cards for both
                            // pages (see $post_type above), so it needed the same fix.
                            $speaker_form_id = 'speakerFormEmbed' . get_the_ID();
                        ?>
                        <span class="std-button form-popup-button-container red-button" style="padding: 0;">
                            <?php if( has_term('adapt-analysts', 'expertise') ) : ?>
                                <a class="formPopupHubspot" href="#<?= $speaker_form_id; ?>" data-embed-template="<?= $speaker_form_id; ?>Tpl" data-prefill-title="<?= esc_attr( get_the_title() ); ?>">Submit an Analyst Enquiry</a>
                            <?php elseif( has_term('adapt-advisors', 'expertise') ) : ?>
                                <a class="formPopupHubspot" href="#<?= $speaker_form_id; ?>" data-embed-template="<?= $speaker_form_id; ?>Tpl" data-prefill-title="<?= esc_attr( get_the_title() ); ?>">Submit an Advisor Enquiry</a>
                            <?php endif; ?>
                        </span>
                        <div style="display:none">
                            <div id="<?= $speaker_form_id; ?>">
                                <span class="form">
                                    <template id="<?= $speaker_form_id; ?>Tpl">
                                        <?php if( has_term('adapt-analysts', 'expertise') ) : ?>
                                            <?php echo get_field( 'speaker_form_script', 'options' ); ?>
                                        <?php elseif( has_term('adapt-advisors', 'expertise') ) : ?>
                                            <?php echo get_field( 'speaker_form_script_advisor', 'options' ); ?>
                                        <?php endif; ?>
                                    </template>
                                </span>
                            </div>
                        </div>
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

    $response['pagination'] = adapt_render_ajax_filter_pagination( $speakers_query, $paged );

    wp_reset_postdata();

    echo json_encode($response);
    wp_die();
}

// Partner Ajax filtering
add_action('wp_ajax_filter_partners', 'filter_partners_callback');
add_action('wp_ajax_nopriv_filter_partners', 'filter_partners_callback');

function filter_partners_callback() {
    check_ajax_referer( 'adapt_filter_nonce', 'nonce' );

    [ $paged, $expertise_slugs, $posts_per_page, $offset ] = adapt_get_ajax_filter_request_params();

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
                <a class="slide-out-bio" href="#<?php echo esc_attr( $post_slug ); ?>" id="<?php echo esc_attr( $post_slug ); ?>">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $team_member_image = get_field( 'logo' ); ?>
                            <img src="<?php echo esc_url( $team_member_image ); ?>" alt="<?php the_title(); ?>" />
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
                <div id="<?php echo esc_attr( $post_slug ); ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'logo' ); ?>
                                    <img src="<?php echo esc_url( $team_member_image ); ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>                                                    
                                <a class="website" href="<?php echo get_field('website_url'); ?>" target="_blank"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/website.svg" alt="Website" width="28" /></a>
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

    $response['pagination'] = adapt_render_ajax_filter_pagination( $partners_query, $paged );

    wp_reset_postdata();

    echo json_encode($response);
    wp_die();
}

// Edge Partner Ajax filtering
add_action('wp_ajax_edge_filter_partners', 'edge_filter_partners_callback');
add_action('wp_ajax_nopriv_edge_filter_partners', 'edge_filter_partners_callback');

function edge_filter_partners_callback() {
    check_ajax_referer( 'adapt_filter_nonce', 'nonce' );

    [ $paged, $expertise_slugs, $posts_per_page, $offset ] = adapt_get_ajax_filter_request_params();

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
                <a class="slide-out-bio" href="#<?php echo esc_attr( $post_slug ); ?>" id="<?php echo esc_attr( $post_slug ); ?>">
                    <span class="image-container">
                        <span class="bg-container">
                            <?php $team_member_image = get_field( 'logo' ); ?>
                            <img src="<?php echo esc_url( $team_member_image ); ?>" alt="<?php the_title(); ?>" />
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
                <div id="<?php echo esc_attr( $post_slug ); ?>" class="full-bio">
                    <div class="bio-content-wrapper">
                        <span class="close-bio"></span>
                        <span class="bio-top">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $team_member_image = get_field( 'logo' ); ?>
                                    <img src="<?php echo esc_url( $team_member_image ); ?>" alt="<?php the_title(); ?>" />
                                </span>
                                <span class="border-offset"></span>
                            </span>
                            <span class="text">
                                <h2><?php the_title(); ?></h2>                                                    
                                <a class="website" href="<?php echo get_field('website_url'); ?>" target="_blank"><img class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/website.svg" alt="Website" width="28" /></a>
                            </span>
                        </span>
                        <span class="bio-bottom">
                            <?php echo get_field('partner_details'); ?>                                               
                        </span>                                               
                    </div>
                        <span class="speaker-button-container">
                        <button type="button"
                            data-company="<?php the_title(); ?>"
                            class="open-form std-button red-button white-text text-white" style="color: #fff;">Request an Introduction
                            </button>
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

    $response['pagination'] = adapt_render_ajax_filter_pagination( $partners_query, $paged );

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
        // Built from get_template() instead of a hardcoded folder name, so
        // this keeps matching main.min.js correctly if the theme folder is
        // ever renamed (e.g. a parallel "adapt_optimize" deploy folder used
        // for testing before switching the active theme).
        $exclusions[] = '/themes/' . get_template() . '/assets/js/main.min.js';
    }

    return $exclusions;
} );


add_filter( 'pre_get_rocket_option_remove_unused_css', function( $value ) {
    if ( is_front_page() ) {
        return 0;
    }
    return $value;
} );


add_filter('wpseo_use_page_analysis', '__return_false');

add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

// header.php already outputs its own og:image, driven by the ACF fields
// editors actually use (featured_image / seo_image / video_poster). Yoast's
// own Open Graph output would otherwise add a second, conflicting og:image
// (and og:title/og:description/og:url) with no de-duplication, so Yoast's
// Open Graph module is disabled here in favour of the theme's version.
add_filter('wpseo_opengraph', '__return_false');

// Yoast SEO already builds the site's JSON-LD structured-data graph
// (Organization/WebSite/Article, etc.) automatically, so the theme should
// not output a second, competing schema block. This only fills in the
// Organization "logo" property -- using the same ACF logo field the header
// already renders -- as a fallback for when a logo hasn't been separately
// set in Yoast's own Site Identity / Company Logo setting. If Yoast already
// has a logo configured, this leaves Yoast's value untouched.
add_filter( 'wpseo_schema_organization', function ( $data ) {
    if ( ! empty( $data['logo']['url'] ) ) {
        return $data;
    }

    $logo = get_field( 'icon', 'options' );
    if ( empty( $logo['url'] ) ) {
        $logo = get_field( 'logo_dark_theme', 'options' );
    }

    if ( empty( $logo['url'] ) ) {
        return $data;
    }

    $data['logo'] = array(
        '@type'      => 'ImageObject',
        'url'        => $logo['url'],
        'contentUrl' => $logo['url'],
    );

    if ( ! empty( $logo['width'] ) ) {
        $data['logo']['width'] = (int) $logo['width'];
    }
    if ( ! empty( $logo['height'] ) ) {
        $data['logo']['height'] = (int) $logo['height'];
    }
    if ( ! empty( $logo['alt'] ) ) {
        $data['logo']['caption'] = $logo['alt'];
    }

    return $data;
} );

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

// ROLLBACK 2026-09-03 (round 8): rounds 6, 7, and 8 have now ALL crashed
// /analyst-presentations, despite three completely different diagnostic
// implementations (unsafe string concat; wp_footer + esc_html/wp_json_encode;
// and, this last time, a file_put_contents-based logger wrapped in
// try/catch(Throwable), which by construction should not have been able to
// throw past its own try block). The fact that all three failed identically
// (same 500, same response length) strongly suggests the crash is NOT caused
// by anything specific to each diagnostic's code, but by something else --
// possibly in how this page's internal loopback request for its initial
// speaker list behaves under CI/deploy conditions, or a deploy-sync issue
// separate from this function's logic entirely. Do not add another
// diagnostic directly inside apto/get_orderby until that's understood;
// investigate the loopback request and deploy pipeline first.
add_filter( 'apto/get_orderby', 'my_theme_apto_taxonomy_scoped_orderby', 10, 3 );
function my_theme_apto_taxonomy_scoped_orderby( $new_orderby, $orderby, $query ) {
    global $wpdb;

    if ( is_admin() ) {
        return $new_orderby;
    }

    if ( ! function_exists( 'apto_get_order_list' ) ) {
        return $new_orderby;
    }

    // BUGFIX 2026-09-03 (round 5): expertise (speaker / executive_advisor
    // AJAX filters) is resolved independently below, straight off this
    // query's own tax_query, WITHOUT going through
    // apto_get_query_post_type_taxonomy() / apto_get_order_type() the way
    // the resource-type branch further down does. Confirmed via Debug
    // Marks that round 4 (routing expertise through that same
    // resolve-via-APTO's-own-match path) still produced the unscoped
    // whole-post-type Archive FIELD() list, unchanged. The likely reason:
    // Sort #66399 ("Speakers (speaker)", no taxonomy Query Rule, Auto
    // Apply Sort = Yes) matches every speaker query -- including the
    // taxonomy-scoped ones meant for the dedicated per-term Advanced Sort
    // (e.g. #66404, scoped to expertise=ADAPT Analysts) -- so
    // apto_get_query_post_type_taxonomy() likely resolves against #66399
    // instead (taxonomy empty) and/or apto_get_order_type() reports 'auto'
    // (since #66399 itself has Auto Apply Sort enabled), tripping this
    // function's own early-return guards before the expertise-specific
    // logic ever ran. Reading the term directly off the query and calling
    // apto_get_order_list() with it sidesteps APTO's own match resolution
    // entirely, so it can't be misdirected to the wrong Sort.
    $post_type = $query->get( 'post_type' );

    if ( in_array( $post_type, array( 'speaker', 'executive_advisor' ), true ) ) {
        $term_id = 0;

        foreach ( (array) $query->get( 'tax_query' ) as $clause ) {
            if ( is_array( $clause ) && isset( $clause['taxonomy'] ) && 'expertise' === $clause['taxonomy'] ) {
                $term = get_term_by( $clause['field'], reset( (array) $clause['terms'] ), 'expertise' );
                if ( $term ) {
                    $term_id = $term->term_id;
                }
                break;
            }
        }

        if ( $term_id ) {
            $order_list = apto_get_order_list( $post_type, $term_id, 'expertise', $query );

            // count() on a non-array/non-Countable is a fatal TypeError on
            // PHP 8, so check the type before counting rather than
            // assuming the plugin always returns an array.
            if ( is_array( $order_list ) && count( $order_list ) > 0 ) {
                return "FIELD({$wpdb->posts}.ID, " . implode( ',', array_map( 'absint', $order_list ) ) . ")";
            }
        }

        // No expertise clause found, or no manual order set for that term
        // (e.g. the two-term "no selection" safety-net query, or a term
        // without its own Advanced Sort yet) -- fall through to whatever
        // APTO/WordPress would otherwise have used.
        return $new_orderby;
    }

    // These two are provided by the Advanced Post Types Order plugin, not
    // this theme. Guarding against them missing (plugin deactivated, or a
    // future update renames one) so this degrades to the default order
    // instead of a fatal "Call to undefined function" on every front-end
    // page that runs a resource-type query.
    if ( ! function_exists( 'apto_get_query_post_type_taxonomy' )
        || ! function_exists( 'apto_get_order_type' )
    ) {
        return $new_orderby;
    }

    $post_type_taxonomy = apto_get_query_post_type_taxonomy( $query );

    // Defensive against the plugin ever returning something other than a
    // clean [post_type, taxonomy] pair -- list() on anything shorter throws
    // an "Undefined array key" warning per element on PHP 8.
    if ( ! is_array( $post_type_taxonomy ) || count( $post_type_taxonomy ) < 2 ) {
        return $new_orderby;
    }

    [ $post_type, $taxonomy ] = $post_type_taxonomy;

    if ( 'resource-type' !== $taxonomy ) {
        return $new_orderby;
    }

    // Don't override if this query already resolves to an "automatic" APTO order
    if ( 'auto' === apto_get_order_type( $query ) ) {
        return $new_orderby;
    }

    // Resolve the resource-type term_id from this query's own tax_query
    $term_id = 0;
    foreach ( (array) $query->get( 'tax_query' ) as $clause ) {
        if ( is_array( $clause ) && isset( $clause['taxonomy'] ) && 'resource-type' === $clause['taxonomy'] ) {
            $term = get_term_by( $clause['field'], reset( (array) $clause['terms'] ), 'resource-type' );
            if ( $term ) {
                $term_id = $term->term_id;
            }
            break;
        }
    }

    if ( ! $term_id ) {
        return $new_orderby;
    }

    $order_list = apto_get_order_list( $post_type, $term_id, $taxonomy, $query );

    // count() on a non-array/non-Countable is a fatal TypeError on PHP 8, so
    // check the type before counting rather than assuming the plugin always
    // returns an array.
    if ( is_array( $order_list ) && count( $order_list ) > 0 ) {
        // Manually ordered via Advanced Post Types Order — respect it
        $new_orderby = "FIELD({$wpdb->posts}.ID, " . implode( ',', array_map( 'absint', $order_list ) ) . ")";
    } else {
        // No manual order set for this term — fall back to date, newest first
        $new_orderby = "{$wpdb->posts}.post_date DESC";
    }

    return $new_orderby;
}

?>