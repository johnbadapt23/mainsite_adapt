<section class="two-column-acordion">
	<div class="container">
		<div class="title-container">
			<h2 class="text-black">
				<?php echo get_sub_field( 'title' ); ?>
			</h2>
            <span class="text text-black"><?php echo get_sub_field( 'text' ); ?></span>
		</div>
		<div class="accordion-container">
			<div class="accordion-image-column">
				<div class="accordion-image-inner">
                    <?php $image = get_sub_field( 'image' ); ?>
        			<?php if ( $image ) { ?>
        				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
        			<?php } ?>
				</div>
			</div>
			<div class="accordion-text-column">
				<?php if ( have_rows( 'accordion_item' ) ) : ?>
					<?php $counter=1;?>
					<?php while ( have_rows( 'accordion_item' ) ) : the_row(); ?>
						<span class="faq-container faq-item <?php if($counter == 1){ ?> active<?php } ?>">
							<span class="question<?php if($counter == 1){ ?> active<?php } ?>"><?php echo get_sub_field( 'title' ); ?></span>
							<span class="accordion-content" <?php if($counter == 1){ ?>style="display: block;"<?php } ?>>
                                <?php if ( have_rows( 'tags' ) ) : ?>
            						<?php while ( have_rows( 'tags' ) ) : the_row(); ?>
            							<span class="tag"><?php echo get_sub_field( 'tag_text' ); ?></span>
            						<?php endwhile; ?>
            					<?php else : ?>
            						<?php // no rows found ?>
            					<?php endif; ?>
                            </span>
						</span>
						<?php $counter++;?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
