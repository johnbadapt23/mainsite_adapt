<?php
// Centred heading + subtext + single CTA button on white, sitting
// above the Hero image per Figma ("Know what A/NZ buyers care
// about..."). Adapted from the centred text column in
// templates/components/_introduction-block.php with the image/video
// column removed -- this design is text-only, no media.
//
// "Add Image" toggle (show_image) switches this layout to a second
// rendering: a full-bleed 50/50 split, dark text panel on the left
// (same heading/sub-text/button, left-aligned instead of centred)
// with the uploaded image filling the right half edge-to-edge, full
// height. No existing component in the theme already does this --
// checked every templates/*/_two-column-image-text.php variant (all
// sit inside .container with a gutter, image never full-bleed) and
// _full-image-text.php (full-bleed image, but the text is an
// overlaid box on top of it, not a separate side-by-side panel) --
// so this is built directly into this layout as an alternate
// rendering path rather than forcing an existing component that
// doesn't actually match.
$show_image = get_sub_field( 'show_image' );
$image = $show_image ? get_sub_field( 'image' ) : null;
?>
<?php if ( $show_image && $image ) { ?>
    <section class="intro-statement intro-statement-split">
        <div class="split-text-column">
            <div class="text-container">
                <?php if ( get_sub_field( 'heading' ) ) { ?>
                    <h1><?php echo get_sub_field( 'heading' ); ?></h1>
                <?php } ?>
                <?php if ( get_sub_field( 'sub_text' ) ) { ?>
                    <span class="sub-text"><?php echo get_sub_field( 'sub_text' ); ?></span>
                <?php } ?>
                <?php if ( get_sub_field( 'button_text' ) ) { ?>
                    <span class="button-container">
                        <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'button_target' ); ?>" class="std-button red-button">
                            <?php echo get_sub_field( 'button_text' ); ?>
                        </a>
                    </span>
                <?php } ?>
            </div>
        </div>
        <div class="split-image-column">
            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                'alt'     => $image['alt'],
                'loading' => 'lazy',
            ) ); ?>
        </div>
    </section>
<?php } else { ?>
    <section class="intro-statement background-white">
        <div class="container">
            <div class="text-container">
                <?php if ( get_sub_field( 'heading' ) ) { ?>
                    <h1><?php echo get_sub_field( 'heading' ); ?></h1>
                <?php } ?>
                <?php if ( get_sub_field( 'sub_text' ) ) { ?>
                    <span class="sub-text"><?php echo get_sub_field( 'sub_text' ); ?></span>
                <?php } ?>
                <?php if ( get_sub_field( 'button_text' ) ) { ?>
                    <span class="button-container">
                        <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'button_target' ); ?>" class="std-button red-button">
                            <?php echo get_sub_field( 'button_text' ); ?>
                        </a>
                    </span>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
