<section class="auto-play-card-carousel" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?>>
    <div class="container">
        <div class="title-container">
            <span><?php echo get_sub_field( 'title' ); ?></span>
        </div>
       <div class="auto-card-container-inner">
            <div class="slide-wrapper">
                <?php if( have_rows('cards') ): ?>
                    <?php while( have_rows('cards') ): the_row(); ?>
                        <span class="slide">
                            <span class="card-image-container image-container">
                                <?php $image = get_sub_field('image'); ?>
                                <?php if ($image): ?>
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                <?php endif; ?>
                            </span>
                            <span class="text-container">
                                <span class="white-text labelMedium"><?php echo get_sub_field('title'); ?></span>
                                <span class="text text-medium-grey"><?php echo get_sub_field('text'); ?></span>
                                <?php if( get_sub_field('add_link') == 'yes' && have_rows('link') ): ?>
                                    <span class="link-container">
                                        <?php while( have_rows('link') ): the_row(); ?>
                                            <a class="text-link external-link red-text red-link red-underline-link"
                                            href="<?php echo get_sub_field('link_url'); ?>"
                                            target="<?php echo get_sub_field('link_target'); ?>">
                                            <?php echo get_sub_field('link_text'); ?>
                                            </a>
                                        <?php endwhile; ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </span>
                    <?php endwhile; ?>

                    <!-- Duplicate slides for smooth infinite scroll -->
                    <?php while( have_rows('cards') ): the_row(); ?>
                        <span class="slide">
                            <span class="card-image-container image-container">
                                <?php $image = get_sub_field('image'); ?>
                                <?php if ($image): ?>
                                    <img loading="lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                <?php endif; ?>
                            </span>
                            <span class="text-container">
                                <span class="white-text labelMedium"><?php echo get_sub_field('title'); ?></span>
                                <span class="text text-medium-grey"><?php echo get_sub_field('text'); ?></span>
                                <?php if( get_sub_field('add_link') == 'yes' && have_rows('link') ): ?>
                                    <span class="link-container">
                                        <?php while( have_rows('link') ): the_row(); ?>
                                            <a class="text-link external-link red-text red-link red-underline-link"
                                            href="<?php echo get_sub_field('link_url'); ?>"
                                            target="<?php echo get_sub_field('link_target'); ?>">
                                            <?php echo get_sub_field('link_text'); ?>
                                            </a>
                                        <?php endwhile; ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </span>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>             
    </div>
</section>
 <script>
    document.addEventListener('DOMContentLoaded', function(){
        const wrapper = document.querySelector('.auto-card-container-inner .slide-wrapper');
        if(!wrapper) return;

        const slides = wrapper.querySelectorAll('.slide');
        let totalWidth = 0;

        slides.forEach(slide => {
            const style = getComputedStyle(slide);
            const marginRight = parseFloat(style.marginRight);
            totalWidth += slide.offsetWidth + marginRight;
        });

        // Half the total width because slides are duplicated
        wrapper.style.setProperty('--scroll-width', `-${totalWidth/2}px`);
    });
</script>