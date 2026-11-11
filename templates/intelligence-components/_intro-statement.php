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

// Per explicit request: the button can now open a popup form instead
// of just linking out, copying the exact pattern already used by
// templates/components/_cta-block.php -- a "Popup Form or Link?"
// select, an a.form-popup-button trigger pointing at a #formPopup
// anchor, and a hidden .popupBlockOuter container holding the embed.
// a.form-popup-button is already bound site-wide to magnificPopup
// (source/js/main.js), so no new JS is needed here, only the markup.
// The popup id is suffixed with get_row_index() (unique per flexible
// content row) rather than cta-block's hardcoded "formPopup", since
// this layout can in principle repeat on a page and a duplicate id
// would make every trigger open only the first row's popup.
$link_type = get_sub_field( 'popup_form_or_link' );
$popup_id  = 'introStatementFormPopup-' . get_row_index();
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
                        <?php if ( $link_type == 'popup' ) { ?>
                            <a href="#<?php echo esc_attr( $popup_id ); ?>" class="std-button red-button form-popup-button">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'button_target' ); ?>" class="std-button red-button">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } ?>
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
                        <?php if ( $link_type == 'popup' ) { ?>
                            <a href="#<?php echo esc_attr( $popup_id ); ?>" class="std-button red-button form-popup-button">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'button_target' ); ?>" class="std-button red-button">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } ?>
                    </span>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php if ( $link_type == 'popup' && get_sub_field( 'form_embed' ) ) { ?>
    <div style="display:none;">
        <div class="popupBlockOuter" id="<?php echo esc_attr( $popup_id ); ?>">
            <div class="requestFormContainer">
                <div class="container">
                    <?php if ( get_sub_field( 'form_title' ) ) { ?>
                        <h2 class="form-title"><?php echo get_sub_field( 'form_title' ); ?></h2>
                    <?php } ?>
                    <div class="form-container">
                        <?php echo get_sub_field( 'form_embed' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
