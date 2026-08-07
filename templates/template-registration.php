<section class="topicBanner webinarBanner webinarListingBanner">
	<div class="imageSizeContainer">
		<div class="bgContainer bg-black">

		</div>
		<div class="container">
			<div class="column webinar-column first-column">
				<span class="bannerBreadcrumbs">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb">Analyst Market Briefings</span></a>
                </span>
				<h1 class="text-white">Analyst Market Briefings</h1>
				<p class="text-white"><?php echo get_field( 'webinar_listing_banner_subtitle', 'option' ); ?></p>
			</div>
		</div>
	</div>
</section>
<section class="register-listing">
	<div class="container">
		<div class="register-filter">
			<span class="register-toggle all-button active">All</span>
			<span class="register-toggle upcoming-toggle-button">Upcoming</span>
			<span class="register-toggle past-button">Past Sessions</span>
		</div>
		<div class="register-listing-container upcoming active">
			<?php
			$today = date('Ymd');
			$args = array(
				'post_type' => 'registration',
				'meta_key'  => 'event_date',
			    'orderby'   => 'meta_value_num',
			    'order'     => 'ASC',
				'meta_query' => array(
					array(
						'key'     => 'event_date',
						'compare' => '>=',
						'value'   => $today,
					),
					array(
						'key'     => 'button',
						'compare' => '==',
						'value'   => 'register',
					),
				),
			);
			?>
			<div class="upcoming-listing">
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php if ( have_rows( 'membership_ids' ) ) : ?>
						        <?php $counter = 0; ?>
						        <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
						            <?php if ( $counter == 0 ) {
						                $members = $members . get_sub_field( 'membership_id' );
						            } else {
						                $members = $members . ',' . get_sub_field( 'membership_id' );
						            } ?>
						           <?php $counter++; ?>
						       <?php endwhile; ?>
						   <?php endif; ?>
						   <?php if(current_user_can('mepr-active','memberships:' . $members)) { ?>
							   <?php
							   $date_string = get_field('event_date');
							   $date = DateTime::createFromFormat('Ymd', $date_string);

							   ?>
							   <div class="item one-third">
								   <a class="item-link" href="<?php the_permalink(); ?>" target="_self">
									   <span class="item-container">
										   <span class="item-top">
											   <span class="upcoming-button">Upcoming</span>
											   <span class="listing-date text-red"><?php echo $date->format('l, j F, Y'); ?> @<?php echo get_field( 'event_start_time' ); ?></span>
											   <h3 role="heading" aria-level="2" class="item-title text-black"><?php the_title(); ?></h3>
											   <?php
											   		$text = get_field( 'sub_title' );
											   		$trimmed_content = wp_trim_words( $text, $num_words = 22, $more = null );
											   ?>
											   <span class="text-dark item-excerpt"><?php echo $trimmed_content; ?></span>
										   </span>
										   <span class="bottom-bar">
					   							<span class="registerButton background-red" href="#">Register</span>
										   </span>
									   </span>
								   </a>
							   </div>


						<?php } ?>
						<?php unset($members); ?>
					<?php endwhile; ?>
				<?php endif;
				wp_reset_postdata();
				?>
				<?php
				$today = date('Ymd');
				$args = array(
					'post_type' => 'registration',
					'meta_key'  => 'event_date',
				    'orderby'   => 'meta_value_num',
				    'order'     => 'ASC',
					'meta_query' => array(
						array(
							'key'     => 'event_date',
							'compare' => '>=',
							'value'   => $today,
						),
						array(
							'key'     => 'button',
							'compare' => '==',
							'value'   => 'upcoming',
						),
					),
				);
				?>
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php if ( have_rows( 'membership_ids' ) ) : ?>
						        <?php $counter = 0; ?>
						        <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
						            <?php if ( $counter == 0 ) {
						                $members = $members . get_sub_field( 'membership_id' );
						            } else {
						                $members = $members . ',' . get_sub_field( 'membership_id' );
						            } ?>
						           <?php $counter++; ?>
						       <?php endwhile; ?>
						   <?php endif; ?>
						   <?php if(current_user_can('mepr-active','memberships:' . $members)) { ?>
							   <?php
							   $date_string = get_field('event_date');
							   $date = DateTime::createFromFormat('Ymd', $date_string);

							   ?>
							   <div class="item one-third">
								   <span class="item-link">
									   <span class="item-container">
										   <span class="item-top">
											   <span class="upcoming-button">Upcoming</span>
											   <span class="listing-date text-dark"><?php echo $date->format('l, j F, Y'); ?></span>
											   <h3 class="item-title text-black"><?php the_title(); ?></h3>
											   <?php
											   		$text = get_field( 'sub_title' );
											   		$trimmed_content = wp_trim_words( $text, $num_words = 22, $more = null );
											   ?>
											   <span class="text-dark item-excerpt"><?php echo $trimmed_content; ?></span>
										   </span>
										   <span class="bottom-bar">
					   							<span class="registerButton background-grey" href="#">Coming Soon</span>
										   </span>
									   </span>
								   </span>
							   </div>
						<?php } ?>
						<?php unset($members); ?>
					<?php endwhile; ?>
				<?php endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<div class="register-listing-container past-sessions active">
			<?php
			$today = date('Ymd');
			$args = array(
				'post_type' => 'post',
				'meta_key'  => 'replay_event_date',
			    'orderby'   => 'meta_value_num',
			    'order'     => 'ASC',
				'tax_query' => array(
			        'relation' => 'AND',
					array (
	                    'taxonomy' => 'filter-types',
	                    'field' => 'slug',
	                    'terms' => 'workshop-recordings',
	                    'operator' => 'IN',
	                ),
				),
				'meta_query' => array(
					array(
						'key'     => 'replay_event_date',
						'compare' => '<=',
						'value'   => $today,
					),
				),
			);
			?>
			<div class="upcoming-listing">
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php if ( have_rows( 'membership_ids' ) ) : ?>
						        <?php $counter = 0; ?>
						        <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
						            <?php if ( $counter == 0 ) {
						                $members = $members . get_sub_field( 'membership_id' );
						            } else {
						                $members = $members . ',' . get_sub_field( 'membership_id' );
						            } ?>
						           <?php $counter++; ?>
						       <?php endwhile; ?>
						   <?php endif; ?>
						   <?php if(current_user_can('mepr-active','memberships:' . $members)) { ?>
							   <?php
							   $date_string = get_field('replay_event_date');
							   $date = DateTime::createFromFormat('Ymd', $date_string);
							   ?>
							   <div class="item one-third">
								   <a class="item-link past-item" href="<?php the_permalink(); ?>" target="_self">
									   <span class="item-container">
										   <span class="item-top">
											   <span class="past-sessions-button">Past Sessions</span>
											   <span class="listing-date text-dark"><?php echo $date->format('j F, Y'); ?></span>
											   <h3 class="item-title text-black"><?php the_title(); ?></h3>
											   <?php
											   		$text = get_the_excerpt();
											   		$trimmed_content = wp_trim_words( $text, $num_words = 22, $more = null );
											   ?>
											   <span class="text-dark item-excerpt"><?php echo $trimmed_content; ?></span>
										   </span>
										   <span class="bottom-bar past-item-bottom">
					   							<span class="replay-button" href="#">Watch Replay</span>
										   </span>
									   </span>
								   </a>
							   </div>


						<?php } ?>
						<?php unset($members); ?>
					<?php endwhile; ?>
				<?php endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</section>
