<section class="market-featured filter-listing">
    <div class="container">
        <div class="title-container desktop">
            <div class="introduction-column two-thirds">                
                <h2 class="taxonomy-title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
        </div>
        <div class="post-container grid-wrapper">
            <?php if ( get_sub_field( 'choose_posts' ) == 'no') { ?>
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'paged'=> $paged,
                        'tax_query' => array(
                            'relation' => 'AND',
                            array (
                                'taxonomy' => 'topic',
                                'field' => 'slug',
                                'terms'    => 'go-to-market'
                            ),
                        )
                    );

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div class="item one-third articles">
                            <a href="<?php the_permalink(); ?>" target="_self">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php $featured_image = get_field( 'featured_image' ); ?>
                                        <?php if ( $featured_image ) { ?>
                                            <img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                        <?php } ?>
                                    </span>                               
                                </span>
                            </a>
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
                                    <?php if (yoast_get_primary_term_id('resource-type')) {
                                        $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                        $postType= get_term( $primary_term_type_id );
                                    } else {
                                        if(get_the_terms( $post->ID, 'resource-type' )){
                                            $terms = get_the_terms( $post->ID, 'resource-type' );
                                            foreach($terms as $term) {
                                                $postType= $term;
                                            }
                                        }
                                    }?>
                                    <?php if ( !empty( $postTopic ) ) { ?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text mobile-hide"><?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                    <?php if ( !empty( $postType ) ) { ?>
                                        <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text">/ <?php echo $postType->name; ?> </a>
                                    <?php } ?>
                                    
                                </span>
                                <a href="<?php the_permalink(); ?>"  target="_self" class="title label-XXLarge text-black"><?php the_title(); ?></a>
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
                            <div class="item one-third articles">
                                <a href="<?php the_permalink(); ?>" target="_self">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $featured_image = get_field( 'featured_image' ); ?>
                                            <?php if ( $featured_image ) { ?>
                                                <img src="<?php echo $featured_image['url']; ?>" alt="<?php echo $featured_image['alt']; ?>" />
                                            <?php } ?>
                                        </span>                               
                                    </span>
                                </a>
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
                                        <?php if (yoast_get_primary_term_id('resource-type')) {
                                            $primary_term_type_id = yoast_get_primary_term_id('resource-type');
                                            $postType= get_term( $primary_term_type_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'resource-type' )){
                                                $terms = get_the_terms( $post->ID, 'resource-type' );
                                                foreach($terms as $term) {
                                                    $postType= $term;
                                                }
                                            }
                                        }?>
                                        <?php if ( !empty( $postType ) ) { ?>
                                            <a href="<?php echo get_term_link($postType); ?>" class="topic-filter-text"><?php echo $postType->name; ?> </a>
                                        <?php } ?>
                                        <?php if ( !empty( $postTopic ) ) { ?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" target="_self" class="title label-XXLarge text-black"><?php the_title(); ?></a>
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
        <div class="button-container">
            <?php if ( have_rows( 'button' ) ) : ?>
				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    <?php if (get_sub_field('link_target') == 'scroll-to'){ ?> 
                        <a class="std-button red-button scroll-to-button" href="#<?php echo get_sub_field( 'link' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>										
                    <?php } else { ?> 
                        <a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>										
                    <?php } ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
