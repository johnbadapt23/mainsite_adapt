<?php
// Adapted from templates/gtm-components/_three-column-with-video.php:
// same title/sub_title header and .column.one-third three-up grid, with
// the video/image block above the cards removed (not in this design)
// and each card's icon swapped for a plain "number" text field (the
// design shows 01/02/03, not icons).

if ( ! function_exists( 'adapt_pain_vs_promise_prompt_list' ) ) :
function adapt_pain_vs_promise_prompt_list( $text ) {
    // Per explicit request, the "Text" field content for this layout's
    // current use (the prompt examples moved over from the old Sticky
    // Text Image Columns section) renders as its own rounded pill per
    // line, same #FDF1F1/8px/16-20px treatment as .prompt-example on
    // section.intelligence-use-case and .text-point on the old sticky
    // cards section, rather than one plain paragraph.
    // Same "br" quirk as adapt_sticky_card_prompt_list() in
    // templates/benchmark-components/_sticky-cards.php: this field's
    // "New Lines" setting (new_lines: br) already inserts a literal
    // "<br />" before each real line break without removing the break
    // itself, so strip the tag back out and split on the line break
    // that's still there -- otherwise esc_html() below would print
    // "<br />" as visible text instead of a line break.
    $text  = preg_replace( '/<br\s*\/?>/i', '', $text );
    $lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', trim( $text ) ) ) );
    if ( empty( $lines ) ) {
        return;
    }
    echo '<div class="prompt-points">';
    foreach ( $lines as $line ) {
        echo '<span class="prompt-point">' . esc_html( $line ) . '</span>';
    }
    echo '</div>';
}
endif;
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
                            <?php adapt_pain_vs_promise_prompt_list( get_sub_field( 'text' ) ); ?>
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
