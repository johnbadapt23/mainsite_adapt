<?php
/**
 * Template Name: Events Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php $keyword = isset( $_GET['searchWords'] ) ? sanitize_text_field( $_GET['searchWords'] ) : ''; ?>
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
                            <input class="searchInput" type="text" name="searchWords" id="search" <?php if ($keyword != ''){?> value="<?php echo esc_attr( $keyword ); ?>" <?php } else { ?>value=""<?php } ?> placeholder="SEARCH FOR.. OR FILTER" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                        </span>
                        <span class="spacer"></span>
                        <span class="categories">
                            <span class="more">More</span>
                            <?php
                            $term_m = 'event-category';
                            $filterCat = isset( $_GET['categories'] ) ? array_map( 'sanitize_text_field', (array) $_GET['categories'] ) : '';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="categories[]" <?php if($filterCat == '') { } else { if (in_array( $term -> slug, $filterCat )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="types">
                            <span class="title">Type</span>
                            <?php
                            $term_m = 'event-type';
                            $filterType = isset( $_GET['types'] ) ? array_map( 'sanitize_text_field', (array) $_GET['types'] ) : '';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <?php $image = get_field('icon', $term); ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="types[]" <?php if($filterType == '') { } else { if (in_array( $term -> slug, $filterType )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="duration">
                            <span class="title">Duration</span>
                            <?php
                            $term_m = 'event-duration';
                            $filterDuration = isset( $_GET['duration'] ) ? array_map( 'sanitize_text_field', (array) $_GET['duration'] ) : '';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="duration[]" <?php if($filterDuration == '') { } else { if (in_array( $term -> slug, $filterDuration )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="submitContainer">
                            <input type="submit" class="button filterButton" value="Filter" />
                            <?php if ($filterCat != '' || $filterDuration != '' || $filterType != '' ) { ?>
                                <a class="clear" href="/edge-events">Clear</a>
                            <?php } ?>
                        </span>
                    </form>
                </div>
                <div class="viewContainer"><a class="tooltip list"><span class="tooltiptext">Switch to list view</a></div>
            </div>
            <!-- <div class="filter">
                <span class="dropDown">
                    <select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>

                    </select>
                </span>
            </div> -->
        </div>
    </section>

    <section class="blogWrapper post-events">
        <div class="container">
            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => 'event',
                        's' => $keyword,
                        'posts_per_page' => 9,
                        'paged'=> $paged ,
                        'tax_query' => array(
                            'relation' => '&'
                        )
                    );

                    if($filterCat != '') {
                        foreach( $filterCat as $filter){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'event-category',
                                    'field' => 'slug',
                                    'terms' => $filter,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    if($filterType != '') {
                        foreach( $filterType as $type){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'event-type',
                                    'field' => 'slug',
                                    'terms' => $type,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    if($filterDuration != '') {
                        foreach( $filterDuration as $duration){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'event-duration',
                                    'field' => 'slug',
                                    'terms' => $duration,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) :
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>

                    <a href="<?php the_permalink(); ?>" class="postLink layout<?php echo $counter; ?>" target="_self">
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
