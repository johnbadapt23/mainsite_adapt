<div class="post-content-inner tick-list" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<div class="tick-list-inner">
		<?php if ( get_sub_field( 'title' )) { ?>
			<span class="list-title labelXXLarge text-black"><?php echo get_sub_field( 'title' ); ?></span>
		<?php } ?>
		<span class="list-container text-black">
			<?php if ( have_rows( 'list_items' ) ) : ?>
				<?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
					<span class="list-item text-black labelXLarge"><?php echo get_sub_field( 'list_item' ); ?></span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</span>
	</div>
</div>
