<?php
/**
 * Template Name: Login Template
 */

get_header();

?>


<main class="page flexible login background-white" id="main">
    <?php if ( have_rows( 'content' ) ): ?>
    	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'login_block' ) : ?>
                <section class="login-module">
                    <div class="container">
                        <div class="login-inner">
                            <h1 class="h2-style black-text"><?php echo get_sub_field( 'title' ); ?></h1>
                            <div class="column-container">
                                <?php if ( have_rows( 'column' ) ) : ?>
                    				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                                        <div class="column site-link-column background-light-grey one-half">
                                            <span class="logo-container">
                                                <span class="image-container">
                                                    <span class="bg-container contained-image">
                                                        <?php $logo = get_sub_field( 'logo' ); ?>
                                    					<?php if ( $logo ) { ?>
                                    						<?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                    							'alt'     => $logo['alt'],
                                    							'loading' => 'lazy',
                                    						) ); ?>
                                    					<?php } ?>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="content-container">
                                                <h4 role="heading" aria-level="2" class="black-text"><?php echo get_sub_field( 'title' ); ?></h4>
                                                <span class="text black-text"><?php echo get_sub_field( 'text' ); ?></span>
                                            </span>
                                            <span class="button-container">
                                                <?php if ( have_rows( 'button' ) ) : ?>
                            						<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            							<a class="std-button red-button button-with-arrow" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                            						<?php endwhile; ?>
                            					<?php else : ?>
                            						<?php // no rows found ?>
                            					<?php endif; ?>
                                            </span>
                                        </div>
                    				<?php endwhile; ?>
                    			<?php else : ?>
                    				<?php // no rows found ?>
                    			<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
