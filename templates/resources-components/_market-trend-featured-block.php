<section class="market-trends-featured featured-module">
    <div class="container">
        <div class="title-container desktop">
            <div class="introduction-column two-thirds">
                <?php $term = get_term_by('slug', 'market-trend-reports', 'resource-type');?>
                <?php if (get_sub_field( 'title' )) {?>
                    <h2 class="taxonomy-title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="taxonomy-description text-black"><?php echo get_sub_field( 'text' ); ?></p>
                <?php } else { ?>

                    <h2 class="taxonomy-title text-black"><?php echo $term->name; ?></h2>
                    <p class="taxonomy-description text-black"><?php echo $term->description; ?></p>
                <?php } ?>
            </div>
            <div class="link-container one-third">
                <a class="red-text-link" href="<?php echo get_term_link($term); ?>"><?php echo get_sub_field( 'view_all_text' ); ?></a>
            </div>
        </div>
        <div class="sidebar">
            <span class="subscribe-sidebar-form background-pink">
                <span class="icon-container">
                    <span class="icon-inner">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg"/>
                    </span>
                </span>
                <h5 class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></h5>
                <p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>                
                <span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
            </span>
        </div>
        <div class="title-container mobile">
            <div class="introduction-column two-thirds">
                <?php $term = get_term_by('slug', 'market-trend-reports', 'resource-type');?>
                <?php if (get_sub_field( 'title' )) { ?>
                    <h2 class="taxonomy-title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                    <p class="taxonomy-description text-black"><?php echo get_sub_field( 'text' ); ?></p>
                <?php } else { ?>

                    <h2 class="taxonomy-title text-black"><?php echo $term->name; ?></h2>
                    <p class="taxonomy-description text-black"><?php echo $term->description; ?></p>
                <?php } ?>
            </div>
            <div class="link-container one-third">
                <a class="red-text-link" href="<?php echo get_term_link($term); ?>"><?php echo get_sub_field( 'view_all_text' ); ?></a>
            </div>
        </div>
        <div class="post-container grid-wrapper">
            <?php if ( get_sub_field( 'select_or_most_recent' ) == 'most-recent') { ?>
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 6,
                        'paged'=> $paged,
                        'tax_query' => array(
                            'relation' => 'AND',
                            array (
                                'taxonomy' => 'resource-type',
                                'field' => 'slug',
                                'terms'    => 'market-trend-reports'
                            ),
                        )
                    );

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div class="item market-trend-reports">
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php $featured_image = get_field( 'featured_image' ); ?>
                                    <?php if ( $featured_image ) { ?>
                                    	<img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                    <?php } ?>
                                </span>
                                <span class="bg-container bg-container-hover">
                                    <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                    <?php if ( $listing_hover_image ) { ?>
                                    	<img src="<?php echo $listing_hover_image['url']; ?>" alt="<?php echo $listing_hover_image['alt']; ?>" />
                                    <?php } ?>
                                </span>
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
                                    } ?>
                                    <?php if ( !empty( $postTopic ) ) { ?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title label-XXLarge text-black"><?php the_title(); ?></a>
                            </span>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
    			<?php endif; ?>
            <?php } else { ?>
                <?php if ( have_rows( 'posts' ) ) : ?>
    				<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
    					<?php $post_object = get_sub_field( 'post' ); ?>
    					<?php if ( $post_object ): ?>
    						<?php $post = $post_object; ?>
    						<?php setup_postdata( $post ); ?>
                            <div class="item market-trend-reports">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                        <?php if ( $featured_image ) { ?>
                                        	<img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                        <?php } ?>
                                    </span>
                                    <span class="bg-container bg-container-hover">
                                        <?php $listing_hover_image = get_field( 'listing_hover_image' ); ?>
                                        <?php if ( $listing_hover_image ) { ?>
                                        	<img src="<?php echo $listing_hover_image['url']; ?>" alt="<?php echo $listing_hover_image['alt']; ?>" />
                                        <?php } ?>
                                    </span>
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
    						<?php wp_reset_postdata(); ?>
    					<?php endif; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            <?php } ?>
        </div>
    </div>
</section>
