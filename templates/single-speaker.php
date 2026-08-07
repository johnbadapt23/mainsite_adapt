
<section class="speakerProfile">
    <div class="container">
        <div class="speakerLeft">
            <span class="colorBlock"></span>
            <div class="contentWrapper">
                <div class="imageContainer">
                    <div class="image" style="background-image: url(<?php echo get_field('speaker_image'); ?>)">
                    </div>
                    <?php if ( get_field ( 'linked_in_url' ) ) { ?>
                        <a class="linkedIn" href="<?php echo get_field('linked_in_url'); ?>" target="_blank" rel="noopener noreferrer"></a>
                    <?php } ?>
                </div>

                <div class="textBlock">
                    <span class="quoteBlock">
                        <?php echo get_field( 'quote_block' ); ?>
                    </span>
                    <span class="quoteAuthor">
                        <?php echo get_field( 'quote_subtext' ); ?>
                    </span>

                    <?php
						$post_tags = get_the_terms( $post->ID, 'speaker-tag');
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
                </div>
            </div>
        </div>
        <div class="speakerRight">
            <h1 class="title"><?php echo the_title(); ?></h1>
            <h3 role="heading" aria-level="2" class="subtitle"><?php echo get_field('speaker_description'); ?></h3>
            <hr>
            <?php if ( get_field ( 'logo' ) ) { ?>
                <div class="logoWrapper">
                    <div class="logoContainer">
                        <div class="logo" style="background-image: url(<?php echo get_field('logo'); ?>);">
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="textBlock black-text">
                <?php echo get_field('speaker_details'); ?>
            </div>
        </div>
    </div>
</section>
