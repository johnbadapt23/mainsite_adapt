<div class="share">
    <h5>Share</h5>

    <ul>
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>" class="facebook" target="_blank"></a>
        </li>
        <li>
            <a href="https://twitter.com/home?status=<?php echo urlencode(get_the_permalink()); ?>" class="twitter"></a>
        </li>
        <li>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_the_permalink()); ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()))); ?>&description=<?php echo urlencode(get_field('intro')); ?>" class="pinterest" target="_blank"></a>
        </li>
        <li>
            <a href="mailto:?&subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_the_permalink()); ?>" class="email" target="_blank"></a>

        </li>
    </ul>

</div>
