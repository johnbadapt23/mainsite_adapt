<section class="two-column-subscribe">
    <div class="container">
        <span class="title-container">
            <h2 class="title text-black"><?php echo get_sub_field( 'title' ); ?></h2>
        </span>
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-half <?php echo get_sub_field( 'background_colour' ); ?>">
                        <span class="top-content">
                            <span class="text-content">
                                <span class="pre-title"><?php echo get_sub_field( 'pre_title' ); ?></span>
            					<h4 role="heading" aria-level="3" class="black-text"><?php echo get_sub_field( 'title' ); ?></h4>
                            </span>
        					<?php $image = get_sub_field( 'image' ); ?>
                            <span class="card-image-container">
                                <span class="image-container">
                                    <span class="bg-container contained-image">
                                        <?php if ( $image ) { ?>
                    						<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                    							'alt'     => $image['alt'],
                    							'loading' => false,
                    						) ); ?>
                    					<?php } ?>
                                    </span>
                                </span>
                            </span>
                        </span>
                        <span class="text-tags-container">
                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                            <?php if ( have_rows( 'tags' ) ) : ?>
                                <span class="tags-container">
            						<?php while ( have_rows( 'tags' ) ) : the_row(); ?>
            							<span class="tag"><?php echo get_sub_field( 'tag' ); ?></span>
            						<?php endwhile; ?>
                                </span>
        					<?php else : ?>
        						<?php // no rows found ?>
        					<?php endif; ?>
                        </span>
    					<span class="form-container">
                            <span class="form-embed"><?php echo get_sub_field( 'form_embed' ); ?></span>
                            <span class="form-popup-button-container"><?php echo get_sub_field( 'form_button_code' ); ?></span>
                        </span>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
