
<section class="blogWrapper post-insights gridModule">
    <div class="container">
        <h2 class="relatedTitle"><?php the_sub_field( 'block_title' ); ?></h2>

        <?php if ( have_rows( 'related_articles' ) ) : ?>

            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
                    <?php $post_object = get_sub_field( 'article' ); ?>
                    <?php if ( $post_object ): ?>
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>

                        <?php if ( get_field ( 'member_content' ) == 'yes' ) { ?>
                            <?php if(current_user_can('mepr_auth')) {?>
                                <span class="postLink layout<?php echo $counter; ?>">
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <a href="<?php the_permalink(); ?>" class="imageContainer">
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                </div>
                                            <?php } else { ?>
                                                <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                </div>
                                            <?php } ?>
                                        </a>
                                        <span class="blogText">
                                            <span class="postDetails">
                                                <span class="info">
                                                    <span class="date">
                                                        <?php echo get_the_date('d.m.Y'); ?>
                                                    </span>
                                                    <span class="readTime">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                            <span class="excerpt">
                                                <?php echo the_excerpt(); ?>
                                            </span>

                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } else { ?>
                                <span href="<?php the_permalink(); ?>" class="postLink layout<?php echo $counter; ?> memberContentLock" target="_self">
                                    <span class="overlay">
                                        <span class="exclusiveContent">
                                            <span class="overlayText"><?php the_field('member_content_post_overlay_text', 'option'); ?></span>
                                            <span class="registerLogin">
                                                <a class="registerLink" href="/members">Register</a>
                                                <span>or</span>
                                                <a class="loginLink" href="/members-login">Login</a>
                                            </span>
                                        </span>
                                    </span>
                                    <div class="linkWrapper">
                                        <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                            <span class="podcast"></span>
                                        <?php } ?>
                                        <div class="imageContainer">
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                                </a>
                                            <?php } ?>
                                        </div>
                                        <span class="blogText">
                                            <span class="postDetails">
                                                <span class="info">
                                                    <span class="date">
                                                        <?php echo get_the_date('d.m.Y'); ?>
                                                    </span>
                                                    <span class="readTime">
                                                        <?php the_field( 'read_time' ); ?>
                                                    </span>
                                                </span>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                            <span class="excerpt">
                                                <?php echo the_excerpt(); ?>
                                            </span>

                                            <?php
                                                $post_tags = get_the_tags();
                                            ?>

                                            <?php if ( $post_tags ) { ?>
                                                <div class="tags">
                                                    <?php foreach( $post_tags as $tag ) { ?>
                                                        <span>
                                                            <?php echo '#' . $tag->name  ; ?>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </span>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="postLink layout<?php echo $counter; ?>">

                                <div class="linkWrapper">
                                    <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                        <span class="podcast"></span>
                                    <?php } ?>
                                    <a href="<?php the_permalink(); ?>" class="imageContainer">
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'video_poster' ); ?>');">
                                            </div>
                                        <?php } else { ?>
                                            <div class="image" style="background-image: url('<?php the_field( 'featured_image' ); ?>');">
                                            </div>
                                        <?php } ?>
                                    </a>
                                    <span class="blogText">
                                        <span class="postDetails">
                                            <span class="info">
                                                <span class="date">
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                </span>
                                                <span class="readTime">
                                                    <?php the_field( 'read_time' ); ?>
                                                </span>
                                            </span>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                        <span class="excerpt">
                                            <?php echo the_excerpt(); ?>
                                        </span>

                                        <?php
                                            $post_tags = get_the_tags();
                                        ?>

                                        <?php if ( $post_tags ) { ?>
                                            <div class="tags">
                                                <?php foreach( $post_tags as $tag ) { ?>
                                                    <span>
                                                        <?php echo '#' . $tag->name  ; ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </span>
                                </div>
                            </span>
                        <?php } ?>

                        <?php wp_reset_postdata(); ?>

                    <?php endif; ?>
                    <?php $counter++; ?>
                <?php endwhile; ?>

            </div>
        <?php endif; ?>

        <?php if ( have_rows( 'button_block' ) ) : ?>
            <div class="buttonBlock">
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <a href="<?php the_sub_field('link_url'); ?>" class="button" target="<?php the_sub_field('link_target'); ?>"><?php the_sub_field('link_text'); ?></a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
