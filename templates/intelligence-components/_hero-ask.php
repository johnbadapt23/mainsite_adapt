<?php
// Figma-style hero: browser-window product screenshot sits inside a
// coloured section (background_colour sub field, default background-red,
// same configurable pattern as closing_cta_form) and overlaps down into
// the white of the section below it -- replicated from the existing
// "Introduction Block" overlap technique already used on the Careers
// page (templates/components/_introduction-block.php /
// section.introduction-block.background-black in
// source/scss/templates/_flexible.scss): the section keeps its colour
// across its top portion only, a white rectangle covers its own bottom
// portion, and the image straddles that boundary so it visually bleeds
// into the next (white) section. The heading/subtext/"Book a Demo"
// button above belongs to the separate intro_statement layout (see
// templates/intelligence-components/_intro-statement.php), so this
// section stays image-only.
?>
<section class="intelligence-hero <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field( 'background_colour' ); ?><?php } else { ?>background-red<?php }?>">
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
