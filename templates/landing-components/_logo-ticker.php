<section class="logo-ticker-tape background-white">
	<div class="band-container-backwards">
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_logos' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_logos' ) ) : the_row(); ?>
					<?php $logo = get_sub_field( 'logo' ); ?>
					<?php if ( $logo ) { ?>
						<span class="ticker-logo-container">
							<span class="bg-container">
								<img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
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
					<?php $logo = get_sub_field( 'logo' ); ?>
					<?php if ( $logo ) { ?>
						<span class="ticker-logo-container">
							<span class="bg-container">
								<img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
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
