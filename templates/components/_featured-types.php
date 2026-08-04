<section class="featured-types background-light-grey">
    <div class="article-container-three-post">
        <div class="container">
            <div class="grid-wrapper">
                <?php if ( have_rows( 'type' ) ) : ?>
    				<?php while ( have_rows( 'type' ) ) : the_row(); ?>
                        <span class="item one-third articles type-container">
                            <?php $resource_type_term = get_sub_field( 'resource_type' ); ?>
                            <span class="image-container">
                                <span class="bg-container">
                                    <a href="/resource-type/<?php echo $resource_type_term->slug; ?>">
                                        <?php $featured_image = get_sub_field( 'image' ); ?>
                                        <?php if ( $featured_image ) { ?>
                                            <?php echo wp_get_attachment_image( $featured_image['ID'], 'full', false, array(
                                                'alt'     => $featured_image['alt'],
                                                'loading' => false,
                                            ) ); ?>
                                        <?php } ?>
                                    </a>
                                </span>
                            </span>
                            <span class="item-content-container">
                                <a href="/resource-type/<?php echo $resource_type_term->slug; ?>" class="title label-XXLarge text-black"><?php echo $resource_type_term->name; ?></a>
                            </span>
                        </span>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <div class="item one-third articles all-container">
                    <span class="item-content-container">
                        <span class="labelLarge text-black"><?php echo get_sub_field( 'all_resources_text' ); ?></span>
                    </span>
                    <span class="link-container">
                        <a class="text-link red-text medium-text-link red-underline-link" href="/all-resources">All Resources</a>
                    </span>
                </div>
            </div>
        </div>
</section>
