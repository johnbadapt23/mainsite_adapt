<section class="market-testimonial">
    <div class="container">          
        <span class="testimonial-container">
            <span class="testimonial-inner">
                <h4 class="testimonial text-black"><?php echo get_sub_field( 'quote' ); ?></h4>
                <div class="thumbnail-container">
                    <?php $logo = get_sub_field( 'logo' ); ?>
                    <?php if ( $logo ) { ?>
                        <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                    <?php } ?>
                </div>
            </span>
        </span>                    	
    </div>
</section>
