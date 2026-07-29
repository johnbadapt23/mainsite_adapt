<div class="related-articles-press related-articles-external-press related-articles">
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
                                <?php if ( have_rows( 'publication' ) ) : ?>
                                	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
                                		<?php $shortName = get_sub_field( 'publication_short_name' ); ?>
                                		<?php $publishedDate = get_sub_field( 'published_date' ); ?>
                                	<?php endwhile; ?>
                                <?php else : ?>
                                	<?php // no rows found ?>
                                <?php endif; ?>
                                <?php if($publishedDate){
                                    // Load field value.
                                    $date_string = $publishedDate;

                                    // Create DateTime object from value (formats must match).
                                    $date = DateTime::createFromFormat('Ymd', $date_string);
                                } ?>
                                <a href="<?php the_permalink(); ?>">
                                    <span class="item press-release-item external-press-release">
                                        <span class="container">
                                            <span class="item-content-container column">
                                                <span class="content-inner">
                                                    <span class="tag-container"><span class="tag">#<?php echo $shortName; ?></span></span>
                                                    <span class="title text-black labelXXLarge"><?php the_title(); ?></span>
                                                    <span class="published-date">/<?php echo $date->format('M j, Y'); ?></span>
                                                    <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                                                </span>
                                            </span>
                                            <span class="read-more-container column">
                                                <span class="v-wrap">
                                                    <span class="v-box">
                                                        <span class="read-more text-link text-red">Read more</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </a>
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
                        'post_type' => 'news',
                        'posts_per_page' => 2,
                        'paged'=> $paged                       
                    ); ?>
                    <?php $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <?php if ( have_rows( 'publication' ) ) : ?>
                                <?php while ( have_rows( 'publication' ) ) : the_row(); ?>
                                    <?php $shortName = get_sub_field( 'publication_short_name' ); ?>
                                    <?php $publishedDate = get_sub_field( 'published_date' ); ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if($publishedDate){
                                // Load field value.
                                $date_string = $publishedDate;

                                // Create DateTime object from value (formats must match).
                                $date = DateTime::createFromFormat('Ymd', $date_string);
                            } ?>
                            <a href="<?php the_permalink(); ?>">
                                <span class="item press-release-item external-press-release">
                                    <span class="container">
                                        <span class="item-content-container column">
                                            <span class="content-inner">
                                                <span class="tag-container"><span class="tag">#<?php echo $shortName; ?></span></span>
                                                <span class="title text-black labelXXLarge"><?php the_title(); ?></span>
                                                <span class="published-date">/<?php echo $date->format('M j, Y'); ?></span>
                                                <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                                            </span>
                                        </span>
                                        <span class="read-more-container column">
                                            <span class="v-wrap">
                                                <span class="v-box">
                                                    <span class="read-more text-link text-red">Read more</span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                            </a>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                <?php } ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
