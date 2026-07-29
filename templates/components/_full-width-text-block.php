<section class="fullWidthTextBlock <?php the_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="inner">
            <?php if ( get_sub_field ( 'title' ) ) { ?>
                <div class="titleBlock">
                    <h2><?php the_sub_field( 'title' ); ?></h2>
                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                        <span class="hrWrapper">
                            <hr>
                        </span>
                    <?php } else { ?>
                        <span class="hrWrapper no-margin-bottom">
                            <hr>
                        </span>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="textBlockWrapper">
                <div class="textBlock">
                    <?php the_sub_field( 'text_block' ); ?>
                </div>
                <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                    <span class="buttonWrapper">
                        <a class="logoBlockLink button" href="<?php the_sub_field( 'link_url' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
                    </span>
                <?php } ?>
            </div>

        </div>
    </div>
</section>
