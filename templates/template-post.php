<section class="postHeader post-insights">
    <div class="container">
        <div class="headerWrapper">
            <h1><?php the_field( 'title_text', 'option' ); ?></h1>
            <span class="subTitle">
                <?php the_field( 'sub_title', 'option' ); ?>
            </span>
            <?php if(current_user_can('mepr-active','membership:26')) { ?>
            <?php } else { ?>
                <span class="memberLogin">
                    <span class="title">Members Area</span>
                    <a class="button" href="/members-login" target="_self">Login</a>
                    <a class="text" href="/members" target="_self">Register</a>
                </span>
            <?php } ?>
        </div>
        <div class="filter">
            <div class="search">

            </div>

            <div class="categories">
                <form action="" name="insightsFilter" class="insightsFilter" method="get">
                    <span class="categories">
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
                            <span class="checkboxButton">
                                <label>
                                  <input type="checkbox" name="categories[]" <?php if($filterCat == '') { } else { if (in_array( $term -> slug, $filterCat )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                </label>
                            </span>
                        <?php } ?>
                    </span>

                    <input type="submit" class="button filterButton" value="Filter" />
                </form>
            </div>
        </div>
    </div>
</section>

<section class="blogWrapper post-insights">
    <div class="container">
        <div id="loop" class="grid">
            <?php $counter = -1; ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <?php if ( get_field ( 'member_content' ) == yes ) { ?>
                    <?php if(current_user_can('mepr-active','membership:26')) { ?>
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
                        <span href="<?php the_permalink(); ?>" class="postLink layout<?php echo $counter; ?> memberContentLock" target="_self">
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
                        </span>
                    <?php } ?>
                <?php } else { ?>
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
                <?php } ?>

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
