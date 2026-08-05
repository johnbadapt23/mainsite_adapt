<section class="meet-the-team">
    <div class="container">
        <div class="team-container">
            <div class="team-container-inner">
                <?php if ( have_rows( 'team_members' ) ) : ?>
                	<?php while ( have_rows( 'team_members' ) ) : the_row(); ?>
                		<?php $post_object = get_sub_field( 'team_member' ); ?>
                		<?php if ( $post_object ): ?>
                			<?php $post = $post_object; ?>
                            <?php $post_slug = $post->post_name; ?>
                			<?php setup_postdata( $post ); ?>
                                <span class="column one-quarter" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $team_member_image = get_field( 'team_member_image' ); ?>
                                            <?php if ( $team_member_image ) { ?>
                                                <?php echo wp_get_attachment_image( $team_member_image['ID'], 'full', false, array(
                                                    'alt'     => $team_member_image['alt'],
                                                    'loading' => false,
                                                ) ); ?>
                                            <?php } ?>
                                        </span>
                                        <span class="border-offset"></span>
                                        <span class="border-offset-two"></span>
                                    </span>
                                </span>
                			<?php wp_reset_postdata(); ?>
                		<?php endif; ?>
                	<?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="content-container">
            <div class="content-container-inner">
                <h2 class="title black-text"><?php echo get_sub_field( 'title' ); ?></h2>
    			<span class="text-container black-text"><?php echo get_sub_field( 'text' ); ?></span>
                <span class="button-link-container">
        			<?php if ( have_rows( 'button' ) ) : ?>
        				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
        					<a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
        			<?php if ( have_rows( 'text_link' ) ) : ?>
        				<?php while ( have_rows( 'text_link' ) ) : the_row(); ?>
        					<a class="text-link red-text large-link-text red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </span>
            </div>
        </div>
    </div>
</section>
