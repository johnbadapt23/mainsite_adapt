<section class="ticker-tape background-pink">
	<div class="container">
		<div class="introduction-container">
			<h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
			<span class="tickertape-intro-text"><?php echo get_sub_field( 'text' ); ?></span>
		</div>
	</div>
	<div class="band-container-forwards">
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_left' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_left' ) ) : the_row(); ?>
					<span><span class="ticker-tag"><?php echo get_sub_field( 'text' ); ?></span></span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </span>
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_left' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_left' ) ) : the_row(); ?>
					<span><span class="ticker-tag"><?php echo get_sub_field( 'text' ); ?></span></span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </span>
    </div>
    <div class="band-container-backwards">
        <span class="moving-text">
			<?php if ( have_rows( 'ticker_tape_right' ) ) : ?>
				<?php while ( have_rows( 'ticker_tape_right' ) ) : the_row(); ?>
					<span><span class="ticker-tag"><?php echo get_sub_field( 'text' ); ?></span></span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </span>
        <span class="moving-text"><?php if ( have_rows( 'ticker_tape_right' ) ) : ?>
			<?php while ( have_rows( 'ticker_tape_right' ) ) : the_row(); ?>
				<span><span class="ticker-tag"><?php echo get_sub_field( 'text' ); ?></span></span>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?> <span><h2 class="inherit-text h1-degular"><?php echo get_sub_field( 'ticker_tape_text' ); ?></h2></span>
        </span>
    </div>
</section>
