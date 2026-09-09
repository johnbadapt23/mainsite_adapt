<?php
/**
 * Template Name: Search Results
 */

get_header();

?>
<?php global $displayed_posts;
$displayed_posts = array ();
$keyword = sanitize_text_field( $_GET['searchWords'] ?? '' );
$filterType = sanitize_text_field( $_GET['filter-type'] ?? '' );

if($keyword != '') {
    $args = array(
        'ep_integrate'   => true,
        'post_type' => 'post',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        // Only used below to collect which top-level resource-type
        // terms are in use among the matched posts (filter buttons) --
        // never displayed itself, so fields=>ids skips fetching
        // post_content/postmeta for every match instead of full post
        // objects.
        'fields' => 'ids',
        's' => $keyword,
        'paged'=> $paged
    );
} else {
    $args = array(
        'ep_integrate'   => true,
        'post_type' => 'post',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'fields' => 'ids',
        'paged'=> $paged
    );
}
?>

<main class="page flexible search-results" id="main">
    <section class="filter-title-block search-filter-title-block">
        <div class="container">
            <div class="title-container">
                <h2 class="type-title h1-stlye text-black"><span class="text-medium-grey">Search results for: </span><?php echo esc_html( $keyword ); ?></h2>
            </div>
            <div class="topic-button-container-outer">
                <div class="topic-button-container filter-button-container">
                    <a href="/search-results?searchWords=<?php echo urlencode( $keyword ); ?>&sentence=1"class="filter-button<?php if($filterType == '') { ?> selected<?php }?>">All</a>

                    <?php
                    // BUGFIX/PERF 2026-09-09: this used to run a full
                    // posts_per_page=>-1 WP_Query (every matched post,
                    // every column, every meta join) purely to walk its
                    // results and collect distinct top-level
                    // resource-type terms for the filter buttons below --
                    // the posts themselves were never displayed.
                    // fields=>ids (set above) makes the query only fetch
                    // the ID column; get_the_terms() per ID is unchanged
                    // (still one cached lookup per matched post), so the
                    // collected $terms set and its order are identical to
                    // before, just without the full post-object overhead.
                    $terms = array();
                    $loop = new WP_Query( $args );
                    if ( $loop->posts ) :
                        foreach ( $loop->posts as $result_post_id ) :
                            $topics = get_the_terms( $result_post_id, 'resource-type' );
                            if($topics){
                                foreach( $topics as $topic ){
                                    if($topic-> parent == 0){
                                        if( ! in_array( $topic, $terms )){
                                            $terms[] = $topic;
                                        }
                                    }
                                }
                            }
                        endforeach;
                    endif;
                    ?>
                    <?php foreach($terms as $term) { ?>
                        <a href="/search-results?searchWords=<?php echo urlencode( $keyword ); ?>&sentence=1&filter-type=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterType == '') { } else { if ($term -> slug == $filterType ) { ?> selected<?php }}?><?php if ($term->slug == 'peer-insights'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="filter-listing search-listing">
        <div class="container">
            <div class="grid-wrapper">
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    's' => $keyword,
                    'paged'=> $paged,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => 'private-post',
                            'operator' => 'NOT IN',
                        ),
                        'relation' => 'AND',
                    )
                );

                if($filterType != '') {
                    if(empty($filterType)){

                    } else {
                        // print_r($filterType);
                        array_push($args['tax_query'],array(
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms' => $filterType,
                                'operator' => 'IN'
                            )
                        );
                    }
                }

                // Was a full second WP_Query (posts_per_page => -1, same
                // post_type/s/tax_query as $args below) run only to loop
                // through every matching post in PHP and count them --
                // $args itself doesn't set no_found_rows, so WordPress
                // already computes the correct total via
                // SQL_CALC_FOUND_ROWS for the real, paginated query below;
                // $posts->found_posts gives the identical number for free.
                ?>
                <?php $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <span class="total"><span class="text-medium-grey">Showing: </span><span class="text-black"><?php echo $posts->found_posts; ?> results</span></span>
                    <div class="search-results">
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="item full-width">
                                <div class="item-column one-half image-column">
                                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    				<?php if ($video_link){ ?>
                    				<?php } else { ?>
                    					<?php $video_link = get_field( 'vimeo_code' ); ?>
                    				<?php } ?>
                                    <?php if ($video_link){ ?>
                                        <span class="video-container">
                                            <a href="<?php the_permalink(); ?>">
                                                <span class="bg-container">
                                                    <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                    <?php if ( $video_poster_image ) { ?>
                                                        <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $video_poster_image['alt'],
                                                            'loading' => 'lazy',
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
                                            </a>
                                        </span>
                                    <?php } else { ?>
                                        <span class="image-container">
                                            <a href="<?php the_permalink(); ?>">
                                                <span class="bg-container">
                                                    <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                                    <?php if ( $best_practice_listing_image ) { ?>
                                                        <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $best_practice_listing_image['alt'],
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
                                                    <?php  }?>
                                                </span>
                                                <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                <?php if ( $listing_hover_image ) { ?>
                                                    <span class="bg-container bg-container-hover">
                                                        <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                            'alt'     => $listing_hover_image['alt'],
                                                            'loading' => 'lazy',
                                                        ) ); ?>
                                                    </span>
                                                <?php } ?>
                                            </a>
                                        </span>
                                    <?php }?>
                                </div>
                                <div class="item-column one-half text-column">
                                    <span class="item-content-container">
                                        <span class="topic-filter">
                                            <?php if (yoast_get_primary_term_id('topics')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('topics');
                                                $postTopic = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'topics' )){
                                                    $terms = get_the_terms( $post->ID, 'topics' );
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
                                            <?php if ( !empty( $postType ) ) { ?>
                                                <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                            <?php } ?>
                                            <?php if ( !empty( $postTopic ) ) { ?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                        <span class="excerpt text-black"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' );?></span>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <div class="page-navi-container">
            <div class="container">
                <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                    <?php wp_reset_postdata(); ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
