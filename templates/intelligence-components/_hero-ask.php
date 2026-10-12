<?php
// Figma builds this section as a single flat product-screenshot image
// with a red backdrop baked into the PNG itself (not a section
// background colour) -- the heading/subtext/"Book a Demo" button seen
// above it in Figma belong to the separate intro_statement layout
// (see templates/intelligence-components/_intro-statement.php), so this
// section stays image-only.
?>
<section class="intelligence-hero">
    <div class="container">
        <?php $image = get_sub_field( 'hero_image' ); ?>
        <?php if ( $image ) { ?>
            <span class="hero-image">
                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                    'alt'     => $image['alt'],
                    'loading' => 'eager',
                ) ); ?>
            </span>
        <?php } ?>
    </div>
</section>
