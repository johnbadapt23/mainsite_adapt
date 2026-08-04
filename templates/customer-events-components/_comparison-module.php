<section class="comparison-module background-white">
    <div class="container">
        <span class="title-container">
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
        </span>
        <div class="comparison-table-container">
            <div class="title-column-container">
                <span class="empty-column row-title-column">
                </span>
                <span class="title-column background-white">
                    <span class="labelXXLarge text-black">ADAPT</span>
                    <span class="labelSmall text-black">Enterprise+</span>
                </span>
                <span class="title-column">
                    <span class="labelXXLarge text-secondary">Gartner</span>
                    <span class="labelSmall text-secondary">Executive ​Partner Program </span>
                </span>
            </div>
            <?php if ( have_rows( 'comparison_row' ) ) : ?>
                
                <?php while ( have_rows( 'comparison_row' ) ) : the_row(); ?>
                    <div class="comparison-row-container">
                        <span class="row-title-column border-row">
                            <span class="labelLarge"><?php echo get_sub_field( 'row_title' ); ?></span>
                        </span>
                        <span class="value-column background-white">
                            <?php if (get_sub_field( 'adapt_text_value' )) { ?>
                                <span class="text-value text"><?php echo get_sub_field( 'adapt_text_value' ); ?></span> 
                            <?php } else { ?> 
                                <span class="image-value">
                                    <?php $adapt_image_value = get_sub_field( 'adapt_image_value' ); ?>
                                    <?php if ( $adapt_image_value ) { ?>
                                        <?php echo wp_get_attachment_image( $adapt_image_value['ID'], 'full', false, array(
                                            'alt'     => $adapt_image_value['alt'],
                                            'loading' => false,
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                            <?php } ?>                            
                            
                        </span>
                        <span class="value-column">
                            <?php if (get_sub_field( 'gartner_text_value' )) { ?> 
                                <span class="text-value text text-secondary"><?php echo get_sub_field( 'gartner_text_value' ); ?></span> 
                            <?php } else { ?> 
                                <span class="image-value">
                                    <?php $gartner_image_value = get_sub_field( 'gartner_image_value' ); ?>
                                    <?php if ( $gartner_image_value ) { ?>
                                        <?php echo wp_get_attachment_image( $gartner_image_value['ID'], 'full', false, array(
                                            'alt'     => $gartner_image_value['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                            <?php } ?>                           
                        </span>
                    </div>
                <?php endwhile; ?>                
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
        <?php if ( get_sub_field( 'download_link' ) ) { ?>
            <span class="button-container">
                <a class="std-button red-button" target="_blank" href="<?php the_sub_field( 'download_link' ); ?>"><?php echo get_sub_field( 'download_link_text' ); ?></a>
            </span>
        <?php } ?>
    </div>
</section>


