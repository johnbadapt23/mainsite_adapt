<section class="singlePost locked">
	<span class="lockOverlay"></span>
	<div class="container">
		<div class="inner">
			<div class="fullWidth">
				<div class="left">
					<span class="excerpt"><?php the_sub_field( 'excerpt' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="registrationOverlay">
	<div class="container">
	    <div class="inner">
	        <hr class="fullwidth">
	        <div class="titleBlock">
	            <?php if ( get_field ('member_content_section_overlay_title', 'option') ) { ?>
	                <h2><?php the_field('member_content_section_overlay_title', 'option'); ?></h2>
	                <hr>
	            <?php } ?>
	            <?php if ( get_field ('member_content_section_overlay_subtitle', 'option') ) { ?>
	                <h3><?php the_field('member_content_section_overlay_subtitle', 'option'); ?></h2>
	            <?php } ?>
	            <a href="/researchadvisory" class="button">Register</a>
	            <a href="#loginform" class="loginPopupButton textLink">Login</a>
	        </div>
	    </div>
	</div>
</section>
