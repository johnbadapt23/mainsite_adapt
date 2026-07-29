<?php $title = get_sub_field( 'title' ); ?>
<?php $subtitle = get_sub_field( 'sub_title' ); ?>
<?php $totalcount = 0;
$locations = get_sub_field('value');
if (is_array($locations)) {
  $totalcount = count($locations);
} ?>
<section class="values-full-screen">
    <?php $valuesCounter = 1; ?>
    <?php if ( have_rows( 'value' ) ) : ?>
		<?php while ( have_rows( 'value' ) ) : the_row(); ?>
            <div class="value <?php echo get_sub_field( 'background_colour' ); ?>">
                <div class="container">
                    <div class="v-wrap">
                        <div class="v-box">
                            <div class="inner">
                                <?php if($valuesCounter == 1){ ?>
                                    <span class="pre-content-container">
                            			<h2><?php echo $title; ?></h2>
                            			<span class="sub-title"><?php echo $subtitle; ?></span>
                                    </span>
                                <?php } ?>
                    			<?php $animation_json = get_sub_field( 'animation_json' ); ?>
                                <?php $animation_id = get_sub_field( 'value_id' ); ?>
                                <?php if ( $animation_json ) { ?>
                                    <span class="animation-container">
                                        <span class="animator-player">
                                            <lottie-player loop speed="1" id="<?php echo $animation_id; ?>" src="<?php echo $animation_json['url']; ?>" background="transparent" style="width: 100%; height: auto"></lottie-player>
                                        </span>
                                    </span>
                                    <script>
                                        LottieInteractivity.create({
                                            player:'#<?php echo $animation_id; ?>',
                                            mode:"scroll",
                                            actions: [
                                                {
                                                visibility: [0.25, 1.0],
                                                type: "play"
                                                }
                                            ]
                                        });
                                    </script>
                                <?php } ?>
                                <span class="content-container">
                        			<h2><?php echo get_sub_field( 'title' ); ?></h2>
                        			<span class="sub-title"><?php echo get_sub_field( 'text' ); ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <span class="progress-bar-container">
                        <span class="counter"><?php if($valuesCounter < 10){ ?>0<?php } ?><?php echo $valuesCounter; ?></span>
                        <span class="progress-bar-outer">
                            <span class="progress-bar-inner <?php echo $animation_id; ?>"></span>
                        </span>
                        <span class="total-count"><?php if($totalcount < 10){ ?>0<?php } ?><?php echo $totalcount; ?></span>
                        <?php $progressHeight = $valuesCounter / $totalcount * 100; ?>
                        <style>
                            .progress-bar-inner.<?php echo $animation_id; ?> {
                                height: <?php echo $progressHeight; ?>%;
                            }
                        </style>
                    </span>
                </div>
            </div>
            <?php $valuesCounter++; ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
</section>
