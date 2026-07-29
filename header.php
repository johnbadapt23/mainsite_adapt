<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

<title><?php wp_title(); ?></title>

<!-- main.min.css is loaded via wp_enqueue_style() in functions.php (my_enqueue_scripts),
     output below by wp_head(). It used to also be hardcoded here with a stale manual
     "?ver=1.0.34", which loaded the same stylesheet twice on every page. -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/skelet-icons-master/style.css">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/site.webmanifest">
<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#000000">
<meta name="theme-color" content="#000000">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com">
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU"></script> -->
<?php if ( get_field ( 'featured_image' ) ) { ?>
    <?php $image_seo = get_field( 'seo_image' ); ?>
    <meta property="og:image" content="<?php echo $image_seo['url']; ?>" />
<?php } else if ( get_field ( 'featured_image' ) ) { ?>
    <?php $featured_image_seo = get_field( 'featured_image' ); ?>
    <meta property="og:image" content="<?php echo $featured_image_seo['url']; ?>" />
<?php } else if ( get_field ( 'video_poster' )) { ?>
    <?php $video_poster_image = get_field( 'video_poster' ); ?>
    <meta property="og:image" content="<?php echo $video_poster_image['url']; ?>" />
<?php } ?>
<?php wp_head(); ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NHF4ZRS');</script>
<!-- End Google Tag Manager -->

<!-- Google tag ga4 (gtag.js) --> 
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J1TXH5MDSE"></script> 
<script>
    window.dataLayer = window.dataLayer || [];   
    function gtag(){dataLayer.push(arguments);}   
    gtag('js', new Date());   gtag('config', 'G-J1TXH5MDSE'); 
    gtag('config', 'AW-769682308');
</script>

<?php
    global $post;
    $post_slug = ( isset($post->post_name) ) ? $post->post_name : '';
?>
<?php if ( is_page_template('templates/template-thank-you-new.php') || is_page_template('templates/template-thank-you.php') ) { ?>
    <!-- Event snippet for Form Submits | All Forms conversion page --> 
    <script> gtag('event', 'conversion', {'send_to': 'AW-769682308/TFnsCK6mn80ZEITXge8C'});</script>
<?php } ?>
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/8336221.js"></script>
<!-- End of HubSpot Embed Code -->
</head>
<body <?php body_class(''); ?> data-page-id="<?= the_ID(); ?>" rel="<?php if ( is_404() ): echo 'notFound'; endif; ?>">
    <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NHF4ZRS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->


	<?php get_template_part( 'templates/partials/_header' ); ?>
