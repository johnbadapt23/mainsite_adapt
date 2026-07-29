<section class="call-to-action-block <?php echo get_sub_field('background_colour'); ?> <?php echo get_sub_field('add_horizontal_line_top'); ?> <?php echo get_sub_field('add_horizontal_line_bottom'); ?>">
    <div class="container">
        <div class="content">
            <span class="h2-style"><?php echo get_sub_field('text_block'); ?></span>
            <?php if ( get_sub_field ( 'button_link' ) ) { ?>
                <div class="button-wrapper">
                    <a href="<?php echo get_sub_field('button_link'); ?>" class="std-button red-button button-with-arrow" target="<?php echo get_sub_field('button_target'); ?>"><?php echo get_sub_field('button_text'); ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
