<?php

//current url
function current_url() {
	$url = 'http://'; //( 'on' == $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= ( '80' == $_SERVER['SERVER_PORT'] ) ? '' : ':' . $_SERVER['SERVER_PORT'];
	$url .= $_SERVER['REQUEST_URI'];
	return trailingslashit( $url );
}


// get id from slug
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

// get excerpt by id
function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id);
    $the_excerpt = $the_post->post_excerpt;
    return $the_excerpt;
}

// check from pagination
function is_paginated() {
    global $wp_query;
    if ( $wp_query->max_num_pages > 1 ) {
        return true;
    } else {
        return false;
    }
}

// create slug
function slugify ($string) {
    $string = utf8_encode($string);
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
    $string = str_replace(' ', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);

    if (empty($string)) {
        return '';
    }

    return $string;
}

// update query string
function updateQueryString($key, $value){
	parse_str($_SERVER['QUERY_STRING'], $query_string);

	if ($value == '') {
		unset($query_string[$key]);
	} else {
		$query_string[$key] = $value;
	}

	return http_build_query($query_string);
}

// redirect page
function redirectPage($url){
	echo '<script type="text/javascript">window.location = "' . $url . '"</script>';
}

?>
