<?php $agendaGallery = get_sub_field( 'agenda_id' ); ?>
<section class="agenda-item" id="<?php echo get_sub_field( 'agenda_id' ); ?>">
    <div class="container">
        <div class="speaker-image-container">
            <?php if ( have_rows( 'speakers' ) ) : ?>
                <span class="speakers-images">
                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                        <?php $speaker = get_sub_field( 'speaker' ); ?>
                        <?php if ( $speaker ): ?>
                            <?php $counter=1; ?>
                            <?php foreach ( $speaker as $post ):  ?>
                                <?php setup_postdata ( $post ); ?>
                                <a class="speaker-popup" href="#<?php echo $agendaGallery; ?>speakerPopup-<?php echo $counter;?>">
                                    <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                    <span class="speaker-image">
                                        <span class="image-container">
                                            <span class="bg-container">
                                                <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                                <?php if ( $speaker_image ) { ?>
                                                	<?php echo wp_get_attachment_image( $speaker_image['ID'], 'full', false, array(
                                                		'alt'     => $speaker_image['alt'],
                                                		'loading' => false,
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
                                    </span>
                                </a>
                                <div style="display: none;">
                                    <div class="speaker-popup-container" id="<?php echo $agendaGallery; ?>speakerPopup-<?php echo $counter;?>">
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
                    <?php endwhile; ?>
                </span>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
        <div class="content-container">
            <span class="time"><?php echo get_sub_field( 'time' ); ?></span>
            <h3 class="agenda-title"><?php echo get_sub_field( 'title' ); ?></h3>
            <?php if ( have_rows( 'company' ) ) : ?>
                <span class="company-container">
                    <span class="company-with">with</span>
                    <?php while ( have_rows( 'company' ) ) : the_row(); ?>
                        <?php if (get_sub_field( 'link' )){ ?>
                            <a class="company" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo get_sub_field( 'company_name' ); ?></a>
                        <?php } else { ?>
                            <span class="company"><?php echo get_sub_field( 'company_name' ); ?></span>
                        <?php } ?>
                    <?php endwhile; ?>
                </span>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'speakers' ) ) : ?>
                <span class="speakers-title-container">
                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                        <?php $speaker = get_sub_field( 'speaker' ); ?>
                        <?php if ( $speaker ): ?>
                            <?php $counterName=1; ?>
                            <?php foreach ( $speaker as $post ):  ?>
                                <a class="speaker-popup-text" href="#<?php echo $agendaGallery; ?>speakerPopup-<?php echo $counterName;?>">
                                    <span class="speaker">
                                        <?php setup_postdata ( $post ); ?>
                                        <span class="title-container"><?php the_title(); ?></span>
                                        <span class="job-title">- <?php echo get_field( 'job_title' ); ?></span>
                                    </span>
                                </a>
                                <?php $counterName++; ?>
                            <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </span>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
            <?php if ( have_rows( 'speakers' ) ) : ?>
                <span class="speakers-images mobile">
                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                        <?php $speaker = get_sub_field( 'speaker' ); ?>
                        <?php if ( $speaker ): ?>
                            <?php $counterImage=1; ?>
                            <?php foreach ( $speaker as $post ):  ?>
                                <?php setup_postdata ( $post ); ?>
                                <a class="speaker-popup-mobile" href="#<?php echo $agendaGallery; ?>speakerPopup-<?php echo $counterImage;?>">
                                    <?php $speaker_image = get_field( 'speaker_image' ); ?>
                                    <span class="speaker-image">
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
                                    </span>
                                </a>
                                <?php $counterImage++; ?>
                            <?php endforeach; ?>
                        <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </span>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
            <span class="agenda-description">
                <span class="read-more-overlay"><span class="read-more">Read More</span></span>
                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
            </span>
        </div>
    </div>
</section>
