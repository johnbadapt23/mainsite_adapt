<?php
// Adapted from templates/components/_switch-module.php: same pattern
// (title + a row of tab buttons, one .switch-content-container panel
// per row, only one visible at a time) but with its own class names
// (intelligence-tab-button / intelligence-tab-panel) rather than
// reusing .module-switch-button / .switch-content-container directly.
//
// Not reused as-is because _switch-module.php's own click handler is
// currently broken: source/js/main.js listens for
// '.module-switcher a.module-switch-button' (an anchor) but the
// template renders a <button>, so the handler never attaches and
// clicking those tabs does nothing anywhere they're already used
// today. Rather than silently inherit that bug here, or change the
// shared selector/markup and risk an unrelated behaviour change on
// every existing page using that component, this gets its own
// correctly-wired listener (see the tab-switch script enqueued
// alongside this template) scoped to these new class names only.
//
// Each tab's panel also has a different content shape than
// _switch-module.php's generic two-column icon+text grid: a heading,
// a repeater of prompt-example boxes, and a single screenshot image.
//
// Title is wysiwyg with the same "Bold for red text" convention as
// pain_vs_promise's title field (see that layout's instructions) --
// Figma uses the same black-heading / bolded-red-phrase treatment
// here, so this reuses the existing pattern rather than inventing a
// second way to colour part of a heading.
?>
<section class="intelligence-use-case background-light-grey">
    <div class="container">
        <div class="top-block" data-aos="fade-up" data-aos-duration="600">
            <span class="title-switcher-container">
                <h2 class="title bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if ( get_sub_field( 'sub_title' ) ) { ?>
                    <span class="sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
                <?php } ?>
                <?php if ( have_rows( 'module' ) ) : ?>
                    <span class="intelligence-tab-bar">
                        <?php $buttonCounter = 1; ?>
                        <?php while ( have_rows( 'module' ) ) : the_row(); ?>
                            <button type="button" class="intelligence-tab-button<?php if ( $buttonCounter == 1 ) { ?> active<?php } ?>"><?php echo get_sub_field( 'title' ); ?></button>
                            <?php $buttonCounter++; ?>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </span>
        </div>

        <?php if ( have_rows( 'module' ) ) : ?>
            <?php $counter = 1; ?>
            <?php while ( have_rows( 'module' ) ) : the_row(); ?>
                <div class="intelligence-tab-panel<?php if ( $counter == 1 ) { ?> active<?php } ?>">
                    <div class="column one-half text-column">
                        <?php if ( get_sub_field( 'title' ) ) { ?>
                            <span class="panel-title"><?php echo get_sub_field( 'title' ); ?></span>
                        <?php } ?>
                        <?php if ( get_sub_field( 'panel_heading' ) ) { ?>
                            <span class="panel-heading"><?php echo get_sub_field( 'panel_heading' ); ?></span>
                        <?php } ?>
                        <?php if ( have_rows( 'prompt_examples' ) ) : ?>
                            <div class="prompt-examples">
                                <?php while ( have_rows( 'prompt_examples' ) ) : the_row(); ?>
                                    <span class="prompt-example"><?php echo get_sub_field( 'prompt_text' ); ?></span>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </div>
                    <div class="column one-half image-column">
                        <?php $screenshot = get_sub_field( 'screenshot' ); ?>
                        <?php if ( $screenshot ) { ?>
                            <span class="device-frame">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php echo wp_get_attachment_image( $screenshot['ID'], 'adapt-optimized', false, array(
                                            'alt'     => $screenshot['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    </span>
                                </span>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <?php $counter++; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>

        <?php if ( get_sub_field( 'footer_note' ) ) { ?>
            <span class="footer-note"><?php echo get_sub_field( 'footer_note' ); ?></span>
        <?php } ?>
    </div>
</section>
