<section class="company-slider background-light-grey">
    <div class="container">
        <div class="title-container">
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="company-slide-container">
            <?php if ( have_rows( 'slide' ) ) : ?>
				<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                    <div class="company-slide slide">
                        <div class="slide-inner background-medium-light-grey">
                            <span class="logo-container">
                                <span class="logo-container-colour-mask" style="background-color:<?php echo get_sub_field( 'active_background_colour' ); ?>;"></span>
                                <span class="logo-container-inner">
                                    <?php $logo_white = get_sub_field( 'logo_white' ); ?>
                                    <?php if ( $logo_white ) { ?>
                                        <?php echo wp_get_attachment_image( $logo_white['ID'], 'full', false, array(
                                            'class'   => 'logo-white',
                                            'alt'     => $logo_white['alt'],
                                            'loading' => false,
                                        ) ); ?>
                                    <?php } ?>
                                    <?php $logo_dark = get_sub_field( 'logo_dark' ); ?>
                                    <?php if ( $logo_dark ) { ?>
                                        <?php echo wp_get_attachment_image( $logo_dark['ID'], 'full', false, array(
                                            'class'   => 'logo-dark',
                                            'alt'     => $logo_dark['alt'],
                                            'loading' => 'lazy',
                                        ) ); ?>
                                    <?php } ?>
                                </span>
                            </span>
                            <span class="slider-text-container">
                                <span class="slider-text-inner">
                                    <p class="p-large"><?php echo get_sub_field( 'text' ); ?></p>
                                    <span class="bottom-text">
                                        <span class="label-small title-label text-black"><?php echo get_sub_field( 'bottom_text_title' ); ?></span>
                                        <span class="label-small text-label text-dark-grey"><?php echo get_sub_field( 'bottom_text' ); ?></span>
                                    </span>
                                </span>
                            </span>
                        </div>                                                
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>            
        </div>
        <span class="company-progress-container">
            <span class="company-progress">
            </span>
        </span>
    </div>
</section>

