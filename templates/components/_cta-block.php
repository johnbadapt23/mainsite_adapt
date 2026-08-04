<section class="cta-block"<?php if (get_sub_field( 'id' )) { ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="cta-content">
            <span class="offset-container"></span>
            <div class="cta-inner">
        		<span class="cta-title"><?php echo get_sub_field( 'title' ); ?></span>
        		<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                <?php $linkType = get_sub_field( 'popup_form_or_link' ); ?>
        		<?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container">
            			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if ($linkType == 'link'){ ?>
                                <a class="std-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                            <?php } else { ?>
                                <a class="std-button form-popup-button" href="#formPopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                            <?php }?>
            			<?php endwhile; ?>
                    </span>
        		<?php else : ?>
        			<?php // no rows found ?>
        		<?php endif; ?>
                <div class="bottom-image-container">
            		<?php $image = get_sub_field( 'image' ); ?>
            		<?php if ( $image ) { ?>
            			<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
            				'alt'     => $image['alt'],
            				'loading' => false,
            			) ); ?>
            		<?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ( get_sub_field('form_embed')) { ?>
	<div style="display:none;">
		<div class="popupBlockOuter" id="formPopup">
	        <div class="requestFormContainer">
				<div class="container">
					<h2 class="form-title"><?php echo get_sub_field('form_title', 'options'); ?></h2>
					<div class="form-container">
						<?php echo get_sub_field('form_embed'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
