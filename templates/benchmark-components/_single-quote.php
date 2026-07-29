
<section class="quote-slider customer-events-slider single-quote benchmarking-quote background-black <?php echo get_sub_field( 'padding_top' ); ?> <?php echo get_sub_field( 'padding_bottom' ); ?>">
	<div class="container">
		<div class="quote-slider-outer background-tertiary-black">
			<div class="quote-module">
                <div class="quote-slide">
                    <div class="customer-quote-slider-inner">
                        <?php if (get_sub_field( 'large_quote_text' )) { ?> 
                            <h2 class="quote text-white <?php if (get_sub_field( 'quote_text' )) { ?><?php } else { ?> large-margin-bottom<?php } ?>"><?php echo get_sub_field( 'large_quote_text' ); ?></h2>
                        <?php } ?>	
                        <?php if (get_sub_field( 'quote_text' )) { ?>
                            <span class="small-quote-text p-small text-white-dark-mode"><?php echo get_sub_field( 'quote_text' ); ?></span>
                        <?php } ?>	
                        <?php $logo = get_sub_field( 'logo' ); ?>	
                        <span class="logo-text-container<?php if ( $logo ) { ?><?php } else { ?> no-logo<?php } ?>">	                            
                            <?php if ( $logo ) { ?>
                                <span class="logo-container">
                                    <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />
                                </span>
                            <?php } ?>		
                            <span class="quote-name-container">	
                                <span class="quote-title text-white labelMedium"><?php echo get_sub_field( 'name' ); ?></span>
                                <span class="quote-business grey-text labelMedium"><?php echo get_sub_field( 'role' ); ?></span>
                            </span>                           
                        </span>
                        <?php if(get_sub_field('full_story_link')){ ?> 
                            <span class="link-container">
                                <?php $target = get_sub_field( 'link_target' ); ?>
                                <?php if($target){ ?> 
                                    <a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field('full_story_link'); ?>" target="<?php echo $target; ?>">Read full story</a>
                                <?php } else { ?> 
                                    <a class="red-text text-link large-link-text red-underline-link external-link" href="<?php echo get_sub_field('full_story_link'); ?>" target="_self">Read full story</a>
                                <?php } ?>                                
                            </span>
                        <?php } ?>
                    </div>
                </div>					
			</div>			
		</div>
	</div>
</section>