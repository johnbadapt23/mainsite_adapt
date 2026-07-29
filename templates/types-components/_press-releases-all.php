<?php if ( have_rows( 'publication' ) ) : ?>
	<?php while ( have_rows( 'publication' ) ) : the_row(); ?>
		<?php $shortName = get_sub_field( 'publication_short_name' ); ?>
		<?php $publishedDate = get_sub_field( 'published_date' ); ?>
	<?php endwhile; ?>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
<?php if($publishedDate){
    // Load field value.
    $date_string = $publishedDate;

    // Create DateTime object from value (formats must match).
    $date = DateTime::createFromFormat('Ymd', $date_string);
} ?>
<a href="<?php the_permalink(); ?>">
    <span class="item press-release-item external-press-release">
        <span class="container">
            <span class="item-content-container column">
                <span class="content-inner">
                    <span class="tag-container"><span class="tag">#<?php echo $shortName; ?></span></span>
                    <span class="title text-black labelXXLarge"><?php the_title(); ?></span>
                    <span class="published-date">/<?php echo $date->format('M j, Y'); ?></span>
                    <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                </span>
            </span>
            <span class="read-more-container column">
                <span class="v-wrap">
                    <span class="v-box">
                        <span class="read-more text-link text-red">Read more</span>
                    </span>
                </span>
            </span>
        </span>
    </span>
</a>
