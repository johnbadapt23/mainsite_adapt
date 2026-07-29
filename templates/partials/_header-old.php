<header class="header clear" role="banner">
	<span class="logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php the_field('logo','options'); ?>" width="<?php the_field('logo_width','options'); ?>" alt="Adapt" />
		</a>
	</span>
	<div class="buttonWrapper">
		<a class="nav">
			<span class="ham"></span>
		</a>
	</div>
	<span class="desktopNav">
		<?php theme_nav('main'); ?>
		<?php if(current_user_can('mepr-active')) { ?>
			<span class="log-out-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>account">Members Area</a></span>
		<?php } ?>
	</span>
	<nav class="mobileMenu">
		<div class="mobileMenuItems">
			<span class="mobileLogo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php the_field('mobile_logo','options'); ?>" alt="Adapt" />
				</a>
			</span>
			<span class="menuTop">
				<?php theme_nav('main'); ?>
				<?php if(current_user_can('mepr-active')) { ?>
					<span class="log-out-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>account">Members Area</a></span>
				<?php } ?>
				<span class="social">
					<?php if(get_field('linkedin_url','options')) { ?>
						<a href="<?php the_field('linkedin_url','options') ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin.svg" alt="LinkedIn" width="25" />
						</a>
					<?php } ?>
					<?php if(get_field('youtube_url','options')) { ?>
						<a href="<?php the_field('youtube_url','options') ?>" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.svg" alt="YouTube" width="25" />
						</a>
					<?php } ?>
				</span>
			</span>
		</div>
	</nav>
</header>

<?php if ( is_page_template( 'templates/template-insights.php' ) || is_page_template ( 'templates/template-events.php' ) ||  is_page_template( 'templates/template-agenda.php' ) ) { ?>
	<div class="formPopup mfp-hide" id="form">
		<a class="popup-modal-dismiss"></a>
		<?php if ( get_field ( 'form_title', 'option' ) ) { ?>
			<h2><?php the_field( 'form_title', 'option' ); ?></h2>
		<?php } ?>
		<?php if ( get_field ( 'form_subtitle', 'option' ) ) { ?>
			<h3><?php the_field( 'form_subtitle', 'option' ); ?></h3>
		<?php } ?>
		<?php if ( get_field ( 'form_shortcode', 'option' ) ) { ?>
			<div class="formWrapper register"><?php the_field( 'form_shortcode', 'option' ); ?></div>
		<?php } ?>
	</div>
<?php } ?>

<?php if( get_field('members_login_form', 'option')){ ?>
	<div class="loginFormPopup mfp-hide" id="loginform">
		<a class="popup-modal-dismiss"></a>
		<h2><?php the_field('members_form_title', 'option'); ?></h2>
		<div class="loginFormContainer">
			<?php the_field('members_login_form', 'option'); ?>
		</div>
	</div>
<?php } ?>
