<?php
// Figma ("01 Hero") builds this whole section from a single static
// image on a red background -- a flat screenshot of the live
// research.adapt.com.au tool (search bar, suggested prompts, Most
// Popular list all baked into the one PNG), not separate editable
// layers. An earlier version of this template built those as real,
// functional markup (a working <form> search bar, clickable prompt
// buttons, a live Most Popular WP_Query) -- none of that exists in
// the design, so this has been replaced with a single image upload
// field. Section background is red to match Figma; see
// section.intelligence-hero in source/scss/templates/_intelligence.scss.
?>
<section class="intelligence-hero background-red">
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
