<section class="two-column-cta <?php echo get_sub_field( 'background_colour' ); ?>" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
    <div class="container">
        <div class="cta-column-container">
            <div class="column one-half image-column <?php echo get_sub_field( 'image_column' ); ?>">
                <span class="offset-image-one"></span>
                <span class="offset-image-two"></span>
                <span class="offset-image-three"></span>
                <?php $image = get_sub_field( 'image' ); ?>
    			<?php if ( $image ) { ?>
    				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
    					'alt'     => $image['alt'],
    					'loading' => false,
    				) ); ?>
    			<?php } ?>
            </div>
            <div class="column one-half text-column <?php echo get_sub_field( 'image_column' ); ?>">
                <h2 class="title black-text"><?php echo get_sub_field( 'title' ); ?></h2>
    			<span class="text-container black-text"><?php echo get_sub_field( 'text' ); ?></span>
                <span class="button-container">
    			<?php if ( have_rows( 'button' ) ) : ?>
    				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
    					<a class="std-button red-button button-with-arrow white-arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </div>
        </div>
    </div>
</section>
