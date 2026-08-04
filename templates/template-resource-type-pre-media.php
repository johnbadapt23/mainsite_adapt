<?php global $displayed_posts;
$displayed_posts = array ();

?>
<?php

$q = get_queried_object();
$resourceType = get_field( 'type', $q );
$keyword = $_GET['searchWords'];
$filterTopic = $_GET['filter-topic'];

if($keyword != '') {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'resource-type',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
} else {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'resource-type',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
}
?>
<?php if ($q->slug == 'in-the-news'){ ?>
    <section class="filter-listing in-the-news-listing">
        <div class="container">
            <div class="title-container">
                <h1 class="type-title text-black"><?php echo $q->name; ?></h1>
                <span class="type-description text-black"><?php echo $q->description; ?></span>
            </div>
        </div>
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 5,
                'paged'=> $paged,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'resource-type',
                        'field' => 'slug',
                        'terms'    => $q->slug
                    ),
                )
            );
            $posts = new WP_Query( $args );
            if( $posts->have_posts() ): ?>
                <div class="press-listing-container">
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php get_template_part( 'templates/types-components/_press-releases-all' ); ?>
                    <?php endwhile; ?>
                </div>
            <?php endif;?>
    </section>
<?php } else { ?>
    <section class="filter-title-block<?php if ($q->slug == 'peer-insights'){ ?> background-black<?php } ?>">
        <div class="container">
            <div class="title-container">
                <h1 class="type-title<?php if ($q->slug == 'peer-insights'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?php echo $q->name; ?></h1>
                <p class="type-description<?php if ($q->slug == 'peer-insights'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?php echo $q->description; ?></p>
            </div>
            <div class="topic-button-container-outer">
                <div class="topic-button-container filter-button-container">
                    <a class="all filter-button<?php if ( $keyword != '' || $filterTopic != '' ){ ?><?php } else { ?> selected<?php } ?><?php if ($q->slug == 'peer-insights'){ ?> peer-insights<?php } ?>" href="<?php echo get_term_link( $q );?>">All</a>
                    <?php $terms = array(); ?>
                    <?php $loop = new WP_Query( $args ); ?>
                    <?php if ( $loop->have_posts() ) : ?>
                        <?php while ( $loop->have_posts() ) : $loop->the_post();
                            $topics = get_the_terms( $post->ID, 'topics' );
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
                        <a href="<?php echo get_term_link( $q );?>?filter-topic=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterTopic == '') { } else { if ($term -> slug == $filterTopic ) { ?> selected<?php }}?><?php if ($q->slug == 'peer-insights'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php if ( $keyword != '' || $filterTopic != '' ){ ?>
        <section class="filter-listing<?php if ($q->slug == 'peer-insights'){ ?> background-black<?php } ?>">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <div class="container">
                <div class="grid-wrapper" id="loop">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 12,
                    'paged'=> $paged,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'resource-type',
                            'field' => 'slug',
                            'terms'    => $q->slug
                        ),
                    )
                );

                if($filterTopic != '') {
                    if(empty($filterTopic)){

                    } else {
                        // print_r($filterType);
                        array_push($args['tax_query'],array(
                                'taxonomy' => 'topics',
                                'field' => 'slug',
                                'terms' => $filterTopic,
                                'operator' => 'IN'
                            )
                        );
                    }
                }
                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php if ($q->slug == 'best-practices-guides'){ ?>
                            <?php get_template_part( 'templates/types-components/_best-practices-filtered' ); ?>
                        <?php } ?>
                        <?php if ($q->slug == 'peer-insights'){ ?>
                            <?php get_template_part( 'templates/types-components/_peer-insights-filtered' ); ?>
                        <?php } ?>
                        <?php if ($q->slug == 'market-trend-reports'){ ?>
                            <?php if($postCounter == 1){ ?>
                                <div class="market-trend-reports-container-full-width">
                            <?php } ?>
                                <div class="item market-trend-reports">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                            <?php if ( $featured_image ) { ?>
                                                <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                	'alt'     => $featured_image['alt'],
                                                	'loading' => false,
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                        <span class="bg-container bg-container-hover">
                                            <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                            <?php if ( $listing_hover_image ) { ?>
                                                <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'full', false, array(
                                                	'alt'     => $listing_hover_image['alt'],
                                                	'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </span>
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
                                            <?php if($postTopic){?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                    </span>
                                </div>
                            <?php if($postCounter == 9){ ?>
                            </div>
                        </div>
                    </div>
                    <div class="market-trend-reports-container-three-post three-post-container background-light-grey">
                        <div class="container">
                            <div class="grid-wrapper">
                            <?php } ?>

                        <?php } ?>
                        <?php if ($q->slug == 'articles'){ ?>
                            <?php get_template_part( 'templates/types-components/_articles-filtered' ); ?>
                        <?php } ?>
                        <?php $postCounter++; ?>
                    <?php endwhile; ?>
                    <?php if ($q->slug == 'market-trend-reports'){ ?>
                        </div>
                    <?php } ?>
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
    <?php } else { ?>
        <!-- Featured Post Container  -->
        <?php  if(!is_paged()) { ?>
            <?php if ( have_rows( 'filter_landing_content', $q ) ): ?>
            	<?php while ( have_rows( 'filter_landing_content', $q ) ) : the_row(); ?>
                    <?php if ( get_row_layout() == 'featured_post' ) : ?>
                        <section class="resources-featured featured-module filter-featured-post <?php echo $resourceType; ?><?php if ($q->slug == 'peer-insights'){ ?> background-black<?php } ?>">
                            <div class="container">
                                <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                <?php if (get_sub_field( 'featured_or_most_recent' ) == 'featured') { ?>
                                    <?php if ($q->slug == 'peer-insights'){ ?>
                                        <div class="column one-half insights-featured-column">
                                            <?php if ( $post_object ): ?>
                                                <?php $post = $post_object; ?>
                                                <?php setup_postdata( $post ); ?>
                                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                <span class="video-container">
                                                    <?php if ($video_link){ ?>
                                                        <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                    <?php } ?>
                                                        <span class="bg-container">
                                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                            <?php if ( $video_poster_image ) { ?>
                                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                	'alt'     => $video_poster_image['alt'],
                                                                	'loading' => 'lazy',
                                                                ) ); ?>
                                                            <?php } ?>
                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                <span class="opacity-overlay"></span>
                                                            <?php } ?>
                                                            <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                            <?php if ($video_link){ ?>
                                                                <span class="video-button">
                                                                </span>
                                                            <?php } ?>
                                                        </span>
                                                    <?php if ($video_link){ ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
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
                                                        <?php if($postTopic){?>
                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                        <?php } ?>
                                                    </span>
                                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                                </span>
                                                <?php wp_reset_postdata(); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="side-bar-column one-half">
                                            <div class="recent-sidebar">
                                                <?php if ( have_rows( 'sidebar_posts' ) ) : ?>
                                                    <?php while ( have_rows( 'sidebar_posts' ) ) : the_row(); ?>
                                                        <?php if ( have_rows( 'posts' ) ) : ?>
                                                            <?php while ( have_rows( 'posts' ) ) : the_row(); ?>
                                                                <?php $post_object = get_sub_field( 'post' ); ?>
                                                                <?php if ( $post_object ): ?>
                                                                    <?php $post = $post_object; ?>
                                                                    <?php setup_postdata( $post ); ?>
                                                                    <div class="resources-side-posts">
                                                                        <div class="resources-side-posts-inner">
                                                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                            				<?php if ($video_link){ ?>
                                                            				<?php } else { ?>
                                                            					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                                            				<?php } ?>
                                                                            <?php if ($video_link){ ?>
                                                                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                                                    <span class="video-container">
                                                                                        <span class="bg-container">
                                                                                            <?php $video_poster_image = get_field( 'video_poster_image' ); ?>
                                                                                            <?php if ( $video_poster_image ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                                                	'alt'     => $video_poster_image['alt'],
                                                                                                	'loading' => 'lazy',
                                                                                                ) ); ?>
                                                                                            <?php } ?>
                                                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                                <span class="opacity-overlay"></span>
                                                                                            <?php } ?>
                                                                                            <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                                                            <?php if ($video_link){ ?>
                                                                                                <span class="video-button">
                                                                                                </span>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                    </span>
                                                                                </a>
                                                                            <?php } else { ?>
                                                                                <span class="image-container">
                                                                                    <span class="bg-container">
                                                                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                                        <?php if ( $featured_image ) { ?>
                                                                                            <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                                                            	'alt'     => $featured_image['alt'],
                                                                                            	'loading' => 'lazy',
                                                                                            ) ); ?>
                                                                                        <?php } ?>
                                                                                    </span>
                                                                                </span>
                                                                            <?php } ?>
                                                                            <div class="post-content-container">
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
                                                                                    <?php if($postTopic){?>
                                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                                    <?php } ?>
                                                                                </span>
                                                                                <a href="<?php the_permalink(); ?>" class="title text-white"><h4 class="title text-white labelLarge"><?php the_title(); ?></h4></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php wp_reset_postdata(); ?>
                                                                <?php endif; ?>
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
                                    <?php } else { ?>
                                        <?php if ( $post_object ): ?>
                                        	<?php $post = $post_object; ?>
                                        	<?php setup_postdata( $post ); ?>
                                                <div class="item <?php echo $q->slug; ?> full-width">
                                                    <div class="item-column one-half image-column">
                                                        <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                        				<?php if ($video_link){ ?>
                                        				<?php } else { ?>
                                        					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                        				<?php } ?>
                                                        <?php if ($video_link){ ?>
                                                            <span class="video-container">
                                                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                                    <span class="bg-container">
                                                                        <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                                        <?php if ( $video_poster_image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                            	'alt'     => $video_poster_image['alt'],
                                                                            	'loading' => 'lazy',
                                                                            ) ); ?>
                                                                        <?php } ?>
                                                                        <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                            <span class="opacity-overlay"></span>
                                                                        <?php } ?>
                                                                        <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                                        <?php if ($video_link){ ?>
                                                                            <span class="video-button">
                                                                            </span>
                                                                        <?php } ?>
                                                                    </span>
                                                                </a>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span class="image-container">
                                                                <span class="bg-container">
                                                                    <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                    <?php if ( $featured_image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                                        	'alt'     => $featured_image['alt'],
                                                                        	'loading' => 'lazy',
                                                                        ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                                <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                                <?php if ( $listing_hover_image ) { ?>
                                                                    <span class="bg-container bg-container-hover">
                                                                        <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'full', false, array(
                                                                        	'alt'     => $listing_hover_image['alt'],
                                                                        	'loading' => 'lazy',
                                                                        ) ); ?>
                                                                    </span>
                                                                <?php } ?>
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
                                                                <?php if($postTopic){?>
                                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                <?php } ?>
                                                            </span>
                                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                                            <span class="excerpt"><?php the_excerpt(); ?></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                                    <?php if($q->slug == 'peer-insights'){ ?>
                                        <?php $postNumber = 4 ?>
                                    <?php } else { ?>
                                        <?php $postNumber = 1 ?>
                                    <?php } ?>
                                    <?php
                                        $args = array(
                                            'post_type' => 'post',
                                            'posts_per_page' => $postNumber,
                                            'paged'=> $paged,
                                            'tax_query' => array(
                                                'relation' => 'AND',
                                                array (
                                                    'taxonomy' => 'resource-type',
                                                    'field' => 'slug',
                                                    'terms'    => $q->slug
                                                )
                                            )
                                        );
                                        $posts = new WP_Query( $args ); ?>
                                        <?php if ($q->slug == 'peer-insights'){ ?>
                                            <?php if( $posts->have_posts() ): ?>
                                                <?php $peerCounter = 1; ?>
                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                    <?php if($peerCounter == 1){ ?>
                                                        <div class="column one-half insights-featured-column">
                                                            <?php if ( $post_object ): ?>
                                                                <?php $post = $post_object; ?>
                                                                <?php setup_postdata( $post ); ?>
                                                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                				<?php if ($video_link){ ?>
                                                				<?php } else { ?>
                                                					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                                				<?php } ?>
                                                                <span class="video-container">
                                                                    <?php if ($video_link){ ?>
                                                                        <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                                    <?php } ?>
                                                                        <span class="bg-container">
                                                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                                            <?php if ( $video_poster_image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                                	'alt'     => $video_poster_image['alt'],
                                                                                	'loading' => 'lazy',
                                                                                ) ); ?>
                                                                            <?php } ?>
                                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                <span class="opacity-overlay"></span>
                                                                            <?php } ?>
                                                                            <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                                            <?php if ($video_link){ ?>
                                                                                <span class="video-button">
                                                                                </span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php if ($video_link){ ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                </span>
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
                                                                        <?php if($postTopic){?>
                                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                                                </span>
                                                                <?php wp_reset_postdata(); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="side-bar-column one-half">
                                                            <div class="recent-sidebar">
                                                    <?php } else { ?>
                                                        <div class="resources-side-posts">
                                                            <div class="resources-side-posts-inner">
                                                                <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                                				<?php if ($video_link){ ?>
                                                				<?php } else { ?>
                                                					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                                				<?php } ?>
                                                                <?php if ($video_link){ ?>
                                                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                                        <span class="video-container">
                                                                            <span class="bg-container">
                                                                                <?php $video_poster_image = get_field( 'video_poster_image' ); ?>
                                                                                <?php if ( $video_poster_image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                                    	'alt'     => $video_poster_image['alt'],
                                                                                    	'loading' => 'lazy',
                                                                                    ) ); ?>
                                                                                <?php } ?>
                                                                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                    <span class="opacity-overlay"></span>
                                                                                <?php } ?>
                                                                                <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                                                <?php if ($video_link){ ?>
                                                                                    <span class="video-button">
                                                                                    </span>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <span class="image-container">
                                                                        <span class="bg-container">
                                                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                            <?php if ( $featured_image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                                                	'alt'     => $featured_image['alt'],
                                                                                	'loading' => 'lazy',
                                                                                ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } ?>
                                                                <div class="post-content-container">
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
                                                                        <?php if($postTopic){?>
                                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <a href="<?php the_permalink(); ?>" class="title text-white"><h4 class="title text-white labelLarge"><?php the_title(); ?></h4></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                <?php $peerCounter++; ?>
                                                <?php endwhile; ?>
                                                        </div>
                                                    </div>
                                                <?php wp_reset_postdata(); ?>
                                            <?php endif; ?>
                                        <?php } else { ?>
                                            <?php if( $posts->have_posts() ): ?>
                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                    <div class="item <?php echo $q->slug; ?> full-width">
                                                        <div class="item-column one-half image-column">
                                                            <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                                            				<?php if ($video_link){ ?>
                                            				<?php } else { ?>
                                            					<?php $video_link = get_field( 'vimeo_code' ); ?>
                                            				<?php } ?>
                                                            <?php if ($video_link){ ?>
                                                                <span class="video-container">
                                                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                                                        <span class="bg-container">
                                                                            <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                                            <?php if ( $video_poster_image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                                                	'alt'     => $video_poster_image['alt'],
                                                                                	'loading' => 'lazy',
                                                                                ) ); ?>
                                                                            <?php } ?>
                                                                            <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                                                <span class="opacity-overlay"></span>
                                                                            <?php } ?>
                                                                            <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                                            <?php if ($video_link){ ?>
                                                                                <span class="video-button">
                                                                                </span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </a>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="image-container">
                                                                    <span class="bg-container">
                                                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                                                        <?php if ( $featured_image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                                            	'alt'     => $featured_image['alt'],
                                                                            	'loading' => 'lazy',
                                                                            ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                                    <?php if ( $listing_hover_image ) { ?>
                                                                        <span class="bg-container bg-container-hover">
                                                                            <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'full', false, array(
                                                                            	'alt'     => $listing_hover_image['alt'],
                                                                            	'loading' => 'lazy',
                                                                            ) ); ?>
                                                                        </span>
                                                                    <?php } ?>
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
                                                                    <?php if($postTopic){?>
                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                                    <?php } ?>
                                                                </span>
                                                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                                                <span class="excerpt text-black"><?php the_excerpt(); ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                                <?php wp_reset_postdata(); ?>
                                			<?php endif; ?>
                                        <?php } ?>
                                <?php } ?>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <?php // no layouts found ?>
            <?php endif; ?>
        <?php } ?>
        <section class="filter-listing<?php if ($q->slug == 'peer-insights'){ ?> background-black<?php } ?>">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <div class="container">
                <?php if ($q->slug == 'peer-insights'){ ?>
                    <?php $pagNumber = 8; ?>
                <?php } ?>
                <?php if ($q->slug == 'market-trend-reports'){ ?>
                    <?php $pagNumber = 18; ?>
                <?php } ?>
                <?php if ($q->slug == 'best-practices-guides'){ ?>
                    <?php $pagNumber = 9; ?>
                <?php } ?>
                <?php if ($q->slug   == 'articles'){ ?>
                    <?php $pagNumber = 12; ?>
                <?php } ?>
                <div class="grid-wrapper" id="loop">
                    <?php $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => $pagNumber,
                        'paged'=> $paged,
                        'tax_query' => array(
                            'relation' => 'AND',
                            array (
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms'    => $q->slug
                            ),
                        )
                    );
                    $posts = new WP_Query( $args ); ?>
                    <?php if ($q->slug == 'market-trend-reports'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <?php if($postCounter == 1){ ?>
                                    <div class="market-trend-reports-container-side-bar">
                                <?php } ?>
                                    <div class="item market-trend-reports">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $featured_image = get_field( 'featured_image' ); ?>
                                                <?php if ( $featured_image ) { ?>
                                                	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                		'alt'     => $featured_image['alt'],
                                                		'loading' => 'lazy',
                                                	) ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="bg-container bg-container-hover">
                                                <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                                <?php if ( $listing_hover_image ) { ?>
                                                	<?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'full', false, array(
                                                		'alt'     => $listing_hover_image['alt'],
                                                		'loading' => 'lazy',
                                                	) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
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
                                                <?php if($postTopic){?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                        </span>
                                    </div>
                                <?php if($postCounter == 6){ ?>
                                </div>
                                <div class="sidebar market-trend-reports-sidebar">
                                    <span class="subscribe-sidebar-form background-pink">
                                        <h5 class="labelXXLarge text-black"><?php the_field( 'title', 'options' ); ?></h5>
                                        <p class="text-black"><?php the_field( 'text', 'options' ); ?></p>
                                        <span class="subscribe-form-container"><?php the_field( 'form_embed', 'options' ); ?></span>
                    					<span class="form-popup-button-container with-white-arrow with-arrow"><?php the_field( 'form_button', 'options' ); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="market-trend-reports-container-three-post three-post-container background-light-grey">
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php if($postCounter == 9){ ?>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="grid-wrapper">
                                    <div class="market-trend-reports-container-full-width">
                                <?php } ?>
                                <?php $postCounter++; ?>
                            <?php endwhile; ?>
                        <?php endif;?>
                        </div>
                    <?php } ?>
                    <?php if ($q->slug == 'best-practices-guides'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <div class="item one-third best-practice-item">
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
                                                    <?php if($postTopic){?>
                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge <?php the_field( 'best_practice_listing_text_colour' ); ?>"><?php the_title(); ?></a>
                                            </span>
                                        </span>
                            </div>
                            <?php endwhile; ?>
                            <?php $postCounter++; ?>
                        <?php endif;?>
                    <?php } ?>
                    <?php if ($q->slug == 'peer-insights'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <?php if($postCounter == 1){ ?>
                                    <div class="peer-insights-container-side-bar">
                                <?php } ?>
                                <div class="item one-third peer-insights-item">
                                    <?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
                    				<?php if ($video_link){ ?>
                    				<?php } else { ?>
                    					<?php $video_link = get_field( 'vimeo_code' ); ?>
                    				<?php } ?>
                                    <span class="video-container">
                                        <?php if ($video_link){ ?>
                                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo $video_link; ?>">
                                        <?php } ?>
                                            <span class="bg-container">
                                                <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                                <?php if ( $video_poster_image ) { ?>
                                                	<?php echo wp_get_attachment_image( $video_poster_image['ID'], 'full', false, array(
                                                		'alt'     => $video_poster_image['alt'],
                                                		'loading' => 'lazy',
                                                	) ); ?>
                                                <?php } ?>
                                                <?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
                                                    <span class="opacity-overlay"></span>
                                                <?php } ?>
                                                <span class="video-play-time"><?php the_field( 'video_time' ); ?></span>
                                                <?php if ($video_link){ ?>
                                                    <span class="video-button">
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        <?php if ($video_link){ ?>
                                            </a>
                                        <?php } ?>
                                    </span>
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
                                            <?php if($postTopic){?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-white"><?php the_title(); ?></a>
                                    </span>
                                </div>
                                <?php if($postCounter == 2){ ?>
                                    <div class="sidebar">
                                        <span class="subscribe-sidebar-form background-tertiary-black">
                                            <h5 class="labelXXLarge text-white"><?php the_field( 'title', 'options' ); ?></h5>
                                            <p class="text-white"><?php the_field( 'text', 'options' ); ?></p>
                                            <span class="subscribe-form-container"><?php the_field( 'form_embed', 'options' ); ?></span>
                        					<span class="form-popup-button-container with-white-arrow with-arrow"><?php the_field( 'form_button', 'options' ); ?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="peer-insights-full-container">
                                <?php } ?>
                                <?php $postCounter++; ?>
                        <?php endwhile; ?>
                        </div>
                    <?php endif;?>
                    <?php } ?>
                    <?php if ($q->slug == 'articles'){ ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php $postCounter = 1; ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                    <div class="item one-third articles">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $featured_image = get_field( 'featured_image' ); ?>
                                                <?php if ( $featured_image ) { ?>
                                                	<?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                		'alt'     => $featured_image['alt'],
                                                		'loading' => 'lazy',
                                                	) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
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
                                                <?php if($postTopic){?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                                        </span>
                                    </div>
                                <?php if($postCounter == 3){ ?>
                            </div>
                        </div>
                        <div class="article-container-three-post three-post-container background-light-grey">
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php if($postCounter == 9){ ?>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="grid-wrapper">
                                <?php } ?>
                                <?php $postCounter++; ?>
                            <?php endwhile; ?>
                        <?php endif;?>
                        </div>
                    <?php } ?>
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
    <?php } ?>
<?php } ?>
