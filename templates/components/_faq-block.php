<section class="faq-block">
    <div class="container">
        <div class="title-column">
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="faq-column">
			<?php if ( have_rows( 'faq' ) ) : ?>
				<?php while ( have_rows( 'faq' ) ) : the_row(); ?>
                    <span class="faq-item">
                        <span class="question">
	                       <?php echo get_sub_field( 'question' ); ?>
                       </span>
                       <span class="answer">
    					   <?php echo get_sub_field( 'answer' ); ?>
                       </span>
                   </span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
