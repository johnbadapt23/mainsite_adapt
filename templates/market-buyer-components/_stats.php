<section class="stats background-white radius-top full-height-image">
    <span class="top-background background-black"></span>
    <div class="container">
        <div class="column-container">
            <div class="column one-half quote-column">
                <span class="text-container">
                    <span class="icon-container">
                        <?php $icon = get_sub_field( 'icon' ); ?>
                        <?php if ( $icon ) { ?>
                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                                'alt'     => $icon['alt'],
                                'loading' => false,
                            ) ); ?>
                        <?php } ?>
                    </span>
                    <span class="headerLarge bold-red text-black"><?php echo get_sub_field( 'heading' ); ?></span>
                    <?php $graphic = get_sub_field( 'image' ); ?>
                    <?php if ( $graphic ) { ?>
                        <?php echo wp_get_attachment_image( $graphic['ID'], 'full', false, array(
                            'class'   => 'mobile-image',
                            'alt'     => $graphic['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                    <span class="labelXL text-black"><?php echo get_sub_field( 'text' ); ?></span>
                    <span class="labelXsmall medium-grey"><?php echo get_sub_field( 'source_text' ); ?></span>
                </span>
            </div>
            <div class="column one-half image-column">                
                <?php $graphic = get_sub_field( 'image' ); ?>
                <?php if ( $graphic ) { ?>
                    <?php echo wp_get_attachment_image( $graphic['ID'], 'full', false, array(
                        'class'   => 'desktop-image',
                        'alt'     => $graphic['alt'],
                        'loading' => 'lazy',
                    ) ); ?>
                <?php } ?>
            </div>
        </div>
    <div>
</section>