<?php
// Reuses the existing "Join the ADAPT Insider Community" subscribe box
// as-is -- same markup/classes (.subscribe-sidebar-form.background-pink)
// and same sitewide title/text/form_button fields pulled from the ACF
// Options page, as already rendered inside the sidebar of the "Article
// Content" layout's own show_subscribe_module toggle (see
// templates/post-components/_post-content.php). Registered as its own
// selectable layout instead, so an editor can drop this CTA box in
// anywhere among the other Content Blocks rows (not only tied to
// whichever row has that toggle switched on).
?>
<div class="post-content-inner cta-box" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<span class="subscribe-sidebar-form background-pink">
		<span class="icon-container">
			<span class="icon-inner">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
			</span>
		</span>
		<span class="labelXXLarge text-black"><?php echo get_field( 'title', 'options' ); ?></span>
		<p class="text-black"><?php echo get_field( 'text', 'options' ); ?></p>
		<span class="form-popup-button-container with-white-arrow with-arrow"><?php echo get_field( 'form_button', 'options' ); ?></span>
	</span>
</div>
