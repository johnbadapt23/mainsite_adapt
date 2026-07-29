<section class="animation-introduction <?php echo get_sub_field( 'background_colour' ); ?>">
    <?php $animation_json = get_sub_field( 'background_animation' ); ?>
    <?php $animation_id = get_sub_field( 'value_id' ); ?>
    <?php if ( $animation_json ) { ?>
        <div class="animation-underlay-container">
            <div class="container">
                <span class="animation-container">
                    <span class="animator-player">
                        <lottie-player loop autoplay speed="1" id="<?php echo $animation_id; ?>" src="<?php echo $animation_json['url']; ?>" background="transparent" style="width: 100%; height: auto"></lottie-player>
                    </span>
                </span>
            </div>
        </div>
    <?php } ?>
    <div class="container">
        <div class="introduction-content-container">
            <h1><?php echo get_sub_field( 'title' ); ?></h1>
			<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
			<?php if ( have_rows( 'button' ) ) : ?>
                <span class="button-container">
    				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if(get_sub_field( 'button_type' ) == 'scroll-to-link') { ?>
                            <a class="scroll-to-button std-button  red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                        <?php } else { ?>
                            <a class="std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                        <?php } ?>
    				<?php endwhile; ?>
                </span>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
			<?php if ( have_rows( 'text_links' ) ) : ?>
                <span class="text-link-container">
    				<?php while ( have_rows( 'text_links' ) ) : the_row(); ?>
    					<a class="text-link external-link red-text red-underline-link large-link-text" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'text' ); ?></a>
    				<?php endwhile; ?>
                </span>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
