<section class="large-testimonial-slider">
    <div class="container">
        <div class="background-container">
            <?php $background_image = get_sub_field( 'background_image' ); ?>
            <?php if ( $background_image ) { ?>
                <?php echo wp_get_attachment_image( $background_image['ID'], 'adapt-optimized', false, array(
                    'alt'     => $background_image['alt'],
                    'loading' => 'lazy',
                ) ); ?>
            <?php } ?>
        </div>  
        <?php
        // Whether the tag field should render beside the name/role block
        // (ADAPT Intelligence only) instead of above the quote, per the
        // updated design for that template. Mirrors the same
        // _wp_page_template postmeta check custom_body_classs() in
        // includes/_customisations.php uses, so this stays scoped to this
        // one template and never changes the testimonial slider on
        // template-gtm.php or any other template reusing this partial.
        global $post;
        $is_adapt_intelligence_template = $post && get_post_meta( $post->ID, '_wp_page_template', true ) === 'templates/template-adapt-intelligence.php';
        ?>
        <div class="large-quote-slide-container" data-aos="fade-up" data-aos-duration="600">
            <?php if ( have_rows( 'slides' ) ) : ?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
                    <div class="slide">
                        <div class="slide-inner">
                            <span class="logo-container">
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                        'alt'     => $logo['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                <?php } ?>
                            </span>
                            <span class="quote-container">
                                <?php if ( get_sub_field( 'tag' ) && ! $is_adapt_intelligence_template ) { ?>
                                    <span class="tag-container medium-grey"><?php the_sub_field( 'tag' ); ?></span>
                                <?php } ?>
                                <span class="quote header-large"><?php the_sub_field( 'quote' ); ?></span>
                                <span class="name-image-container">
                                    <?php $quote_image = get_sub_field( 'quote_image' ); ?>
                                    <?php if ( $quote_image ) { ?>
                                        <span class="image-column">
                                            <?php echo wp_get_attachment_image( $quote_image['ID'], 'adapt-optimized', false, array(
                                                'alt'     => $quote_image['alt'],
                                                'loading' => 'lazy',
                                            ) ); ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ( get_sub_field( 'tag' ) && $is_adapt_intelligence_template ) { ?>
                                        <span class="tag-container medium-grey"><?php the_sub_field( 'tag' ); ?></span>
                                    <?php } ?>
                                    <span class="name-role">
                                        <span class="name labelMedium primary-white"><?php the_sub_field( 'name' ); ?></span>
                                        <span class="name labelMedium medium-grey"><?php the_sub_field( 'role' ); ?></span>
                                    </span>
                                </span>
                            </span>
                            <?php if ( get_sub_field( 'link' ) ) { ?>
                                <span class="link-container">
                                    <a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Read full story</a>
                                </span>
                            <?php } ?>
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

			