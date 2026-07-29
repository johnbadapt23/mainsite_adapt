	// TOGGLE ELEMENT
	$('[data-toggle]').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('active');
		$('#' + $(this).data('toggle')).toggleClass('active');
		$('#' + $(this).data('toggle')).find('input').first().focus();
		$('body').toggleClass($(this).data('toggle') + '-active');
	});

	// CLOSE ELEMENT
	$('[data-close]').on('click', function(e) {
		e.preventDefault();
		$(this).removeClass('active');
		$('#' + $(this).data('toggle')).removeClass('active');
		$('body').removeClass($(this).data('toggle') + '-active');
	});

	// SCROLL TO ELEMENT
	$('[data-scroll-element]').on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: $('#' + $(this).data('scroll-element')).offset().top - $(this).data('scroll-offset') }, 500);
	});

	// SCROLL TO HREF
	$('[data-scroll-to]').on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top - $(this).data('scroll-offset') }, 500);
	});

	// SCROLL TOP
	$('[data-top]').on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: 0 }, 500);
	});

	// FADE OUT
	$('body').on('click', 'a[href*="' + window.location.hostname + '"], a[href^="/"]', function(e){
		if( $(this).attr('data-filter') == '' && $(this).attr('data-load-more') == '' ) {
			$('main, section.subscribe, footer').fadeOut(300);
			$('body').addClass('loading');
		}
	});

	// ACCORDIAN
	$('[data-accordian]').on('click', 'div.title', function(e) {
		e.preventDefault();

		var element = $(this).parent().parent();

		if ( !$(this).parent().hasClass('active') ) {
			if ( element.data('collapse') ) {
				element.find('li').removeClass('active');
			}

			$(this).parent().toggleClass('active');
		} else {
			$(this).parent().removeClass('active');
		}
	});

	// CLEAN EMBED IFRAMES
	$('iframe[src*=youtube]').each(function(){
		$(this).attr('src', $(this).attr('src') + '?modestbranding=1&autohide=1&showinfo=0&controls=0')
		$(this).attr('scrolling','no');
	});
	$('iframe[src*=vimeo]').each(function(){
		$(this).attr('src', $(this).attr('src') + '?badge=0&byline=0&title=0')
		$(this).attr('scrolling','no');
	});
