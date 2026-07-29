<?php
/**
 * Template Name: Search Results
 */

get_header();

?>
<?php global $displayed_posts;
$displayed_posts = array ();
$keyword = $_GET['searchWords'];
$filterType = $_GET['filter-type'];

if($keyword != '') {
    $args = array(
        'ep_integrate'   => true,
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged
    );
} else {
    $args = array(
        'ep_integrate'   => true,
        'post_type' => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged
    );
}
?>

<main class="page flexible search-results" id="main">
    <section class="filter-title-block search-filter-title-block">
        <div class="container">
            <div class="title-container">
                <h2 class="type-title h1-stlye text-black"><span class="text-medium-grey">Search results for: </span><?php echo $keyword; ?></h2>
            </div>
            <div class="topic-button-container-outer">
                <div class="topic-button-container filter-button-container">
                    <a href="/search-results?searchWords=<?php echo $keyword; ?>&sentence=1"class="filter-button<?php if($filterType == '') { ?> selected<?php }?>">All</a>

                    <?php $terms = array(); ?>
                    <?php $loop = new WP_Query( $args ); ?>
                    <?php if ( $loop->have_posts() ) : ?>
                        <?php while ( $loop->have_posts() ) : $loop->the_post();
                            $topics = get_the_terms( $post->ID, 'resource-type' );
                            if($topics){
                                foreach( $topics as $topic ){
                                    if($topic-> parent == 0){
                                        if( ! in_array( $topic, $terms )){
                                            $terms[] = $topic;
                                        }
                                    }
                                }
                            }
                        ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                    <?php endif; ?>
                    <?php wp_reset_query(); ?>
                    <?php foreach($terms as $term) { ?>
                        <a href="/search-results?searchWords=<?php echo $keyword; ?>&sentence=1&filter-type=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterType == '') { } else { if ($term -> slug == $filterType ) { ?> selected<?php }}?><?php if ($term->slug == 'peer-insights'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
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

                $counterargs = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
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
                        array_push($counterargs['tax_query'],array(
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms' => $filterType,
                                'operator' => 'IN'
                            )
                        );
                    }
                }
                $loop = new WP_Query( $counterargs );
                    if ( $loop->have_posts() ) :
                        $counterResults = 0;
                        while ( $loop->have_posts() ) : $loop->the_post();
                            $counterResults++;
                        endwhile;
                    endif;

                    wp_reset_query(); ?>
                <?php $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <span class="total"><span class="text-medium-grey">Showing: </span><span class="text-black"><?php echo $counterResults; ?> results</span></span>
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
                                                        <img src="<?php echo $video_poster_image['url']; ?>" alt="<?php echo $video_poster_image['alt']; ?>" />
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
                                                        <img src="<?php echo $best_practice_listing_image['url']; ?>" alt="<?php echo $best_practice_listing_image['alt']; ?>" />
                                                    <?php } else { ?>
                                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                                        <?php if ( $featured_image ) { ?>
                                                            <img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                                        <?php } ?>
                                                    <?php  }?>
                                                </span>
                                                <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                <?php if ( $listing_hover_image ) { ?>
                                                    <span class="bg-container bg-container-hover">
                                                        <img src="<?php echo $listing_hover_image['url']; ?>" alt="<?php echo $listing_hover_image['alt']; ?>" />
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
                <?php wp_reset_query(); ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
