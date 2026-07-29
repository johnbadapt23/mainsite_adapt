<?php
/**
 * Template Name: Events Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php $keyword = $_GET['searchWords']; ?>
    <section class="postHeader post-events">
        <div class="container">
            <div class="headerWrapper">
                <h1><?php the_field( 'events_listing_title_text', 'option' ); ?></h1>
                <span class="subTitle">
                    <?php the_field( 'events_listing_sub_title', 'option' ); ?>
                </span>
            </div>
            <div class="filter">
                <div class="formContainer">
                    <form action="" name="insightsFilter" class="insightsFilter" method="get">
                        <span class="search">
                            <input class="searchInput" type="text" name="searchWords" id="search" <?php if ($keyword != ''){?> value="<?php echo $keyword; ?>" <?php } else { ?>value=""<?php } ?> placeholder="<?php the_field( 'events_search_placeholder_text', 'option' ); ?>" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                        </span>
                        <span class="submitContainer">
                            <?php if ($keyword != '' ) { ?>
                                <span class="results">Showing results for <strong><?php echo $keyword; ?></strong></span>
                                <a class="clear" href="/edge-events">Clear</a>
                            <?php } ?>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="blogWrapper post-events insightsArticleWrapper">
        <div class="container">
            <div id="loop" class="list">
                <?php $counter = -1; ?>
                <?php

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    if($keyword != '') {
                        $args = array(
                            'post_type' => 'event',
                            's' => $keyword,
                            'posts_per_page' => 9,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'event-category',
                                    'field' => 'slug',
                                    'terms' => 'private-events',
                                    'operator' => 'NOT IN',
                                ),
                                'relation' => '&'
                            )
                        );
                    } else {
                        $args = array(
                            'post_type' => 'event',
                            'posts_per_page' => 9,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'event-category',
                                    'field' => 'slug',
                                    'terms' => 'private-events',
                                    'operator' => 'NOT IN',
                                ),
                                'relation' => '&'
                            )
                        );
                    }

                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) :
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>

                    <a href="<?php the_permalink(); ?>" class="postLink list-view layout<?php echo $counter; ?>" target="_self">
                        <div class="linkWrapper">

                            <div class="imageContainer">
                                <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                </div>
                            </div>
                            <span class="blogText">
                                <span class="articleLink"><?php echo the_title(); ?></span>
                                <span class="excerpt">
                                    <?php the_field('event_short_description_for_listing'); ?>
                                </span>
                                <span class="link-text">Learn More</span>
                                <?php
                                    $post_tags = get_the_terms( $post->ID, 'events-tag');
                                ?>

                                <?php if ( $post_tags ) { ?>
                                    <div class="tags">
                                        <?php $i = 0; ?>
                                        <?php foreach( $post_tags as $tag ) { ?>
                                            <span>
                                                <?php echo '#' . $tag->name  ; ?>
                                            </span>
                                             <?php $i++;
                                             if ($i >= 4){
                                                  break;
                                                }?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </span>
                        </div>
                    </a>
                    <?php $counter++; ?>
                <?php endwhile; else : ?>
                	<h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

            <?php if( $loop->max_num_pages > 1 ): ?>
                <span class="pagWrapper">
                    <span id="pagination" class="button-container"><?php next_posts_link( 'Load More', $loop->max_num_pages ); ?></span>
                </span>
            <?php endif; ?>

            <div class="formTrigger">
                <?php if ( get_field ( 'form_title', 'option' ) ) { ?>
                    <h2><?php the_field( 'form_title', 'option' ); ?></h2>
                <?php } ?>
                <?php if ( get_field ( 'form_subtitle', 'option' ) ) { ?>
                    <h3><?php the_field( 'form_subtitle', 'option' ); ?></h3>
                <?php } ?>
                <?php if ( get_field ( 'call_to_action_text', 'option' ) ) { ?>
                    <h4><?php the_field( 'call_to_action_text', 'option' ); ?></h4>
                <?php } ?>

                <a class="logoBlockLink button popup-modal" href="#form"><?php the_field( 'button_text', 'option' ); ?></a>
            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
