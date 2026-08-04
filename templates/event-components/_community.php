<section class="community-block background-white">
    <div class="container">
        <div class="top-container column-container">
            <div class="column column-one one-half title-column">
                <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
            <div class="column column-one one-half text-column">
                <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
            </div>
        </div>
        <div class="bottom-container column-container">
            <div class="one-third column image-column first-column">
                <span class="image-one">
                    <span class="absolute-title"><?php echo get_sub_field( 'column_one_image_one_title' ); ?></span>
                    <?php $column_one_image_one = get_sub_field( 'column_one_image_one' ); ?>
                    <?php if ( $column_one_image_one ) { ?>
                    	<?php echo wp_get_attachment_image( $column_one_image_one['ID'], 'full', false, array(
                    		'alt'     => $column_one_image_one['alt'],
                    		'loading' => false,
                    	) ); ?>
                    <?php } ?>
                </span>
                <span class="image-two">
                    <span class="absolute-title"><?php echo get_sub_field( 'column_one_image_two_title' ); ?></span>
                    <?php $column_one_image_two = get_sub_field( 'column_one_image_two' ); ?>
                    <?php if ( $column_one_image_two ) { ?>
                    	<?php echo wp_get_attachment_image( $column_one_image_two['ID'], 'full', false, array(
                    		'alt'     => $column_one_image_two['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
            </div>
            <div class="one-third column image-column large-column">
                <span class="image-large">
                    <span class="absolute-title"><?php echo get_sub_field( 'column_two_image_title' ); ?></span>
                    <?php $column_two_image = get_sub_field( 'column_two_image' ); ?>
                    <?php if ( $column_two_image ) { ?>
                    	<?php echo wp_get_attachment_image( $column_two_image['ID'], 'full', false, array(
                    		'alt'     => $column_two_image['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
            </div>
            <div class="one-third column image-column large-column">
                <span class="image-large">
                    <span class="absolute-title"><?php echo get_sub_field( 'column_three_image_title' ); ?></span>
                    <?php $column_three_image = get_sub_field( 'column_three_image' ); ?>
                    <?php if ( $column_three_image ) { ?>
                    	<?php echo wp_get_attachment_image( $column_three_image['ID'], 'full', false, array(
                    		'alt'     => $column_three_image['alt'],
                    		'loading' => 'lazy',
                    	) ); ?>
                    <?php } ?>
                </span>
            </div>
        </div>
    </div>
</section>
