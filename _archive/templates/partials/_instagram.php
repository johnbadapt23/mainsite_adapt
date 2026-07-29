
<section class="instagram">
    <div class="container">
        <div>
            <h3>instagram@<a href="https://www.instagram.com/<?php the_field('instagram_username', 'options'); ?>" target="_blank"><?php the_field('instagram_username', 'options'); ?></a></h3>
            <ul class="photos" data-macy>
        		<?php
                    //https://instagram.com/oauth/authorize/?client_id=a923810abd4a4472919a791e0f73bd3e&redirect_uri=http://localhost:4100&response_type=token
        			$Instagram = new Instagram(get_field('instagram_api', 'options'), get_field('instagram_photos', 'options'), 'standard_resolution');
                    if(isset($Instagram)) {
        			foreach ($Instagram::$result->data as $photo) {
        			    $img = $photo->images->{$Instagram::$display_size};
        			    $img = $img->url;
        			    $link = $photo->link;
        		?>
        		<li>
        			<a href="<?php echo $link; ?>" target="_blank">
                        <img src="<?php echo $img; ?>">
                    </a>
        		</li>
        		<?php } } ?>
            </ul>


            <div class="load">
                <a data-load-more class="button">Load More</a>
            </div>

        </div>

    </div>
</section>
