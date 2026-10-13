<?php
// Adapted from templates/gtm-components/_three-column-with-video.php:
// same title/sub_title header and .column.one-third three-up grid, with
// the video/image block above the cards removed (not in this design)
// and each card's icon swapped for a plain "number" text field (the
// design shows 01/02/03, not icons).
?>
<section class="three-column-video-gtm pain-vs-promise <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field( 'background_colour' ); ?><?php } else { ?>background-white<?php }?>" id="<?php echo get_sub_field( 'id' ); ?>">
    <div class="container">
        <div class="title-container" data-aos="fade-up" data-aos-duration="600">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
        </div>
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
                <?php $painVsPromiseCounter = 0; ?>
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-third" data-aos="fade-up" data-aos-duration="600" data-aos-delay="<?php echo $painVsPromiseCounter * 150; ?>">
                        <span class="number-container">
                            <span class="number"><?php echo get_sub_field( 'number' ); ?></span>
                        </span>
                        <span class="text-container">
                            <span class="labelXL"><?php echo get_sub_field( 'title' ); ?></span>
                            <p class="p-small text-dark-grey"><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
                    </div>
                    <?php $painVsPromiseCounter++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
