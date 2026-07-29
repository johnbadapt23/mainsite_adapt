
<section class="pagination" data-pagination data-loading="Loading..." data-text="Load More">
	<div class="container">
		<a href="/exchanges/page/2" data-load-more  id="more" class="button">Load more</a>
		<?php //next_posts_link(__('Load more')); ?>

		<?php //echo paginate_links(); ?>

		<?php next_posts_link( 'Load more' , $exchanges->max_num_pages ); ?>
	</div>
</section>
