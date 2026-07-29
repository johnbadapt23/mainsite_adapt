<?php
/**
 * Template Name: Register Template
 */

get_header();
?>

<main class="<?php if ( is_home() ): echo 'blog'; else: echo $postType; endif; ?><?php if ( is_search() ): echo ' search'; endif; ?><?php if ( is_404() ): echo ' notFound'; endif; ?>" id="main" >
    <section class="default">
        <div class="container <?php the_field('content_width'); ?>">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif;  ?>
            <?php if ( get_field ( 'register_button_text' )) { ?>
                <span class="register-button-container">
                    <a class="button" href="<?php the_field('register_button_link');?>" target="_self"><?php the_field('register_button_text');?></a>
                </span>
            <?php } ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
