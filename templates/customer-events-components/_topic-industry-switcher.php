<section class="topic-industry-switcher background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="top-container column-container">
            <div class="column-one breadcrumb column">
                <span class="label-small">Topics & Industries</span> 
            </div>
            <div class="column-two title-column column">
                <h2 class="black-text title-switcher">
                    <?php echo get_sub_field( 'title' ); ?>
                    <span class="switcher-container">
                        <span class="switch-title topics active">topics <span class="mobile-only">and</span></span>
                        <span class="switch">                            
                            <label class="switch">
                                <input type="checkbox" id="topicIndustrySwitch">
                                <span class="slider round"></span>
                            </label>
                            <span class="tooltip label-Xsmall">Switch between 'Topics' and 'Industries' to see our full expertise.</span>                                                        
                        </span>
                        <span class="switch-title industries">industries</span>
                    </span>
                </h2>
            </div>
        </div>
        <div class="bottom-container">
            <div class="topics-container active">
                <?php if ( have_rows( 'accordion' ) ) : ?>
                    <div class="accordion-container">
                        <?php $counter=1;?>
                        <?php while ( have_rows( 'accordion' ) ) : the_row(); ?>
                            <div class="accordion-item faq-container">
                                <span class="accordion-counter"><?php echo $counter; ?></span>
                                <span class="accordion-inner faq-item">
                                    <span class="accordion-title labelXLarge question"><?php echo get_sub_field( 'title' ); ?></span>
                                    <span class="accordion-content p-medium" style="display: none;"><?php echo get_sub_field( 'text' ); ?></span>
                                </span>
                            </div>
                            <?php $counter++;?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <div class="industries-container">
                <div class="text-outer-container">
                    <span class="p-large"><?php echo get_sub_field( 'industries_text_top' ); ?></span>
                </div>
                <div class="image-outer">
                    <?php $image = get_sub_field( 'image' ); ?>
                    <?php if ( $image ) { ?>
                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                            'class'   => 'desktop mobile-hide',
                            'alt'     => $image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                    <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
                    <?php if ( $mobile_image ) { ?>
                        <?php echo wp_get_attachment_image( $mobile_image['ID'], 'full', false, array(
                            'class'   => 'mobile desktop-hide',
                            'alt'     => $mobile_image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>					