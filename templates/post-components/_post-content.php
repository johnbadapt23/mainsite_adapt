<div class="post-content-inner article-content" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<div class="post-text text-black post-content-text">
		<?php echo get_sub_field( 'article_content' ); ?>
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

	<?php if ( get_sub_field( 'show_subscribe_module' ) == 'yes') { ?>
		<div class="sidebar-content">
			<span class="subscribe-sidebar-form background-pink">
				<span class="icon-container">
					<span class="icon-inner">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg"/>
					</span>
				</span>
				<h5 class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></h5>
				<p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>			
				<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
			</span>
		</div>
	<?php } ?>
</div>
