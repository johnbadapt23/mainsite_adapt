<section class="logo-ticker-tape <?php echo get_sub_field( 'background_colour' ); ?>">
	<!-- <div class="container">
		<div class="title-container">
			<span class="ticker-title"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
	</div> -->
	<div class="band-container-backwards">
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_logos' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_logos' ) ) : the_row(); ?>
					<?php 
					$logo = get_sub_field( 'logo' ); 
					$logo_id = attachment_url_to_postid( $logo['url'] );
					?>
					<?php if ( $logo_id ) { ?>
						<span class="ticker-logo-container">
							<span class="bg-container">
								<?= wp_get_attachment_image($logo_id, 'full'); ?>
							</span>
						</span>
					<?php } ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </span>
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_logos' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_logos' ) ) : the_row(); ?>
					<?php 
					$logo = get_sub_field( 'logo' ); 
					$logo_id = attachment_url_to_postid( $logo['url'] );
					?>
					<?php if ( $logo_id ) { ?>
						<span class="ticker-logo-container">
							<span class="bg-container">
								<?= wp_get_attachment_image($logo_id, 'full'); ?>
							</span>
						</span>
					<?php } ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </span>
    </div>
</section>
