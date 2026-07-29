<section class="flip-card-module background-black">
    <div class="container">
        <div class="top-container">
            <div class="inner">
                <h2 class="white-text"><?php echo get_sub_field( 'title' ); ?></h2>
                <span class="text white-text desktop"><?php echo get_sub_field( 'sub_title' ); ?></span>
                <span class="text white-text mobile"><?php echo get_sub_field( 'sub_title_mobile' ); ?></span>
            </div>
        </div>
        <div class="flip-card-container desktop">
            <?php if ( have_rows( 'cards' ) ) : ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                    <div class="flip-card one-quarter">
                        <div class="flip-card-inner">
                            <div class="flip-card-front background-tertiary-black">
                                <div class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                        					<?php $logo = get_sub_field( 'logo' ); ?>
                        					<?php if ( $logo ) { ?>
                        						<img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="background-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $card_image = get_sub_field( 'card_image' ); ?>
                        					<?php if ( $card_image ) { ?>
                        						<img loading="lazy" src="<?php echo $card_image['url']; ?>" alt="<?php echo $card_image['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="flip-card-back background-tertiary-black">
                                <span class="v-wrap">
                                    <span class="v-box">
                                        <span class="flip-content">
                                            <span class="flip-card-title text-white labelLarge"><?php echo get_sub_field( 'card_title' ); ?></span>
                        					<?php if ( have_rows( 'tags' ) ) : ?>
                                                <span class="tag-container">
                            						<?php while ( have_rows( 'tags' ) ) : the_row(); ?>
                            							<span class="tag"><?php echo get_sub_field( 'tag' ); ?></span>
                            						<?php endwhile; ?>
                                                </span>
                        					<?php else : ?>
                        						<?php // no rows found ?>
                        					<?php endif; ?>
                                        </span>
                                    </span>
                                </span>
                                <div class="background-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $card_image = get_sub_field( 'card_image' ); ?>
                        					<?php if ( $card_image ) { ?>
                        						<img loading="lazy" src="<?php echo $card_image['url']; ?>" alt="<?php echo $card_image['alt']; ?>" />
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
            <?php if ( have_rows( 'link_card' ) ) : ?>
				<?php while ( have_rows( 'link_card' ) ) : the_row(); ?>
                    <div class="link-card one-quarter background-red">
                        <div class="link-card-inner">
        					<h4 class="text-white"><?php echo get_sub_field( 'title' ); ?></h4>
                            <span class="button-container">
                                <?php if(get_sub_field( 'button_type' ) == 'scroll-to-link') { ?>
                                    <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button white-button">
                                        <?php echo get_sub_field( 'button_text' ); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button white-button">
                                        <?php echo get_sub_field( 'button_text' ); ?>
                                    </a>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
        <div class="flip-card-container mobile">
            <?php if ( have_rows( 'cards' ) ) : ?>
                <?php $counter=1; ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
                    <div class="slide">
                        <div class="flip-card one-quarter<?php if ($counter == 1){ ?> active<?php } ?>">
                        <div class="flip-card-inner">
                            <div class="flip-card-front background-tertiary-black">
                                <div class="logo-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                        					<?php $logo = get_sub_field( 'logo' ); ?>
                        					<?php if ( $logo ) { ?>
                        						<img loading="lazy" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="background-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $card_image = get_sub_field( 'card_image' ); ?>
                        					<?php if ( $card_image ) { ?>
                        						<img loading="lazy" src="<?php echo $card_image['url']; ?>" alt="<?php echo $card_image['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="flip-card-back background-tertiary-black">
                                <span class="v-wrap">
                                    <span class="v-box">
                                        <span class="flip-content">
                                            <span class="flip-card-title text-white labelLarge"><?php echo get_sub_field( 'card_title' ); ?></span>
                        					<?php if ( have_rows( 'tags' ) ) : ?>
                                                <span class="tag-container">
                            						<?php while ( have_rows( 'tags' ) ) : the_row(); ?>
                            							<span class="tag"><?php echo get_sub_field( 'tag' ); ?></span>
                            						<?php endwhile; ?>
                                                </span>
                        					<?php else : ?>
                        						<?php // no rows found ?>
                        					<?php endif; ?>
                                        </span>
                                    </span>
                                </span>
                                <div class="background-container">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $card_image = get_sub_field( 'card_image' ); ?>
                        					<?php if ( $card_image ) { ?>
                        						<img loading="lazy" src="<?php echo $card_image['url']; ?>" alt="<?php echo $card_image['alt']; ?>" />
                        					<?php } ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <?php $counter++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
            <?php if ( have_rows( 'link_card' ) ) : ?>
				<?php while ( have_rows( 'link_card' ) ) : the_row(); ?>
                    <div class="slide">
                        <div class="link-card one-quarter background-red">
                            <div class="link-card-inner">
            					<h4 class="text-white"><?php echo get_sub_field( 'title' ); ?></h4>
                                <span class="button-container">
                                    <?php if(get_sub_field( 'button_type' ) == 'scroll-to-link') { ?>
                                        <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button white-button">
                                            <?php echo get_sub_field( 'button_text' ); ?>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button white-button">
                                            <?php echo get_sub_field( 'button_text' ); ?>
                                        </a>
                                    <?php } ?>
                                </span>
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
