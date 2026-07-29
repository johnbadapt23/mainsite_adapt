<section class="text-title-list two-column-list background-pink" <?php if ( get_sub_field( 'id' )){ ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="text-title-list-container">
            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            <div class="text-title-list-inner">
                <div class="list-column column column-one one-half">
                    <?php if ( have_rows( 'list_items' ) ) : ?>
                        <?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
                            <span class="list-item text-black labelXLarge">
                                <span class="title"><?php echo get_sub_field( 'list_text_title' ); ?></span>
                                <span class="text"><?php echo get_sub_field( 'list_text' ); ?></span>
                            </span>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
                <div class="list-column column column-two one-half">
                    <?php if ( have_rows( 'list_items_column_two' ) ) : ?>
                        <?php while ( have_rows( 'list_items_column_two' ) ) : the_row(); ?>
                            <span class="list-item text-black labelXLarge">
                                <span class="title"><?php echo get_sub_field( 'list_text_title' ); ?></span>
                                <span class="text"><?php echo get_sub_field( 'list_text' ); ?></span>
                            </span>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
