<section class="about-block background-black">
    <div class="container">
        <div class="about-container">      
            <?php if(get_sub_field('title')) { ?> 
                <h2><?php echo get_sub_field( 'title' ); ?></h2>
            <?php } ?>                  
             <?php $top_corner_icon = get_sub_field( 'top_corner_icon' ); ?>
            <?php if ( $top_corner_icon ) { ?>
                <span class="logo">
                    <?php echo wp_get_attachment_image( $top_corner_icon['ID'], 'adapt-optimized', false, array(
                        'alt'     => $top_corner_icon['alt'],
                        'width'   => '140',
                        'loading' => 'lazy',
                    ) ); ?>
                </span>
            <?php } ?>   
            <div class="column-container">                             
                <div class="column one-half">                   
                    <span class="text"><?php echo get_sub_field( 'column_one_text' ); ?></span>
                </div>
                <div class="column one-half">
                    <span class="text">
                        <?php echo get_sub_field( 'column_two_text' ); ?>
                    </span>
                    <?php if ( have_rows( 'link' ) ) : ?>
        				<?php while ( have_rows( 'link' ) ) : the_row(); ?>
        					<a class="about-link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
        				<?php endwhile; ?>
        			<?php else : ?>
        				<?php // no rows found ?>
        			<?php endif; ?>
                </div>
            </div>
    		<?php $rotating_icon = get_sub_field( 'rotating_icon' ); ?>
            <span class="rotating-image-container">
        		<?php if ( $rotating_icon ) { ?>
        			<?php echo wp_get_attachment_image( $rotating_icon['ID'], 'adapt-optimized', false, array(
        				'id'      => 'rotatingImage',
        				'alt'     => $rotating_icon['alt'],
        				'loading' => 'lazy',
        			) ); ?>
        		<?php } ?>
            </span>
        </div>
    </div>
</section>
