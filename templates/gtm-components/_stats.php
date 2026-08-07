<section<?php if ( get_sub_field ('id')) { ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="stats background-white radius-top">
    <span class="top-background background-black"></span>
    <div class="container">
        <div class="column-container">
            <div class="column one-half quote-column">
                <span class="icon-container">
                    <?php $icon = get_sub_field( 'icon' ); ?>
                    <?php if ( $icon ) { ?>
                        <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
                            'alt'     => $icon['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                </span>
                <span class="headerLarge bold-black secondary-dark"><?php echo get_sub_field( 'quote' ); ?></span>
                <?php $graphic = get_sub_field( 'graphic' ); ?>
                <?php if ( $graphic ) { ?>
                    <?php echo wp_get_attachment_image( $graphic['ID'], 'full', false, array(
                        'class'   => 'mobile-image',
                        'alt'     => $graphic['alt'],
                        'loading' => 'lazy',
                    ) ); ?>
                <?php } ?>
                <span class="name-role">
                    <span class="labelXL text-black"><?php echo get_sub_field( 'name' ); ?></span>
                    <span class="labelXL secondary-dark"><?php echo get_sub_field( 'role' ); ?></span>
                </span>
            </div>
            <div class="column one-half image-column">                
                <?php $animation_json = get_sub_field( 'animation_json' ); ?>
                <?php $animation_id = get_sub_field( 'value_id' ); ?>
                <?php if ( $animation_json ) { ?>
                    <span class="animation-container">
                        <span class="animator-player">
                            <lottie-player speed="1" id="<?php echo $animation_id; ?>" src="<?php echo $animation_json['url']; ?>" background="transparent" style="width: 100%; height: auto"></lottie-player>
                        </span>
                    </span>
                    <script>
                        LottieInteractivity.create({
                            player:'#<?php echo $animation_id; ?>',
                            mode:"scroll",
                            actions: [
                                {
                                visibility: [0.25, 1.0],
                                type: "playOnce"
                                }
                            ]
                        });
                    </script>
                <?php } else { ?>
                    <?php $graphic = get_sub_field( 'graphic' ); ?>
                    <?php if ( $graphic ) { ?>
                        <?php echo wp_get_attachment_image( $graphic['ID'], 'full', false, array(
                            'class'   => 'desktop-image',
                            'alt'     => $graphic['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <div>
</section>





