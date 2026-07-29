<section class="navigation print-no fixed-menu <?php the_field( 'fixed_menu_background_colour' ); ?> <?php the_field( 'hide_main_menu' ); ?>">
	<div class="container">
		<ul>
			<li class="mobile"><a class="activeMenuItem"><?php if ( get_field('fixed_menu_title')){ ?><?php the_field('fixed_menu_title'); ?><?php } else {?>SECTION<?php } ?></a></li>
            <?php while ( have_rows( 'fixed_menu' ) ) : the_row(); ?>
				<li>
					<a class="scroll-button" href="#<?php the_sub_field( 'section_id' ); ?>"><?php the_sub_field( 'section_name' ); ?></a>
				</li>
			<?php endwhile; ?>
		</ul>
		<div class="fixedButtonWrapper">
			<a class="fixednav">
				<span class="ham"></span>
			</a>
		</div>
	</div>
</section>
