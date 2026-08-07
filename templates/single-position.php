<section class="position-information">
    <span class="half-background background-pink one-half"></span>
    <div class="container">
        <div class="position-column-container">
            <div class="position-sticky position-title-column one-half">
                <span class="back-container"><a class="back" href="/careers" target="_self">Careers</a></span>
                <span class="title-tag-container">
                    <h1 class="text-black h2-style"><?php the_title(); ?></h1>
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
                <?php if ( have_rows( 'meet_your_team' ) ) : ?>
                	<?php while ( have_rows( 'meet_your_team' ) ) : the_row(); ?>
                        <span class="team-container">
                            <?php if(get_sub_field('title')){ ?> 
                                <span class="labelMedium text-black"><?php echo get_sub_field('title'); ?></span>
                            <?php } else {?>
                                <span class="labelMedium text-black">Meet your team</span>
                            <?php } ?>                            
                            <?php if ( have_rows( 'team_members' ) ) : ?>
                                <span class="team-image-container">
                        			<?php while ( have_rows( 'team_members' ) ) : the_row(); ?>
                        				<?php $post_object = get_sub_field( 'team_member' ); ?>
                        				<?php if ( $post_object ): ?>
                        					<?php $post = $post_object; ?>
                        					<?php setup_postdata( $post ); ?>
                                                <span class="team-image">
                                                    <span class="image-container">
                                                        <span class="bg-container">
                                                            <?php $speaker_image = get_field( 'team_member_image' ); ?>
                                                            <?php if ( $speaker_image ) { ?>
                                                                <?php echo wp_get_attachment_image( $speaker_image['ID'], 'full', false, array(
                                                                    'alt'     => $speaker_image['alt'],
                                                                    'loading' => 'lazy',
                                                                ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                        <span class="border-offset"></span>
                                                    </span>
                                                </span>
                        					<?php wp_reset_postdata(); ?>
                        				<?php endif; ?>
                        			<?php endwhile; ?>
                                </span>
                    		<?php else : ?>
                    			<?php // no rows found ?>
                    		<?php endif; ?>
                        </span>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <span class="apply-container bottom-position">
                    <?php if(get_field('hubspot_form_embed')){ ?>
                        <span class="form-popup-button-container">
                            <a class="formPopupHubspot std-button red-button apply-button" href="#formPopup">Apply Now</a>
                        </span>
                        <div style="display: none;">         
                            <div class="preview-cta-form login-form-container" id="formPopup">
                                <div class="form-container"><?php echo get_field( 'hubspot_form_embed' ); ?></div>
                            </div>
                        </div> 
                    <?php } else { ?> 
                        <span class="form-popup-button-container red-button apply-button"><?php echo get_field( 'apply_form_button' ); ?></span>
                        <div>
                            <?php echo get_field( 'apply_form_embed' ); ?>
                        </div>
                    <?php } ?>

                    <span class="share-job-container desktop">
                        <span class="copy-link">
                            <input type="text" value="<?php echo the_permalink(); ?>" id="jobLink" style="display: none;">
                            <a onclick="copyJobLink()">
                                <span class="image-icon-container">
                                    <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link.svg" alt="" width="32px"/>
                                    <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link-hover.svg" alt="" width="32px"/>
                                </span>
                                <span class="job-link-text">Copy Link</span>
                            </a>
                        </span>
                        <script>
                            function copyJobLink() {
                        		// Get the text field
                        		var copyText = document.getElementById("jobLink");

                        		// Select the text field
                        		copyText.select();
                        		copyText.setSelectionRange(0, 99999); // For mobile devices

                        		// Copy the text inside the text field
                        		navigator.clipboard.writeText(copyText.value);
                                jQuery('.copy-link .job-link-text').html('Copied');
                                jQuery('.copy-link .job-link-text').addClass('text-red');
                        	}
                        </script>
                        <span class="email-link">
                            <a class="email-button" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="image-icon-container">
                                    <img loading="lazy" class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" alt="" width="32px"/>
                                    <img loading="lazy" class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" alt="" width="32px"/>
                                </span>

                                <span class="job-link-text">Email Me</span>
                            </a>
                        </span>
                    </span>
                    <span class="share-job-container mobile">
                        <button class="share-button" type="button" title="Share this article">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/post-share.svg" alt="" width="32px"/>
                            <span class="job-link-text">Share</span>
                        </button>
                        <script>
                            const shareButton = document.querySelector('.share-button');
                            const emailButton = document.querySelector('.email-button');
                            shareButton.addEventListener('click', event => {
                              if (navigator.share) {
                               navigator.share({
                                  title: '<?php the_title(); ?>',
                                  url: '<?php echo the_permalink(); ?>'
                                }).then(() => {
                                  console.log('Thanks for sharing!');
                                })
                                .catch(console.error);
                                } else {
                                    emailButton.click();
                                }
                            });
                        </script>
                    </span>
                    <span class="back-top-link">
                        <button type="button" class="back-to-top">
                            <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-back-top.svg" alt="" width="32px"/>
                            <span class="job-link-text">Back to Top</span>
                        </button>
                    </span>
                </span>
            </div>
            <div class="overview-column one-half">
                <span class="overview-text post-content text-black">
                    <?php echo get_field( 'overview' ); ?>
                </span>
            </div>
        </div>
    </div>
</section>
<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'icon_slider' ) : ?>
            <?php get_template_part( 'templates/components/_position-icon-slider' ); ?>
		<?php elseif ( get_row_layout() == 'title_text_list' ) : ?>
			<?php get_template_part( 'templates/components/_text-title-list' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_list' ) : ?>
            <?php get_template_part( 'templates/components/_two-column-list' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>

<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php
$obj = get_queried_object();
$positionID = $obj->ID;

$args = array(
    'post_type' => 'position',
    'posts_per_page' => -1,
    'paged'=> $paged
);

$posts = new WP_Query( $args );
if( $posts->have_posts() ): ?>
    <section class="open-positions">
        <div class="container">
            <div class="title-column column">
                <h2 class="text-black">Open Positions</h2>
                <span class="curly-arrow-container"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/curved-arrows-right.svg" alt="" width="200"/></span>
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
<?php wp_reset_postdata(); ?>
<?php wp_reset_query(); ?>
