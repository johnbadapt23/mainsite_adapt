
<section class="quote-slider customer-events-slider single-quote">
	<div class="container">
		<div class="quote-slider-outer background-white">
			<div class="quote-module">
                <div class="quote-slide">
                    <div class="customer-quote-slider-inner">
                        <?php if (get_sub_field( 'large_quote_text' )) { ?> 
                            <h2 class="quote text-black <?php if (get_sub_field( 'quote_text' )) { ?><?php } else { ?> large-margin-bottom<?php } ?>"><?php echo get_sub_field( 'large_quote_text' ); ?></h2>
                        <?php } ?>	
                        <?php if (get_sub_field( 'quote_text' )) { ?>
                            <span class="small-quote-text p-small"><?php echo get_sub_field( 'quote_text' ); ?></span>
                        <?php } ?>		
                        <span class="logo-text-container">	
                            <?php $logo = get_sub_field( 'logo' ); ?>
                            <?php if ( $logo ) { ?>
                                <span class="logo-container">
                                    <?php echo wp_get_attachment_image( $logo['ID'], 'adapt-optimized', false, array(
                                        'alt'     => $logo['alt'],
                                        'loading' => 'lazy',
                                    ) ); ?>
                                </span>
                            <?php } ?>		
                            <span class="quote-name-container">	
                                <span class="quote-title text-black labelMedium"><?php echo get_sub_field( 'name' ); ?></span>
                                <span class="quote-business grey-text labelMedium"><?php echo get_sub_field( 'role' ); ?></span>
                            </span>
                        </span>
                    </div>
                </div>					
			</div>			
		</div>
	</div>
</section>