<?php
/**
 * Template Name: Speaker Template
 */

get_header();
?>

<section class="postHeader">
    <div class="container">
        <div class="headerWrapper">
            <h1><?php the_field( 'members_area_title_text', 'option' ); ?></h1>
            <span class="subTitle">
                <?php the_field( 'members_area_sub_title', 'option' ); ?>
            </span>
        </div>
        <div class="filter">
            <span class="dropDown">
                <select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
                    <option value=""><?php echo esc_attr(__('Select Category')); ?></option>

                    <?php
                        $option = '<option value="' . get_option('home') . '/category/">All Categories</option>'; // change category to your custom page slug
                        $categories = get_categories();
                        foreach ($categories as $category) {
                            $option .= '<option value="'.get_option('home').'/category/'.$category->slug.'">';
                            $option .= $category->cat_name;
                            $option .= ' ('.$category->category_count.')';
                            $option .= '</option>';
                        }
                        echo $option;
                    ?>
                </select>
            </span>
        </div>
    </div>
</section>

<section class="blogWrapper">
    <div class="container">
        <div id="loop" class="grid">
            <?php $counter = -1; ?>
            <?php

                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $args = array( 'post_type' => 'speaker', 'posts_per_page' => 9, 'paged'=> $paged );
                $loop = new WP_Query( $args );
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>

                <a href="<?php the_permalink(); ?>" class="postLink layout<?php echo $counter; ?>" target="_self">
                    <div class="linkWrapper">
                        <?php if ( get_field ( 'podcast_file' ) ) { ?>
                            <span class="podcast"></span>
                        <?php } ?>
                        <div class="imageContainer">
                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                </div>
                            <?php } else { ?>
                                <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                </div>
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
                            <span class="articleLink"><?php echo the_title(); ?></span>
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
                </a>
                <?php $counter++; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata();?>

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


<?php get_footer(); ?>
