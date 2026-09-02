<?php global $displayed_posts;
$displayed_posts = array ();

?>
<?php

$keyword = isset( $_GET['searchWords'] ) ? sanitize_text_field( $_GET['searchWords'] ) : '';

if($keyword != '') {
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged
     );
} else {
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => -1,
        'paged'=> $paged
    );
}
?>

<section class="filter-listing in-the-news-listing background-secondary-light-grey">
    <div class="container">
        <div class="title-container">
            <h1 class="type-title text-black"><?php echo get_field('news_title', 'options'); ?></h1>
            <span class="type-description"><?php echo get_field('news_description', 'options'); ?></span>
        </div>       
    </div>
     <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
    <?php
    $args = array(
        'post_type' => 'news',        
        'posts_per_page' => 5,
        'meta_key'  => 'publication_date',
        'orderby'   => 'meta_value_num',
        'order'     => 'DESC',
        'paged'=> $paged
    );
    $newsPosts = new WP_Query( $args );
    if( $newsPosts->have_posts() ): ?>
        <div class="press-listing-container news-listing-container">
            <?php while( $newsPosts->have_posts() ) : $newsPosts->the_post(); ?>
                <a href="<?php the_permalink(); ?>">
                    <span class="item press-release-item">
                        <span class="container">
                            <span class="press-date-container column desktop">
                                <span class="v-wrap">
                                    <span class="v-box">
                                        <span class="date-inner">                                          
                                            <?php if (get_field('publication_date')){ ?>                         
                                                <?php  $date_string = get_field('publication_date');
                                                // Create DateTime object from value (formats must match).
                                                $date = DateTime::createFromFormat('Ymd', $date_string); ?>                                
                                                <span class="date-day text-red"><?php echo $date->format('j'); ?></span>
                                                <span class="date-month text-black labelMedium"><?php echo $date->format('M'); ?></span>
                                                <span class="date-year text-black labelMedium"><?php echo $date->format('Y'); ?></span>
                                            <?php } else { ?>
                                                 <span class="date-day text-red"><?php echo get_the_date('j') ?></span>
                                                <span class="date-month text-black labelMedium"><?php echo get_the_date('M') ?></span>
                                                <span class="date-year text-black labelMedium"><?php echo get_the_date('Y') ?></span>
                                            <?php } ?>                                                                                        
                                        </span>
                                    </span>
                                </span>
                            </span>
                            <span class="item-content-container column">
                                <span class="content-inner">
                                    <span class="title text-black labelXXLarge"><?php the_title(); ?></span>
                                    <span class="published-date mobile-only">
                                        <?php if (get_field('published_date')){ ?>                         
                                            <?php  $date_string = get_field('published_date');
                                            // Create DateTime object from value (formats must match).
                                            $date = DateTime::createFromFormat('Ymd', $date_string); ?>
                                            /<?php echo $date->format('M j, Y'); ?>
                                        <?php } else { ?> 
                                            /<?php echo get_the_date('M j, Y') ?>
                                        <?php } ?>		
                                    </span>
                                    <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                                </span>
                            </span>
                            <span class="read-more-container column">
                                <span class="v-wrap">
                                    <span class="v-box">
                                        <span class="read-more text-link text-red">Read more</span>
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
            <?php endwhile; ?>
        </div>
    <?php endif;?>     
    <div class="page-navi-container">
        <div class="container">
            <?php wp_pagenavi( array( 'query' => $newsPosts ) ); ?>
                <?php wp_reset_postdata(); ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div> 
</section>
