<section class="videoBlock image-button-block scrollPos" style="background-image: url(<?php the_sub_field('image'); ?>);" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <?php if( get_sub_field('dark_overlay') == 'yes') { ?>
        <span class="dark-overlay"></span>
    <?php } ?>
    <div class="container">
        <div class="content">
            <?php if( get_sub_field ( 'title' ) ) { ?>
                <div class="column title">
                    <span class="title"><?php the_sub_field('title'); ?></span>
                </div>
                <hr>
            <?php } ?>
            <?php if( get_sub_field ( 'description' ) ) { ?>
                <div class="column text">
                    <span class="text"><?php the_sub_field('description'); ?></span>
                </div>
            <?php } ?>
            <?php if ( have_rows( 'button_block' ) ) : ?>
				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <span class="videoLink buttonContainer">
                        <a href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>" class="button">
                            <?php the_sub_field( 'link_text' ); ?>
                        </a>
                    </span>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
