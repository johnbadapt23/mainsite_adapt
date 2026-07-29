<a href="<?php the_permalink(); ?>">
    <span class="item press-release-item">
        <span class="container">
            <span class="press-date-container column desktop">
                <span class="v-wrap">
                    <span class="v-box">
                        <span class="date-inner">
                            <?php if (get_field('published_date')){ ?>                         
                            <?php  $date_string = get_field('published_date');
                                // Create DateTime object from value (formats must match).
                                $date = DateTime::createFromFormat('Ymd', $date_string); ?>                                
                                <span class="date-day text-red"><?php echo $date->format('j'); ?></span>
                                <span class="date-month text-black labelMedium"><?php echo $date->format('M'); ?></span>
                                <span class="date-year text-black labelMedium"><?php echo $date->format('Y'); ?></span>
                            <?php } else { ?> 
                                <span class="date-day text-red"><?php echo get_the_date('j') ?></span>
                                <span class="date-month text-black labelMedium"><?php echo get_the_date('M') ?></span>
                                <span class="date-year text-black labelMedium"><?php echo get_the_date('Y') ?></span>
                            <?php } ?>		
                           
                        </span>
                    </span>
                </span>
            </span>
            <span class="item-content-container column">
                <span class="content-inner">
                    <span class="title text-black labelXXLarge"><?php the_title(); ?></span>
                    <span class="published-date mobile-only">
                        <?php if (get_field('published_date')){ ?>                         
                            <?php  $date_string = get_field('published_date');
                            // Create DateTime object from value (formats must match).
                            $date = DateTime::createFromFormat('Ymd', $date_string); ?>
                            /<?php echo $date->format('M j, Y'); ?>
                        <?php } else { ?> 
                            /<?php echo get_the_date('M j, Y') ?>
                        <?php } ?>		
                    </span>
                    <p class="excerpt text-dark-grey"><?php the_excerpt(); ?></p>
                </span>
            </span>
            <span class="read-more-container column">
                <span class="v-wrap">
                    <span class="v-box">
                        <span class="read-more text-link text-red">Read more</span>
                    </span>
                </span>
            </span>
        </span>
    </span>
</a>
