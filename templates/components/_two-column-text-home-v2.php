<?php if( get_sub_field('background_image') ) : ?>
<style>
#home-two-column-text-<?= get_row_index(); ?>{
    position: relative;
    padding: 120px 0;
}
#home-two-column-text-<?= get_row_index(); ?> + section.video-module{
    display: none;
}
#home-two-column-text-<?= get_row_index(); ?>:before{
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, #121212 23.91%, rgba(18, 18, 18, 0.4) 117.72%);
}
#home-two-column-text-<?= get_row_index(); ?> .container{
    position: relative;
    z-index: 1;
}
#home-two-column-text-<?= get_row_index(); ?> .column-container .column.body-text{
    margin-top: 518px;
    position: relative;
    padding-left: 0;
}
#home-two-column-text-3 .column-container .column.body-text:before {
    background: radial-gradient(rgba(0, 0, 0, .8), rgba(0, 0, 0, .8) 30%, transparent, transparent);
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: calc(100% + 250px);
    height: 368px;
    z-index: -1;
    border-radius: 100%;
    transform: translateY(-50%) translateX(-50%);
}

@media (min-width: 1025px){
    #home-two-column-text-<?= get_row_index(); ?> .column-container .column.h4-style{
        width: 62%;
    }
    #home-two-column-text-<?= get_row_index(); ?> .column-container .column.body-text{
        width: 38%;
    }
}
@media (max-width: 1024px){
    #home-two-column-text-<?= get_row_index(); ?> .column-container .column.body-text {
        margin-top: 50px;
    }
    #home-two-column-text-<?= get_row_index(); ?> .column-container .column.body-text:before {
        background: radial-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .5) 40%, transparent, transparent);
    }
}
</style>
<?php endif; ?>




<section id="home-two-column-text-<?= get_row_index(); ?>" class="flex-two-column-text home-two-column-text <?php echo get_sub_field( 'background_colour' ); ?>" style="background-image: url(<?= get_sub_field('background_image')['url']; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">
    <div class="container">
        <?php if (get_sub_field( 'title' )) { ?>
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
        <?php } ?>
            <div class="column-container">
                <?php if ( have_rows( 'column' ) ) : ?>
    				<?php while ( have_rows( 'column' ) ) : the_row(); ?>
                        <div class="column one-half <?php echo get_sub_field( 'text_size' ); ?>">
                            <span class="text <?php echo get_sub_field( 'text_size' ); ?>"><?php echo get_sub_field( 'text' ); ?></span>
                            <?php if ( have_rows( 'link' ) ) : ?>
                                <span class="link-container">
            						<?php while ( have_rows( 'link' ) ) : the_row(); ?>
            							<a class="text-link large-link-text red-underline-link red-text red-arrow" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
            						<?php endwhile; ?>
                                </span>
        					<?php else : ?>
        						<?php // no rows found ?>
					<?php endif; ?>
                        </div>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </div>
        </div>
    </div>
</section>
