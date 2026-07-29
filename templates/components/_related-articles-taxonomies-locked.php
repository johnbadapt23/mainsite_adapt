<section class="relatedArticlesCarousel scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="inner">
            <h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>
                <div class="owl-carousel articlesCarouselTaxonomies">
                    <?php if (get_sub_field( 'taxonomy_type' ) == 'event') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'insights-event';
                        $terms     =  get_sub_field( 'event' );

                        foreach( $terms as $term ) :
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 8,
                                'orderby'        => 'rand',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );

                            $posts = new WP_Query( $args );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else if (get_sub_field( 'taxonomy_type' ) == 'topic') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'topic';
                        $terms     =  get_sub_field( 'topic' );

                        foreach( $terms as $term ) :
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 8,
                                'orderby'        => 'rand',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );

                            $posts = new WP_Query( $args );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                          <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else if (get_sub_field( 'taxonomy_type' ) == 'filter-type') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'filter-types';
                        $terms     =  get_sub_field( 'filter_type' );

                        foreach( $terms as $term ) :
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 8,
                                'orderby'        => 'rand',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );

                            $posts = new WP_Query( $args );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php the_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php the_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php the_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php the_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php the_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else { ?>
                        <span>No posts</span>
                    <?php  }?>
                </div>
            </div>
            <?php if ( have_rows( 'button_block' ) ) : ?>
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <div class="buttonBlock <?php the_sub_field('link_orientation'); ?>">
                        <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>
