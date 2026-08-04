<section class="default">
    <div class="container">
        <div class="not-found-inner">
            <span class="pre-title-text not-found-text text-black">404</span>
            <span class="not-found-title h1-style"><?php echo get_field( '404_title', 'options' ); ?></span>
            <span class="not-found-image-container">
                <?php $image = get_field( '404_image', 'options' ); ?>
                <?php if ($image ) { ?>
                	<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array(
                		'alt'     => $image['alt'],
                		'loading' => false,
                	) ); ?>
                <?php } ?>
            </span>
            <span class="not-found-button-container button-container">
                <a class="std-button red-button" href="/">Back to Homepage</a>
            </span>
            <span class="pre-link-text not-found-text text-black"><?php echo get_field( '404_pre_link_text', 'options' ); ?></span>
            <?php if ( have_rows( '404_links', 'options' ) ) : ?>
                <span class="not-found-link-container">
                	<?php while ( have_rows( '404_links', 'options' ) ) : the_row(); ?>
                		<a class="text-red text-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self"><?php echo get_sub_field( 'link_text' ); ?></a>
                	<?php endwhile; ?>
                </span>
            <?php else : ?>
            	<?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
