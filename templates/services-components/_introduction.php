<?php if(get_sub_field( 'background_colour' ) == 'background-black'){ ?>
    <?php $textColour = 'text-white'; ?>
    <?php $preText = 'text-medium-grey'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
    <?php $preText = 'text-dark-grey'; ?>
<?php }?>

<section class="events-title-block services-introduction <?php echo get_sub_field( 'background_colour' ); ?>">
	<div class="container">
		<div class="content-container">
			<span class="pre-title <?php echo $preText; ?>"><?php echo get_sub_field( 'pre_title' ); ?></span>
			<h1 class="title <?php echo $textColour; ?>"><?php echo get_sub_field( 'title' ); ?></h1>
			<span class="text <?php echo $textColour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
			<span class="button-container">
                <?php if ( have_rows( 'button' ) ) : ?>
                    <?php $counter = 1;?>
    				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                            <a href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>" class="scroll-to-button std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>" class="std-button<?php if($counter == 1){ ?> red-button<?php } else { ?> red-outline-button<?php } ?>">
                                <?php echo get_sub_field( 'button_text' ); ?>
                            </a>
                        <?php } ?>
                        <?php $counter++; ?>
    				<?php endwhile; ?>
    			<?php else : ?>
    				<?php // no rows found ?>
    			<?php endif; ?>
            </span>
		</div>
        <span class="side-image-container">
            <span class="image-container">
                <span class="bg-container contained-image">
                    <?php $side_image = get_sub_field( 'side_image' ); ?>
                    <?php if ( $side_image ) { ?>
                        <?php echo wp_get_attachment_image( $side_image['ID'], 'full', false, array(
                            'alt'     => $side_image['alt'],
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php } ?>
                </span>
                <span class="fade"></span>
            </span>
        </span>
	</div>
</section>
