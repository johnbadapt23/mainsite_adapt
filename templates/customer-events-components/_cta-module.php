<section class="new-cta background-black">
    <div class="cta-outer background-red">
        <div class="container">
            <div class="column-container">
                <div class="column title-column">
                    <h2 class="white-text">
                        <?php echo get_sub_field( 'title' ); ?>
                    </h2>
                </div>
                <div class="column cta-column">
                    <span class="cta-animation-container">
                        <?php if (get_sub_field('link_type') == 'link'){ ?> 
                            <a class="text-link-animation white-text header-Xsmall arrow-text-link growing-circle-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
                                <?php echo get_sub_field( 'link_text' ); ?>
                                <span class="arrow-container">
                                </span>
                                <span class="large-circle-full"></span>
                                <span class="large-circle-dotted"></span>
                                <span class="largest-circle-full"></span>
                            </a>
                        <?php } else { ?>
                            <span class="text-link-animation form-container white-text header-Xsmall arrow-text-link growing-circle-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
                                <?php echo get_sub_field( 'form_button' ); ?>
                                <span class="arrow-container">
                                </span>
                                <span class="large-circle-full"></span>
                                <span class="large-circle-dotted"></span>
                                <span class="largest-circle-full"></span>
                            </a>
                        <?php } ?>                        
                    </span>
                </div>
            </div>
        </div>	
    </div>
</section>