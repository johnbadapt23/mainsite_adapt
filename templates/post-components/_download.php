<div class="post-content-inner download" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<?php $image = get_sub_field( 'image' ); ?>
	<?php if ( $image ) { ?>
		<span class="download-image-container">
			<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
				'alt'     => $image['alt'],
				'loading' => false,
			) ); ?>
		</span>
	<?php } ?>
	<span class="download-title labelXXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
	<span class="button-container">
		<a class="std-button download-button" href="#<?php echo get_sub_field('unique_id'); ?>">
			<?php echo get_sub_field( 'button_text' ); ?>
		</a>
	</span>
	<span class="download-form-container" style="display: none;">
		<span class="download-form-popup" id="<?php echo get_sub_field('unique_id'); ?>">
			<?php echo get_sub_field( 'form_embed' ); ?>
		</span>
	</span>
</div>
