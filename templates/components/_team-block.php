<section class="team-block <?php echo get_sub_field( 'background_colour' ); ?> <?php echo get_sub_field('display_full_bios'); ?>">
    <div class="container">
        <?php $number_of_columns = get_sub_field('number_of_columns'); ?>
        <?php if ( get_sub_field ( 'display_full_bios' ) == 'bio-block' ) { ?>
            <div class="column-container">
                <div class="column title-column">
                    <h2><?php echo get_sub_field( 'heading' ); ?></h2>
                </div>
                <?php if ( have_rows( 'team_members' ) ) : ?>
                    <div class="column team-column <?php echo $number_of_columns; ?>">
                    	<?php while ( have_rows( 'team_members' ) ) : the_row(); ?>
                    		<?php $post_object = get_sub_field( 'team_member' ); ?>
                    		<?php if ( $post_object ): ?>
                    			<?php $post = $post_object; ?>
                                <?php $post_slug = $post->post_name; ?>
                    			<?php setup_postdata( $post ); ?>
                                    <a class="column <?php echo $number_of_columns; ?> slide-out-bio" href="#<?php echo $post_slug; ?>" id="<?php echo $post_slug; ?>">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $team_member_image = get_field( 'team_member_image' ); ?>
                                                <?php if ( $team_member_image ) { ?>
                                                    <?php echo wp_get_attachment_image( $team_member_image['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $team_member_image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="border-offset"></span>
                                            <span class="border-offset-two"></span>
                                        </span>
                                        <span class="text">
                                            <h2><?php the_title(); ?></h2>
                                            <h3><?php echo get_field('team_member_description'); ?></h3>
                                        </span>
                                    </a>
                    			<?php wp_reset_postdata(); ?>
                    		<?php endif; ?>
                    	<?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php } else { ?>
            <div class="column-container">
                <div class="column title-column">
                    <h2><?php echo get_sub_field( 'heading' ); ?></h2>
                </div>
                <?php if ( have_rows( 'team_members' ) ) : ?>
                    <div class="column team-column <?php echo $number_of_columns; ?>">
                    	<?php while ( have_rows( 'team_members' ) ) : the_row(); ?>
                    		<?php $post_object = get_sub_field( 'team_member' ); ?>
                    		<?php if ( $post_object ): ?>
                    			<?php $post = $post_object; ?>
                    			<?php setup_postdata( $post ); ?>
                                    <span class="column <?php echo $number_of_columns; ?>">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $team_member_image = get_field( 'team_member_image' ); ?>
                                                <?php if ( $team_member_image ) { ?>
                                                    <?php echo wp_get_attachment_image( $team_member_image['ID'], 'adapt-optimized', false, array(
                                                        'alt'     => $team_member_image['alt'],
                                                        'loading' => 'lazy',
                                                    ) ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="border-offset"></span>
                                        </span>
                                        <span class="text">
                                            <h2><?php the_title(); ?></h2>
                                            <h3><?php echo get_field('team_member_description'); ?></h3>
                                        </span>
                                    </span>
                    			<?php wp_reset_postdata(); ?>
                    		<?php endif; ?>
                    	<?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php } ?>
    </div>

    <?php if ( get_sub_field ( 'display_full_bios' ) == 'bio-block' ) { ?>
        <?php if ( have_rows( 'team_members' ) ) : ?>
            <?php while ( have_rows( 'team_members' ) ) : the_row(); ?>
                <?php $post_object = get_sub_field( 'team_member' ); ?>
                <?php if ( $post_object ): ?>
                    <?php $post = $post_object; ?>
                    <?php $post_slug = $post->post_name; ?>
                    <?php setup_postdata( $post ); ?>
                        <div id="<?php echo $post_slug; ?>" class="full-bio">
                            <span class="close-bio"></span>
                            <span class="bio-top">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php $team_member_image = get_field( 'team_member_image' ); ?>
                                        <?php if ( $team_member_image ) { ?>
                                            <?php echo wp_get_attachment_image( $team_member_image['ID'], 'adapt-optimized', false, array(
                                                'alt'     => $team_member_image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="border-offset"></span>
                                </span>
                                <span class="text">
                                    <h2><?php the_title(); ?></h2>
                                    <h3><?php echo get_field('team_member_description'); ?></h3>
                                    <a class="linkedin" href="<?php echo get_field('linked_in_url'); ?>"><img loading="lazy" class="linkedin-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-new.svg" alt="LinkedIn" width="28" /></a>
                                </span>
                            </span>
                            <span class="bio-bottom">
                                <?php echo get_field('about'); ?>
                            </span>
                        </div>
                        <div class="click-overlay"></div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>
    <?php } else { ?>
    <?php } ?>
</section>
