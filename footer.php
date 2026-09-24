

	<?php get_template_part( 'templates/partials/_footer' ); ?>

	<?php wp_footer(); ?>
	<?php
	// modernizr-2.7.1.min.js (S107): removed. Was loaded async (never
	// render-blocking) but confirmed dead weight regardless -- grepped
	// this theme's entire SCSS for Modernizr's own class-based
	// feature-detection convention (.no-svg, .no-flexbox, .js/.no-js on
	// <html>, etc.) and found none; the theme's many unrelated ".no-*"
	// classes (.no-margin, .no-padding-top, .no-anim, ...) are this
	// codebase's own BEM-style modifiers, not Modernizr's. Also matches
	// the source/gulp/paths.js comment already noting Modernizr isn't in
	// the built JS bundle and ".addIndicators(" (a different unrelated
	// check) is never called. One residual gap this can't rule out: a
	// content editor could have pasted a raw third-party embed snippet
	// via an ACF field (same pattern as the HubSpot script found during
	// the CSP work) that calls window.Modernizr's JS API directly rather
	// than relying on its CSS classes -- not visible from a theme-code
	// grep. If something breaks after this, that's the first place to
	// look.
	?>

</body>
<script type="text/javascript" nonce="<?php echo esc_attr( adapt_csp_nonce() ); ?>">
_linkedin_partner_id = "8720060";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript" nonce="<?php echo esc_attr( adapt_csp_nonce() ); ?>">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src=https://px.ads.linkedin.com/collect/?pid=8720060&fmt=gif />
</noscript>
</html>
