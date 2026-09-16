<?php global $displayed_posts;
$displayed_posts = array ();
?>
<section class="filter-listing in-the-news-listing background-black">
    <div class="container">
        <div class="title-container media-title">
            <h1 class="type-title text-white"><?php echo get_field('media_title', 'options'); ?></h1>
            <span class="type-description media-description text-white"><?php echo get_field('media_description', 'options'); ?></span>
        </div>        
        <div class="sidebar-container">
            <span class="media-enquiries-container">
                <span class="media-contact-title text-white">For media enquiries, please contact</span>                    
                    <a class="media-contact text-white" href="mailto:media@adapt.com.au">media@adapt.com.au</a>                  
                </span>
            </span>
        </div>       
    </div>
     <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
    <?php
    $args = array(
        'post_type' => 'media',
        'posts_per_page' => 5,
        'meta_key'  => 'published_date',
        'orderby'   => 'meta_value_num',
        'order'     => 'DESC',
        'paged'=> $paged
    );
    $posts = new WP_Query( $args );   
    if( $posts->have_posts() ): ?>
        <div class="press-listing-container media-listing-container">
            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                <?php get_template_part( 'templates/types-components/_media-all' ); ?>
            <?php endwhile; ?>
        </div>
    <?php endif;?>
    <div class="page-navi-container background-white">
        <div class="container">
            <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
            <?php wp_reset_postdata(); ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>

            
       