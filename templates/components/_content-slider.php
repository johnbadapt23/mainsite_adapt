<?php if(get_sub_field( 'background_colour' ) == 'background-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<section<?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id');?>"<?php } ?> class="content-slider-module <?php echo get_sub_field( 'background_colour' ); ?>">
    <div class="container">
        <div class="text-container top-content">
            <h2 class="<?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
            <span class="links-container">
                <?php if ( have_rows( 'link' ) ) : ?>
    				<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                        <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="text-link large-link-text red-text red-underline-link">
                            <?php echo get_sub_field( 'link_text' ); ?>
                        </a>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </span>
        </div>
        <div class="content-slider-container">
            <div class="home-content-slider">
                <?php if ( have_rows( 'slider' ) ) : ?>
                    <?php $counter=1;?>
    				<?php while ( have_rows( 'slider' ) ) : the_row(); ?>
                        <div class="slide content-slide">
                            <div class="content-slide-inner">
                                <div class="image-column">
                                    <div class="image-container">
                                        <div class="bg-container contained">
                        					<?php $image = get_sub_field( 'image' ); ?>
                        					<?php if ( $image ) { ?>
                        						<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                        							'alt'     => $image['alt'],
                        							'loading' => 'lazy',
                        						) ); ?>
                        					<?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-column">
                                    <h4 role="heading" aria-level="3" class="<?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></h4>
                                    <span class="bottom-container">
                                        <span class="caption-sub-title text-medium-grey"><?php echo get_sub_field( 'caption_sub_title' ); ?></span>
                                        <span class="caption-title <?php echo $textColour; ?>"><?php echo get_sub_field( 'caption_title' ); ?></span>
                                    </span>
                                    <?php if ( have_rows( 'link' ) ) : ?>
                                        <span class="link-container">
                    						<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="text-link medium-link-text external-link red-text red-underline-link">
                                                    <?php echo get_sub_field( 'link_text' ); ?>
                                                </a>
                    						<?php endwhile; ?>
                                        </span>
                					<?php else : ?>
                						<?php // no rows found ?>
                					<?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php $counter ++; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </div>
            <div class="progress-container">
    			<?php $slideCount = $counter - 1; ?>
                <?php $slidePercent = 100 / $slideCount; ?>
                <div class="home-component-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $slidePercent;?>" style="background-size:<?php echo $slidePercent;?>%">
                    <span class="slider__label sr-only">
                </div>
    		</div>
        </div>
    </div>
</section>
