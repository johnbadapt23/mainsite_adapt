<section<?php if ( get_sub_field ('id')) { ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="overlapping-cards background-lightest-grey">
    <div class="container">
        <div class="title-container">
            <span class="inner-text">                        
                <h2 class="bold-red text-black"><?php echo get_sub_field('title'); ?></h2>
                <span class="text secondary-dark"><?php echo get_sub_field('text'); ?></span>
            </span>
        </div>

        <div class="overlapping-cards-container">
            <?php if (have_rows('cards')): ?>
                <?php while (have_rows('cards')): the_row(); ?>
                    <div class="overlapping-card-wrapper">
                        <div class="overlapping-card">
                            <div class="column-container">
                                <div class="column text-column one-half">
                                    <div class="text-inner">
                                        <h2 class="bold-red text-black"><?php echo get_sub_field('card_title'); ?></h2>
                                        <span class="text p-large secondary-dark"><?php echo get_sub_field('card_text'); ?></span>
                                    </div>
                                </div>
                                <div class="column one-half image-column">
                                    <div class="image-container square">
                                        <div class="bg-container">
                                            <?php $image = get_sub_field('image'); ?>
                                            <?php if ($image): ?>
                                                <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                                    'alt'     => $image['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
