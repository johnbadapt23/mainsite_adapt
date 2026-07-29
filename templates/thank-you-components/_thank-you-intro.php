<section class="thank-you-pink-introduction">
    <div class="container">
        <div class="thank-you-inner background-pink">
            <span class="animation-icon-container">
                <span class="animator-player thank-you-animation">
                    <?php $animation = get_sub_field( 'animation' ); ?>
        			<?php if ( $animation ) { ?>
                        <lottie-player id="thanks" autoplay loop speed="1" src="<?php echo $animation['url']; ?>" background="transparent" style="width: 100%; height: auto"></lottie-player>
        			<?php } ?>
                </span>
            </span>
            <span class="title-container">
                <h1 class="h2-style black-text"><?php echo get_sub_field( 'title' ); ?></h1>
                <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
            </span>
        </div>
    </div>
</section>
