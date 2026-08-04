<section class="four-column-block">
    <div class="block-container">
        <div class="container">
            <div class="column one-quarter">
                <h2><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
			<?php if ( have_rows( 'content_columns' ) ) : ?>
				<?php while ( have_rows( 'content_columns' ) ) : the_row(); ?>
                    <div class="column one-quarter">
    					<?php $icon = get_sub_field( 'icon' ); ?>
                        <span class="icon-container">
        					<?php if ( $icon ) { ?>
        						<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
        							'alt'     => $icon['alt'],
        							'loading' => false,
        						) ); ?>
        					<?php } ?>
                        </span>
    					<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
    					<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                        <span class="expanding-overlay"></span>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
