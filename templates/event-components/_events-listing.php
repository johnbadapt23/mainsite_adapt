<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php
$today = date('Y');
$args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'ASC',
    'meta_query' => array(
        array(
            'key'     => 'date',
            'compare' => '>=',
            'value'   => $today,
        )
    ),
); ?>
<?php $loop = new WP_Query( $args ); ?>
<?php $terms = array(); ?>
<?php if ( $loop->have_posts() ) : ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
    <?php
$years = get_the_terms( $post->ID, 'years' );
if($years){
    foreach( $years as $year ){
        if($year-> parent == 0){
            if( ! in_array( $year, $terms )){
                $terms[] = $year;
            }
        } else {

        }
    }
}
?>
<?php endwhile; ?>
<?php else : ?>
<?php endif; ?>
<?php wp_reset_postdata();
?>

<section class="events-listing-module background-black" <?php if (get_sub_field( 'id' )) { ?>id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="events-listing-top">
            <h2 class="text-white"><?php echo get_sub_field( 'title' ); ?></h2>
            <span class="year-button-container" id="yearButtons">
                <span class="year-button-sticky">
                    <div class="container">
                        <span class="year-container">
                            <?php $keys = array_column($terms, 'slug');
                                  array_multisort($keys, SORT_ASC, $terms); ?>
                            <?php
                            $i = 0;
                            foreach($terms as $term) { ?>
                                <a data-date="<?php echo $term->slug; ?>" class="year-button<?php if($term->slug == $today){?> active<?php } ?><?php if ($i == 0) {?> active<?php } ?>" value="<?php echo $term->name; ?>"><?php echo $term -> name; ?></a>
                            <?php
                            $i++;
                            } ?>
                         </span>
                     </div>
                     <?php if ( have_rows( 'buttons' ) ) : ?>
                         <?php $counter = 1;?>
         				<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                             <?php if(get_sub_field( 'button_type' ) == 'download') { ?>
                                 <?php if(get_sub_field('download_button') == 'yes'){ ?>
                                     <span class="download-button-container-mobile-sticky">
                                         <?php echo get_sub_field( 'download_form_button' ); ?>
                                         <span class="text">Download</span>
                                     </span>
                                 <?php }  ?>
                             <?php } ?>
         				<?php endwhile; ?>
         			<?php else : ?>
         				<?php // no rows found ?>
         			<?php endif; ?>
                    <span class="back-top-mobile-sticky">
                        <button type="button" class="back-to-top-sticky" aria-label="Back to top"></button>
                        <span class="text">Back to top</span>
                    </span>
                 </span>
             </span>
            <span class="button-container right-align">
                <?php if ( have_rows( 'buttons' ) ) : ?>
                    <?php $counter = 1;?>
    				<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                    	<?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                            <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } elseif(get_sub_field( 'button_type' ) == 'link') { ?>
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button <?php echo get_sub_field( 'button_type' ); ?><?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } elseif(get_sub_field( 'button_type' ) == 'hubspot-download') { ?>
                                <a class="formPopupHubspot download-file-button stdBtn std-button <?php if($counter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#eventsListFormPopup<?php echo $counter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="eventsListFormPopup<?php echo $counter; ?>">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_code' ); ?></div>
                                    </div>
                                </div> 
                        <?php } elseif(get_sub_field( 'button_type' ) == 'download') { ?>
                            <?php if(get_sub_field('download_button') == 'yes'){ ?>
                                <span class="download-button-container <?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                    <?php echo get_sub_field( 'download_form_button' ); ?>
                                </span>
                            <?php } else { ?>
                                <span class="form-popup-button-container <?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                    <?php echo get_sub_field( 'download_form_button' ); ?>
                                </span>
                            <?php } ?>
                            <span class="download-form"><?php echo get_sub_field( 'download_form_code' ); ?></span>
                        <?php } ?>
                        <?php $counter++; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </span>
            </span>
        </div>
    </div>
    <div class="events-listing">
        <?php
		$today = date('Ymd');
		$args = array(
			'post_type' => 'event',
			'meta_key'  => 'date',
            'posts_per_page' => -1,
            'no_found_rows' => true,
		    'orderby'   => 'meta_value_num',
		    'order'     => 'ASC',
			'meta_query' => array(
				array(
					'key'     => 'date',
					'compare' => '>=',
					'value'   => $today,
				)
			),
		);
		?>
        <?php $posts = new WP_Query( $args );
			if( $posts->have_posts() ): ?>
                <?php $counter = 1; ?>
				<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <?php // Load field value.
                    $date_string = get_field('date');

                    // Create DateTime object from value (formats must match).

                    $date = DateTime::createFromFormat('Ymd', $date_string); 
                    $year = $date->format('Y');
                
                    // Check if this is the first event of the current year
                    if (!isset($seen_years[$year])) {
                        $seen_years[$year] = true; // Mark the year as seen
                        $id_attribute = 'id="' . $year . '"';
                        $firstClass = 'first-of-year';
                    } else {
                        $id_attribute = ''; // No ID attribute for subsequent events of the same year
                        $firstClass = 'not-first-of-year';
                    }
                    ?>
                    <?php if(get_field('event_link')){ ?> 
                        <a href="<?php echo get_field( 'event_link' ); ?>" target="_blank" rel="noopener noreferrer">
                    <?php } ?>                    
                        <span class="item event-item background-black <?php echo $firstClass; ?> <?php echo $date->format('Y'); ?>" data-date="<?php echo $date->format('Y'); ?>" <?php echo $id_attribute; ?>>
                            <span class="container">
                                <span class="event-image-container column">
                                    <span class="image-container">
                                        <span class="bg-container">
                                            <?php $listing_image = get_field( 'listing_image' ); ?>
                                            <?php if ( $listing_image ) { ?>
                                            	<?php echo wp_get_attachment_image( $listing_image['ID'], 'adapt-optimized', false, array(
                                            		'alt'     => $listing_image['alt'],
                                            		'loading' => 'lazy',
                                            	) ); ?>
                                            <?php } ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="date-content-container">
                                    <span class="press-date-container column">
                                        <span class="v-wrap">
                                            <span class="v-box">
                                                <span class="date-inner">
                                                    <span class="date-day text-medium-grey"><?php echo $date->format('j'); ?></span>
                                                    <span class="mobile-container">
                                                        <span class="date-month text-medium-grey labelMedium"><?php echo $date->format('M'); ?></span>
                                                        <span class="date-year text-medium-grey labelMedium"><?php echo $date->format('Y'); ?></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="item-content-container column">
                                        <span class="content-inner">
                                            <span class="title text-white labelXXLarge"><?php the_title(); ?></span>
                                            <p class="location text-dark-grey"><?php echo get_field( 'location' ); ?></p>
                                            <p class="excerpt text-white"><?php echo get_field( 'listing_description' ); ?></p>
                                            <span class="mobile-link-container"><span class="text-link red-text external-link red-underline-link">Learn More</span></span>
                                        </span>
                                    </span>
                                </span>
                            </span>
                        </span>                    
                    <?php if(get_field('event_link')){ ?> 
                        </a>
                    <?php } ?>                    
                <?php endwhile; ?>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
</section>
