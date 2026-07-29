<?php

// theme_setup
function theme_setup() {

	// date_default_timezone_set('Australia/Melbourne');

	add_editor_style();
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
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
