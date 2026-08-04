 <section class="logo-scroller logo-ticker-tape">
    <div class="container">
        <?php if ( have_rows( 'top_row' ) ) : ?>
            <?php while ( have_rows( 'top_row' ) ) : the_row(); ?>
                <div class="band-container-backwards">
                    <span class="moving-text play">
                        <?php if ( have_rows( 'company' ) ) : ?>
                            <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                            <?php } ?>
                                                <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                    'class'   => 'colour-image',
                                                    'alt'     => $logo['alt'],
                                                    'loading' => false,
                                                ) ); ?>
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                    <span class="moving-text play">
                        <?php if ( have_rows( 'company' ) ) : ?>
                            <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                            <?php } ?>
                                                <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                    'class'   => 'colour-image',
                                                    'alt'     => $logo['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <?php if ( have_rows( 'bottom_row' ) ) : ?>
            <?php while ( have_rows( 'bottom_row' ) ) : the_row(); ?>
                <div class="band-container-forwards">
                    <span class="moving-text">
                        <?php if ( have_rows( 'company' ) ) : ?>
                            <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                            <?php } ?>
                                                <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                    'class'   => 'colour-image',
                                                    'alt'     => $logo['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                        <span class="moving-text">
                        <?php if ( have_rows( 'company' ) ) : ?>
                            <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                                <?php $logo = get_sub_field( 'logo' ); ?>
                                <?php if ( $logo ) { ?>
                                    <span class="ticker-logo-container">
                                        <span class="bg-container">
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                <a href="<?php echo get_sub_field( 'company_link' );?>" target="_blank" rel="noopener noreferrer">
                                            <?php } ?>
                                                <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array(
                                                    'class'   => 'colour-image',
                                                    'alt'     => $logo['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php if(get_sub_field( 'company_link' )) { ?>
                                                </a>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>