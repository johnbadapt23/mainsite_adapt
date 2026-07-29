<section class="fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php the_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php the_sub_field( 'font_colour' ); ?><?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php the_sub_field( 'text_editor' ); ?>
        <?php if ( have_rows( 'button_block' ) ) : ?>
            <div class="buttonBlock">
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
