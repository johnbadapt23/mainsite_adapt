<section class="animated-text <?php echo get_sub_field( 'background_colour' ); ?>" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
    <div class="container">
        <div class="inner">
            <div class="animated-text-container">
                <span id="animatedText"><?php echo get_sub_field( 'text' ); ?></span>
            </div>
        </div>
    </div>
</section>
