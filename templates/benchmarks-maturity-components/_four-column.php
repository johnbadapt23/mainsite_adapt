<section class="benchmarking-four-column background-white">
    <div class="container">
        <div class="outer-container">
            <span class="decorative-border outer light"></span>
            <span class="decorative-border inner light"></span>
            <span class="icon-title">
                <?php $title_icon = get_sub_field( 'title_icon' ); ?>
                <span class="icon-container">
                    <?php if ( $title_icon ) { ?>
                        <img src="<?php echo $title_icon['url']; ?>" alt="<?php echo $title_icon['alt']; ?>" />
                    <?php } ?>
                </span>
                <span class="title-text font-ibm text-red"><?php echo get_sub_field( 'title' ); ?></span>
            </span>
            <div class="column-container">
                <?php if ( have_rows( 'column' ) ) : ?>
                    <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                        <div class="column one-quarter">
                            <span class="headerXL text-black"><?php echo get_sub_field( 'title' ); ?></span>
                            <span class="labelMedium secondary-dark"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>