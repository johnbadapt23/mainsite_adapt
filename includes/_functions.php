<?php

//current url
function current_url() {
	$url = 'http://'; //( 'on' == $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= ( '80' == $_SERVER['SERVER_PORT'] ) ? '' : ':' . $_SERVER['SERVER_PORT'];
	$url .= $_SERVER['REQUEST_URI'];
	return trailingslashit( $url );
}

// REMOVED 2026-09-07: get_id_by_slug(), get_excerpt_by_id(), is_paginated(),
// slugify(), updateQueryString(), and redirectPage() were all dead code --
// confirmed via a whole-repo grep (all file types, not just *.php) that
// none of the 6 has a single call site anywhere outside its own
// definition here. slugify() also used utf8_encode(), deprecated since
// PHP 8.2. current_url() (above) is the only function in this file that's
// actually used (includes/_customisations.php:101) -- kept as-is.

/**
 * Render an <img> tag from an ACF image field's value, regardless of that
 * field's configured "Return Format" (Image Array, Image ID, or Image URL).
 *
 * WHY THIS EXISTS: a straight `wp_get_attachment_image( $field['ID'], ... )`
 * call only works when the field is set to return an array -- but this
 * theme has the SAME field name (e.g. "logo", "speaker_image") configured
 * with different return formats in different ACF field groups (confirmed
 * via acf-json/*.json), so a template can't assume which shape it's
 * getting just from the field name. This wrapper accepts whatever ACF
 * hands back and resolves it to a real attachment ID when possible, so it
 * gets the wp_get_attachment_image() benefits (registered sizes, WebP via
 * the theme's rewrite step, srcset/sizes, consistent lazy-loading) without
 * needing each call site to know or care about that field's return format.
 *
 * 2026-09-08: added as part of migrating raw <img src="<?php echo esc_url($field); ?>">
 * usage (found across functions.php and several templates) toward
 * wp_get_attachment_image(). Deliberately NOT a blanket find/replace --
 * every one of the fields being migrated was individually confirmed to
 * actually be an ACF image field first.
 *
 * @param mixed  $field ACF image field value: an attachment array
 *                       (Return Format = Image Array), an int/numeric
 *                       string (Image ID), or a plain URL string
 *                       (Image URL).
 * @param string $size  Registered image size for wp_get_attachment_image().
 *                       Defaults to 'full' to match the raw <img> tags'
 *                       existing behavior of rendering the original,
 *                       unscaled image -- pass a registered size explicitly
 *                       if a smaller size is actually wanted.
 * @param array  $attr  Extra <img> attributes (alt, class, loading, etc.),
 *                       same shape wp_get_attachment_image() itself takes.
 * @return string HTML <img> tag, or '' if $field is empty.
 */
function adapt_acf_image( $field, $size = 'full', $attr = array() ) {
	if ( empty( $field ) ) {
		return '';
	}

	$attachment_id = 0;

	if ( is_array( $field ) && ! empty( $field['ID'] ) ) {
		// Return Format = Image Array (or Image Object -- same shape for
		// our purposes here).
		$attachment_id = (int) $field['ID'];
	} elseif ( is_numeric( $field ) ) {
		// Return Format = Image ID.
		$attachment_id = (int) $field;
	} elseif ( is_string( $field ) ) {
		// Return Format = Image URL. attachment_url_to_postid() does a DB
		// lookup by GUID -- only succeeds if $field is the attachment's
		// original (non-resized) URL and it's still in the media library.
		$attachment_id = attachment_url_to_postid( $field );
	}

	if ( $attachment_id ) {
		$image_html = wp_get_attachment_image( $attachment_id, $size, false, $attr );
		if ( $image_html ) {
			return $image_html;
		}
	}

	// Fallback: couldn't resolve an attachment ID (external URL, a URL
	// whose attachment no longer exists in the media library, etc.) --
	// render the same plain <img src="..."> the call site used to render
	// directly, so behavior never regresses even when resolution fails.
	$url = is_array( $field ) ? ( $field['url'] ?? '' ) : ( is_string( $field ) ? $field : '' );
	if ( ! $url ) {
		return '';
	}
	$attr_html = '';
	foreach ( $attr as $attr_name => $attr_value ) {
		$attr_html .= sprintf( ' %s="%s"', esc_attr( $attr_name ), esc_attr( $attr_value ) );
	}
	return sprintf( '<img src="%s"%s />', esc_url( $url ), $attr_html );
}

?>
