<section class="default background-white text-black">
    <div class="container">
        <div class="content-inner content-container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif;  ?>
        </div>
    </div>
</section>
