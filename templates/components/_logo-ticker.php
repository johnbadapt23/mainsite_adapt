<section class="logo-ticker-tape <?php echo get_sub_field( 'background_colour' ); ?>">
	<div class="container">
		<div class="title-container">
			<span class="ticker-title"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
	</div>
	<div class="band-container-backwards">
		<?php
		// See _logo-ticker-v2.php's identical fix: resolve each logo's
		// attachment ID once instead of once per render loop (this ticker
		// renders the logo set twice for the marquee animation).
		$ticker_logo_ids = array();
		if ( have_rows( 'ticker_tape_logos' ) ) :
			while ( have_rows( 'ticker_tape_logos' ) ) : the_row();
				$logo = get_sub_field( 'logo' );
				$logo_id = $logo ? attachment_url_to_postid( $logo['url'] ) : 0;
				if ( $logo_id ) {
					$ticker_logo_ids[] = $logo_id;
				}
			endwhile;
		endif;
		?>
        <span class="moving-text">
			<?php foreach ( $ticker_logo_ids as $logo_id ) : ?>
				<span class="ticker-logo-container">
					<span class="bg-container">
						<?= wp_get_attachment_image($logo_id, 'adapt-optimized'); ?>
					</span>
				</span>
			<?php endforeach; ?>
        </span>
        <span class="moving-text">
			<?php foreach ( $ticker_logo_ids as $logo_id ) : ?>
				<span class="ticker-logo-container">
					<span class="bg-container">
						<?= wp_get_attachment_image($logo_id, 'adapt-optimized'); ?>
					</span>
				</span>
			<?php endforeach; ?>
        </span>
    </div>
</section>
