<section class="events-logos-text-block subscribe-logo-text-block background-white">
    <div class="events-logos-text-block-outer background-white">
        <div class="container">
            <div class="text-content-container">
                <div class="text-inner">
                    <span class="icon-container">
                        <?php $icon = get_sub_field( 'icon' ); ?>
            			<?php if ( $icon ) { ?>
            				<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
            			<?php } ?>
                    </span>
                    <span class="text">
                        <h3 class="black-text"><?php echo get_sub_field( 'text' ); ?></h3>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
