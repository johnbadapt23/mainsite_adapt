<?php
/**
 * Template Name: Agenda Template
 */

get_header();
?>

<main id="main" role="main" class="agenda" data-page-id="<?= the_ID();?>"> 
    <?php
    	$bannerSlides = get_field('banner_slides');
    	$bannerButtons = get_field('banner_buttons');
    ?>
    <?php if($bannerSlides) { ?>
    	<section class="banner">
    		<ul class="slides">
    			<?php foreach($bannerSlides as $slide) { ?>
    				<li style="background-image:url(<?php echo $slide['image']; ?>);">
    					<?php if( $slide['dark_overlay'] == 'yes') { ?>
    						<span class="dark-overlay"></span>
    					<?php } ?>
    					<img src="<?php echo $slide['image']; ?>" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" alt="Adapt - <?php echo the_title(); ?>" />
    					<div class="container">
    						<div class="content">
    							<?php if($slide['inset_image']) { ?>
    								<div class="insetImage">
    									<div class="image" style="background-image:url(<?php echo $slide['inset_image']; ?>);">
    									</div>
    									<img src="<?php echo $slide['inset_image']; ?>" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" alt="Adapt - <?php echo the_title(); ?>" />
    								</div>
    							<?php } ?>
    							<?php if($slide['title']) { ?>
    								<div class="column title">
    									<span class="title"><?php echo $slide['title']; ?></span>
    								</div>
    							<?php } ?>
    							<?php if($slide['text']) { ?>
    								<div class="column text">
    									<span class="text"><?php echo $slide['text']; ?></span>
    								</div>
    							<?php } ?>
    							<?php if($slide['video']) { ?>
    								<span class="videoLink">
                                        <?php if ($slide['video'][0]['vimeo_code_popup']){ ?>
											<a href="https://vimeo.com/<?php echo $slide['video'][0]['vimeo_code_popup']; ?>" class="image popup-vimeo">
										<?php } else { ?>
											<a href="#" class="playBtn">
										<?php }?>
    										<span class="icon">
    											<img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" alt="Play Icon" width="51" />
    										</span>
    										<span class="text">
    											<span><?php if($slide['video'][0]['video_button_text']) { ?><?php echo $slide['video'][0]['video_button_text']; ?><?php } else { ?>Watch Video<?php } ?></span>
    											<span><?php echo $slide['video'][0]['duration']; ?></span>
    										</span>
    									</a>
    								</span>
    							<?php } ?>
    						</div>
    					</div>
    				</li>
    			<?php } ?>
    			<?php foreach($bannerSlides as $slide) { ?>
                    <div class="videoPlayerContainer">
		                <?php if($slide['video']) { ?>
    						<span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" width="25" alt="close" /></span>
    						<div class="videoWrapper">
    							<video width="100%" id="popupVideo" controls controlsList="nodownload">
    								<source type="video/mp4" src="<?php echo $slide['video'][0]['vimeo_code']; ?>" />
    							</video>
    						</div>
        				<?php } ?>
                    </div>
    			<?php } ?>
    		</ul>
    	</section>
    <?php } ?>

    <section class="navigation">
        <div class="container">
            <?php if( get_field('pre_event') == 'yes') {
                    if ( have_rows( 'day' ) ) : $counter = 0; ?>
                    <ul>
                        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
                            <li>
                                <a class="scroll-button" href="#day<?php echo $counter; ?>"><?php if ($counter == 0) {?>Pre Event<?php } else {?>Day <?php echo $counter; }?></a>
                            </li>
                        <?php $counter ++; endwhile;  ?>
                        <li class="register">
                            <a class="popup-modal registerInterest" href="#form">Register Interest</a>
                        </li>
                    </ul>
                    <?php endif;
                } else {
                    if ( have_rows( 'day' ) ) : $counter = 1; ?>
                    <ul>
                        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
                            <li>
                                <a class="scroll-button" href="#day<?php echo $counter; ?>">Day <?php echo $counter; ?></a>
                            </li>
                        <?php $counter ++; endwhile;  ?>
                        <li class="register">
                            <a class="popup-modal registerInterest" href="#form">Register Interest</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                <?php } ?>
        </div>
    </section>
    <section class="eventShare">
    	<div class="container">
            <div class="inner">
        		<div class="share">
        			<a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php echo the_permalink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/email.svg" alt="Share via Email" /><span>Email</span></a>
                    <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title();?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" alt="Share on LinkedIn" /><span>Share</span></a>
        		</div>
            </div>
    	</div>
    </section>

    <?php if ( have_rows( 'day' ) ) : if( get_field('pre_event') == 'yes') { $counter = 0; } else { $counter = 1; } ?>

        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
            <section id="day<?php echo $counter; ?>" class="dayWrapper day<?php echo $counter; ?> active">
                <div class="container">
                    <div class="inner">
                        <div class="titleBlock">
                            <h1 class="pageTitleLine"><?php echo the_title(); ?></h1>
                            <div class="top">
                                <span class="left">
                                    <h2><?php the_sub_field( 'title' ); ?></h2>
                                    <hr>
                                </span>
                                <span class="right">
                                    <a class="button popup-modal registerInterest" href="#form">Register Interest</a>
                                    <?php if ( get_field( 'ticket_link' )) { ?>
                                        <a class="button ticket buttonTicket" href="<?php the_field( 'ticket_link' ); ?>" target="_blank">Purchase Tickets</a>
                                    <?php } ?>
                                    <a class="button print buttonPrint" id="print" onclick="window.print()">Print Agenda</a>
                                </span>
                            </div>
                            <div class="bottom">
                                <h3><?php the_sub_field( 'date' ); ?></h3>
                        		<h4><?php the_sub_field( 'day' ); ?></h4>
                                <a class="button popup-modal mobile registerInterest" href="#form">Register Interest</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <?php if ( have_rows( 'itinerary_item' ) ) : ?>
                <section class="itineraryBlock">
					<?php while ( have_rows( 'itinerary_item' ) ) : the_row(); ?>
                        <?php if ( get_sub_field ( 'single_or_double_track' ) == 'single' ) { ?>
                            <div class="agendaBlock single<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
        						<div class="item">
        							<div class="container">
        								<div class="inner">
                                            <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                <div class="time">
                                                    <div class="v-wrap">
                                                        <div class="v-box left">
                                                           <?php the_sub_field( 'time' ); ?>
                                                       </div>
                                                    </div>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoWrapper">
                                    					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>

                                                            <span class="logoContainer">
                                                                <span class="logo" style="background-image: url(<?php the_sub_field('logo'); ?>);">
                                                                </span>
                                                            </span>

                                    					<?php endwhile; ?>
                                                    </div>
                                				<?php endif; ?>
                                                <div class="detailWrap">
                                                    <div class="v-wrap">
                                                        <div class="v-box left">
                        									<span class="title">
                        										<?php the_sub_field( 'title' ); ?>
                        									</span>
                                                            <?php if ( have_rows( 'speakers' ) ) : ?>
                                            					<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                            						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                            						<?php if ( $post_object ): ?>
                                            							<?php $post = $post_object; ?>
                                            							<?php setup_postdata( $post ); ?>
                                            								<span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                            							<?php wp_reset_postdata(); ?>
                                            						<?php endif; ?>
                                            					<?php endwhile; ?>
                                            				<?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                <div class="hidden">
                                                    <span class="detail">
                                                        <?php the_sub_field( 'detailed_text' ); ?>
                                                    </span>
                                                </div>
                                                <span class="read-more-container">
                                                    <span class="read-more">+ Read More</span>
                                                </span>
                                            <?php } ?>
        								</div>
        							</div>
        						</div>
                            </div>
                        <?php } else { ?>
                            <div class="agendaBlock double<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <?php if ( have_rows( 'double_track_details' ) ) : ?>
                                    <div class="headerBlock">
                                        <div class="container">
                                            <div class="inner">
                                    			<?php while ( have_rows( 'double_track_details' ) ) : the_row(); ?>
                                                    <div class="column">
                                                        <span class="trackTitle">
                            				                <?php the_sub_field( 'track_title' ); ?>
                                                        </span>
                                                        <span class="hrWrapper">
                                                            <hr>
                                                        </span>
                                                        <span class="facilitator">
                                            				<?php the_sub_field( 'facilitator' ); ?>
                                                        </span>
                                                        <span class="facilitatorTitle">
                                                            <?php the_sub_field( 'facilitator_title' ); ?>
                                                        </span>
                                                    </div>
                                    			<?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                        		<?php endif; ?>
                                <div class="item">
        							<div class="container">
        								<div class="inner">

                                            <div class="time">
                                                <div class="v-wrap">
                                                    <div class="v-box left">
                                                       <?php the_sub_field( 'time' ); ?>
                                                   </div>
                                               </div>
                                           </div>

                                            <div class="columnsWrapper">
                                                <div class="left">
                                                    <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">
                                                        <div class="detailWrap">
                                                            <div class="v-wrap">
                                                                <div class="v-box left">
                                									<span class="title">
                                										<?php the_sub_field( 'title' ); ?>
                                									</span>
                                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                    					<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                    						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                                    						<?php if ( $post_object ): ?>
                                                    							<?php $post = $post_object; ?>
                                                    							<?php setup_postdata( $post ); ?>
                                                                                    <span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                                    							<?php wp_reset_postdata(); ?>
                                                    						<?php endif; ?>
                                                    					<?php endwhile; ?>
                                                    				<?php endif; ?>

                                                                    <?php if ( have_rows( 'logos' ) ) : ?>
                                                                        <div class="logoWrapper">
                                                        					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                                <span class="logoContainer">
                                                                                    <span class="logo" style="background-image: url(<?php the_sub_field('logo'); ?>);">
                                                                                    </span>
                                                                                </span>
                                                        					<?php endwhile; ?>
                                                                        </div>
                                                    				<?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                        <div class="hidden">
                                                            <span class="detail">
                                                                <?php the_sub_field( 'detailed_text' ); ?>
                                                            </span>
                                                        </div>
                                                        <span class="read-more-container">
                                                            <span class="read-more">+ Read More</span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                                <div class="right">
                                                    <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                        <div class="detailWrap">
                                                            <div class="v-wrap">
                                                                <div class="v-box left">
                                									<span class="title">
                                										<?php the_sub_field( 'title_track_two' ); ?>
                                									</span>
                                                                    <?php if ( have_rows( 'speakers_track_two' ) ) : ?>
                                                    					<?php while ( have_rows( 'speakers_track_two' ) ) : the_row(); ?>
                                                    						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                                    						<?php if ( $post_object ): ?>
                                                    							<?php $post = $post_object; ?>
                                                    							<?php setup_postdata( $post ); ?>
                                                                                    <span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                                    							<?php wp_reset_postdata(); ?>
                                                    						<?php endif; ?>
                                                    					<?php endwhile; ?>
                                                    				<?php endif; ?>
                                                                    <?php if ( have_rows( 'logos_track_two' ) ) : ?>
                                                                        <div class="logoWrapper">
                                                        					<?php while ( have_rows( 'logos_track_two' ) ) : the_row(); ?>
                                                                                <span class="logoContainer">
                                                                                    <span class="logo" style="background-image: url(<?php the_sub_field('logo'); ?>);">
                                                                                    </span>
                                                                                </span>
                                                        					<?php endwhile; ?>
                                                                        </div>
                                                    				<?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                        <div class="hidden">
                                                            <span class="detail">
                                                                <?php the_sub_field( 'detailed_text_track_two' ); ?>
                                                            </span>
                                                        </div>
                                                        <span class="read-more-container">
                                                            <span class="read-more">+ Read More</span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            </div>
        								</div>
        							</div>
        						</div>
                            </div>
                        <?php } ?>
					<?php endwhile; ?>
				</section>
			<?php endif; ?>

        <?php $counter ++; endwhile;  ?>

    <?php endif; ?>
</main>

<section class="printContainer">
    <div class="imageHeader"><img src="<?php the_field( 'print_header' ); ?>" alt="Adapt - <?php echo the_title(); ?>"/></div>
    <div class="content">
    <?php if ( have_rows( 'day' ) ) : $counter = 1; ?>

        <?php while ( have_rows( 'day' ) ) : the_row(); ?>

            <div class="titleBlock">
                <div class="container">

                    <h2><?php the_sub_field( 'title' ); ?></h2>


                    <h4><?php the_sub_field( 'day' ); ?> <?php the_sub_field( 'date' ); ?></h4>

                </div>

            </div>

            <?php if ( have_rows( 'itinerary_item' ) ) : ?>

                    <?php while ( have_rows( 'itinerary_item' ) ) : the_row(); ?>
                        <?php if ( get_sub_field ( 'single_or_double_track' ) == 'single' ) { ?>
                            <div class="agendaBlock single<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <div class="item">
                                    <div class="container">
                                        <div class="wrapperPrint">
                                            <div class="leftColumn">
                                                <div class="timePrint">
                                                   <?php the_sub_field( 'time' ); ?>
                                                </div>
                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoWrapperPrint">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <img src="<?php the_sub_field('logo'); ?>" width="100" alt="Adapt" />
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="rightColumn">
                                                <div class="detailWrapPrint">
                                                    <span class="title">
                                                        <?php the_sub_field( 'title' ); ?>
                                                    </span>
                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                        <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                            <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                            <?php if ( $post_object ): ?>
                                                                <?php $post = $post_object; ?>
                                                                <?php setup_postdata( $post ); ?>
                                                                    <span class="description"><span class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                                                <?php wp_reset_postdata(); ?>
                                                            <?php endif; ?>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                    <span class="detail">
                                                        <?php the_sub_field( 'detailed_text' ); ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="agendaBlock double<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <?php if ( have_rows( 'double_track_details' ) ) : ?>
                                    <div class="headerBlock">
                                        <div class="container">

                                            <?php while ( have_rows( 'double_track_details' ) ) : the_row(); ?>
                                                <div class="column">
                                                    <span class="trackTitle">
                                                        <?php the_sub_field( 'track_title' ); ?>
                                                    </span>
                                                    <span class="hrWrapper">
                                                        <hr>
                                                    </span>
                                                    <span class="facilitator">
                                                        <?php the_sub_field( 'facilitator' ); ?>
                                                    </span>
                                                    <span class="facilitatorTitle">
                                                        <?php the_sub_field( 'facilitator_title' ); ?>
                                                    </span>
                                                </div>
                                            <?php endwhile; ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="item">
                                    <div class="container">

                                        <div class="time">
                                            <div class="v-wrap">
                                                <div class="v-box left">
                                                   <?php the_sub_field( 'time' ); ?>
                                               </div>
                                           </div>
                                       </div>

                                        <div class="columnsWrapper">
                                            <div class="left">
                                                <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">
                                                    <div class="detailWrap">
                                                        <div class="v-wrap">
                                                            <div class="v-box left">
                                                                <span class="title">
                                                                    <?php the_sub_field( 'title' ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'speakers' ) ) : ?>
                                                                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                                        <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                        <?php if ( $post_object ): ?>
                                                                            <?php $post = $post_object; ?>
                                                                            <?php setup_postdata( $post ); ?>
                                                                                <span class="description"><span class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                                                            <?php wp_reset_postdata(); ?>
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>

                                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                                    <div class="logoWrapperPrint">
                                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                            <img src="<?php the_sub_field('logo'); ?>" width="100" alt="Adapt" />
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>

                                                    <span class="detail">
                                                        <?php the_sub_field( 'detailed_text' ); ?>
                                                    </span>

                                                <?php } ?>
                                            </div>
                                            <div class="right">
                                                <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                    <div class="detailWrap">
                                                        <div class="v-wrap">
                                                            <div class="v-box left">
                                                                <span class="title">
                                                                    <?php the_sub_field( 'title_track_two' ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'speakers_track_two' ) ) : ?>
                                                                    <?php while ( have_rows( 'speakers_track_two' ) ) : the_row(); ?>
                                                                        <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                        <?php if ( $post_object ): ?>
                                                                            <?php $post = $post_object; ?>
                                                                            <?php setup_postdata( $post ); ?>
                                                                                <span class="description"><span class="speakerName"><?php the_title(); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php the_field('speaker_description'); ?></span></span>
                                                                            <?php wp_reset_postdata(); ?>
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>
                                                                <?php if ( have_rows( 'logos_track_two' ) ) : ?>
                                                                    <div class="logoWrapperPrint">
                                                                        <?php while ( have_rows( 'logos_track_two' ) ) : the_row(); ?>
                                                                            <img src="<?php the_sub_field('logo'); ?>" width="100" alt="Adapt" />
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>

                                                    <span class="detail">
                                                        <?php the_sub_field( 'detailed_text_track_two' ); ?>
                                                    </span>

                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>

            <?php endif; ?>

        <?php $counter ++; endwhile;  ?>

    <?php endif; ?>
    </div>

</section>

<div class="formPopup mfp-hide" id="form">
    <a class="popup-modal-dismiss"></a>
    <?php if ( get_field ( 'form_title') ) { ?>
        <h2><?php the_field( 'form_title'); ?></h2>
    <?php } ?>
    <?php if ( get_field ( 'form_subtitle' ) ) { ?>
        <h3><?php the_field( 'form_subtitle'); ?></h3>
    <?php } ?>
    <?php if ( get_field ( 'form_shortcode' ) ) { ?>
        <div class="formWrapper register"><?php the_field( 'form_shortcode' ); ?></div>
    <?php } ?>
</div>

<?php get_footer(); ?>
