<?php $postType = $_GET['post_type']; ?>

    <section class="postHeader">
        <div class="container">
            <div class="headerWrapper">
                <h1><?php the_field( 'title_text', 'option' ); ?></h1>
                <span class="subTitle">
                    <?php the_field( 'sub_title', 'option' ); ?>
                </span>
                <span class="memberLogin">
                    <span class="title">Members Area</span>
                    <?php if(current_user_can('mepr-active')) { ?>
                        <span class="log-out-link"><?php echo do_shortcode("[mepr-login-link]"); ?></span>
                    <?php } else { ?>
                        <a class="button" href="/members-login" target="_self">Login</a>
                        <a class="text" href="/members" target="_self">Register</a>
                    <?php } ?>
                </span>
            </div>
            <?php if($postType == 'post') { ?>
                <div class="filter">
                    <div class="search">
                        <form action="/" method="get">
                            <input class="searchInput" type="text" name="s" id="search" placeholder="SEARCH FOR.. OR FILTER" value="" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                            <input type="hidden" value="1" name="sentence" />
                            <input type="hidden" value="post" name="post_type" id="post_type" />
                        </form>
                    </div>
                    <div class="formContainer">
                        <form action="/adapt-insights/" name="insightsFilter" class="insightsFilter" method="get">
                            <span class="spacer"></span>
                            <span class="categories">
                                <span class="more">More</span>
                                <?php
                                $term_m = 'category';
                                $filterCat = $_GET['categories'];
                                ?>
                                <?php
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => true,
                                ) );
                                ?>
                                <?php foreach($terms as $term) { ?>
                                    <span class="checkboxButton <?php echo $term -> slug; ?>">
                                        <label>
                                          <input type="checkbox" name="categories[]" <?php if($filterCat == '') { } else { if (in_array( $term -> slug, $filterCat )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                        </label>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="types">
                                <span class="title">Type</span>
                                <?php
                                $term_m = 'article-type';
                                $filterType = $_GET['types'];
                                ?>
                                <?php
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => true,
                                ) );
                                ?>
                                <?php foreach($terms as $term) { ?>
                                    <span class="checkboxButton">
                                        <label>
                                          <input type="checkbox" name="types[]" <?php if($filterType == '') { } else { if (in_array( $term -> slug, $filterType )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                        </label>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="events">
                                <span class="title">Event</span>
                                <?php
                                $term_m = 'insights-event';
                                $filterEvent = $_GET['events'];
                                ?>
                                <?php
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => true,
                                ) );
                                ?>
                                <?php foreach($terms as $term) { ?>
                                    <span class="checkboxButton">
                                        <label>
                                          <input type="checkbox" name="events[]" <?php if($filterEvent == '') { } else { if (in_array( $term -> slug, $filterEvent )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                        </label>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="duration insights-duration">
                                <span class="title">Duration</span>
                                <?php
                                $term_m = 'article-duration';
                                $filterDuration = $_GET['duration'];
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
                            </span>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <?php if($postType == 'event') { ?>
                <div class="filter">
                    <div class="search">
                        <form action="/" method="get">
                            <input class="searchInput" type="text" name="s" id="search" placeholder="SEARCH FOR.. OR FILTER" value="" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                            <input type="hidden" value="1" name="sentence" />
                            <input type="hidden" value="event" name="post_type" id="post_type" />
                        </form>
                    </div>
                    <div class="formContainer">
                        <form action="/edge-events/" name="insightsFilter" class="insightsFilter" method="get">
                            <span class="spacer"></span>
                            <span class="categories">
                                <span class="more">More</span>
                                <?php
                                $term_m = 'event-category';
                                $filterCat = $_GET['categories'];
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
                                $filterType = $_GET['types'];
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
                                          <input type="checkbox" name="types[]" <?php if($filterType == '') { } else { if (in_array( $term -> slug, $filterType )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><img src="<?php echo $image; ?>" alt=""/><?php echo $term -> name; ?></span>
                                        </label>
                                    </span>
                                <?php } ?>
                            </span>
                            <span class="duration">
                                <span class="title">Duration</span>
                                <?php
                                $term_m = 'event-duration';
                                $filterDuration = $_GET['duration'];
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
                            </span>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

<?php if($postType == 'post') { ?>
    <section class="blogWrapper">
        <div class="container">
            <h3>Showing results for: "<?php the_search_query(); ?>"</h3>
            <hr/>
            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                    <?php if(current_user_can('mepr_auth')) {?>
                        <!--  User has access to post -->

                        <span class="postLink layout<?php echo $counter; ?>">
                            <div class="linkWrapper">
                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                    <span class="podcast"></span>
                                <?php } ?>
                                <a href="<?php the_permalink(); ?>" class="imageContainer">
                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                        <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                        </div>
                                    <?php } else { ?>
                                        <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                        </div>
                                    <?php } ?>
                                </a>
                                <span class="blogText">
                                    <span class="postDetails">
                                        <span class="info">
                                            <span class="date">
                                                <?php echo get_the_date('d.m.Y'); ?>
                                            </span>
                                            <span class="readTime">
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
                                            <?php foreach( $post_tags as $tag ) { ?>
                                                <span>
                                                    <?php echo '#' . $tag->name  ; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </span>
                            </div>
                        </span>

                    <?php } else { ?>
                        <!--  User has no access to post -->
                            <span class="postLink layout<?php echo $counter; ?> memberContentLock">
                                <span class="overlay">
                                    <span class="exclusiveContent">
                                        <span class="overlayText"><?php the_field('member_content_post_overlay_text', 'option'); ?></span>
                                        <span class="registerLogin">
                                            <a class="registerLink" href="/members">Register</a>
                                            <span>or</span>
                                            <a class="loginLink" href="/members-login">Login</a>
                                        </span>
                                    </span>
                                </span>
                                <div class="linkWrapper">
                                    <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                        <span class="podcast"></span>
                                    <?php } ?>
                                    <div class="imageContainer">
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <span class="blogText">
                                        <span class="postDetails">
                                            <span class="info">
                                                <span class="date">
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                </span>
                                                <span class="readTime">
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
                                                <?php foreach( $post_tags as $tag ) { ?>
                                                    <span>
                                                        <?php echo '#' . $tag->name  ; ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </span>
                                </div>
                            </span>

                    <?php }?>

                    <?php $counter++; ?>

                <?php endwhile; endif; ?>

            </div>

            <?php if(paginate_links()) { ?>
                <span class="pagWrapper">
                    <span id="pagination" class="button-container"><?php next_posts_link( 'See More', $loop->max_num_pages ); ?></span>
                </span>
            <?php } ?>

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

<?php } ?>

<?php if($postType == 'event') { ?>
    <section class="blogWrapper">
        <div class="container">
            <h3>Showing results for: "<?php the_search_query(); ?>"</h3>
            <hr/>
            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="postLink layout<?php echo $counter; ?>" target="_self">
                        <div class="linkWrapper">

                            <div class="imageContainer">
                                <div class="image" style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');">
                                </div>
                            </div>
                            <span class="blogText">
                                <span class="postDetails">
                                    <span class="info">
                                        <span class="date">
                                            <?php echo get_the_date('d.m.Y'); ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="articleLink"><?php echo the_title(); ?></span>
                                <span class="excerpt">
                                    <?php the_field('event_short_description_for_listing'); ?>
                                </span>

                                <?php
                                    $post_tags = get_the_terms( $post->ID, 'events-tag');
                                ?>

                                <?php if ( $post_tags ) { ?>
                                    <div class="tags">
                                        <?php foreach( $post_tags as $tag ) { ?>
                                            <span>
                                                <?php echo '#' . $tag->name  ; ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </span>
                        </div>
                    </a>
                    <?php $counter++; ?>
                <?php endwhile; endif; ?>
            </div>


            <?php if(paginate_links()) { ?>
                <span class="pagWrapper">
                    <span id="pagination" class="button-container"><?php next_posts_link( 'See More', $loop->max_num_pages ); ?></span>
                </span>
            <?php } ?>

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
<?php } ?>
