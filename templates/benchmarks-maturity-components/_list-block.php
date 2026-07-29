<section class="list-block background-black">
    <div class="container">
        <?php if ( get_sub_field ('title') ) { ?>
            <div class="title-block">
                <h2><?php echo get_sub_field('title'); ?></h2>
            </div>
        <?php } ?>
        <?php if ( have_rows( 'list_items' ) ) : ?>
            <div class="list-container">
                <?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
                    <?php $icon = get_sub_field( 'icon' ); ?>
                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                        <a class="item link mobile-hide" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>">
                            <span class="column">    
                                <?php if ( $icon ) { ?>
                                    <span class="icon">
                                        <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                    </span>
                                <?php } ?>
                                <span class="title label-XL text-white">
                                    <?php the_sub_field( 'title' ); ?>
                                </span>
                            </span>
                            <span class="column">
                                <span class="p-xsmall medium-grey">
                                    <?php the_sub_field( 'text' ); ?>
                                </span>
                            </span>
                        </a>
                        <span class="item link desktop-hide">
                            <span class="column title-column">    
                                <?php if ( $icon ) { ?>
                                    <span class="icon">
                                        <img loading="lazy" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                    </span>
                                <?php } ?>
                                <span class="title label-XL text-white">
                                    <?php the_sub_field( 'title' ); ?>
                                </span>
                                <span class="expand"></span>
                            </span>
                            <span class="column more-info">
                                <span class="p-xsmall medium-grey">
                                    <?php the_sub_field( 'text' ); ?>
                                </span>
                                <span class="text-link-container">
                                    <a class="red-text external-link text-link red-underline-link" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>">Learn more</a>
                                </span>
                            </span>
                        </span>
                    <?php } else { ?>
                        <span class="item">
                            <span class="column title-column">    
                                <?php if ( $icon ) { ?>
                                    <span class="icon">
                                        <img loading="lazy" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                    </span>
                                <?php } ?>
                                <span class="title label-XL text-white">
                                    <?php the_sub_field( 'title' ); ?>
                                </span>
                                <span class="expand"></span>
                            </span>
                            <span class="column more-info">
                                <span class="p-xsmall medium-grey">
                                    <?php the_sub_field( 'text' ); ?>
                                </span>
                            </span>
                        </span>
                    <?php } ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>