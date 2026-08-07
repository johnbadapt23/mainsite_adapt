<section class="quicklinks-with-hover background-black">
    <div class="container">
        <div class="title-container">
            <?php echo get_sub_field( 'title' ); ?>
        </div>
        <div class="background-card-container">
            <?php $background_image = get_sub_field( 'background_image' ); ?>
            <div class="background-container" style="background-image:url(<?php echo $background_image['url']; ?>)">
			</div>
            <div class="card-container">
                <?php if ( have_rows( 'quicklinks' ) ) : ?>
                    <?php while ( have_rows( 'quicklinks' ) ) : the_row(); ?>
                        <?php if ( get_sub_field('link_url') !== '' && get_sub_field('link_url') !== null ) { ?>
                            <a class="card quicklink-link" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>">
                        <?php } else { ?> 
                            <span class="card span-card">
                        <?php } ?>                        
                            <?php $icon = get_sub_field( 'icon' ); ?>
                            <?php if ( $icon ) { ?>
                                <span class="image-container">
                                    <?php echo wp_get_attachment_image( $icon['ID'], 'adapt-optimized', false, array(
                                        'alt'     => $icon['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                </span>
                            <?php } ?>
                            <span class="text labelMedium">
                                <?php echo get_sub_field( 'text' ); ?>
                            </span>
                        <?php if ( get_sub_field('link_url') !== '' && get_sub_field('link_url') !== null ) { ?>
                            </a>
                        <?php } else { ?> 
                            </span>
                        <?php } ?>                        
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>