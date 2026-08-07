<section class="three-column-icon-text-ecosystems <?php if(get_sub_field('background_colour')){ ?><?php echo get_sub_field('background_colour'); ?><?php } else { ?>background-true-black<?php }?> <?php echo get_sub_field( 'padding_top' ); ?> <?php echo get_sub_field( 'padding_bottom' ); ?>">
    <div class="container">
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-third">
                        <span class="icon-container">
                            <?php $icon = get_sub_field( 'icon' ); ?>
                            <?php if ( $icon ) { ?>
                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                                    'alt'     => $icon['alt'],
                                    'loading' => 'lazy',
                                ) ); ?>
                            <?php } ?>
                        </span>
                        <span class="text-container">
                            <h5 class="light-grey-text"><?php echo get_sub_field( 'text' ); ?></h5>
                        </span>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>

