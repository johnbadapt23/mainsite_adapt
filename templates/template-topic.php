<?php global $displayed_posts;
$displayed_posts = array (); ?>

<?php
$q = get_queried_object();
$keyword = isset($_GET['searchWords']) ? sanitize_text_field($_GET['searchWords']) : '';
$filterType  = isset($_GET['filter-type']) ? sanitize_text_field($_GET['filter-type']) : '';

if($keyword != '') {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'topic',
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
                'taxonomy' => 'topic',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
}
?>



<section class="filter-title-block">
    <div class="container">
        <div class="title-container">
            <h1 class="type-title<?php if ($q->slug == 'peer-insights'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?php echo $q->name; ?></h1>
            <p class="type-description<?php if ($q->slug == 'peer-insights'){ ?> text-white<?php } else { ?> text-black<?php }?>"><?php echo $q->description; ?></p>
        </div>
        <div class="topic-button-container-outer">
            <div class="topic-button-container filter-button-container">
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
                <?php wp_reset_postdata(); ?>
                <a href="<?php echo get_term_link( $q );?>"class="filter-button<?php if($filterType == '') { ?> selected<?php } ?>">All</a>
                <?php foreach($terms as $term) { ?>
                    <a href="<?php echo get_term_link( $q );?>?filter-type=<?php echo $term -> slug; ?>"class="filter-button<?php if($filterType == '') { } else { if ($term -> slug == $filterType ) { ?> selected<?php }}?><?php if ($q->slug == 'peer-insights'){ ?> peer-insights<?php } ?>"><?php echo $term -> name; ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</section>



<section class="filter-listing">
    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
    <div class="container">
        <?php if ($filterType  == 'peer-insights' || $filterType  == 'expert-presentations' ){ ?>
            <?php $pagNumber = 8; ?>
        <?php } ?>
        <?php if ($filterType  == 'market-trend-reports'){ ?>
            <?php $pagNumber = 18; ?>
        <?php } ?>
        <?php if ($filterType  == 'best-practices-guides'){ ?>
            <?php $pagNumber = 9; ?>
        <?php } ?>
        <?php if ($filterType  == 'articles'){ ?>
            <?php $pagNumber = 12; ?>
        <?php } ?>
        <?php if(empty($filterType)){ ?>
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
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms'    => $q->slug
                    ),
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
            $posts = new WP_Query( $args ); ?>
            <?php if ($filterType == 'market-trend-reports'){ ?>
                <?php if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php if($postCounter == 1){ ?>
                            <div class="market-trend-reports-container-side-bar">
                        <?php } ?>
                            <div class="item market-trend-reports">
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
                                        <span class="bg-container bg-container-hover">
                                            <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                            <?php if ( $listing_hover_image ) { ?>
                                                <?php echo wp_get_attachment_image( $listing_hover_image['ID'], 'adapt-optimized', false, array(
                                                	'alt'     => $listing_hover_image['alt'],
                                                	'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </a>
                                </span>
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
                                        <?php if ( !empty( $postTopic ) ) { ?>
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
                                <span class="icon-container">
                                    <span class="icon-inner">
                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                    </span>
                                </span>
                                <h2 class="labelXXLarge text-black h5-style"><?php echo get_field( 'title', 'options' ); ?></h2>
                                <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>

            					<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
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
            <?php if ($filterType == 'best-practices-guides'){ ?>
                <?php if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div class="item one-third best-practice-item">
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                            <?php if ( $best_practice_listing_image ) { ?>
                                                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'adapt-optimized', false, array(
                                                	'alt'     => $best_practice_listing_image['alt'],
                                                	'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </a>
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
                    <?php endwhile; ?>
                    <?php $postCounter++; ?>
                <?php endif;?>
            <?php } ?>
            <?php if ($filterType  == 'peer-insights' || $filterType  == 'expert-presentations' ){ ?>
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
                                    <a href="<?php the_permalink(); ?>">
                                <?php } ?>
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
                                <?php if ($video_link){ ?>
                                    </a>
                                <?php } ?>
                            </span>
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
                                    <?php if ( !empty( $postTopic ) ) { ?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                            </span>
                        </div>
                        <?php if($postCounter == 2){ ?>
                            <div class="sidebar">
                                <span class="subscribe-sidebar-form background-pink">
                                    <span class="icon-container">
                                        <span class="icon-inner">
                                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
                                        </span>
                                    </span>
                                    <h2 class="labelXXLarge text-black h5-style"><?php echo get_field( 'title', 'options' ); ?></h2>
                                    <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>
                                    
                					<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
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
            <?php if ($filterType == 'articles'){ ?>
                <?php if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="item one-third articles">
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
                                        <?php if ( !empty( $postTopic ) ) { ?>
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
            <?php if(empty($filterType)){ ?>
                <?php if( $posts->have_posts() ): ?>
                    <?php $postCounter = 1; ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="item one-third articles">
                                <span class="image-container">
                                    <a href="<?php the_permalink(); ?>">
                                        <span class="bg-container">
                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                             <?php $video_poster_image = get_field( 'video_poster' ); ?>
                                             <?php $best_practice_listing_image = get_field( 'best_practice_listing_image' ); ?>
                                            <?php if ( $featured_image ) { ?>
                                                <?php echo wp_get_attachment_image( $featured_image['ID'], 'adapt-optimized', false, array(
                                                	'alt'     => $featured_image['alt'],
                                                	'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } elseif ( $video_poster_image ){ ?> 
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
                                            <?php } else { ?>
                                                <?php if ( $best_practice_listing_image ) { ?>
                                                <?php echo wp_get_attachment_image( $best_practice_listing_image['ID'], 'adapt-optimized', false, array(
                                                	'alt'     => $best_practice_listing_image['alt'],
                                                	'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                           <?php } ?>
                                        </span>
                                    </a>
                                </span>                               
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
                                        <?php if ( !empty( $postTopic ) ) { ?>
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
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
