<?php if ( have_rows( 'membership_content' ) ) : ?>
    <?php $counter = 0; ?>
        <?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
            <?php if ( $counter == 0 ) {
               $members = $members . get_sub_field( 'membership_id' );
            } else {
               $members = $members . ',' . get_sub_field( 'membership_id' );
            } ?>
            <?php $counter++; ?>
        <?php endwhile; ?>
        <?php if(current_user_can('mepr-active','memberships:' . $members)): ?>
            <section class="twoColumnCard scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
                <div class="container">
                    <div class="inner">
                        <h2 class="blockTitle"><?php the_sub_field( 'title' ); ?></h2>
                        <hr>
                        <p class="blockText"><?php the_sub_field( 'text' ); ?></p>
                    </div>
                    <?php if ( have_rows( 'card' ) ) : ?>
                        <div class="cardContainer">
                            <?php while ( have_rows( 'card' ) ) : the_row(); ?>
                                <div class="twoColumnCard">
                                    <a class="formPopupCardButton">
                                        <span class="imageContainer">
                                            <span class="image" style="background-image:url(<?php the_sub_field( 'image' ); ?>);">
                                            </span>
                                        </span>
                                    </a>
                                    <span class="textContainer">
                                        <h3 class="cardTitle"><?php the_sub_field( 'title' ); ?></h3>
                                        <span class="cardText"><?php the_sub_field( 'text' ); ?></span>
                                        <a class="button formPopupCardTextButton"><?php the_sub_field( 'button_text' ); ?></a>
                                    </span>
                                    <div class="cardPopupContainer" style="display: none;">
                                        <div class="cardPopup formPopup" id="cardFormPopup">
                                            <div class="formWrapper cardFormWrapper">
                                                <?php the_sub_field( 'form' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>


                </div>
            </section>
        <?php else : ?>
            <?php if( $members =='3829'){ ?>
            <?php } else { ?>
                <?php get_template_part( 'templates/components/_locked-content' ); ?>
            <?php } ?>
        <?php endif; ?>
<?php else: ?>
    <section class="twoColumnCard scrollPos" <?php if( get_sub_field('id')){?>id="<?php the_sub_field('id'); ?>"<?php } ?>>
        <div class="container">
            <div class="inner">
                <h2 class="blockTitle"><?php the_sub_field( 'title' ); ?></h2>
                <hr>
                <p class="blockText"><?php the_sub_field( 'text' ); ?></p>
            </div>
            <?php if ( have_rows( 'card' ) ) : ?>
                <div class="cardContainer">
                    <?php while ( have_rows( 'card' ) ) : the_row(); ?>
                        <div class="twoColumnCard">
                            <a class="formPopupCardButton">
                                <span class="imageContainer">
                                    <span class="image" style="background-image:url(<?php the_sub_field( 'image' ); ?>);">
                                    </span>
                                </span>
                            </a>
                            <span class="textContainer">
                                <h3 class="cardTitle"><?php the_sub_field( 'title' ); ?></h3>
                                <span class="cardText"><?php the_sub_field( 'text' ); ?></span>
                                <a class="button formPopupCardTextButton"><?php the_sub_field( 'button_text' ); ?></a>
                            </span>
                            <div class="cardPopupContainer" style="display: none;">
                                <div class="cardPopup formPopup" id="cardFormPopup">
                                    <div class="formWrapper cardFormWrapper">
                                        <?php the_sub_field( 'form' ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>


        </div>
    </section>

<?php endif; ?>
