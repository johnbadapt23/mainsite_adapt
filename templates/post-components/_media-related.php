<div class="related-articles-press related-articles">
    <div class="container">
        <?php while ( have_rows( 'featured_news' ) ) : the_row(); ?>
            <span class="labelXXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
            <div class="post-container">
                <?php if ( get_sub_field( 'select_articles_or_by_taxonomy' ) == 'choose-articles' ){ ?>
                    <?php if ( have_rows( 'articles' ) ) : ?>
                        <?php while ( have_rows( 'articles' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'post' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?>
                                    <div class="item press-release-item">
                                        <span class="press-date-container column">
                                            <span class="v-wrap">
                                                <span class="v-box">
                                                    <span class="date-inner">
                                                        <?php if (get_field('published_date')){ ?>                         
                                                        <?php  $date_string = get_field('published_date');
                                                            // Create DateTime object from value (formats must match).
                                                            $date = DateTime::createFromFormat('Ymd', $date_string); ?>
                                                            Published <?php echo $date->format('M j, Y'); ?> in
                                                            <span class="date-day text-red"><?php echo $date->format('j') ?></span>
                                                            <span class="date-month text-black labelMedium"><?php echo $date->format('M') ?></span>
                                                            <span class="date-year text-black labelMedium"><?php echo $date->format('Y') ?></span>
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
                                                <a href="<?php the_permalink(); ?>" class="title text-black labelXXLarge"><?php the_title(); ?></a>
                                                <span class="published-date mobile-only">
                                                    <?php if (get_field('published_date')){ ?>                         
                                                        <?php  $date_string = get_field('published_date');
                                                        // Create DateTime object from value (formats must match).
                                                        $date = DateTime::createFromFormat('Ymd', $date_string); ?>
                                                        /<?php echo $date->format('M j, Y'); ?>
                                                    <?php } else { ?> 
                                                        /<?php echo $date->format('M j, Y'); ?>
                                                    <?php } ?>	
                                                </span>                                                
                                                <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                                            </span>
                                        </span>
                                        <span class="read-more-container column">
                                            <span class="v-wrap">
                                                <span class="v-box">
                                                    <a href="<?php the_permalink(); ?>" class="read-more text-link text-red">Read more</a>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } else { ?>
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                    <?php
                    $args = array(
                        'post_type' => 'media',
                        'posts_per_page' => 2,
                        'paged'=> $paged                        
                    ); ?>
                    <?php $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <div class="item press-release-item">
                                <span class="press-date-container column">
                                    <span class="v-wrap">
                                        <span class="v-box">
                                            <span class="date-inner">
                                                <span class="date-day text-red"><?php echo get_the_date('j') ?></span>
                                                <span class="date-month text-black labelMedium"><?php echo get_the_date('M') ?></span>
                                                <span class="date-year text-black labelMedium"><?php echo get_the_date('Y') ?></span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                                <span class="item-content-container column">
                                    <span class="content-inner">
                                        <a href="<?php the_permalink(); ?>" class="title text-black labelXXLarge"><?php the_title(); ?></a>
                                        <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                                    </span>
                                </span>
                                <span class="read-more-container column">
                                    <span class="v-wrap">
                                        <span class="v-box">
                                            <a href="<?php the_permalink(); ?>" class="read-more text-link text-red">Read more</a>
                                        </span>
                                    </span>
                                </span>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                <?php } ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
