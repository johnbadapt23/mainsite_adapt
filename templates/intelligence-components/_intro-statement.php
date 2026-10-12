<?php
// Centred heading + subtext + single CTA button on white, sitting
// above the Hero image per Figma ("Know what A/NZ buyers care
// about..."). Adapted from the centred text column in
// templates/components/_introduction-block.php with the image/video
// column removed -- this design is text-only, no media.
?>
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
