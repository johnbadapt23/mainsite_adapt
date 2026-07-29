<section class="red-cta">
    <span class="absolute-top <?php echo get_sub_field( 'background_above' ); ?>"></span>
    <span class="absolute-bottom <?php echo get_sub_field( 'background_below' ); ?>"></span>
    <div class="cta-container-outer background-red">
        <div class="container">
            <div class="cta-container-inner">
                <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="link-container">
                    <a class="text-link large-link-text white-text white-underline-link white-arrow" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                </span>
            </div>
        </div>
    </div>
</section>
