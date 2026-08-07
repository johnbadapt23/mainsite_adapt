<?php

// theme_setup
function theme_setup() {

	// date_default_timezone_set('Australia/Melbourne');

	add_editor_style();
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );

	// Every wp_get_attachment_image() call in the theme (500+) requested
	// the 'full' size, meaning a card thumbnail displayed at a few hundred
	// pixels wide was downloading the same multi-megabyte original as a
	// full-width hero banner -- a major contributor to page weight and
	// Lighthouse's "properly size images" audit. This registers one
	// generously-capped size to replace 'full' across the theme.
	//
	// crop is deliberately false: WP scales the image down proportionally
	// (constrains to whichever of width/height is hit first) rather than
	// cropping to an exact box, so the output always keeps the original's
	// exact aspect ratio. That matters because this theme's CSS does its
	// own cropping/fitting (background-size: cover, object-fit, etc.) --
	// as long as the aspect ratio is unchanged, the browser-side crop
	// looks identical, just working from a smaller, faster-loading file.
	// 2000px comfortably covers this theme's 1340px max container width
	// even at ~1.5x pixel density, with headroom for the rare full-bleed
	// section that exceeds the container.
	//
	// Note: this only affects newly-uploaded media and anything re-saved
	// going forward. Already-uploaded images need their thumbnails
	// regenerated (e.g. via WP-CLI `wp media regenerate` or a "Regenerate
	// Thumbnails" plugin run from wp-admin) before wp_get_attachment_image()
	// calls requesting this size actually serve the smaller file instead
	// of silently falling back to 'full'.
	add_image_size( 'adapt-optimized', 2000, 2000, false );
	// Lets WordPress core inject the <title> tag itself (via wp_head()),
	// instead of the theme hardcoding a <title> tag that calls wp_title().
	// Without this, Yoast SEO's title customization (per-page SEO titles,
	// title templates) can't reliably take over the <title> tag.
	add_theme_support( 'title-tag' );

	if ( function_exists('acf_add_options_page') ) {
    	acf_add_options_page();
    }
}

?>
