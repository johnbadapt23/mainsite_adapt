<?php
// Same visual component as the sidebar's "Join the ADAPT Insider
// Community" box (.subscribe-sidebar-form.background-pink, see
// templates/post-components/_post-content.php's show_subscribe_module
// toggle) but with its own editable Heading/Text/Button Text/Button
// Link/Button Target fields per request, instead of always pulling the
// sitewide title/text/form_button fields from the ACF Options page.
// Each field falls back to that same sitewide copy when left blank, so
// a row added without touching any field still renders the original
// box, and the button becomes a plain href link (std-button-style
// markup, not the Options page's raw form_button HTML/popup trigger)
// so Button Text/Link/Target are genuinely editable per row.
$heading      = get_sub_field( 'heading' );
$text         = get_sub_field( 'text' );
$button_text  = get_sub_field( 'button_text' );
$button_link  = get_sub_field( 'button_link' );
$button_target = get_sub_field( 'button_target' );

if ( ! $heading ) {
	$heading = get_field( 'title', 'options' );
}
if ( ! $text ) {
	$text = get_field( 'text', 'options' );
}
if ( ! $button_text ) {
	$button_text = 'Join the Community';
}
if ( ! $button_target ) {
	$button_target = '_self';
}
?>
<div class="post-content-inner cta-box" <?php if( get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
	<span class="subscribe-sidebar-form background-pink">
		<span class="icon-container">
			<span class="icon-inner">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/pink-subscribe-envelope.svg" alt=""/>
			</span>
		</span>
		<span class="labelXXLarge text-black"><?php echo esc_html( $heading ); ?></span>
		<p class="text-black"><?php echo esc_html( $text ); ?></p>
		<?php if ( $button_link ) { ?>
			<span class="form-popup-button-container with-white-arrow with-arrow">
				<a href="<?php echo esc_url( $button_link ); ?>" target="<?php echo esc_attr( $button_target ); ?>"><?php echo esc_html( $button_text ); ?></a>
			</span>
		<?php } ?>
	</span>
</div>
