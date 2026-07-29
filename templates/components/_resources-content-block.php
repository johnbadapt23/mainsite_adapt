<section class="resources-content" id="<?php echo get_sub_field( 'id' ); ?>">
    <div class="container">
        <div class="column resources-column second-column right-column">
            <span class="resources-subtitle speaker-subtitle"><?php echo get_sub_field( 'speakers_column_title' ); ?></span>
			<?php $speakers = get_sub_field( 'speakers' ); ?>
			<?php if ( $speakers ): ?>
				<?php foreach ( $speakers as $post ):  ?>
					<?php setup_postdata ( $post ); ?>
                    <span class="speaker">
                        <span class="authorSingle">
                            <span class="authorImage" style="background-image: url(<?php echo get_field( 'speaker_image' ); ?>);">
                                <img class="delete-no" style="display: none;" src="<?php echo get_field( 'speaker_image' ); ?>" alt=""/>
                            </span>
                            <span class="authorText">
                                <span class="authorName">
                                    <?php the_title(); ?>
                                </span>
                                <span class="authorDescription">
                                    <?php echo get_field( 'speaker_description' ); ?>
                                </span>
                            </span>
                        </span>
                    </span>
				<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
        </div>
        <div class="column resources-column first-column">
			<span class="resources-subtitle"><?php echo get_sub_field( 'content_subtitle' ); ?></span>
			<span class="resources-content content">
				<?php echo get_sub_field( 'content' ); ?>
			</span>
        </div>
    </div>
</section>
