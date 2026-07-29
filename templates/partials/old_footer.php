<?php
$linkedInLink = get_field( 'linked_in', 'options'  );
$youtubeLink = get_field( 'you_tube', 'options'  );
$accessLink = get_field( 'access_the_portal_link', 'options'  );
$accessLinkText = get_field( 'access_the_portal_link_text', 'options'  );
?>
<?php if ( get_field( 'hide_header_and_footer' ) == 1 ) { ?>
<?php } else { ?> 
<footer>
	<div class="container">
		<div class="footer-top">
			<div class="footer-column-container">
				<?php if ( have_rows( 'footer_columns', 'options'  ) ) : ?>
					<?php $counter=1; ?>
						<?php while ( have_rows( 'footer_columns', 'options'  ) ) : the_row(); ?>
							<div class="footer-column column one-quarter">
								<span class="footer-column-title-wrapper"><span class="footer-column-title"><?php the_sub_field( 'column_title' ); ?></span></span>
								<?php if ( have_rows( 'link' ) ) : ?>
									<span class="footer-link-container-wrapper">
										<?php while ( have_rows( 'link' ) ) : the_row(); ?>
											<span class="footer-link-container">
												<a class="footer-link" href="<?php the_sub_field( 'link' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
											</span>
										<?php endwhile; ?>
										<?php if ($counter == 2){ ?>
											<a class="footer-link portal text-red external-link" href="<?php echo $accessLink; ?>" target="_blank"><?php echo $accessLinkText; ?></a>
										<?php } ?>
										</span>
								<?php else : ?>
									<?php // no rows found ?>
								<?php endif; ?>
								<?php if ($counter == 4){ ?>
									<span class="footer-column-title follow-us-title">Follow Us</span>
									<span class="social-links">
										<?php if ($linkedInLink) {?>
											<a class="social-link linkedin" href="<?php echo $linkedInLink;?>" target="_blank">
												<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
													 viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
												<style type="text/css">
													.st0{fill:#ffffff;}
												</style>
												<path class="st0" d="M19.3,2V18c0,0.4-0.1,0.7-0.4,1c-0.3,0.3-0.6,0.4-1,0.4H2c-0.4,0-0.7-0.1-1-0.4c-0.3-0.3-0.4-0.6-0.4-1V2
													c0-0.4,0.1-0.7,0.4-1c0.3-0.3,0.6-0.4,1-0.4H18c0.4,0,0.7,0.1,1,0.4C19.2,1.3,19.3,1.7,19.3,2z M6.2,7.8H3.4v8.8h2.7V7.8z M6.4,4.8
													c0-0.2,0-0.4-0.1-0.6C6.2,4,6.1,3.8,5.9,3.7C5.8,3.5,5.6,3.4,5.4,3.3C5.2,3.2,5,3.2,4.8,3.2h0C4.4,3.2,4,3.4,3.7,3.7
													C3.4,4,3.2,4.4,3.2,4.8c0,0.4,0.2,0.8,0.5,1.1C4,6.2,4.4,6.4,4.8,6.4c0.2,0,0.4,0,0.6-0.1c0.2-0.1,0.4-0.2,0.5-0.3
													c0.2-0.1,0.3-0.3,0.4-0.5C6.4,5.2,6.4,5,6.4,4.8L6.4,4.8z M16.6,11.3c0-2.6-1.7-3.7-3.3-3.7c-0.5,0-1.1,0.1-1.6,0.3
													c-0.5,0.2-0.9,0.6-1.2,1.1h-0.1V7.8H7.8v8.8h2.7v-4.7c0-0.5,0.1-1,0.4-1.3s0.7-0.6,1.2-0.6h0.1c0.9,0,1.5,0.5,1.5,1.9v4.7h2.7
													L16.6,11.3z"/>
												</svg>

											</a>
										 <?php } ?>
										 <?php if ($youtubeLink) {?>
										  <a class="social-link youtube" href="<?php echo $youtubeLink;?>" target="_blank">
											  <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											  	 viewBox="0 0 23 15" style="enable-background:new 0 0 23 15;" xml:space="preserve">
											  <style type="text/css">
											  	.st0{fill:#ffffff;}
											  </style>
											  <path class="st0" d="M22.2,2.3c-0.1-0.4-0.4-0.9-0.7-1.2c-0.3-0.3-0.8-0.6-1.2-0.7C18.5,0,11.6,0,11.6,0C8.7,0,5.8,0.1,2.9,0.4
											  	C2.4,0.6,2,0.8,1.7,1.1C1.3,1.5,1.1,1.9,1,2.3C0.6,4,0.5,5.7,0.5,7.4c0,1.7,0.1,3.4,0.5,5.1c0.1,0.4,0.4,0.8,0.7,1.2
											  	C2,14,2.4,14.2,2.9,14.3c1.8,0.5,8.7,0.5,8.7,0.5c2.9,0,5.8-0.1,8.7-0.4c0.5-0.1,0.9-0.4,1.2-0.7c0.3-0.3,0.6-0.7,0.7-1.2
											  	c0.3-1.7,0.5-3.4,0.5-5.1C22.7,5.7,22.5,4,22.2,2.3L22.2,2.3z M9.4,10.5V4.2l5.8,3.2L9.4,10.5z"/>
											  </svg>
										  </a>
										 <?php } ?>
									</span>
								<?php } ?>
							</div>
							<?php $counter++; ?>
						<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
			<div class="footer-subscribe-container">
				<div class="subscribe-sidebar-form background-tertiary-black">
					<h5 class="labelXXLarge text-white"><?php the_field( 'title', 'options' ); ?></h5>
					<p class="text-white"><?php the_field( 'text', 'options' ); ?></p>
					<?php if ( have_rows( 'subscribe_button', 'options') ) : ?>
						<?php while ( have_rows( 'subscribe_button', 'options' ) ) : the_row(); ?>
							<a class="std-button red-outline-button button-with-arrow red-arrow-button" href="<?php the_sub_field( 'button_link' ); ?>"><?php the_sub_field( 'button_text' ); ?></a>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="logo-container">
				<?php $footer_icon = get_field( 'footer_icon', 'options'  ); ?>
				<?php if ( $footer_icon ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img class="logo" src="<?php echo $footer_icon['url']; ?>" alt="<?php echo $footer_icon['alt']; ?>" />
					</a>
				<?php } ?>
				<span class="back-to-top">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-up.svg" width="60" class="scroll-up"/>
				</span>
			</div>
			<?php if ( have_rows( 'footer_bottom', 'options' ) ) : ?>
				<?php while ( have_rows( 'footer_bottom', 'options' ) ) : the_row(); ?>
					<div class="footer-bottom-links-container">
						<div class="footer-bottom-left">
							<span class="footer-bottom-text text-medium-grey"><?php the_sub_field( 'footer_bottom_text' ); ?></span>
						</div>
						<div class="footer-bottom-right">
							<span class="copyright text-medium-grey">ADAPT &copy; <?php echo date('Y'); ?></span>
							<?php if ( have_rows( 'footer_bottom_links' ) ) : ?>
								<?php while ( have_rows( 'footer_bottom_links' ) ) : the_row(); ?>
									<a class="footer-bottom-link text-medium-grey" href="<?php the_sub_field( 'link' ); ?>" target="<?php the_sub_field( 'link_target' ); ?>"><?php the_sub_field( 'link_text' ); ?></a>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php } ?>


<span class="subscribe-form-container"><?php the_field( 'form_embed', 'options' ); ?></span>

<?php if ( get_field( 'register_form_embed' )) { ?>
	<div style="display:none;">
		<div class="popupBlockOuter" id="register">
	        <div class="requestFormContainer">
				<div class="container">
					<h2 class="form-title"></h2>
					<div class="form-container">
						<?php the_field('register_form_embed'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<?php if ( get_field('register_form', 'options')) { ?>
		<div style="display:none;">
			<div class="popupBlockOuter" id="register">
		        <div class="requestFormContainer">
					<div class="container">
						<h2 class="form-title"><?php the_field('register_form_title', 'options'); ?></h2>
						<div class="form-container">
							<?php the_field('register_form', 'options'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php }?>
