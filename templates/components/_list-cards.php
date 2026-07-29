<section class="list-card-module background-black" id="<?php echo get_sub_field('id');?>">
    <div class="container">
        <div class="top-container">
            <div class="inner">
                <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
        </div>
        <div class="list-card-container">
            <?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                    <div class="list-card one-third">
                        <div class="list-card-inner">
                            <div class="list-card-front background-tertiary-black">
                                <div class="title-container">
                                    <h4 class="white-text"><?php echo get_sub_field( 'card_title' ); ?></h4>
                                </div>
                                <?php if ( have_rows( 'card_list' ) ) : ?>
                                    <div class="hover-list-container">
                                        <div class="list-container">
                    						<?php while ( have_rows( 'card_list' ) ) : the_row(); ?>
                    							<span class="list-item"><?php echo get_sub_field( 'list_text' ); ?></span>
                    						<?php endwhile; ?>
                                        </div>
                                        <?php if ( have_rows( 'links' ) ) : ?>
                                            <span class="text-link-container">
                                                <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                                    <a class="text-link large-link-text red-text red-underline-link <?php if(get_sub_field( 'link_target' ) == '_blank') { ?>external-link<?php } else { ?>red-arrow<?php  }?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                <?php endwhile; ?>
                                            </span>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
            					<?php else : ?>
            						<?php // no rows found ?>
            					<?php endif; ?>
                                <div class="background-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $card_image = get_sub_field( 'card_image' ); ?>
                        					<?php if ( $card_image ) { ?>
                        						<img src="<?php echo $card_image['url']; ?>" alt="<?php echo $card_image['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
