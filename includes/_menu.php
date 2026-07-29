<?php

// theme_menus
function theme_menus() {
    register_nav_menus(array(
        'main-menu' => __('Menu', 'theme'),
        'mobile-menu' => __('Mobile Menu', 'theme'),
        'footer-menu' => __('Footer Menu', 'theme'),
    ));
}

// theme_nav
function theme_nav($name){
	wp_nav_menu(
		array(
			'theme_location'  => $name . '-menu',
			'container'       => '',
			'container_class' => false,
			'container_id'    => '',
			'menu_class'      => $name . '-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		)
	);
}

// theme_nav
function theme_nav_mobile($name){
	wp_nav_menu(
		array(
			'theme_location'  => $name . '-menu',
			'container'       => '',
			'container_class' => false,
			'container_id'    => '',
			'menu_class'      => $name . '-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		)
	);
}

// The block below (speaker to executive_advisor migration script, custom admin
// menu, and pre_get_posts / parent_file / submenu_file filters) was already
// fully commented out line-by-line and never executed. Left as-is, unchanged,
// in case it needs to be resurrected.

/* MANUAL MIGRATION -> copy all "advisor" data from "speaker" post type */
/* VISIT THIS URL TO DO THE MIGRATION /wp-admin/?migrate_executive_advisors=1 */

// add_action('admin_init', function () {

//     if (!current_user_can('manage_options')) {
//         return;
//     }

//     if (!isset($_GET['migrate_executive_advisors'])) {
//         return;
//     }

//     $speakers = get_posts([
//         'post_type'      => 'speaker',
//         'posts_per_page' => -1,
//         'post_status'    => 'any',
//         'tax_query'      => [
//             [
//                 'taxonomy' => 'expertise',
//                 'operator' => 'EXISTS',
//             ],
//         ],
//     ]);

//     foreach ($speakers as $speaker) {

//         // Prevent duplicate migration
//         $existing = get_posts([
//             'post_type'      => 'executive_advisor',
//             'posts_per_page' => 1,
//             'post_status'    => 'any',
//             'meta_key'       => '_original_speaker_id',
//             'meta_value'     => $speaker->ID,
//         ]);

//         if (!empty($existing)) {
//             continue;
//         }

//         $new_post_id = wp_insert_post([
//             'post_type'    => 'executive_advisor',
//             'post_title'   => $speaker->post_title,
//             'post_content' => $speaker->post_content,
//             'post_excerpt' => $speaker->post_excerpt,
//             'post_status'  => $speaker->post_status,
//             'post_author'  => $speaker->post_author,
//             'post_date'    => $speaker->post_date,
//             'post_name'    => $speaker->post_name,
//         ]);

//         if (is_wp_error($new_post_id)) {
//             continue;
//         }

//         update_post_meta($new_post_id, '_original_speaker_id', $speaker->ID);

//         // Copy featured image
//         $thumbnail_id = get_post_thumbnail_id($speaker->ID);
//         if ($thumbnail_id) {
//             set_post_thumbnail($new_post_id, $thumbnail_id);
//         }

//         // Copy all meta fields, including ACF fields
//         $meta = get_post_meta($speaker->ID);

//         foreach ($meta as $meta_key => $meta_values) {

//             // Skip system/meta fields that should not be duplicated
//             if (in_array($meta_key, [
//                 '_edit_lock',
//                 '_edit_last',
//                 '_wp_old_slug',
//                 '_original_speaker_id',
//             ], true)) {
//                 continue;
//             }

//             foreach ($meta_values as $meta_value) {
//                 add_post_meta(
//                     $new_post_id,
//                     $meta_key,
//                     maybe_unserialize($meta_value)
//                 );
//             }
//         }

//         // Copy expertise taxonomy terms if executive_advisor supports expertise
//         $terms = wp_get_object_terms($speaker->ID, 'expertise', [
//             'fields' => 'ids',
//         ]);

//         if (!is_wp_error($terms) && !empty($terms)) {
//             wp_set_object_terms($new_post_id, $terms, 'expertise');
//         }
//     }

//     wp_die('Executive Advisors migration completed.');
// });

// CUSTOM EXECUTIVE ADVISOR FILTERING MENU


// add_action('admin_menu', function () {

//     add_menu_page(
//         'Executive Advisors',
//         'Executive Advisors',
//         'edit_posts',
//         'edit.php?post_type=speaker&show_expertise_only=1',
//         '',
//         'dashicons-groups',
//         25
//     );

//     add_submenu_page(
//         'edit.php?post_type=speaker&show_expertise_only=1',
//         'All Advisors',
//         'All Advisors',
//         'edit_posts',
//         'edit.php?post_type=speaker&show_expertise_only=1'
//     );

//     add_submenu_page(
//         'edit.php?post_type=speaker&show_expertise_only=1',
//         'Expertise',
//         'Expertise',
//         'manage_categories',
//         'edit-tags.php?taxonomy=expertise&post_type=speaker'
//     );
// });



// add_action('pre_get_posts', function ($query) {

//     if (
//         is_admin() &&
//         $query->is_main_query() &&
//         $query->get('post_type') === 'speaker' &&
//         isset($_GET['show_expertise_only'])
//     ) {
//         $query->set('tax_query', [
//             [
//                 'taxonomy' => 'expertise',
//                 'operator' => 'EXISTS',
//             ],
//         ]);
//     }
// });

// add_filter('parent_file', function ($parent_file) {
//     global $pagenow;

//     if (
//         $pagenow === 'edit.php' &&
//         isset($_GET['post_type'], $_GET['show_expertise_only']) &&
//         $_GET['post_type'] === 'speaker'
//     ) {
//         return 'edit.php?post_type=speaker&show_expertise_only=1';
//     }

//     if (
//         $pagenow === 'edit-tags.php' &&
//         isset($_GET['taxonomy'], $_GET['post_type']) &&
//         $_GET['taxonomy'] === 'expertise' &&
//         $_GET['post_type'] === 'speaker'
//     ) {
//         return 'edit.php?post_type=speaker&show_expertise_only=1';
//     }

//     return $parent_file;
// });

// add_filter('submenu_file', function ($submenu_file, $parent_file) {
//     global $pagenow;

//     if (
//         $pagenow === 'edit.php' &&
//         isset($_GET['post_type'], $_GET['show_expertise_only']) &&
//         $_GET['post_type'] === 'speaker'
//     ) {
//         return 'edit.php?post_type=speaker&show_expertise_only=1';
//     }

//     if (
//         $pagenow === 'edit-tags.php' &&
//         isset($_GET['taxonomy'], $_GET['post_type']) &&
//         $_GET['taxonomy'] === 'expertise' &&
//         $_GET['post_type'] === 'speaker'
//     ) {
//         return 'edit-tags.php?taxonomy=expertise&post_type=speaker';
//     }

//     return $submenu_file;
// }, 10, 2);
?>
