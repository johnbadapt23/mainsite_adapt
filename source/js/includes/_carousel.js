
	var carousel = $('[data-carousel]');

	// items: 1,
	// loop: true,
	// smartSpeed: 500,
	// margin: 0,
	// nav: true,
	// pagination: true,
	// itemsScaleUp : true,
	// navText: ['',''],
	// autoplay: true,
	// autoplaySpeed: 2000,
	// mouseDrag: false,
	// touchDrag: false,

	carousel.owlCarousel({
		itemsScaleUp : true,
		navText: ['',''],
		smartSpeed: 500,
		animateOut: (carousel.data('transitionStyle') ? carousel.data('transitionStyle') : 'fadeOut'),
		items: 1,
		loop: (carousel.data('loop') ? carousel.data('loop') : true),
		margin: (carousel.data('margin') ? carousel.data('margin') : 0),
		nav: (carousel.data('show-direction') ? carousel.data('show-direction') : false),
		pagination: true,
		autoplay: (carousel.data('autoplay') ? carousel.data('autoplay') : false),
		autoplaySpeed: (carousel.data('speed') ? carousel.data('speed') : 1000),
		mouseDrag: (carousel.data('mousedrag') ? carousel.data('mousedrag') : false),
		touchDrag: (carousel.data('touchdrag') ? carousel.data('touchdrag') : true),
		pullDrag: (carousel.data('pulldrag') ? carousel.data('pulldrag') : false),
		slideBy: (carousel.data('items') ? carousel.data('items') : 3),
		dots: (carousel.data('show-dots') ? carousel.data('show-dots') : true),
		navSpeed: (carousel.data('speed') ? carousel.data('speed') : 1000),
		autoplayTimeout: (carousel.data('timeout') ? carousel.data('timeout') : 10000),
		autoplayHoverPause: (carousel.data('pausehover') ? carousel.data('pausehover') : true),
		singleItem : (carousel.data('single') ? carousel.data('single') : false),
    	autoHeight : (carousel.data('autoheight') ? carousel.data('autoheight') : false),
        autoWidth : (carousel.data('autowidth') ? carousel.data('autowidth') : false),
    	startPosition : (carousel.data('position') ? carousel.data('position') : 0),
		center : (carousel.data('center') ? carousel.data('center') : false),
		responsive: {
			0: {
				items: (carousel.data('items-mobile') ? carousel.data('items-mobile') : 1)
			},
			768: {
				items: (carousel.data('items-tablet') ? carousel.data('items-tablet') : 1)
			},
			1024: {
				items: (carousel.data('items') ? carousel.data('items') : 1)
			}
		},
		onInitialized: function() {

            setTimeout(function() {
                carousel.addClass('active');
            }, 600);

			// $('.owl-next').on('click',function(){
			//
			// 	var result = '';
			//
			// 	if($('[data-slide]').text() < $('[data-max]').text()) {
			// 		result = parseFloat($('[data-slide]').text()) + 1;
			//
			//
			// 		if ( result < 10 ) {
			// 			$('[data-slide]').text("0" + result);
			//
			//         } else {
			//             $('[data-slide]').text(result);
			//         }
			//
			// 	}
			// 	else {
			// 		$('[data-slide]').text('01');
			// 	}
			// });
			//
			// $('.owl-prev').on('click',function(){
			// 	if($('[data-slide]').text() == 1) {
			//
			// 		$('[data-slide]').text($('[data-max]').text());
			//
			// 	}
			// 	else {
			// 		var result = parseFloat($('[data-slide]').text()) - 1;
			// 		if ( result < 10 ) {
			// 			$('[data-slide]').text("0" + result);
			//
			//         } else {
			//             $('[data-slide]').text(result);
			//         }
			// 	}
			// });

			$('[data-carousel-next]').on('click', function(e) {
				carousel.trigger('next.owl.carousel', [(carousel.data('speed') ? carousel.data('speed') : 1000)]);
			});

			$('[data-carousel-prev]').on('click', function(e) {
				carousel.trigger('prev.owl.carousel', [(carousel.data('speed') ? carousel.data('speed') : 1000)]);
			});

			// $('[data-carousel-prev]').on('click', function(e) {
			// 	//$('[data-slide]').text( e.page.index + 1 );
			// 	carousel.trigger('prev.owl.carousel', [(carousel.data('speed') ? carousel.data('speed') : 1000)]);
			// });

			carousel.on('changed.owl.carousel', function(e) {
				//$('[data-slide]').text( '0' + (e.page.index + 1) );

				var count = e.page.index + 1;

				if(count == 0) {
					count = 1;
				}

				if ( count < 10 ) {
					$('[data-slide]').text( '0' + count );
		        } else {
					$('[data-slide]').text( count );	
		        }
			});

		}
	});

    var refresh = false;

    carousel.on('change.owl.carousel', function(event) {
        $('.owl-item.active').removeClass('show');
        $('[data-slider-placeholder]').fadeOut(600);
		//$('.owl-next').trigger('click');


        if(!refresh) {
            setTimeout(function() {
                refresh = true;
                $('.owl-item.active').first().addClass('show');

                $('[data-slider-placeholder]').html($('.owl-item.active').first().find('[data-slider-content]').clone()).fadeIn(600);
            }, 600);
        }
        setTimeout(function() {
            refresh = false;
        }, 600);
    });

    $(window).resize(function() {
        carousel.trigger('change.owl.carousel');
    });
