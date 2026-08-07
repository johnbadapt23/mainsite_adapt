<section class="large-testimonial-slider">
    <div class="container">
        <div class="background-container">
            <?php $background_image = get_sub_field( 'background_image' ); ?>
            <?php if ( $background_image ) { ?>
                <?php echo wp_get_attachment_image( $background_image['ID'], 'full', false, array(
                    'alt'     => $background_image['alt'],
                    'loading' => 'lazy',
                ) ); ?>
            <?php } ?>
        </div>  
        <div class="large-quote-slide-container">
            <?php if ( have_rows( 'slides' ) ) : ?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                    <div class="slide">
                        <div class="slide-inner">
                            <span class="logo-container">
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                        'alt'     => $logo['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                <?php } ?>
                            </span>
                            <span class="quote-container">
                                <span class="tag-container medium-grey"><?php the_sub_field( 'tag' ); ?></span>
                                <span class="quote header-large"><?php the_sub_field( 'quote' ); ?></span>
                                <span class="name-image-container">
                                    <span class="image-column">
                                        <?php $quote_image = get_sub_field( 'quote_image' ); ?>
                                        <?php if ( $quote_image ) { ?>
                                            <?php echo wp_get_attachment_image( $quote_image['ID'], 'full', false, array(
                                                'alt'     => $quote_image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="name-role">
                                        <span class="name labelMedium primary-white"><?php the_sub_field( 'name' ); ?></span>
                                        <span class="name labelMedium medium-grey"><?php the_sub_field( 'role' ); ?></span>
                                    </span>
                                </span>
                            </span>
                            <span class="link-container">
                                <a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Read full story</a>
                            </span>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
    <span class="quote-slider-timer"><span class="quote-slider-timer-inner"></span><span>
</section>

			