<section class="speakers-block speakers-block-module">
    <div class="container">
        <div class="speakers-top">
            <h2><?php echo get_sub_field( 'title' ); ?></h2>
            <p class="speaker-sub-title"><?php echo get_sub_field( 'subtitle' ); ?></p>
            <a class="white-button std-button speaker-view-all" href="<?php echo esc_url( home_url( '/' ) ); ?>speakers">View All Speakers</a>
        </div>
        <div class="speakers-bottom mobile-slider">
			<?php $speakers = get_sub_field( 'speakers' ); ?>
			<?php if ( $speakers ): ?>
                <?php $counter=1; ?>
				<?php foreach ( $speakers as $post ):  ?>
					<?php setup_postdata ( $post ); ?>
						<a class="speaker-popup" href="#speakerPopup-<?php echo $counter;?>">
                            <span class="speaker one-quarter">
                                <span class="image-container">
                                    <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                    <span class="bg-container<?php if ( $speaker_image ) { ?><?php } else { ?> no-background<?php } ?>">
                                        <?php if ( $speaker_image ) { ?>
                                            <?php echo wp_get_attachment_image( $speaker_image['ID'], 'full', false, array(
                                                'alt'     => $speaker_image['alt'],
                                                'loading' => false,
                                            ) ); ?>
                                            <span class="speaker-opacity"></span>
                                        <?php } else { ?>
                                            <?php $generic_headshot = get_field( 'generic_headshot', 'options' ); ?>
                                            <?php if ( $generic_headshot ) { ?>
                                                <?php echo wp_get_attachment_image( $generic_headshot['ID'], 'full', false, array(
                                                    'alt'     => $generic_headshot['alt'],
                                                    'loading' => 'lazy',
                                                ) ); ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </span>
                                    <span class="border-offset"></span>
                                    <?php
                                        $title = get_the_title();
                                        $titleWords = explode(" ", $title);
                                    ?>
                                    <span class="title-container"><?php echo $titleWords[0];?></br><?php echo $titleWords[1];?> <?php echo $titleWords[2];?><?php if( $titleWords[3]){ ?> <?php echo $titleWords[3];?><?php } ?> <?php if( $titleWords[4]){ ?> <?php echo $titleWords[4];?><?php } ?></span>
                                </span>
                                <span class="job-title"><?php echo get_field( 'job_title' ); ?></span>
                            </span>
                        </a>
                        <div style="display: none;">
                            <div class="speaker-popup-container" id="speakerPopup-<?php echo $counter;?>">
                                <div class="column white-bg image-column">
                                    <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                            <?php if ( $speaker_image ) { ?>
                                            	<?php echo wp_get_attachment_image( $speaker_image['ID'], 'full', false, array(
                                            		'alt'     => $speaker_image['alt'],
                                            		'loading' => 'lazy',
                                            	) ); ?>
                                            <?php } else { ?>
                                                <?php $generic_headshot = get_field( 'generic_headshot', 'options' ); ?>
                                                <?php if ( $generic_headshot ) { ?>
                                                	<?php echo wp_get_attachment_image( $generic_headshot['ID'], 'full', false, array(
                                                		'alt'     => $generic_headshot['alt'],
                                                		'loading' => 'lazy',
                                                	) ); ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </span>
                                        <span class="border-offset"></span>
                                    </span>
                                    <h3 class="title">
                                        <?php the_title(); ?>
                                        <?php if ( get_field('linkedin')) { ?>
                                            <a class="linkedin-link" href="<?php echo get_field('linkedin');?>" target="_blank" rel="noopener noreferrer"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/round-linkedin.svg" alt="LinkedIn" width="20"/></a>
                                        <?php } ?>
                                    </h3>
                                    <p class="job-title"><?php echo get_field( 'job_title' ); ?></p>
                                    <?php $company_logo = get_field( 'company_logo' ); ?>
                                    <?php if ( $company_logo ) { ?>
                                        <span class="company-logo">
                                	       <?php echo wp_get_attachment_image( $company_logo['ID'], 'full', false, array(
                                	           'alt'     => $company_logo['alt'],
                                	           'loading' => 'lazy',
                                	       ) ); ?>
                                       </span>
                                    <?php } ?>
                                </div>
                                <div class="column dark-bg about-column">
                                    <?php if ( have_rows( 'agenda_items' ) ) : ?>
                                        <div class="agenda-items">
                                            <span class="agenda-content-title">Speaking</span>
                                            <?php while ( have_rows( 'agenda_items' ) ) : the_row(); ?>
                                                <a class="agenda-item" href="<?php echo esc_url( home_url( '/' ) ); ?>agenda#<?php echo get_sub_field( 'agenda_link_id' ); ?>" targt="_self">
                                                    <span class="time"><?php echo get_sub_field( 'time' ); ?></span>
                                                    <span class="agenda-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                </a>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <div class="about">
                                        <span class="about-title">About</span>
                                        <span class="about-text" id="aboutContainer"><?php echo get_field( 'about' ); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $counter++; ?>
				<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
        </div>
    </div>
</section>
