<?php
// Adapted from templates/gtm-components/_three-column-with-video.php:
// same title/sub_title header and .column.one-third three-up grid, with
// the video/image block above the cards removed (not in this design)
// and each card's icon swapped for a plain "number" text field (the
// design shows 01/02/03, not icons).
?>
<section class="three-column-video-gtm pain-vs-promise <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field( 'background_colour' ); ?><?php } else { ?>background-white<?php }?>" id="<?php echo get_sub_field( 'id' ); ?>">
    <div class="container">
        <div class="title-container">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
        </div>
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-third">
                        <span class="number-container">
                            <span class="number"><?php echo get_sub_field( 'number' ); ?></span>
                        </span>
                        <span class="text-container">
                            <span class="labelXL"><?php echo get_sub_field( 'title' ); ?></span>
                            <p class="p-small text-dark-grey"><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
