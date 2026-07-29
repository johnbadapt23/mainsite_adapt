<section class="text-title-list">
    <div class="container">
        <div class="text-title-list-container background-pink">
            <div class="text-title-list-inner">
                <div class="text-title-column column">
                    <span class="v-wrap">
                        <span class="v-box left-align">
                            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
                            <p class="text-black"><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
                    </span>
                </div>
                <div class="list-column column">
                    <?php if ( have_rows( 'list_items' ) ) : ?>
                        <?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
                            <span class="list-item text-black labelXLarge"><?php echo get_sub_field( 'list_text' ); ?></span>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
