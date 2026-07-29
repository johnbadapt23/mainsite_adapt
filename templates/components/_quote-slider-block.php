<section class="quote-slider-block">
    <div class="container">
        <?php if ( have_rows( 'quote' ) ) : ?>
            <div class="quote-slider">
    			<?php while ( have_rows( 'quote' ) ) : the_row(); ?>
                    <span class="quote-slide">
                        <span class="quote-slide-container">
                            <span class="inner">
                                <span class="open-quote">&#8220;</span>
                				<span class="quote"><?php echo get_sub_field( 'quote' ); ?></span>
                				<span class="quoter"><?php echo get_sub_field( 'quoter' ); ?></span>
                            </span>
                        </span>
                    </span>
    			<?php endwhile; ?>
            </div>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
    </div>
</section>
