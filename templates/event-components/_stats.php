<section class="stats background-black">
    <div class="container">
        <div class="stats-inner">
            <?php if ( have_rows( 'stat' ) ) : ?>
                <div class="stats-container">
    				<?php while ( have_rows( 'stat' ) ) : the_row(); ?>
                        <span class="stat" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
        					<h2 class="red-text"><?php echo get_sub_field( 'large_text' ); ?></h2>
        					<span class="text white-text"><?php echo get_sub_field( 'text' ); ?></span>
                        </span>
    				<?php endwhile; ?>
                </div>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
