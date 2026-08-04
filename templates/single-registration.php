<?php

// Load field value.
$date_string = get_field('event_date');

// Create DateTime object from value (formats must match).
$date = DateTime::createFromFormat('Ymd', $date_string);

?>
<?php if ( get_field( 'hide_header_and_footer' ) == 1 ) { ?>
	<style>
		main {
			margin-top: 0px;
		}
	</style>
	<div class="landing-header edge-events-partner-landing registration-header">
		<div class="container">
			<div class="container-inner">
				<span class="logo-container">
					<?php $header_logo = get_field( 'header_logo' ); ?>
					<?php if ( $header_logo ) { ?>
						<?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array(
							'alt'     => $header_logo['alt'],
							'loading' => false,
						) ); ?>
					<?php } ?>            
				</span>
				<span class="text-container-right">
					<span class="powered-by text-grey">Powered by ADAPT ®</span>
				</span>
			</div>
		</div>
	</div>
<?php } ?>
<section class="topicBanner webinarBanner">
	<div class="imageSizeContainer">
		<div class="bgContainer">
			<?php $banner_image = get_field( 'banner_background_image' ); ?>
			<img loading="lazy" class="desktop" src="<?php echo $banner_image['url']; ?>" alt="<?php echo $banner_image['alt']; ?>"/>
			<?php if( get_field('banner_opacity_overlay') == 'no-overlay'){ ?>
			<?php } else { ?>
				<span class="opacity-overlay"></span>
			<?php }?>
		</div>
		<div class="container">
			<div class="column webinar-column first-column">
				<?php if(get_field( 'tag_text' )) { ?>
					<span class="roundtable"><?php echo get_field( 'tag_text' ); ?></span>
				<?php } else { ?>
					<span class="roundtable">Digital Roundtable</span>
				<?php }?>
				<?php if(get_field( 'banner_logo' )) { ?>
					<?php $bannerLogo = get_field('banner_logo'); ?>
					<span class="banner-icon" <?php if( get_field( 'banner_logo_height' )){ ?>style="height: <?php echo get_field( 'banner_logo_height' ); ?>px;"<?php } ?>>
						<?php echo wp_get_attachment_image( $bannerLogo['ID'], 'full', false, array(
							'alt'     => $bannerLogo['alt'],
							'loading' => 'lazy',
						) ); ?>
					</span>
				<?php } ?>
				<h1 class="text-white"><?php the_title(); ?></h1>
				<p class="text-white"><?php echo $date->format('l, j F, Y'); ?> @<?php echo get_field( 'event_start_time' ); ?></p>
			</div>
		</div>
	</div>
</section>
<?php if ( have_rows( 'takeaways' ) ) : ?>
	<?php while ( have_rows( 'takeaways' ) ) : the_row(); ?>
		<?php $extraPadding = 'no-extra-padding';?>
	<?php endwhile; ?>
<?php else : ?>
	<?php $extraPadding = 'no-padding-bottom'; ?>
<?php endif; ?>
<section class="webinar-article bg-white <?php echo $extraPadding; ?>">
	<div class="container">
		<div class="column webinar-column second-column right-column">
			<span class="register-container">
				<span class="sticky-container">
					<span class="upper-container">
						<img loading="lazy" class="calendar-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar.svg" alt=""/ width="26">
						<span class="date-title small-text-grey">Date</span>
						<span class="date text-black"><?php echo $date->format('l, j F, Y'); ?></span>
						<span class="time-title small-text-grey">Time</span>
						<span class="time text-black"><?php echo get_field( 'event_start_time' ); ?> - <?php echo get_field( 'event_end_time' ); ?></span>
						<?php if (get_field( 'time_text' )) { ?>
							<span class="time text-black"><?php echo get_field( 'time_text' ); ?></span>
						<?php } ?>
						<span class="location-title small-text-grey">Location</span>
						<?php if (get_field( 'location_text' )) { ?>
							<span class="location text-black"><?php echo get_field( 'location_text' ); ?></span>
						<?php } else { ?>
							<span class="location text-black">Digital link will be available upon registration.</span>
						<?php } ?>
						<span class="upper-bar"></span>
					</span>
					<span class="bottom-container">
						<span class="bottom-bar"></span>
						<?php if(get_field('pre_button_text')){ ?>
							<?php $preText =  get_field('pre_button_text'); ?>
						<?php } ?>
						<?php if(get_field('button_text')){ ?>
							<?php $buttonText =  get_field('button_text'); ?>
						<?php } ?>
						<?php if( get_field( 'button' ) =='register' ) { ?>
							<span class="title"><?php if($preText){ ?><?php echo $preText; ?><?php } else { ?>Register to Attend<?php } ?></span>
							<a class="registerButton register-scroll-button background-red" href="#registerForm"><?php if($buttonText){ ?><?php echo $buttonText; ?><?php } else { ?>Register<?php } ?></a>
						<?php } else { ?>
							<?php if($preText){ ?><span class="title"><?php echo $preText; ?></span><?php } ?>
							<span class="registerButton upcoming background-grey"><?php if($buttonText){ ?><?php echo $buttonText; ?><?php } else { ?>Upcoming<?php } ?></span>
						<?php } ?>
					</span>
				</span>
			</span>
		</div>
		<div class="column webinar-column first-column">
			<?php if ( have_rows( 'speakers' ) ) : ?>
				<div class="speakers-block less-margin">
					<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
						<span class="speaker-title"><?php echo get_sub_field( 'title' ); ?></span>
						<?php 
							$count = 0;
							$speakers = get_sub_field('speaker');
							if (is_array($speakers)) {
								$count = count($speakers);
							} 
						?>
						<?php if ( have_rows( 'speaker' ) ) : ?>
							<div class="speaker-container">
								<?php $counter=1; ?>								
								<?php while ( have_rows( 'speaker' ) ) : the_row(); ?>
									<?php $post_object = get_sub_field( 'speaker' ); ?>
									<?php if ( $post_object ): ?>
										<?php $post = $post_object; ?>
										<?php setup_postdata( $post ); ?>
											<a <?php if($count == 1){?>class="speaker-popup-single"<?php } else { ?>class="speaker-popup"<?php } ?>href="#speakerPopup<?php echo $counter;?>">
												<span class="speaker column one-half">
													<span class="speaker-container-inner flex-container">
														<span class="speaker-image">
															<span class="bg-image">
																<img loading="lazy" src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
															</span>
														</span>
														<span class="description">
															<span class="speaker-name"><?php echo the_title(); ?></span>
															<span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
														</span>
													</span>
												</span>
											</a>
											<div style="display: none;">
												<div class="speaker-popup-container" id="speakerPopup<?php echo $counter;?>">
													<div class="column white-bg image-column">
														<?php $speaker_image = get_field( 'speaker_image' ); ?>
														<span class="image-container">
															<span class="bg-container">
																<?php $speaker_image = get_field( 'speaker_image' ); ?>
																<?php if ( $speaker_image ) { ?>
																	<img loading="lazy" src="<?php echo $speaker_image; ?>" alt="<?php the_title(); ?>" />
																<?php } ?>
															</span>
															<span class="border-offset"></span>
														</span>
														<h3 class="title">
															<?php the_title(); ?>
															<?php if ( get_field('linked_in_url')) { ?>
																<a class="linkedin-link" href="<?php echo get_field('linked_in_url');?>" target="_blank" rel="noopener noreferrer"><img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/images/round-linkedin.svg" alt="LinkedIn" width="20"/></a>
															<?php } ?>
														</h3>
														<p class="job-title"><?php echo get_field( 'speaker_description' ); ?></p>
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
														<div class="about">
															<span class="about-title">About</span>
															<span class="about-text" id="aboutContainer"><?php echo get_field( 'speaker_details' ); ?></span>
														</div>
													</div>
												</div>
											</div>											
										<?php wp_reset_postdata(); ?>
										<?php $counter++; ?>
									<?php endif; ?>
								<?php endwhile; ?>
							</div>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
			<span class="webinar-subtitle"><?php echo get_field( 'sub_title' ); ?></span>
			<span class="webinar-content content">
				<?php echo get_field( 'content' ); ?>
			</span>
			<?php if ( have_rows( 'takeaways' ) ) : ?>
				<span class="takeaways-container">
					<?php while ( have_rows( 'takeaways' ) ) : the_row(); ?>
						<span class="webinar-subtitle"><?php echo get_sub_field( 'title' ); ?></span>
						<?php if ( have_rows( 'key_takeaways' ) ) : ?>
							<?php while ( have_rows( 'key_takeaways' ) ) : the_row(); ?>
								<span class="takeaway"><?php echo get_sub_field( 'takeaway' ); ?></span>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					<?php endwhile; ?>
				</span>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if(get_field( 'registration_form_embed' )) { ?>
	<div style="display: none;">
		<div class="hidden-fields" style="display: none;">
			<span class="hidden-name"><?php echo get_field( 'registration_form_event_name_sf' ); ?></span>
			<span class="hidden-event"><?php the_title(); ?></span>
			<span class="hidden-date"><?php echo $date->format('l, j F, Y'); ?></span>
			<span class="hidden-id"><?php echo get_field( 'registration_form_sf_id' ); ?></span>
		</div>
		<div class="webinar-register-form" id="registerForm">
			<div class="container">
				<span class="webinar-subtitle"><?php echo get_field( 'registration_form_title' ); ?></span>
				<span class="form-container"><?php echo get_field( 'registration_form_embed' ); ?></span>
			</div>
		</div>
		<?php if ( have_rows( 'registration_form_fields' ) ) : ?>
			<?php while ( have_rows( 'registration_form_fields' ) ) : the_row(); ?>
				<?php if ( get_sub_field( 'country' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					<style>
						.hs_country {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'client_communication_method' ) == 1 ) { ?>
					<span class="client-communication-title"><?php echo get_sub_field( 'client_communication_label' ); ?></span>
					<span class="client-communication-text"><?php echo get_sub_field( 'client_communication_text' ); ?></span>
				<?php } else { ?>
	   				<style>
	   					.hs_client_communication_method {
	   						display: none;
	   					}
	   				</style>
	   			<?php } ?>
				<?php if ( get_sub_field( 'attendance_preference' ) == 1 ) {

				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_attendance_preference {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'beverage_choice' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_wine_choice {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'gift_opt_in_to_the_session' ) == 1 ) { ?>
					<span class="gift-opt-in-text"><?php echo get_sub_field( 'gift_opt_in_text' ); ?></span>
				<?php } else { ?>
	   				<style>
	   					.hs_gift_opt_in {
	   						display: none;
	   					}
	   				</style>
	   			<?php } ?>
				<?php if ( get_sub_field( 'homeoffice_delivery_address' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_home_office_delivery_address {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'dietary_requirements' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_dietary_requirements_,
						 .hs_dietary_requirements {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				 <?php if ( get_sub_field( 'critical_issue' ) == 1 ) { ?>
 				 	<span class="critical-text"><?php echo get_sub_field( 'critical_issue_label' ); ?></span>
					<span class="critical-help-text"><?php echo get_sub_field('critical_issue_help_text'); ?></span>
 				 <?php } else { ?>
 					 <style>
 						 .hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable_,
						 .hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable {
 							 display: none;
 						 }
 					 </style>
 				 <?php } ?>
				<?php if ( get_sub_field( 'marketing' ) == 1 ) { ?>
					<span class="marketing-text"><?php echo get_sub_field( 'marketing_text' ); ?></span>
				<?php } else { ?>
					<style>
						.hs_single_client_opt_in {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'umbrella_opt_in' ) == 1 ) {
					?>
						<span class="umbrella-text"><?php echo get_sub_field( 'umbrella_opt_in_text' ); ?></span>
						<span class="umbrella-help-text"><?php echo get_sub_field( 'help_text' ); ?></span>
					<?php
				} else { ?>
					<style>
						.hs_client_communication_opt_in {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'newsletter_opt_in' ) == 1 ) {
					?>
					<?php if ( get_sub_field( 'newsletter_help_text' )) { ?>
						<span class="newsletter-help-text"><?php echo get_sub_field( 'newsletter_help_text' ); ?></span>
					<?php } ?>
					<?php
				} else { ?>
					<style>
						.hs_newsletter_opt_in {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'remove_lunch_option' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					<style>
						.hs_would_you_like_to_remove_lunch_ {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'invoiced_for_lunch_option' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					<style>
						.hs_would_you_like_to_be_invoiced_for_lunch_ {
							display: none;
						}
					</style>
				<?php } ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
	</div>
<?php } ?>


<?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'speaker_block' ) : ?>
			<section class="webinar-speaker-block bg-lightest">
				<div class="container">
					<span class="webinar-subtitle"><?php echo get_sub_field( 'title' ); ?></span>
					<?php $count = count(get_sub_field('speaker')); ?>
					<?php if ( have_rows( 'speaker' ) ) : ?>
						<div class="speaker-container<?php if ($count > 1){ ?> multiple-speakers<?php } ?>">
						<?php while ( have_rows( 'speaker' ) ) : the_row(); ?>
							<div class="column webinar-column<?php if ($count > 1){ ?> one-half<?php } else { ?> first-column<?php }?>">
							<?php $post_object = get_sub_field( 'speaker' ); ?>
							<?php if ( $post_object ): ?>
								<?php $post = $post_object; ?>
								<?php setup_postdata( $post ); ?>
									<div class="speaker-container-inner">
										<span class="speaker-image">
											<img loading="lazy" src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
										</span>
										<span class="description">
											<span class="speaker-name"><?php echo the_title(); ?></span>
											<span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
										</span>
										<div class="textBlock">
											<?php
												 $text = get_field('speaker_details');
												 $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
											?>
											<span class="speaker-details-excerpt registration-excerpt">
												<?php echo $trimmed_content; ?>
											</span>
											<span class="speaker-details">
												<?php echo get_field('speaker_details'); ?>	
												<span class="speaker-details-less">Less</span>											
											</span>
										</div>
									</div>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>
							</div>
						<?php endwhile; ?>
						</div>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
            </section>
		<?php elseif ( get_row_layout() == 'agenda_block' ) : ?>
			<section class="registration-agenda-block">
				<div class="container">
					<div class="column webinar-column first-column">
						<?php if ( have_rows( 'agenda' ) ) : ?>
							<div class="agenda-container">
								<?php while ( have_rows( 'agenda' ) ) : the_row(); ?>
									<span class="agenda-title">
										<?php echo get_sub_field( 'title' ); ?>
									</span>
									<?php if ( have_rows( 'agenda_item' ) ) : ?>
										<span class="agenda-item-container">
											<?php while ( have_rows( 'agenda_item' ) ) : the_row(); ?>
												<span class="agenda-item">
													<span class="time"><?php echo get_sub_field( 'time' ); ?></span>
													<span class="agenda-item-title"><?php echo get_sub_field( 'title' ); ?></span>
													<span class="agenda-item-text"><?php echo get_sub_field( 'subtitle' ); ?></span>
												</span>
											<?php endwhile; ?>
										</span>
									<?php else : ?>
										<?php // no rows found ?>
									<?php endif; ?>
									<span class="fine-print">
										<?php echo get_sub_field( 'agenda_small_print' ); ?>
									</span>
								<?php endwhile; ?>
							</div>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
					<div class="column webinar-column second-column">
						<?php if ( have_rows( 'resources' ) ) : ?>
							<span class="resources-container">
								<?php while ( have_rows( 'resources' ) ) : the_row(); ?>
									<span class="resources-title"><?php echo get_sub_field( 'title' ); ?></span>
									<?php if ( have_rows( 'resource_item' ) ) : ?>
										<?php while ( have_rows( 'resource_item' ) ) : the_row(); ?>
											<span class="resources-item">
												<span class="icon-container">
													<?php $icon = get_sub_field( 'icon' ); ?>
													<?php if ( $icon ) { ?>
														<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array(
															'alt'     => $icon['alt'],
															'loading' => 'lazy',
														) ); ?>
													<?php } ?>
												</span>
												<span class="resources-item-title"><?php echo get_sub_field( 'title' ); ?></span>
												<span class="resources-item-text"><?php echo get_sub_field( 'text' ); ?></span>
											</span>
										<?php endwhile; ?>
									<?php else : ?>
										<?php // no rows found ?>
									<?php endif; ?>
								<?php endwhile; ?>
							</span>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
			</section>
		<?php elseif ( get_row_layout() == 'two_column_block' ) : ?>
			<section class="registration-two-column-block">
				<div class="container">
					<?php $count = 0;
						$columns = get_sub_field('column');
						if (is_array($columns)) {
						  $count = count($columns);
						}
					?>
					<?php if ( have_rows( 'column' ) ) : ?>
						<?php $columnCount = 1; ?>
						<div class="column-container">
							<?php while ( have_rows( 'column' ) ) : the_row(); ?>
								<div class="column<?php if($count == '3'){?> one-third<?php } else { ?> one-half<?php } ?>">
									<?php if ($columnCount == 2){ ?>
										<?php if( get_sub_field('column_title')){ ?>
			                                <span class="sub-column-title"><?php echo get_sub_field('column_title'); ?></span>
			                            <?php } else { ?>
			                                <span class="sub-column-title">In partnership with</span>
			                            <?php } ?>			                            
			                        <?php } ?>
									<div class="logo-container" <?php if( get_sub_field( 'logo_height' )){ ?>style="height: <?php echo get_sub_field( 'logo_height' ); ?>px;"<?php } ?>>
										<?php $image_logo = get_sub_field( 'image_logo' ); ?>
										<?php if ( $image_logo ) { ?>
											<?php echo wp_get_attachment_image( $image_logo['ID'], 'full', false, array(
												'alt'     => $image_logo['alt'],
												'loading' => 'lazy',
											) ); ?>
										<?php } ?>
									</div>
									<?php if ($columnCount == 1){ ?>
			                            <div class="text-container">
			                                <?php echo get_sub_field( 'text' ); ?>
			                            </div>
			                        <?php } else { ?>
			                            <div class="text-container text-excerpt">
			                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
			                                <span class="read-more">More</span>
			                            </div>
			                        <?php } ?>
								</div>
								<?php $columnCount++; ?>
							<?php endwhile; ?>
						</div>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			</section>
		<?php elseif ( get_row_layout() == 'location_block' ) : ?>
			<?php get_template_part( 'templates/components/_location-block' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
<div class="webinar-mobile-sticky-footer">
	<span class="title">Register to Attend</span>
	<a class="registerButton register-scroll-button background-red" href="#registerForm">Register</a>
</div>
