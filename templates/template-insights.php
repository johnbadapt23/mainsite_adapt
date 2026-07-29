<?php
/**
 * Template Name: Insights Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php
$filterTopics = $_GET['topics'];
$filterType = $_GET['filterType'];
$keyword = $_GET['searchWords'];
$sortBy = $_GET['orderby'];
$sort = $_GET['order'];
$sortPosts = $_GET['sortPost'];

$filterBy = array();
?>

<?php
    if($sortBy != '') {
        if(empty($sortBy)){
            if(empty($sortPosts)){
                $orderBy = 'menu_order';
            } else {
            }
        } else {
            $orderBy = $sortBy;
        }
    } else {
        if(empty($sortPosts)){
            $order = 'ASC';
        }
    }

    if($sort != '') {
        if(empty($sort)){
            if(empty($sortPosts)){
                $orderBy = 'menu_order';
            } else {
            }
        } else {
            $order = $sort;
        }
    } else {
        if(empty($sortPosts)){
            $order = 'ASC';
        }
    }

    if ($sortPosts != '') {
        if(empty($sortPosts)){
            $orderBy = 'menu_order';
            $order = 'ASC';
        } else {
            if ($sortPosts == 'newest') {
                $orderBy = 'date';
                $order = 'DESC';
            } else if ($sortPosts == 'oldest') {
                $orderBy = 'date';
                $order = 'ASC';
            } else if ($sortPosts == 'titleASC') {
                $orderBy = 'title';
                $order = 'ASC';
            } else if ($sortPosts == 'titleDESC') {
                $orderBy = 'title';
                $order = 'DESC';
            }
        }
    } else {
        if(empty($sortBy)){
            $orderBy = 'menu_order';
        } else {
        }

        if(empty($sort)){
            $order = 'ASC';
        } else {
        }
    }

    if($filterTopics != '') {
        $output = array();
        foreach( $filterTopics as $topic){
            $output[] = $topic;
        }
    } else {
        $term_m = 'topic';
        $terms = get_terms( $term_m, array(
            'hide_empty' => false,
        ) );

        $output = array();
        foreach( $terms as $term){
            $output[] = $term->slug;
        }
    }

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    if($filterTopics != '') {
        if($keyword != '') {
            $args = array(
                'post_type' => 'post',
                's' => $keyword,
                'posts_per_page' => 9,
                'paged'=> $paged,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms' => $output,
                        'operator' => 'IN',
                    ),
                    array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => 'private-post',
                            'operator' => 'NOT IN',
                        ),
                    ),

                ),
                'orderby'   => $orderBy,
                'order' => $order
            );
        } else {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged'=> $paged,
                'orderby'   => $orderBy,
                'order' => $order,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms' => $output,
                        'operator' => 'IN',
                    ),
                    array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => 'private-post',
                            'operator' => 'NOT IN',
                        ),
                    ),
                )
            );
        }
    } else {
        if($keyword != '') {
            $args = array(
                'post_type' => 'post',
                's' => $keyword,
                'posts_per_page' => 9,
                'paged'=> $paged,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'private-post',
                        'operator' => 'NOT IN',
                    ),
                    'relation' => 'AND',
                ),
                'orderby'   => $orderBy,
                'order' => $order
            );
        } else {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged'=> $paged,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'private-post',
                        'operator' => 'NOT IN',
                    ),
                    'relation' => 'AND',
                ),
                'orderby'   => $orderBy,
                'order' => $order
            );
        }
    }

    // if(current_user_can('mepr-active','memberships: 3829')) {
    //
    // } else {
    //     array_push($args['tax_query'],array(
    //             'taxonomy' => 'category',
    //             'field' => 'slug',
    //             'terms' => 'advantage-only',
    //             'operator' => 'NOT IN',
    //         )
    //     );
    // }

    if(current_user_can('mepr-active','memberships: 9811')) {

    } else {
        array_push($args['tax_query'],array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'adapt-only',
                'operator' => 'NOT IN',
            )
        );
    }

    if($filterType != '') {
        if(empty($filterType)){

        } else {
            if($filterType == 'all') {
                $term_m = 'filter-types';
                $terms = get_terms( $term_m, array(
                    'hide_empty' => false,
                ) );

                $types = array();
                foreach( $terms as $term){
                    $types[] = $term->slug;
                }
                array_push($args['tax_query'],array(
                        'taxonomy' => 'filter-types',
                        'field' => 'slug',
                        'terms' => $types,
                        'operator' => 'IN'
                    )
                );

            } else {
                // print_r($filterType);
                array_push($args['tax_query'],array(
                        'taxonomy' => 'filter-types',
                        'field' => 'slug',
                        'terms' => $filterType,
                        'operator' => 'IN'
                    )
                );
            }
        }
    }
?>
    <section class="postHeader">
        <div class="container">
            <div class="headerWrapper">
                <h1><?php the_field( 'title_text', 'option' ); ?></h1>
                <span class="subTitle">
                    <?php if(current_user_can('mepr-active')) { ?>
                        <?php the_field( 'sub_title', 'option' ); ?>
                    <?php } else { ?>
                        <?php the_field( 'sub_title_logged_out', 'option' ); ?>
                    <?php } ?>
                </span>
            </div>
            <div class="filter">
                <div class="formContainer">
                    <div class="ajax-search-container">
                        <?php if($keyword != '') { ?>
                            <span class="hidden-keyword" style="display: none;"><?php echo $keyword; ?></span>
                            <span class="clear-keyword">Clear</span>
                        <?php } ?>
                        <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
                        <span class="ajax-search-button">Search</span>
                    </div>
                    <form action="" name="insightsFilter" class="new-filter desktop insightsFilter<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> active<?php } ?>" method="get">

                        <span class="search">
                            <input class="searchInput" type="text" name="searchWords" id="search" <?php if ($keyword != ''){?> value="<?php echo $keyword; ?>" <?php } else { ?>value=""<?php } ?> placeholder="<?php the_field( 'post_search_placeholder_text', 'option' ); ?>" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                            <input type="hidden" value="1" name="sentence" />
                        </span>

                        <span class="topics">
                            <span class="title"><span>Topics</span><span class="mobile-view-all">Topics and Filters</span></span>
                            <?php
                            $term_m = 'topic';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => false,
                            ) );
                            ?>

                            <span class="radioSlideContainer desktop">
                                <?php foreach($terms as $term) { ?>
                                    <span class="radioSlide slide <?php echo $term -> slug; ?>">
                                        <label style="background-image: url(<?php the_field( 'button_image', $term ); ?>);">
                                          <input type="checkbox" name="topics[]" <?php if($filterTopics == '') { } else { if (in_array( $term -> slug, $filterTopics )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>">
                                          <img class="tick" src="<?php echo get_template_directory_uri(); ?>/assets/images/tick.svg" alt="" width="25"/>
                                          <span class="overlay"></span>
                                          <span class="checkbox-text">
                                              <span class="v-wrap">
                                                  <span class="v-box">
                                                      <?php echo $term -> name; ?>
                                                  </span>
                                              </span>
                                          </span>
                                        </label>
                                    </span>
                                <?php } ?>
                            </span>
                        </span>
                        <?php
                        if ($sortBy != '') { ?>
                            <span class="hidden" style="visibility: hidden; opacity: 0;">
                                <input type="checkbox" name="orderby" value="<?php echo $sortBy; ?>" checked>
                                <input type="checkbox" name="order" value="<?php echo $sort; ?>" checked>
                            </span>
                        <?php } ?>
                        <?php if ($filterType != '') { ?>
                            <span class="hidden" style="visibility: hidden; opacity: 0;">
                                <input type="checkbox" name="filterType" value="<?php echo $filterType; ?>" checked>
                            </span>
                        <?php } ?>
                        <span class="submitContainer">
                            <input type="submit" class="button filterButton" value="Filter" />
                            <?php if ($filterTopics != '' || $keyword != '' || $filterDuration != '' || $filterType != '' ) { ?>
                                <a class="clear" href="/blog">Clear</a>
                            <?php } ?>
                        </span>
                    </form>
                </div>
                <div class="sortContainer desktop">
                    <div id="sortby">
                        <span class="select-label">Sort By:</span>
                        <select class="dropdown-class" name="sort-posts" id="sortbox" onchange="document.location.href=location.href+this.options[this.selectedIndex].value;">
                            <?php
                            if ($sortBy != '') {
                                if ($sortBy == 'date') {
                                    if ($sort == 'DESC') {
                                        $sortValue = 'Newest';
                                    } else {
                                        $sortValue = 'Oldest';
                                    }
                                }
                                if ($sortBy == 'title') {
                                    if ($sort == 'DESC') {
                                        $sortValue = 'Title Z - A';
                                    } else {
                                        $sortValue = 'Title A - Z';
                                    }
                                }
                            } else {
                                $sortValue = 'Newest';
                            }
                            ?>
                            <option <?php if ($sortBy == 'date' && $sort == 'DESC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=DESC">Newest</option>
                            <option <?php if ($sortBy == 'date' && $sort == 'ASC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=ASC">Oldest</option>
                            <option <?php if ($sortBy == 'title' && $sort == 'ASC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=ASC">Title A - Z</option>
                            <option <?php if ($sortBy == 'title' && $sort == 'DESC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=DESC">Title Z - A</option>
                        </select>
                    </div>
                    <div id="filterBy">
                        <span class="select-label">Filter By:</span>
                        <select class="dropdown-class" name="filter-posts" id="filterBox" onchange="document.location.href=location.href+this.options[this.selectedIndex].value;">
                             <?php if($filterTopics != '' || $keyword != '') { ?>
                                <?php $terms = array(); ?>
                                <?php $loop = new WP_Query( $args ); ?>
                                <?php if ( $loop->have_posts() ) : ?>
                                   <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                       <?php
                                           $types = get_the_terms( $post->ID, 'filter-types' );
                                           if($types){
                                               foreach( $types as $type ){
                                                   if( ! in_array( $type, $terms )){
                                                       $terms[] = $type;
                                                   }
                                               }
                                           }
                                       ?>
                                   <?php endwhile; ?>
                                    <?php else : ?>
                                   <?php endif; ?>
                                   <?php wp_reset_query();
                                   ?>
                               <?php } else { ?>
                                    <?php
                                    $term_m = 'filter-types';
                                    ?>
                                    <?php
                                    $terms = get_terms( $term_m, array(
                                     'hide_empty' => true,
                                    ) );
                                    ?>
                               <?php }  ?>

                            <option value="<?php if ($filterTopics != '' || $sortBy != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>filterType=all">All</option>
                            <?php foreach($terms as $term) { ?>
                                <option <?php if ($filterType == $term -> slug){?> selected=""<?php } ?>value="<?php if ($filterTopics != '' || $sortBy != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>filterType=<?php echo $term -> slug; ?>"><?php echo $term -> name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php  $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; ?>
                </div>
                <div class="viewContainer desktop insightsArticleWrapper"><a class="tooltip list active"><span class="tooltiptext">Switch to list view</a><a class="tooltip grid"><span class="tooltiptext">Switch to grid view</a></div>
                <div class="mobile-form-container">
                    <div class="top-button-container">
                        <span class="back">
                            Back
                        </span>
                        <a class="reset" href="/blog">Clear</a>
                    </div>
                    <div class="viewContainer mobile insightsArticleWrapper"><a class="tooltip grid"><span class="icon"></span><span class="text">Grid view</span></a><a class="tooltip list active"><span class="icon"></span><span class="text">List view</span></a></div>
                    <div class="mobile-form">
                        <form action="" name="insightsFilter" class="new-filter mobile insightsFilter<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> active<?php } ?>" method="get">
                            <span class="topics mobile">
                                <span class="title active">Topics</span>
                                <?php
                                $term_m = 'topic';
                                ?>
                                <?php
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => false,
                                ) );
                                ?>
                                <span class="radioSlideContainer mobile">
                                    <?php foreach($terms as $term) { ?>
                                        <span class="radioSlide slide <?php echo $term -> slug; ?>">
                                            <label style="background-image: url(<?php the_field( 'button_image', $term ); ?>);">
                                              <input type="checkbox" name="topics[]" <?php if($filterTopics == '') { } else { if (in_array( $term -> slug, $filterTopics )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>">
                                              <img loading="lazy" class="tick" src="<?php echo get_template_directory_uri(); ?>/assets/images/tick.svg" alt="" width="25"/>
                                              <span class="overlay"></span>
                                              <span class="checkbox-text">
                                                  <span class="v-wrap">
                                                      <span class="v-box">
                                                          <?php echo $term -> name; ?>
                                                      </span>
                                                  </span>
                                              </span>
                                              <span class="checkbox-description">
                                                  <?php echo $term -> description; ?>
                                              </span>
                                            </label>
                                        </span>
                                    <?php } ?>
                                </span>
                            </span>
                            <div class="filter-by-mobile" id="filterBy">
                                <span class="title select-label">Filter By: <span class="current-value"><?php if($filterType == '') {?>All<?php } else { if ($filterType == 'all') { ?>All<?php } else {?><?php echo $filterType; ?><?php } } ?></span></span>
                                <span class="mobile-filter-container mobile">
                                    <?php if($filterTopics != '' || $keyword != '') { ?>
                                       <?php $terms = array(); ?>
                                       <?php $loop = new WP_Query( $args ); ?>
                                       <?php if ( $loop->have_posts() ) : ?>
                                          <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                              <?php
                                                  $types = get_the_terms( $post->ID, 'filter-types' );
                                                  if($types){
                                                      foreach( $types as $type ){
                                                          if( ! in_array( $type, $terms )){
                                                              $terms[] = $type;
                                                          }
                                                      }
                                                  }
                                              ?>
                                          <?php endwhile; ?>
                                           <?php else : ?>
                                          <?php endif; ?>
                                          <?php wp_reset_query();
                                          ?>
                                      <?php } else { ?>
                                           <?php
                                           $term_m = 'filter-types';
                                           ?>
                                           <?php
                                           $terms = get_terms( $term_m, array(
                                            'hide_empty' => true,
                                           ) );
                                           ?>
                                      <?php }  ?>
                                      <span class="checkboxButton filterItemMobile">
                                          <label>
                                            <input type="checkbox" name="filterType" <?php if($filterType == '') { } else { if ($filterType == 'all') { ?> checked <?php }}?> value="all"><span class="checkbox-text">All</span>
                                          </label>
                                      </span>
                                    <?php foreach($terms as $term) { ?>
                                        <span class="checkboxButton filterItemMobile">
                                            <label>
                                              <input type="checkbox" name="filterType" <?php if($filterType == '') { } else { if ($filterType == $term -> slug) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                            </label>
                                        </span>
                                    <?php } ?>
                                </span>
                            </div>
                            <div class="sort-by-mobile" id="sortby">
                                <?php
                                if ($sortPosts != '') {
                                    if ($sortPosts == 'newest') {
                                        $sortPostValue = 'Newest';
                                    } else if ($sortPosts == 'oldest') {
                                        $sortPostValue = 'Oldest';
                                    } else if ($sortPosts == 'titleASC') {
                                        $sortPostValue = 'Title A - Z';
                                    } else if ($sortPosts == 'titleDESC') {
                                        $sortPostValue = 'Title Z - A';
                                    }
                                } else {
                                    $sortPostValue = 'Newest';
                                }
                                ?>

                                <span class="title select-label">Sort By: <span class="current-value"><?php  echo $sortPostValue; ?></span></span>
                                <span class="mobile-sort-container">
                                    <span class="checkboxButton sortItemMobile">
                                        <label>
                                          <input type="checkbox" name="sortPost" <?php if($sortPost == '') { } else { if ( $sortPosts == 'newest') { ?> checked <?php }}?> value="newest"><span class="checkbox-text">Newest</span>
                                        </label>
                                    </span>
                                    <span class="checkboxButton sortItemMobile">
                                        <label>
                                          <input type="checkbox" name="sortPost" <?php if($sortPost == '') { } else { if ( $sortPosts == 'oldest') { ?> checked <?php }}?> value="oldest"><span class="checkbox-text">Oldest</span>
                                        </label>
                                    </span>
                                    <span class="checkboxButton sortItemMobile">
                                        <label>
                                          <input type="checkbox" name="sortPost" <?php if($sortPost == '') { } else { if ( $sortPosts == 'titleASC') { ?> checked <?php }}?> value="titleASC"><span class="checkbox-text">Title A - Z</span>
                                        </label>
                                    </span>
                                    <span class="checkboxButton sortItemMobile">
                                        <label>
                                          <input type="checkbox" name="sortPost" <?php if($sortPost == '') { } else { if ( $sortPosts == 'titleDESC') { ?> checked <?php }}?> value="titleDESC"><span class="checkbox-text">Title Z - A</span>
                                        </label>
                                    </span>
                                </span>
                            </div>
                            <span class="submitContainer">
                                <input type="submit" class="button filterButton insightsFilterButton" value="Apply Filters" />
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blogWrapper insightsArticleWrapper">
        <div class="container">
            <div id="loop" class="list<?php if($filterTopics != '' || $keyword != '' ) { ?> results-grid<?php } ?>">
                <?php if($filterTopics != '' || $keyword != '') { ?>
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>

                    <?php
                    $filterTypesResults = array();
                    $term_m = 'filter-types'
                    ?>
                    <?php
                    $terms = get_terms( $term_m, array(
                     'hide_empty' => true,
                    ) );
                    ?>

                    <?php foreach($terms as $term){
                        $filterTypesResults[$term -> slug] = 0;
                    } ?>

                    <?php if($filterTopics != '') {
                            $output = array();
                            foreach( $filterTopics as $topic){
                                $output[] = $topic;
                            }
                        } else {
                            $term_m = 'topic';
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => false,
                            ) );

                            $output = array();
                            foreach( $terms as $term){
                                $output[] = $term->slug;
                            }
                        }
                    ?>

                    <?php if($keyword != '') {
                        $args = array(
                            'post_type' => 'post',
                            's' => $keyword,
                            'posts_per_page' => -1,
                            'paged'=> $paged,
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'topic',
                                    'field' => 'slug',
                                    'terms' => $output,
                                    'operator' => 'IN',
                                ),
                                array(
                                    'relation' => 'AND',
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'slug',
                                        'terms' => 'private-post',
                                        'operator' => 'NOT IN',
                                    ),
                                ),

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
                                    'terms' => $output,
                                    'operator' => 'IN',
                                ),
                                array(
                                    'relation' => 'AND',
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'slug',
                                        'terms' => 'private-post',
                                        'operator' => 'NOT IN',
                                    ),
                                ),
                            )
                        );
                    }
                    // if(current_user_can('mepr-active','memberships: 3829')) {
                    //
                    // } else {
                    //     array_push($args['tax_query'],array(
                    //             'taxonomy' => 'category',
                    //             'field' => 'slug',
                    //             'terms' => 'advantage-only',
                    //             'operator' => 'NOT IN',
                    //         )
                    //     );
                    // }

                    if(current_user_can('mepr-active','memberships: 9811')) {

                    } else {
                        array_push($args['tax_query'],array(
                                'taxonomy' => 'category',
                                'field' => 'slug',
                                'terms' => 'adapt-only',
                                'operator' => 'NOT IN',
                            )
                        );
                    }

                    if($filterType != '') {
                        if(empty($filterType)){

                        } else {
                            if($filterType == 'all') {
                                $term_m = 'filter-types';
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => false,
                                ) );

                                $types = array();
                                foreach( $terms as $term){
                                    $types[] = $term->slug;
                                }
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'filter-types',
                                        'field' => 'slug',
                                        'terms' => $types,
                                        'operator' => 'IN'
                                    )
                                );

                            } else {
                                // print_r($filterType);
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'filter-types',
                                        'field' => 'slug',
                                        'terms' => $filterType,
                                        'operator' => 'IN'
                                    )
                                );
                            }
                        }
                    }
                    $loop = new WP_Query( $args );

                    if ( $loop->have_posts() ) : ?>
                    <?php $counterResults = 0; ?>
                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <?php
                                $terms = get_the_terms( $post->ID, 'filter-types' );
                            ?>

                            <?php if ( $terms ) { ?>
                                <?php foreach($terms as $term) { ?>
                                    <?php $termCounter = $filterTypesResults[$term -> slug];
                                        $termCounter++;
                                        $filterTypesResults[$term -> slug] = $termCounter;
                                    ?>
                                <?php } ?>
                            <?php } ?>
                            <?php $counterResults++; ?>
                        <?php endwhile; ?>

                    <?php

                    // find out the domain:
                    $domain = $_SERVER['HTTP_HOST'];
                    // find out the path to the current file:
                    $path = $_SERVER['SCRIPT_NAME'];
                    // find out the QueryString:
                    $queryString = $_SERVER['QUERY_STRING'];
                    // put it all together:
                    $queryURL = "/blog?" . $queryString; ?>

                    <?php
                        $totalTypes = count($filterTypesResults); ?>
                        <span class="results">
                            <?php
                            $i = 0;
                            $lenall = count($filterTypesResults);
                            $countzero = count(array_keys($filterTypesResults, 0));
                            $len = $lenall - $countzero;
                            ?>
                            <span class="total"><?php echo $counterResults; ?> results found <?php if(empty($filterTopics)){ ?><?php if($keyword != '') {?> for "<?php echo $keyword;?>" <?php } } else { ?><?php if (count($filterTopics) > 1) {?> <?php } else { ?> for "<?php echo $topic;?>"<?php } ?> <?php } ?></span>
                            <span class="breakdown">
                                Explore below
                                <?php
                                foreach($filterTypesResults as $typeName => $typeValue){ ?>
                                    <?php if($typeValue == 0){ ?>
                                    <?php } else { ?>
                                        <?php if ($i == 0) {?> <?php } else if ($i == $len - 1) {?> and <?php } else {?>, <?php }?><a class="articlesButton" href="<?php echo $queryURL; ?>&filterType=<?php echo $typeName; ?>"><?php echo $typeValue; ?> <?php echo $typeName; ?></a>
                                        <?php $i++; ?>
                                    <?php }?>
                                <?php } ?>
                            </span>
                        </span>

                    <?php else : ?>
                        <?php endif; ?>
                        <?php wp_reset_query(); ?>
                <?php } ?>

                <?php $counter = -1; ?>
                <?php
                    if($sortBy != '') {
                        if(empty($sortBy)){
                            if(empty($sortPosts)){
                                $orderBy = 'date';
                            } else {
                            }
                        } else {
                            $orderBy = $sortBy;
                        }
                    } else {
                        if(empty($sortPosts)){
                            $order = 'DESC';
                        }
                    }

                    if($sort != '') {
                        if(empty($sort)){
                            if(empty($sortPosts)){
                                $orderBy = 'date';
                            } else {
                            }
                        } else {
                            $order = $sort;
                        }
                    } else {
                        if(empty($sortPosts)){
                            $order = 'DESC';
                        }
                    }

                    if ($sortPosts != '') {
                        if(empty($sortPosts)){
                            $orderBy = 'date';
                            $order = 'DESC';
                        } else {
                            if ($sortPosts == 'newest') {
                                $orderBy = 'date';
                                $order = 'DESC';
                            } else if ($sortPosts == 'oldest') {
                                $orderBy = 'date';
                                $order = 'ASC';
                            } else if ($sortPosts == 'titleASC') {
                                $orderBy = 'title';
                                $order = 'ASC';
                            } else if ($sortPosts == 'titleDESC') {
                                $orderBy = 'title';
                                $order = 'DESC';
                            }
                        }
                    } else {
                        if(empty($sortBy)){
                            $orderBy = 'date';
                        } else {
                        }

                        if(empty($sort)){
                            $order = 'DESC';
                        } else {
                        }
                    }

                    if($filterTopics != '') {
                        $output = array();
                        foreach( $filterTopics as $topic){
                            $output[] = $topic;
                        }
                    } else {
                        $term_m = 'topic';
                        $terms = get_terms( $term_m, array(
                            'hide_empty' => false,
                        ) );

                        $output = array();
                        foreach( $terms as $term){
                            $output[] = $term->slug;
                        }
                    }

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    if($filterTopics != '') {
                        if($keyword != '') {
                            $args = array(
                                'post_type' => 'post',
                                's' => $keyword,
                                'posts_per_page' => 9,
                                'paged'=> $paged,
                                'tax_query' => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms' => $output,
                                        'operator' => 'IN',
                                    ),
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'category',
                                            'field' => 'slug',
                                            'terms' => 'private-post',
                                            'operator' => 'NOT IN',
                                        ),
                                    ),

                                ),
                                'orderby'   => $orderBy,
                                'order' => $order
                            );
                        } else {
                            $args = array(
                                'post_type' => 'post',
                                'posts_per_page' => 9,
                                'paged'=> $paged,
                                'orderby'   => $orderBy,
                                'order' => $order,
                                'tax_query' => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms' => $output,
                                        'operator' => 'IN',
                                    ),
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'category',
                                            'field' => 'slug',
                                            'terms' => 'private-post',
                                            'operator' => 'NOT IN',
                                        ),
                                    ),
                                )
                            );
                        }
                    } else {
                        if($keyword != '') {
                            $args = array(
                                'post_type' => 'post',
                                's' => $keyword,
                                'posts_per_page' => 9,
                                'paged'=> $paged,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'slug',
                                        'terms' => 'private-post',
                                        'operator' => 'NOT IN',
                                    ),
                                    'relation' => 'AND',
                                ),
                                'orderby'   => $orderBy,
                                'order' => $order
                            );
                        } else {
                            $args = array(
                                'post_type' => 'post',
                                'posts_per_page' => 9,
                                'paged'=> $paged,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'slug',
                                        'terms' => 'private-post',
                                        'operator' => 'NOT IN',
                                    ),
                                    'relation' => 'AND',
                                ),
                                'orderby'   => $orderBy,
                                'order' => $order
                            );
                        }
                    }

                    // if(current_user_can('mepr-active','memberships: 3829')) {
                    //
                    // } else {
                    //     array_push($args['tax_query'],array(
                    //             'taxonomy' => 'category',
                    //             'field' => 'slug',
                    //             'terms' => 'advantage-only',
                    //             'operator' => 'NOT IN',
                    //         )
                    //     );
                    // }

                    if(current_user_can('mepr-active','memberships: 9811')) {

                    } else {
                        array_push($args['tax_query'],array(
                                'taxonomy' => 'category',
                                'field' => 'slug',
                                'terms' => 'adapt-only',
                                'operator' => 'NOT IN',
                            )
                        );
                    }

                    if($filterType != '') {
                        if(empty($filterType)){

                        } else {
                            if($filterType == 'all') {
                                $term_m = 'filter-types';
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => false,
                                ) );

                                $types = array();
                                foreach( $terms as $term){
                                    $types[] = $term->slug;
                                }
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'filter-types',
                                        'field' => 'slug',
                                        'terms' => $types,
                                        'operator' => 'IN'
                                    )
                                );

                            } else {
                                // print_r($filterType);
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'filter-types',
                                        'field' => 'slug',
                                        'terms' => $filterType,
                                        'operator' => 'IN'
                                    )
                                );
                            }
                        }
                    }

                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) : ?>

                <?php while ( $loop->have_posts() ) : $loop->the_post();
                ?>

                <?php if(current_user_can('mepr_auth')) {?>
                    <span class="postLink list-view layout<?php echo $counter; ?>">
                        <div class="linkWrapper">

                            <a href="<?php the_permalink(); ?>" class="imageContainer">
                                <?php if ( get_field( 'listing_image') ) { ?>
                                    <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                        <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                            <span class="watchIcon"></span>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                        <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                            <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                <span class="watchIcon"></span>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                            <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                <span class="watchIcon"></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </a>
                            <span class="blogText">
                                <span class="postDetails">
                                    <span class="info">
                                        <?php
                                        $term_m = 'topic';
                                        ?>
                                        <?php
                                        $terms = get_the_terms( $post->ID, 'topic' );
                                        ?>
                                        <?php if ( $terms ) { ?>
                                            <?php $counterTopic = 0; ?>
                                            <?php $len = count($terms); ?>
                                            <?php foreach($terms as $term) { ?>

                                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                     <a href="<?php echo $queryURL; ?><?php if ($filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else { ?>?<?php } ?>topics[]=<?php echo $term -> slug; ?>"><?php echo $term -> name; ?></a>
                                                </span>
                                                <?php $counterTopic++; ?>
                                            <?php } ?>

                                        <?php } ?>
                                        <span class="date list-info">
                                            <?php echo get_the_date('d.m.Y'); ?>
                                        </span>
                                        <span class="readTime list-info">
                                            <?php the_field( 'read_time' ); ?>
                                        </span>
                                    </span>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                <span class="excerpt">
                                    <?php echo the_excerpt(); ?>
                                </span>

                                <?php
                                    $post_tags = get_the_tags();
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
                                <span class="grid-bottom-details">
                                    <span class="date grid-info">
                                        <?php echo get_the_date('d.m.Y'); ?> |
                                    </span>
                                    <span class="readTime grid-info">
                                        <?php the_field( 'read_time' ); ?>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </span>

                <?php } else { ?>
                    <!--  User has no access to post -->
                        <span class="postLink list-view layout<?php echo $counter; ?> memberContentLock">
                            <span class="overlay">
                                <span class="exclusiveContent">
                                    <span class="overlayText"><?php the_field('member_content_post_overlay_text', 'option'); ?></span>
                                    <span class="registerLogin">
                                        <a class="registerLink" href="/researchadvisory">Register</a>
                                        <span>or</span>
                                        <a class="loginLink loginPopupButton" href="#loginform">Login</a>
                                    </span>
                                </span>
                            </span>
                            <div class="linkWrapper">
                                <a href="<?php the_permalink(); ?>" class="imageContainer">
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                            <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                <span class="watchIcon"></span>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                    <span class="watchIcon"></span>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                    <span class="watchIcon"></span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </a>
                                <span class="blogText">
                                    <span class="postDetails">
                                        <span class="info">
                                                <?php
                                                $term_m = 'topic';
                                                ?>
                                                <?php
                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                ?>
                                                <?php if ( $terms ) { ?>
                                                    <?php $counterTopic = 0; ?>
                                                    <?php $len = count($terms); ?>
                                                    <?php foreach($terms as $term) { ?>

                                                        <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                             <a href="<?php echo $queryURL; ?><?php if ($filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else { ?>?<?php } ?>topics[]=<?php echo $term -> slug; ?>"><?php echo $term -> name; ?></a>
                                                        </span>
                                                        <?php $counterTopic++; ?>
                                                    <?php } ?>

                                                <?php } ?>
                                                <span class="date list-info">
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                </span>
                                                <span class="readTime list-info">
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                        </span>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                    <span class="excerpt">
                                        <?php echo the_excerpt(); ?>
                                    </span>
                                    <span class="grid-bottom-details">
                                        <span class="date grid-info">
                                            <?php echo get_the_date('d.m.Y'); ?> |
                                        </span>
                                        <span class="readTime grid-info">
                                            <?php the_field( 'read_time' ); ?>
                                        </span>
                                    </span>
                                </span>
                            </div>
                        </span>

                <?php }?>

                <?php $counter++; ?>

            <?php endwhile; ?>
            <?php else : ?>
                    <h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
                <?php endif; ?>
                <?php wp_pagenavi( array( 'query' => $loop ) ); ?>
                <?php wp_reset_query(); ?>
                </div>

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
