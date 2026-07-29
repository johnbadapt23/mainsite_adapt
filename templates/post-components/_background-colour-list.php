<div class="post-content-inner background-coloured-list background-pink" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<div class="coloured-list-inner">
		<?php if ( get_sub_field( 'title' )) { ?>
			<span class="list-title labelXXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
		<?php } ?>
		<span class="list-container text-black">
			<?php echo get_sub_field( 'list' ); ?>
		</span>
	</div>
</div>
