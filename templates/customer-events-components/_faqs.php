<section class="customer-events-faqs <?php if( get_sub_field( 'background_colour' )){ ?><?php echo get_sub_field( 'background_colour' ); ?><?php } else { ?>background-black<?php } ?>">
    <div class="container">
        <div class="title-container">
            <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="faq-container">
            <?php if ( have_rows( 'faq_group' ) ) : ?>
				<?php while ( have_rows( 'faq_group' ) ) : the_row(); ?>                
                    <div class="faq-column <?php if(get_sub_field( 'title' )){ ?><?php } else { ?> no-padding-top<?php } ?>">
                        <?php if(get_sub_field( 'title' )){ ?> 
                            <div class="faq-column-inner">
                                <h4 role="heading" aria-level="3" class="secondary-text headerXsmall group-title"><?php echo get_sub_field( 'title' ); ?></h4>                            
                                <?php if ( have_rows( 'faq_item' ) ) : ?>
                                    <?php while ( have_rows( 'faq_item' ) ) : the_row(); ?>
                                        <span class="faq-item">
                                            <span class="question accordion-title labelLarge">
                                                <?php echo get_sub_field( 'item_title' ); ?>
                                            </span>
                                            <span class="answer accordion-content p-small" style="display: none;">
                                                <?php echo get_sub_field( 'text' ); ?>
                                            </span>
                                        </span>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                        <?php } else { ?> 
                            <?php if ( have_rows( 'faq_item' ) ) : ?>
                                <?php while ( have_rows( 'faq_item' ) ) : the_row(); ?>
                                    <div class="faq-single-outer border-top">
                                        <div class="faq-column-inner">
                                            <span class="faq-item">
                                                <span class="question accordion-title labelLarge">
                                                    <?php echo get_sub_field( 'item_title' ); ?>
                                                </span>
                                                <span class="answer accordion-content p-small" style="display: none;">
                                                    <?php echo get_sub_field( 'text' ); ?>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>