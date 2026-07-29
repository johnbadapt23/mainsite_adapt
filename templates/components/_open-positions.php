<?php $args = array(
    'post_type' => 'position',
    'posts_per_page' => -1,
    'paged'=> $paged
);

$posts = new WP_Query( $args );
if( $posts->have_posts() ): ?>
    <section class="open-positions" <?php if ( get_sub_field( 'id' )){ ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
        <div class="container">
            <div class="title-column column">
                <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="curly-arrow-container"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/curved-arrows-right.svg" alt="" width="200"/></span>
            </div>
            <div class="positions-column column">
                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <a href="<?php the_permalink();?>" target="_self">
                        <span class="item position-item">
                            <span class="labelXLarge text-black"><?php the_title(); ?></span>
                            <?php if (yoast_get_primary_term_id('position-team')) {
                                $primary_term_topic_id = yoast_get_primary_term_id('position-team');
                                $postTopic = get_term( $primary_term_topic_id );
                            } else {
                                if(get_the_terms( $post->ID, 'position-team' )){
                                    $terms = get_the_terms( $post->ID, 'position-team' );
                                    foreach($terms as $term) {
                                        $postTopic = $term;
                                    }
                                }
                            }?>
                            <?php if ( !empty( $postTopic ) ) { ?>
                                <span class="team-term text-black"><?php echo $postTopic->name; ?></span>
                            <?php } ?>
                        </span>
                    </a>
                <?php endwhile; ?>
                <?php if ( have_rows( 'looking_for_another_role', 'options' ) ) : ?>
                	<?php while ( have_rows( 'looking_for_another_role', 'options' ) ) : the_row(); ?>
                        <span class="other-roles-container background-medium-light-grey">
                    		<span class="labelXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
                    		<p class="text-black"><?php echo get_sub_field( 'text' ); ?></p>
                            <span class="form-popup-text-link-container submit-cv-link with-red-underline-link text-red text-link"><?php echo get_sub_field( 'submit_cv_form_button' ); ?></span>
                            <span class="form-container"><?php echo get_sub_field( 'submit_cv_form_embed' ); ?></span>
                        </span>
                	<?php endwhile; ?>
                <?php else : ?>
                	<?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif;?>
