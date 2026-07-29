<?php $backgroundColour = get_sub_field( 'background_colour' ); ?>
<section class="four-column-stats <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="four-column-container <?php echo get_sub_field( 'semi_circle_background' ); ?>">
        <?php if (get_sub_field( 'title' )) { ?>
            <span class="absolute-title-container">
                <h3><?php echo get_sub_field( 'title' ); ?></h3>
            </span>
        <?php } ?>
            <?php if ( have_rows( 'columns' ) ) : ?>
                <?php $counter=1;?>
				<?php while ( have_rows( 'columns' ) ) : the_row(); ?>
                    <?php $fadetime = $counter * 600; ?>
                    <div class="column one-quarter" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="<?php echo $fadetime; ?>">
                        <div class="stat-container">
                            <span class="stat-inner <?php echo $backgroundColour; ?>">
                                <h2 class="text-red"><?php echo get_sub_field( 'large_text' ); ?></h2>
                                <span class="small-stat"><?php echo get_sub_field( 'small_text' ); ?></span>
                            </span>
                        </div>
                    </div>
                    <?php $counter++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
