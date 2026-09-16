<section class="logo-ticker-tape <?php echo get_sub_field( 'background_colour' ); ?>">
	<!-- <div class="container">
		<div class="title-container">
			<span class="ticker-title"><?php echo get_sub_field( 'title' ); ?></span>
		</div>
	</div> -->
	<div class="band-container-backwards">
		<?php
		// The ticker renders the same logo set twice back-to-back (two
		// .moving-text spans) so the CSS marquee animation can loop
		// seamlessly. Resolving attachment_url_to_postid( $logo['url'] )
		// inside each of the two render loops ran that lookup query twice
		// per logo per page load (confirmed via Query Monitor's Duplicate
		// Queries panel on the homepage). Resolve each logo's ID once here
		// and reuse it for both spans below -- output is unchanged.
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
