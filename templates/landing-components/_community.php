<section class="community-block background-black landing-community">
    <div class="container">
        <div class="top-container">
            <div class="title-column">
                <h2 class="text-white"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="text text-white"><?php echo get_sub_field( 'text' ); ?></span>
            </div>
        </div>
        <div class="bottom-container column-container">
            <div class="one-third column image-column large-column first-column" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                <span class="image-large">
                    <?php $column_one_image_one = get_sub_field( 'column_one_image' ); ?>
                    <?php if ( $column_one_image_one ) { ?>
                    	<?php echo wp_get_attachment_image( $column_one_image_one['ID'], 'adapt-optimized', false, array(
                    		'alt'     => $column_one_image_one['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>               
            </div>
            <div class="one-third column image-column two-image-column" >
                <span class="image-one" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800" data-aos-delay="400">
                    <?php $column_two_image = get_sub_field( 'column_two_image_one' ); ?>
                    <?php if ( $column_two_image ) { ?>
                    	<?php echo wp_get_attachment_image( $column_two_image['ID'], 'adapt-optimized', false, array(
                    		'alt'     => $column_two_image['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
                 <span class="image-two" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800">
                    <?php $column_one_image_two = get_sub_field( 'column_two_image_two' ); ?>
                    <?php if ( $column_one_image_two ) { ?>
                    	<?php echo wp_get_attachment_image( $column_one_image_two['ID'], 'adapt-optimized', false, array(
                    		'alt'     => $column_one_image_two['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
            </div>
            <div class="one-third column image-column large-column" data-aos="fade-up" data-aos-anchor-placement="center-bottom" data-aos-duration="800" data-aos-delay="800">
                <span class="image-large">
                    <?php $column_three_image = get_sub_field( 'column_three_image' ); ?>
                    <?php if ( $column_three_image ) { ?>
                    	<?php echo wp_get_attachment_image( $column_three_image['ID'], 'adapt-optimized', false, array(
                    		'alt'     => $column_three_image['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
            </div>
        </div>
    </div>
</section>
