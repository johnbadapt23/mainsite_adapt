<?php
// New section -- no equivalent chat/search-bar component existed
// anywhere in the theme (confirmed via repo-wide grep before building
// this), so this is hand-built markup, not adapted from an existing
// partial. The "Most Popular" list below the ask bar DOES reuse an
// existing block: templates/resources-components/_most-popular-posts.php
// (its own post_views_count WP_Query, unrelated to this layout's ACF
// fields), included further down.
?>
<section class="intelligence-hero background-white">
    <div class="container">
        <div class="hero-top">
            <h1 class="heading">
                <?php echo get_sub_field( 'heading' ); ?>
                <?php if ( get_sub_field( 'badge_text' ) ) { ?>
                    <span class="badge"><?php echo get_sub_field( 'badge_text' ); ?></span>
                <?php } ?>
            </h1>
            <?php if ( get_sub_field( 'sub_heading' ) ) { ?>
                <span class="sub-heading"><?php echo get_sub_field( 'sub_heading' ); ?></span>
            <?php } ?>

            <form class="ask-bar" action="/" method="get" role="search">
                <input
                    type="text"
                    name="s"
                    class="ask-bar-input"
                    placeholder="<?php echo esc_attr( get_sub_field( 'ask_bar_placeholder' ) ); ?>"
                    autocomplete="off"
                />
                <button type="submit" class="ask-bar-submit" aria-label="Submit">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2 8h12M8 2l6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>

            <?php if ( have_rows( 'suggested_prompts' ) ) : ?>
                <div class="suggested-prompts">
                    <?php while ( have_rows( 'suggested_prompts' ) ) : the_row(); ?>
                        <button type="button" class="suggested-prompt"><?php echo get_sub_field( 'prompt_text' ); ?></button>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>

        <div class="hero-bottom">
            <div class="side-image-column">
                <?php $image = get_sub_field( 'side_image' ); ?>
                <?php if ( $image ) { ?>
                    <span class="image-container">
                        <span class="bg-container">
                            <?php echo wp_get_attachment_image( $image['ID'], 'adapt-optimized', false, array(
                                'alt'     => $image['alt'],
                                'loading' => 'lazy',
                            ) ); ?>
                        </span>
                    </span>
                <?php } ?>
            </div>
            <div class="most-popular-column">
                <span class="most-popular-title"><?php echo get_sub_field( 'most_popular_title' ); ?></span>
                <div class="most-popular-list">
                    <?php get_template_part( 'templates/resources-components/_most-popular-posts' ); ?>
                </div>
            </div>
        </div>
    </div>
</section>
