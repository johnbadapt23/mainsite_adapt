<?php

// actions
add_action('after_setup_theme', 	'theme_setup' );
add_action('init', 					'theme_scripts');
add_action('wp_enqueue_scripts', 	'theme_styles');
add_action('init', 					'theme_menus');
add_action('widgets_init', 			'theme_widgets' );
add_action('init', 					'custom_theme_pagination');
add_action('init',                  'custom_disable_wp_emojicons');
add_action('init',                  'custom_disable_embeds', 9999);
add_action('admin_menu',            'custom_remove_menus');
add_action('login_head',            'custom_wp_login_logo');
add_action('admin_menu',            'custom_remove_footer');
add_action('init', 					'custom_remove_jquery');
add_action('after_setup_theme', 	'custom_disable_json_api');
add_action('after_setup_theme', 	'custom_remove_json_api');
add_action('acf/init',              'custom_acf_init');
//add_action('init', 				'custom_start_session', 1);

remove_action('wp_head', 			'feed_links_extra', 3);
remove_action('wp_head', 			'feed_links', 2);
remove_action('wp_head', 			'rsd_link');
remove_action('wp_head', 			'wlwmanifest_link');
remove_action('wp_head', 			'index_rel_link');
remove_action('wp_head', 			'parent_post_rel_link', 10, 0);
remove_action('wp_head', 			'start_post_rel_link', 10, 0);
remove_action('wp_head', 			'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 			'wp_generator');
remove_action('wp_head', 			'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 			'rel_canonical');
remove_action('wp_head', 			'wp_shortlink_wp_head', 10, 0);

// filters
add_filter('show_admin_bar', 		'__return_false');
add_filter('xmlrpc_enabled',        '__return_false');
add_filter('excerpt_more', 			'custom_excerpt_link');
add_filter('excerpt_length', 		'custom_excerpt_length', 999 );
add_filter('style_loader_tag', 		'custom_remove_style_type');
add_filter('post_thumbnail_html', 	'custom_remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 	'custom_remove_thumbnail_dimensions', 10);
add_filter('nav_menu_css_class', 	'custom_add_parent_url_menu_class', 10, 3 );
// add_filter('nav_menu_css_class', 	'custom_wp_nav_menu');
add_filter('nav_menu_item_id', 		'custom_wp_nav_menu');
add_filter('page_css_class', 		'custom_wp_nav_menu');
add_filter('wp_nav_menu',			'custom_current_to_active');
add_filter('wp_nav_menu',			'custom_strip_empty_classes');
add_filter('nav_menu_item_id', 		'custom_nav_id_filter', 10, 2 );
add_filter('body_class', 			'custom_body_classs');
add_filter('query_vars', 			'custom_add_query_vars_filter' );
add_filter('login_headerurl',       'custom_wp_login_url');
add_filter('login_headertitle',     'custom_wp_login_title');
add_filter('admin_footer_text',     'custom_footer_admin');
// add_filter('wp_default_scripts', 	'custom_remove_jquery_migrate' );
add_filter('gform_confirmation_anchor',     '__return_false' );
add_filter('gform_confirmation_anchor_1',     '__return_false' );
//add_filter('gform_validation_message_1',   'custom_form_error_message', 10, 2 );

remove_filter('the_excerpt', 		'wpautop');

?>
