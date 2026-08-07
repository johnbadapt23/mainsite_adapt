<section class="two-column-services background-white" id="<?php echo get_sub_field('id');?>">
    <div class="container">
        <?php if (get_sub_field( 'title' )) { ?>
            <span class="title-container">
                <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            </span>
        <?php } ?>
        <?php $background = get_sub_field( 'background_colour' ); ?>
        <div class="services-column-container <?php echo $background; ?>">
            <?php if ( have_rows( 'text_column' ) ) : ?>
            	<?php while ( have_rows( 'text_column' ) ) : the_row(); ?>
                    <div class="column one-half text-column">
                        <div class="text-content-inner">
                    		<span class="pre-title"><?php echo get_sub_field( 'pre_title' ); ?></span>
                    		<h2><?php echo get_sub_field( 'title' ); ?></h2>
                    		<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="links-container desktop">
                        		<?php if ( have_rows( 'button' ) ) : ?>
                        			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        				<a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        			<?php endwhile; ?>
                        		<?php else : ?>
                        			<?php // no rows found ?>
                        		<?php endif; ?>
                        		<?php if ( have_rows( 'text_link' ) ) : ?>
                        			<?php while ( have_rows( 'text_link' ) ) : the_row(); ?>
                                        <a class="text-link large-link-text red-text red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        			<?php endwhile; ?>
                        		<?php else : ?>
                        			<?php // no rows found ?>
                        		<?php endif; ?>
                            </span>
                        </div>
                    </div>
            	<?php endwhile; ?>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'services_column' ) ) : ?>
            	<?php while ( have_rows( 'services_column' ) ) : the_row(); ?>
                    <div class="column one-half icon-text-column">
            		<?php if ( have_rows( 'service' ) ) : ?>
            			<?php while ( have_rows( 'service' ) ) : the_row(); ?>
                            <div class="service">
                				<?php $icon = get_sub_field( 'icon' ); ?>
                                <span class="icon-container">
                                    <span class="image-container">
                                        <span class="bg-container contained-image">
                            				<?php if ( $icon ) { ?>
                            					<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                            						'alt'     => $icon['alt'],
                            						'loading' => 'lazy',
                            					) ); ?>
                            				<?php } ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="text-container">
                    				<span class="text labelLarge"><?php echo get_sub_field( 'text' ); ?></span>
                                </span>
                            </div>
            			<?php endwhile; ?>
            		<?php else : ?>
            			<?php // no rows found ?>
            		<?php endif; ?>
            	<?php endwhile; ?>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'text_column' ) ) : ?>
            	<?php while ( have_rows( 'text_column' ) ) : the_row(); ?>
                    <span class="links-container mobile">
                        <?php if ( have_rows( 'button' ) ) : ?>
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <?php if ( have_rows( 'text_link' ) ) : ?>
                            <?php while ( have_rows( 'text_link' ) ) : the_row(); ?>
                                <a class="text-link large-link-text red-text red-underline-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
