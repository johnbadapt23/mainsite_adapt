<?php if(get_sub_field( 'background_colour' ) == 'background-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<section class="services-two-column-image-text <?php echo get_sub_field( 'background_colour' ); ?>" <?php if(get_sub_field('id')){ ?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="column-container">
            <div class="column one-half image-column">
                <?php $image = get_sub_field( 'image' ); ?>
    			<?php if ( $image ) { ?>
    				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
    					'alt'     => $image['alt'],
    					'loading' => 'lazy',
    				) ); ?>
    			<?php } ?>
            </div>
            <div class="column one-half text-column">
                <h2 class="column-title <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
            </div>
        </div>
    </div>
</section>
