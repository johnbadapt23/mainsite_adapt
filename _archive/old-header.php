<!doctype html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

<title><?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.min.css?vers=1.4">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/skelet-icons-master/style.css">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/site.webmanifest">
<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#000000">
<meta name="theme-color" content="#000000">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU"></script> -->
<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
    <meta property="og:image" content="<?php the_field( 'video_poster' ); ?>" />
<?php } else { ?>
    <meta property="og:image" content="<?php the_field( 'featured_image' ); ?>" />
<?php } ?>

<?php get_template_part( 'templates/partials/_icons' ); ?>
<?php wp_head(); ?>


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NHF4ZRS');</script>
<!-- End Google Tag Manager -->


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131921908-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-131921908-1');
</script>



<script type="text/javascript" defer="defer" src="https://extend.vimeocdn.com/ga/93985650.js"></script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-4832453446872644",
    enable_page_level_ads: true
    });
</script>

<!-- Global site tag (gtag.js) - Google Ads: 769682308 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-769682308"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-769682308');
</script>


<script type="text/javascript">
_linkedin_partner_id = "483852";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=483852&fmt=gif" />
</noscript>


<!-- AMP Analytics -->

<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/3f98959d0c421624e42a5bbdc/ae59b633050950bf69f9b6fd0.js");</script>

<?php
    global $post;
    $post_slug=$post->post_name;
?>

</head>
<body <?php body_class(''); ?> rel="<?php if ( is_404() ): echo 'notFound'; endif; ?>" <?php if(current_user_can('mepr-active')) { ?>id="logged-in"<?php } ?>>
    <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NHF4ZRS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Google Tag Manager -->
    <amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-NDDQ2B4&gtm.url=SOURCE_URL" data-credentials="include"></amp-analytics>


	<?php get_template_part( 'templates/partials/_header' ); ?>
