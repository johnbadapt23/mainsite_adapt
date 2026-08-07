<section class="two-column-two-image-block">
    <div class="block-container">
        <div class="container">
            <div class="column one-half text-column">
                <span class="text-container">
                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                    <?php $portrait_image = get_sub_field( 'portrait_image' ); ?>
        			<?php if ( $portrait_image ) { ?>
                        <span class="portrait-image-container mobile">
                            <span class="image-container">
                                <span class="bg-container">
    		                        <?php echo wp_get_attachment_image( $portrait_image['ID'], 'full', false, array(
    		                            'alt'     => $portrait_image['alt'],
    		                            'loading' => 'lazy',
    		                        ) ); ?>
                                </span>
                            </span>
                        </span>
        			<?php } ?>
                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                    <?php if( get_sub_field( 'register_button_text' )) { ?>
                        <a class="std-button red-outline register-button" href="#register"><?php echo get_sub_field( 'register_button_text' ); ?></a>
                    <?php } ?>
                </span>
            </div>
            <div class="column one-half image-column">
                <?php $portrait_image = get_sub_field( 'portrait_image' ); ?>
    			<?php if ( $portrait_image ) { ?>
                    <span class="portrait-image-container desktop">
                        <span class="image-container">
                            <span class="bg-container">
		                        <?php echo wp_get_attachment_image( $portrait_image['ID'], 'full', false, array(
		                            'alt'     => $portrait_image['alt'],
		                            'loading' => 'lazy',
		                        ) ); ?>
                            </span>
                        </span>
                    </span>
    			<?php } ?>
    			<?php $square_image = get_sub_field( 'square_image' ); ?>
    			<?php if ( $square_image ) { ?>
                    <span class="square-image-container">
                        <span class="image-container">
                            <span class="bg-container">
		                        <?php echo wp_get_attachment_image( $square_image['ID'], 'full', false, array(
		                            'alt'     => $square_image['alt'],
		                            'loading' => 'lazy',
		                        ) ); ?>
                            </span>
                        </span>
                    </span>
    			<?php } ?>
            </div>
        </div>
    </div>
</section>
