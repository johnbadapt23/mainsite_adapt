<?php

// theme_setup
function theme_setup() {

	// date_default_timezone_set('Australia/Melbourne');

	add_editor_style();
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );

	if ( function_exists('acf_add_options_page') ) {
    	acf_add_options_page();
    }
}

?>
