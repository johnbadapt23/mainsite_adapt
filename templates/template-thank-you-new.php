<?php
/**
 * Template Name: Thank You New Template
 */

get_header();

?>

<main class="page flexible thank-you-page" id="main">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'thank_you_introduction' ) : ?>
            <?php get_template_part( 'templates/thank-you-components/_thank-you-intro' ); ?>
        <?php elseif ( get_row_layout() == 'two_column_image_and_list' ) : ?>
            <?php get_template_part( 'templates/thank-you-components/_two-column' ); ?>
        <?php elseif ( get_row_layout() == 'next_steps' ) : ?>
            <?php get_template_part( 'templates/thank-you-components/_next-steps' ); ?>
        <?php elseif ( get_row_layout() == 'featured_posts' ) : ?>
            <?php get_template_part( 'templates/thank-you-components/_featured-posts' ); ?>
        <?php elseif ( get_row_layout() == 'intro_video_block' ) : ?>
            <?php get_template_part( 'templates/landing-components/_intro-video-block' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
</main>
<?php get_footer(); ?>
