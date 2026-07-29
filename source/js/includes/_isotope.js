
var isotope = $('[data-isotope]');
isotope.isotope({
	itemSelector: '.post',
	layoutMode: 'masonry',
	percentPosition: true,
	masonry: {
		gutter: '[data-gutter]'
	}
});

$('[data-filter] a').on('click', function(e) {
	e.preventDefault();
	var $href = (window.location.pathname).split('/');

	if( $href['1'] == 'projects' ||  $href['1'] == 'exchanges' ) {
		$(this).parent().parent().find('a').removeClass('active');
		$(this).addClass('active');

		isotope.isotope({
			filter: ($(this).data('filter') == '*' ? '*' : '.' + $(this).data('filter'))
		});

		if($(this).data('filter') == '*') {
			$('[data-filter]').removeClass('active');
			$('[data-filter]').parent().removeClass('active');
		}
	} else {
		window.location.href = '/' + $href['1'] + 's#' + $(this).attr('href');
	}
});

if( window.location.hash ) {
	setTimeout(function() {
		$('[data-filter] a[href="' + window.location.hash.replace('#','') + '"]').trigger('click');
	}, 800);
}

setTimeout(function() {
	$('[data-isotope]').addClass('active');
}, 1200);
