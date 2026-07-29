<section class="stories-hero-slider">
    <div class="container">
        <div class="hero-slider-container desktop">
            <?php if ( have_rows( 'stories' ) ) : ?>
				<?php while ( have_rows( 'stories' ) ) : the_row(); ?>
                    <span class="storyslide">
                        <?php $post_object = get_sub_field( 'story' ); ?>
                        <?php if ( $post_object ): ?>
                            <?php $post = $post_object; ?>
                            <?php setup_postdata( $post ); ?> 
                                <span class="story-slide-trigger"><span class="labelMedium text-black"><?php echo get_field( 'company_name' ); ?></span></span>                                
                                <span class="story-slide-slide">
                                    <?php $generic_backup_image = get_sub_field( 'generic_backup_image' ); ?> 
                                    <?php $quote = get_sub_field('quote'); ?>                           
                                    <a href="<?php the_permalink(); ?>">
                                        <?php $video_poster = get_field( 'video_poster' ); ?>
                                        <span class="related-inner image-related">
                                            <?php if ( $video_poster ) { ?>
                                                <span class="background-image-container bg-container">
                                                    <img src="<?php echo $video_poster['url']; ?>" alt="<?php echo $video_poster['alt']; ?>" />
                                                </span>
                                                <span class="gradient-container"></span>
                                            <?php } else { ?>
                                                <?php if ( $generic_backup_image ) { ?>
                                                    <span class="background-image-container bg-container">
                                                        <img src="<?php echo $generic_backup_image['url']; ?>" alt="<?php echo $generic_backup_image['alt']; ?>" />
                                                    </span>
                                                    <span class="gradient-container"></span>
                                                <?php } ?>
                                            <?php } ?>
                                            <span class="related-top">
                                                <?php $company_logo = get_field( 'company_logo_white' ); ?>
                                                <?php if ( $company_logo ) { ?>
                                                    <span class="company-logo-container">
                                                        <span class="logo-container">
                                                            <img src="<?php echo $company_logo['url']; ?>" alt="<?php echo $company_logo['alt']; ?>" />
                                                        </span>
                                                    </span>
                                                <?php } ?>                                                                                                    
                                            </span>
                                            <span class="related-bottom">                                
                                                <?php $showtitle = 'yes' ?>
                                                <?php if ( have_rows( 'content' ) ): ?>
                                                    <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                                        <?php if ( get_row_layout() == 'quote' ) : ?>
                                                            <?php $quote_text = !empty($quote) ? $quote : get_sub_field('quote');
    
                                                            if ( !empty($quote_text) ) : ?>
                                                                <h2 class="title">"<?php echo esc_html( $quote_text ); ?>"</h2>
                                                            <?php endif;             

                                                            $showtitle = 'no'; 
                                                            break;?>                                                                                                                                                                                                                        
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <?php // no layouts found ?>
                                                <?php endif; ?>  
                                                <?php if($showtitle == 'yes') { ?>
                                                    <h2 class="title"><?php echo $title; ?></h2>  
                                                <?php } ?>
                                                <?php if ( $video_poster ) { ?>
                                                    <span class="video-caption-button-container">
                                                        <?php if ( have_rows( 'video_caption' ) ) : ?>
                                                            <?php 
                                                                $captions = get_field( 'video_caption' );
                                                                $caption_count = is_array($captions) ? count($captions) : 0;
                                                                $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                            ?>
                                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>                                                                    
                                                                    <span class="name-role">
                                                                        <span class="name labelLarge text-white"><?php echo get_sub_field( 'name' ); ?></span>
                                                                        <span class="role labelLarge text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                    </span>                                                                    
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <span class="button-container">
                                                            <span class="std-button-span red-button">Watch full video <img src="<?php echo get_template_directory_uri(); ?>/assets/images/video-circle-white.svg" width="16"/></span>
                                                        </span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="button-container">
                                                        <span class="std-button-span red-button story-button">Read full article</span>
                                                    </span>
                                                <?php } ?>                                                                                                     
                                            </span>
                                        </span>   
                                    </a>                               
                                </span>                                    
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
         <div class="hero-slider-container mobile mobile-story-slider">
            <?php if ( have_rows( 'stories' ) ) : ?>
				<?php while ( have_rows( 'stories' ) ) : the_row(); ?>
                    <span class="storyslide">
                        <?php $post_object = get_sub_field( 'story' ); ?>
                        <?php if ( $post_object ): ?>
                            <?php $post = $post_object; ?>
                            <?php setup_postdata( $post ); ?> 
                                <span class="story-slide-slide">
                                    <?php $generic_backup_image = get_sub_field( 'generic_backup_image' ); ?>
                                    <?php $quote = get_sub_field('quote'); ?>                             
                                    <a href="<?php the_permalink(); ?>">
                                        <?php $video_poster = get_field( 'video_poster' ); ?>
                                        <span class="related-inner image-related">                                           
                                            <span class="related-top">
                                                <?php if ( $video_poster ) { ?>
                                                    <span class="background-image-container bg-container">
                                                        <img src="<?php echo $video_poster['url']; ?>" alt="<?php echo $video_poster['alt']; ?>" />
                                                    </span>
                                                    <span class="gradient-container"></span>
                                                <?php } else { ?>
                                                    <?php if ( $generic_backup_image ) { ?>
                                                        <span class="background-image-container bg-container">
                                                            <img src="<?php echo $generic_backup_image['url']; ?>" alt="<?php echo $generic_backup_image['alt']; ?>" />
                                                        </span>
                                                        <span class="gradient-container"></span>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php $company_logo = get_field( 'company_logo_white' ); ?>
                                                <?php if ( $company_logo ) { ?>
                                                    <span class="company-logo-container">
                                                        <span class="logo-container">
                                                            <img src="<?php echo $company_logo['url']; ?>" alt="<?php echo $company_logo['alt']; ?>" />
                                                        </span>
                                                    </span>
                                                <?php } ?>                                                                                                    
                                            </span>
                                            <span class="related-bottom">                                
                                                <?php $showtitle = 'yes' ?>
                                                <?php if ( have_rows( 'content' ) ): ?>
                                                    <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                                        <?php if ( get_row_layout() == 'quote' ) : ?>
                                                            <?php $quote_text = !empty($quote) ? $quote : get_sub_field('quote');
    
                                                            if ( !empty($quote_text) ) : ?>
                                                                <h3 class="title-quote">"<?php echo esc_html( $quote_text ); ?>"</h3>
                                                            <?php endif;             

                                                            $showtitle = 'no';       ?>                                                                                                                                                                                                              
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <?php // no layouts found ?>
                                                <?php endif; ?>  
                                                <?php if($showtitle == 'yes') { ?>
                                                    <h3 class="title-quote"><?php echo $title; ?></h3>  
                                                <?php } ?>
                                                <?php if ( $video_poster ) { ?>
                                                    <span class="video-caption-button-container">
                                                        <?php if ( have_rows( 'video_caption' ) ) : ?>
                                                            <?php 
                                                                $captions = get_field( 'video_caption' );
                                                                $caption_count = is_array($captions) ? count($captions) : 0;
                                                                $caption_class = ($caption_count === 2) ? 'two-captions' : '';
                                                            ?>
                                                            <span class="caption-container <?php echo esc_attr( $caption_class ); ?>">
                                                                <?php while ( have_rows( 'video_caption' ) ) : the_row(); ?>                                                                    
                                                                    <span class="name-role">
                                                                        <span class="name labelSmall text-black"><?php echo get_sub_field( 'name' ); ?></span>
                                                                        <span class="role labelSmall text-grey"><?php echo get_sub_field( 'role' ); ?></span>
                                                                    </span>                                                                   
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <span class="button-container">
                                                            <span class="std-button-span red-button">Watch full video <img src="<?php echo get_template_directory_uri(); ?>/assets/images/video-circle-white.svg" width="16"/></span>
                                                        </span>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="button-container">
                                                        <span class="std-button-span red-button story-button">Read full article</span>
                                                    </span>
                                                <?php } ?>                                                                                                     
                                            </span>
                                        </span>   
                                    </a>                               
                                </span>                                    
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </span>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>
