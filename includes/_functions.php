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

?>
