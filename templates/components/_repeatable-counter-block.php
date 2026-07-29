<section class="logoGrid counter <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="titleBlock">
            <span class="title">
                <h2><?php the_sub_field( 'block_title' ); ?></h2>
            </span>

            <span class="description <?php the_sub_field( 'top_right_text_position' ); ?>">
                <h3><?php the_sub_field( 'top_right_text' ); ?></h3>
            </span>
        </div>

        <?php if ( have_rows( 'number_block' ) ) : ?>
            <div class="numberBlock">
                <?php while ( have_rows( 'number_block' ) ) : the_row(); ?>
                    <?php if ( have_rows( 'numbers' ) ) : ?>
                        <div class="logoBlock">
                            <?php while ( have_rows( 'numbers' ) ) : the_row(); ?>
                                <div class="logo">
                                    <span class="number"><?php the_sub_field( 'number' ); ?></span>

                                    <span class="logoTitle">
                                        <?php the_sub_field( 'title' ); ?>
                                    </span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
            <a class="logoBlockLink <?php the_sub_field( 'link_style' ); ?>" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
        <?php } ?>
    </div>
</section>
