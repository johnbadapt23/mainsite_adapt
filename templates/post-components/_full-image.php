<div class="post-content-inner full-image" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<?php $image = get_sub_field( 'image' ); ?>
	<?php if ( $image ) { ?>
		<a class="image-popup" href="<?php echo $image['url']; ?>">
			<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
			<span class="enlarge-image"></span>
		</a>
	<?php } ?>
</div>
