<div class="post-content-inner counter-title-text" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<div class="counter-title-container">
		<span class="counter-circle-outer">
			<span class="counter circle-counter">
				<?php echo get_sub_field( 'counter' ); ?>
			</span>
		</span>
		<span class="title counter-title text-black labelXXLarge"><?php echo get_sub_field( 'title' ); ?></span>
	</div>
	<div class="post-text text-black">
		<?php echo get_sub_field( 'text' ); ?>
	</div>
	<?php $post_object = get_sub_field( 'related_article' ); ?>
	<?php if ( $post_object ): ?>
		<div class="sidebar-content">
			<?php $post = $post_object; ?>
			<?php setup_postdata( $post ); ?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>
</div>
