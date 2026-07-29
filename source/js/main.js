(function($){
	$(document).ready(function (){

		// STANDARD
		@@include('includes/_maps.js')

		match();
		outsideContainer();
		aos();

		if($('.progress-container').length ){
			scrollProgressBar();
		}

		// BACK TO TOP

		$('.back-to-top').on('click', function (e) {
	        e.preventDefault();
			$('html, body').animate({ scrollTop: 0}, 1000);
		});

		// FOOTER LINK REVEAL

		$('.footer-column-title-wrapper').on('click', function (e) {
	        e.preventDefault();

			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.footer-link-container-wrapper').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.footer-link-container-wrapper').slideDown(300);
			}


		});

		// SCROLL UP TO SEE FULL MENU

		var lastScrollTop = 0;
		$(window).scroll(function(event){
		   var st = $(this).scrollTop();
		   if (st > lastScrollTop){
		        $('header').removeClass('scrolled-up');
		   } else {
		        $('header').addClass('scrolled-up');
		   }
		   lastScrollTop = st;
		});

		$(document).on( 'nfFormReady', function( e, layoutView ) {
			match();

		    $('select').select2({
				minimumResultsForSearch: -1,
				templateResult: select2CopyClasses,
			    templateSelection: select2CopyClasses
			});
		});

		$('select').select2({
			minimumResultsForSearch: -1,
			templateResult: select2CopyClasses,
			templateSelection: select2CopyClasses
		});

		// SHOW FULL BIO (TEAM BLOCK)

		$('.slide-out-bio').on( 'click', function(e){
			e.preventDefault();

			if($('.full-bio').eq( $(this).index() ).hasClass('active')){
				$('.full-bio').eq( $(this).index() ).removeClass( 'active' );
				$('.click-overlay').removeClass('active');

			} else {
				$('.full-bio').eq( $(this).index() ).addClass( 'active' );
				$('.full-bio').eq( $(this).index() ).siblings().removeClass( 'active' );
				$('.click-overlay').addClass('active');
			}
		});

		// HIDE FULL BIO

		$('.close-bio').on( 'click', function(e){
			$('.full-bio').removeClass('active');
			$('.click-overlay').removeClass('active');
		});

		$('.click-overlay').on( 'click', function(e){
			$('.full-bio').removeClass('active');
			$('.click-overlay').removeClass('active');
		});

		$('span.scroll-down-button').on( 'click', function(e){
			e.preventDefault();
			var $nextSection = $(this).parent().parent().parent('section').next('section');
			if($(window).width() > 900) {
		    	$('html, body').animate({ scrollTop: $($nextSection).offset().top - 0 }, 1000);
			} else {
				if($($nextSection).hasClass('desktop')){
					$('html, body').animate({ scrollTop: $($nextSection).next('section').offset().top - 60 }, 1000);
				} else {
					$('html, body').animate({ scrollTop: $($nextSection).offset().top - 60 }, 1000);
				}
			}
		});

		var timer;
		// mega menu

		$('.dropdown').on('mouseover',function (e){
			if ($(window).width() >= 1024) {
				clearTimeout(timer);
				$(this).siblings('.dropdown').removeClass('active');
				$(this).addClass('active');
			}
		});

		$('.dropdown').on('click', function(e) {
			if($(this).hasClass('active')){
				$('.megaMenu').removeClass('active');
				$('.dropdown').removeClass('active');
			} else {
				$(this).siblings('.dropdown').removeClass('active');
				$(this).addClass('active');
			}
		});

		$('.dropdown').on('mouseout',function (e){
			if ($(window).width() >= 1024) {
				timer = setTimeout(function(){
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
				}, 500);
			}
		});

		$('.megaMenu').on('mouseover',function (e){
			if ($(window).width() >= 1024) {
				clearTimeout(timer);
			}
		});

		// Scroll To Button

		$('.scroll-to-button').on( 'click', function(e){
			e.preventDefault();
			$section = $(this).attr('href');
			if($(window).width() > 900) {
		    	$('html, body').animate({ scrollTop: $($section).offset().top - 80 }, 1000);
			} else {
				$('html, body').animate({ scrollTop: $($section).next('section').offset().top - 60 }, 1000);
			}
		});


		// Navigation

		$('.main-menu-toggle').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.headerRight .menu').removeClass('menu-open');
				$('.main-menu-hamburger').removeClass('active');
				$('.main-menu-mask').removeClass('active');
			} else {
				$('.headerRight .menu').addClass('menu-open');
				$(this).addClass('active');
				$('.main-menu-hamburger').addClass('active');
				$('.main-menu-mask').addClass('active');
			}
		});

		// Search

		$('.search-button').on( 'click', function(e){
			e.preventDefault();
			$('.search-dropdown').addClass('active');
			$( "#search" ).trigger("focus");
		});

		$('.search-close').on( 'click', function(e){
			e.preventDefault();
			$('.search-dropdown').removeClass('active');
		});

		$('.backTop').on('click', function(e) {
			$('html, body').animate({ scrollTop: $('body').offset().top - 0}, 1000);
		});

		$('.popup-vimeo').magnificPopup({
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

		$('.image-popup').magnificPopup({
			type: 'image',
			mainClass: 'mfp-post-img'
		})

		$('a.register-button').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-registration'
		});

		$('.topic-button-container').perfectScrollbar();
		$('#aboutContainer').perfectScrollbar();

		$('a.speaker-popup').magnificPopup({
			type: 'inline',
			mainClass: 'mfp-speakers',
			preloader: false,
			gallery: {
			    enabled: true
			},
			callbacks: {
				change: function() {
					$('#aboutContainer').perfectScrollbar('destroy');
					timer = setTimeout(function(){
						$('#aboutContainer').perfectScrollbar();
					}, 1);
				},
				buildControls: function() {
				// re-appends controls inside the main container
					this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
				},
			}
		});


		$('a.speaker-popup-text').magnificPopup({
			type: 'inline',
			mainClass: 'mfp-speakers',
			preloader: false,
			gallery: {
			    enabled: true
			},
			callbacks: {
				buildControls: function() {
				// re-appends controls inside the main container
					this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
				}
			}
		});

		$('a.speaker-popup-mobile').magnificPopup({
			type: 'inline',
			mainClass: 'mfp-speakers',
			preloader: false,
			gallery: {
			    enabled: true
			},
			callbacks: {
				buildControls: function() {
				// re-appends controls inside the main container
					this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
				}
			}
		});

		// Flip card functionality
		var fliptimer;

		$('.flip-card').on( 'click', function(e){
			clearTimeout(fliptimer);
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).siblings().removeClass('active');
				$(this).removeClass('active');
			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
			}
		});

		$('.flip-card').on('mouseover',function (e){
			if ($(window).width() >= 1024) {
				$(this).siblings().removeClass('active');
				clearTimeout(fliptimer);
			}
		});

		$('.flip-card-inner').on('mouseout',function (e){
			if ($(window).width() >= 1024) {
				fliptimer = setTimeout(function(){
					$('.flip-card').removeClass('active');
				}, 300);
			}
		});

		// Switcher Module
		$('.module-switcher a.module-switch-button').on( 'click', function(e){
			e.preventDefault();
			var $background = $(this).data('background');
			var $index = $(this).index();
			$('section.switcher-module').removeClass('background-white background-black');
			$('section.switcher-module').addClass($background);
			$('.switch-content-container').removeClass('active');
			$('.switch-content-container').eq($index).addClass('active');
			$(this).siblings('a.module-switch-button').removeClass('active');
			$(this).addClass('active');
		});




		// Flexible content image after code

		var images = $('.content-inner .content p img');
		$(images).each(function() {
		   $(this).wrap('<span class="content-image-container"></span>');
		});


		// $('select').select2({minimumResultsForSearch: -1});

		// if($('body').hasClass('template-flexible')) {
		// 	if ($(window).width() <= 1023) {
		// 		$.localScroll({
		// 			   duration: 1500,
		// 			   offset: -145
		// 		});
		// 	} else {
		// 		$.localScroll({
		// 			   duration: 1500,
		// 			   offset: -170
		// 		});
		// 	}
		// }

		// Quote / Thumbail slider

		if ( $('.quote-slider-module').length ) {
	   		var $slider = $('.quote-slider-module').on('init', function(slick) {
			   $('.quote-slider-module').fadeIn(1000);
		   }).slick({
			   slidesToShow: 1,
			   slidesToScroll: 1,
			   arrows: false,
			   autoplay: true,
			   infinite: true,
			   fade: true,
	   		   speed: 500,
	   	       cssEase: "linear",
		   });

		   if($('.quote-slider-thumbnails').hasClass('three-slides')){
			   var $slider2 = $('.quote-slider-thumbnails').on('init', function(slick) {
				  $('.quote-slider-thumbnails').fadeIn(1000);
			  }).slick({
				  slidesToShow: 3,
				  slidesToScroll: 1,
				  autoplay: false,
				  asNavFor: '.quote-slider-module',
				  dots: false,
				  centerMode: false,
				  focusOnSelect: true
			  });
		   }

		   if($('.quote-slider-thumbnails').hasClass('four-slides')){
			   var $slider2 = $('.quote-slider-thumbnails').on('init', function(slick) {
				  $('.quote-slider-thumbnails').fadeIn(1000);
			  }).slick({
				  slidesToShow: 4,
				  slidesToScroll: 1,
				  autoplay: false,
				  asNavFor: '.quote-slider-module',
				  dots: false,
				  centerMode: false,
				  focusOnSelect: true
			  });
		   }

		   if($('.quote-slider-thumbnails').hasClass('five-slides')){
			   var $slider2 = $('.quote-slider-thumbnails').on('init', function(slick) {
				  $('.quote-slider-thumbnails').fadeIn(1000);
			  }).slick({
				  slidesToShow: 5,
				  slidesToScroll: 1,
				  autoplay: false,
				  asNavFor: '.quote-slider-module',
				  dots: false,
				  centerMode: false,
				  focusOnSelect: true
			  });
		   }

		   if($('.quote-slider-thumbnails').hasClass('six-slides')){
			   var $slider2 = $('.quote-slider-thumbnails').on('init', function(slick) {
				  $('.quote-slider-thumbnails').fadeIn(1000);
			  }).slick({
				  slidesToShow: 6,
				  slidesToScroll: 1,
				  autoplay: false,
				  asNavFor: '.quote-slider-module',
				  dots: false,
				  centerMode: false,
				  focusOnSelect: true
			  });
		   }



			//remove active class from all thumbnail slides
			$('.quote-slider-thumbnails .slick-slide').removeClass('slick-active');

			//set active class to first thumbnail slides
			$('.quote-slider-thumbnails .slick-slide').eq(0).addClass('slick-active');

			var $progressBarQuote = $('.progress-bar');
			var $progressBarActive = $('.active-bar');
			// On before slide change match active thumbnail to current slide
			$('.quote-slider-module').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
			   var mySlideNumber = nextSlide;
			   $('.quote-slider-thumbnails .slick-slide').removeClass('slick-active');
			   $('.quote-slider-thumbnails .slick-slide').eq(mySlideNumber).addClass('slick-active');
			   var calc = ( (nextSlide) / (slick.slideCount) ) * 100;
			   var left = ( (nextSlide) / (slick.slideCount) ) * 100;
			   $progressBarQuote.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			   $progressBarActive.css('left', left + '%');
			   $('.quote-slider-thumbnails .slick-slide').eq(currentSlide).removeClass('slick-current');
			   $('.quote-slider-thumbnails .slick-slide').eq(nextSlide).addClass('slick-current');
			   $('.progress-bar .progress-inner').eq(currentSlide).removeClass('animate');
			   $('.progress-bar .progress-inner').eq(nextSlide).addClass('animate');
			});



		}

		$('.logos-slider').slick({
			slidesToShow: 6,
			slidesToScroll: 1,
			infinite: true,
			arrows: true,
			autoplay: true,
		    autoplaySpeed: 2000,
			dots: false,
			swipeToSlide: true,
			responsive: [
			  {
				breakpoint: 1023,
				settings: {
				  slidesToShow: 3,
				}
			  },
			  {
			   breakpoint: 640,
			   settings: {
				 slidesToShow: 2
			   }
			 }
			]
		});

		$('.logos-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	        $('.logos-slider .slide').addClass('first-click');
			$('.logos-slider button.slick-prev').addClass('active');
		});

		// Careers Icon Slider
		var containerWidth = $('.container').width();
		var windowWidth = $(window).width();
		var paddingWidth = (windowWidth - containerWidth ) / 2 - 8;
		var paddingWidthPx = paddingWidth + 'px';
		// console.log(paddingWidthPx);

		var $slickIconElement = $('.icon-slider');

		var $progressBarIcon = $('.progress');
		var $progressBarLabelIcon = $( '.slider__label' );

		$slickIconElement.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			var calc = ( (nextSlide + 1) / (slick.slideCount) ) * 100;
			$progressBarIcon.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			$progressBarLabelIcon.text( calc + '% completed' );
		});

		$slickIconElement.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: true,
		  // centerMode: true,
		  // centerPadding: paddingWidthPx,
		  autoplay: false,
		  slidesToShow: 3,
		  focusOnSelect: true,
		  responsive: [
		    {
		      breakpoint: 1023,
		      settings: {
		        slidesToShow: 2,
		      }
		  	},
			{
			 breakpoint: 640,
			 settings: {
			   slidesToShow: 1
			 }
		   }
		  ]
		});

		$('.icon-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	        $('.icon-slide').addClass('first-click');
			$('.icon-slider-container button.slick-prev').addClass('active');
		});

		var viewportWidth = jQuery(window).width();
	    if (viewportWidth < 768) {
			$('.speakers-bottom.mobile-slider').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				arrows: false,
				dots: false
			});
	    } else {
	        // Do some thing
	    }

		// Resources Feature Slider

		$('.resources-featured-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			speed: 500,
	        cssEase: "linear",
			infinite: true,
			arrows: false,
			dots: true,
			customPaging : function(slider, i) {
			   var thumb = $(slider.$slides[i]).data();
			   var i = i + 1;
			   return '<a>0'+i+'</a>';
		   },
	   });

	   if($(window).width() < 768) {
		   $('section.featured-module.best-practices-featured .post-container').slick({
			   fade: false,
			   slidesToShow: 1,
			   slidesToShow: 1,
			   infinite: true,
			   dots: 'false',
			   arrows: false
		   });
	   }

	   // Flip card slider

	   if($(window).width() < 768) {
		  $('section.flip-card-module .container .flip-card-container.mobile').slick({
			  fade: false,
			  slidesToShow: 1,
			  slidesToShow: 1,
			  infinite: false,
			  dots: 'false',
			  autoplay: false,
			  arrows: false
		  });
	  }

	   // Peer feature slider with preview slide
		   // prev/next/preview thumbnails images for slick slider
	    $('.peer-featured-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			speed: 500,
			cssEase: "linear",
			infinite: false,
			autoplay: false,
			responsive: [
			  {
				breakpoint: 767,
				settings: {
				   fade: false,
	 			   slidesToShow: 1,
	 			   infinite: true,
	 			   dots: 'false',
	 			   arrows: false
				}
			  }
			]
	    });
		if($(window).width() > 767) {
		    setTimeout(function() {
		      $('.slider-preview').append('<div class="next-slick-img video-container slick-thumb-nav"><div class="bg-container"><img src=""><span class="opacity-overlay"></span><span class="video-play-time"></span><span class="video-button"></div></div></div><div class="next-content"></div>');
		      get_prev_slick_img();
		      get_next_slick_img();

		    }, 500);
		    $(document).on('click', '.peer-featured-slider .slick-prev', function() {
		      get_prev_slick_img();
		    });
		    $(document).on('click', '.peer-featured-slider .slick-next', function() {
				console.log('next');
			    get_next_slick_img();
		    });
		    $('.peer-featured-slider').on('swipe', function(event, slick, direction) {
		      if (direction == 'left') {
		        get_prev_slick_img();
		      }
		      else {
		        get_next_slick_img();
		      }
		    });
		    $('.peer-featured-slider').on('click', 'li button', function() {
		      var li_no = $(this).parent('li').index();
		      if ($(this).parent('li').index() > li_no) {
		        get_prev_slick_img()
		      }
		      else {
		        get_next_slick_img()
		      }
		    });

		    function get_prev_slick_img() {
		      // For prev img
		      var prev_slick_img = $('.slick-current').prev('.peer-slide').find('.video-container .bg-container img').attr('src');
		      $('.prev-slick-img img').attr('src', prev_slick_img);
		      // For next img
		      var prev_next_slick_img = $('.slick-current').next('.peer-slide').find('.video-container .bg-container img').attr('src');
			  var prev_next_playtime = $('.slick-current').next('.peer-slide').find('.video-container .bg-container .video-play-time').html();
			  var next_slick_content = $('.slick-current').next('.peer-slide').find('.item-content-container').html();
		      $('.next-slick-img img').attr('src', prev_next_slick_img);
			  $('.next-slick-img .bg-container .video-play-time').html(prev_next_playtime);
			  $('.slider-preview .next-content').html(next_slick_content);
		    }
		    function get_next_slick_img() {
		      // For next img
		      var next_slick_img = $('.slick-current').next('.peer-slide').find('.video-container .bg-container img').attr('src');
			  var next_slick_content = $('.slick-current').next('.peer-slide').find('.item-content-container').html();
			  var prev_next_playtime = $('.slick-current').next('.peer-slide').find('.video-container .bg-container .video-play-time').html();

		      $('.next-slick-img img').attr('src', next_slick_img);
			  $('.next-slick-img .bg-container .video-play-time').html(prev_next_playtime);
			  $('.slider-preview .next-content').html(next_slick_content);
		      // For prev img
		      var next_prev_slick_img = $('.slick-current').prev('.video-container .bg-container img').find('img').attr('src');
		      $('.prev-slick-img img').attr('src', next_prev_slick_img);
		    }
		}
	    // End

		// Staff Slider

		var $slickElement = $('.staff-slider');

		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElement.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		    var
		        direction,
		        slideCountZeroBased = slick.slideCount - 1;

		    if (nextSlide == currentSlide) {
		        direction = "same";

		    } else if (Math.abs(nextSlide - currentSlide) == 1) {
		        direction = (nextSlide - currentSlide > 0) ? "right" : "left";

		    } else {
		        direction = (nextSlide - currentSlide > 0) ? "left" : "right";
		    }

		    // Add a temp CSS class for the slide animation (.slick-current-clone-animate)
		    if (direction == 'right') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElement).addClass('slick-current-clone-animate');
		    }

		    if (direction == 'left') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElement).addClass('slick-current-clone-animate');
		    }
		});

		$slickElement.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElement).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElement).removeClass('slick-current-clone-animate');
		});

		$slickElement.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			fade: true,
			speed: 1000,
			cssEase: "linear",
			infinite: true,
			autoplay: false,
			dots: false,
			arrows: true,
			responsive: [
			  {
				breakpoint: 767,
				settings: {
	 			   slidesToShow: 1,
	 			   infinite: true,
	 			   dots: false,
	 			   arrows: true
				}
			  }
			]
	    });

		$('.staff-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.staff-slider button.slick-prev').addClass('active');
		});

		// LIFESTYLE SLIDER
		var $slickElementLifestyle = $('.content-slider-container .lifestyle-slider');
		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElementLifestyle.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		    var
		        direction,
		        slideCountZeroBased = slick.slideCount - 1;

		    if (nextSlide == currentSlide) {
		        direction = "same";

		    } else if (Math.abs(nextSlide - currentSlide) == 1) {
		        direction = (nextSlide - currentSlide > 0) ? "right" : "left";

		    } else {
		        direction = (nextSlide - currentSlide > 0) ? "left" : "right";
		    }

		    // Add a temp CSS class for the slide animation (.slick-current-clone-animate)
		    if (direction == 'right') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElementLifestyle).addClass('slick-current-clone-animate');
		    }

		    if (direction == 'left') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElementLifestyle).addClass('slick-current-clone-animate');
		    }
		});

		$slickElementLifestyle.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElementLifestyle).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElementLifestyle).removeClass('slick-current-clone-animate');
		});

		$slickElementLifestyle.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: true,
		  centerMode: true,
		  centerPadding: '0',
		  autoplay: false,
		  slidesToShow: 3,
		  focusOnSelect: true,
		  responsive: [
			{
			 breakpoint: 767,
			 settings: {
			   slidesToShow: 1
			 }
		   }
		  ]
		});

		$('.content-slider-container .lifestyle-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.content-slider-container .lifestyle-slider').addClass('clicked');
			$('.slide').addClass('first-click');
			$('.content-slider-container button.slick-prev').addClass('active');
		});

		// KEYNOTE SLIDER
		var $slickElementKeynote = $('.keynote-slider-module .keynote-slider');

		var $progressBarKeynote= $('.keynote-progress');
		var $progressBarLabel = $( '.slider__label' );

		$slickElementKeynote.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			var calc = ( (nextSlide + 1) / (slick.slideCount) ) * 100;
			$progressBarKeynote.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			$progressBarLabel.text( calc + '% completed' );
		});
		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElementKeynote.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		    var
		        direction,
		        slideCountZeroBased = slick.slideCount - 1;

		    if (nextSlide == currentSlide) {
		        direction = "same";

		    } else if (Math.abs(nextSlide - currentSlide) == 1) {
		        direction = (nextSlide - currentSlide > 0) ? "right" : "left";

		    } else {
		        direction = (nextSlide - currentSlide > 0) ? "left" : "right";
		    }

		    // Add a temp CSS class for the slide animation (.slick-current-clone-animate)
		    if (direction == 'right') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElementKeynote).addClass('slick-current-clone-animate');
		    }

		    if (direction == 'left') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElementKeynote).addClass('slick-current-clone-animate');
		    }
		});

		$slickElementKeynote.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElementKeynote).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElementKeynote).removeClass('slick-current-clone-animate');
		});

		$slickElementKeynote.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: true,
		  autoplay: false,
		  slidesToShow: 2,
		  focusOnSelect: true,
		  responsive: [
			{
			 breakpoint: 640,
			 settings: {
			   slidesToShow: 1
			 }
		   }
		  ]
		});

		$('.keynote-slider-module .keynote-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.keynote-slider-module .keynote-slider').addClass('clicked');
			$('.slide').addClass('first-click');
			$('.keynote-slider-module button.slick-prev').addClass('active');
		});

		// Roundtables SLIDER
		var $slickElementRoundtable = $('.roundtable-card-slider-module .roundtable-card-slider');

		var $progressBarRoundtable= $('.cards-progress');
		var $progressBarLabel = $( '.slider__label' );

		$slickElementRoundtable.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			var calc = ( (nextSlide + 1) / (slick.slideCount) ) * 100;
			$progressBarRoundtable.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );

		});
		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElementRoundtable.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		    var
		        direction,
		        slideCountZeroBased = slick.slideCount - 1;

		    if (nextSlide == currentSlide) {
		        direction = "same";

		    } else if (Math.abs(nextSlide - currentSlide) == 1) {
		        direction = (nextSlide - currentSlide > 0) ? "right" : "left";

		    } else {
		        direction = (nextSlide - currentSlide > 0) ? "left" : "right";
		    }

		    // Add a temp CSS class for the slide animation (.slick-current-clone-animate)
		    if (direction == 'right') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElementRoundtable).addClass('slick-current-clone-animate');
		    }

		    if (direction == 'left') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElementRoundtable).addClass('slick-current-clone-animate');
		    }
		});

		$slickElementRoundtable.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElementRoundtable).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElementRoundtable).removeClass('slick-current-clone-animate');
		});

		$slickElementRoundtable.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: true,
		  autoplay: false,
		  slidesToShow: 3,
		  focusOnSelect: true,
		  responsive: [
			{
				 breakpoint: 640,
				 settings: {
				   slidesToShow: 1
				 }
			 },
			 {   breakpoint: 1023,
	  		     settings: {
	  		         slidesToShow: 2
	  			 }
			 }
		  ]
		});

		$('.roundtable-card-slider-module .roundtable-card-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.roundtable-card-slider-module .roundtable-card-slider').addClass('clicked');
			$('.slide').addClass('first-click');
			$('.roundtable-card-slider-module button.slick-prev').addClass('active');
		});

		// Values full screen blocks

		$('.value .progress-bar-container').on( 'click', function(e){
			e.preventDefault();
			var $thisSection = $(this).parents('.container').parents('.value');
			var $nextSection = $thisSection.next('.value');
			$('html, body').animate({ scrollTop: $nextSection.offset().top-0}, 1000);
		})

		// Content Slider (Home etc)

		var $slickElementContent = $('.content-slider-container .home-content-slider');

		var $progressBarContent= $('.home-component-progress');
		var $progressBarLabel = $( '.slider__label' );

		$slickElementContent.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			var calc = ( (nextSlide + 1) / (slick.slideCount) ) * 100;
			$progressBarContent.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			$progressBarLabel.text( calc + '% completed' );
		});
		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElementContent.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		    var
		        direction,
		        slideCountZeroBased = slick.slideCount - 1;

		    if (nextSlide == currentSlide) {
		        direction = "same";

		    } else if (Math.abs(nextSlide - currentSlide) == 1) {
		        direction = (nextSlide - currentSlide > 0) ? "right" : "left";

		    } else {
		        direction = (nextSlide - currentSlide > 0) ? "left" : "right";
		    }

		    // Add a temp CSS class for the slide animation (.slick-current-clone-animate)
		    if (direction == 'right') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide + slideCountZeroBased + 1) + '"]', $slickElementContent).addClass('slick-current-clone-animate');
		    }

		    if (direction == 'left') {
		        $('.slick-cloned[data-slick-index="' + (nextSlide - slideCountZeroBased - 1) + '"]', $slickElementContent).addClass('slick-current-clone-animate');
		    }
		});

		$slickElementContent.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElementContent).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElementContent).removeClass('slick-current-clone-animate');
		});

		$slickElementContent.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: true,
		  autoplay: false,
		  slidesToShow: 1,
		  focusOnSelect: true
		});

		$('.content-slider-container .home-content-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.content-slider-container .home-content-sliderr').addClass('clicked');
			$('.slide').addClass('first-click');
			$('.content-slider-container button.slick-prev').addClass('active');
		});

		// Scroll to buttons

		$('.scroll-to-link').on( 'click', function(e){
		    e.preventDefault();
		    var target = this.hash;
		    $target = $(target);
			$(this).addClass('active');
			$(this).siblings().removeClass('active');
		    $('html, body').animate({ scrollTop: $target.offset().top-100}, 1000);
		});

		// Event Year Scroll to
		$('.year-button').on( 'click', function(e){
		    e.preventDefault();
		    var target = $(this).data('date');
			$(this).addClass('active');
			$(this).siblings('.year-button').removeClass('active');
			$('html, body').animate({ scrollTop: $('.event-item.'+target+':visible:first').offset().top-80}, 1000);
		});

		// Desktop Search

		$('.search-button').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.header-search-container').slideUp(300);
			} else {
				$(this).addClass('active');
				$('.header-search-container').slideDown(300);
			}
		});

		// Animated Text

		if($('#animatedText').length ) {
			var animated_text = $('#animatedText').text();
			document.getElementById("animatedText").innerHTML = animated_text.replace(/([^\s]+)/g, '<span>$1</span>');
		}


		// Read more / less

		$('.text-excerpt .excerpt-text-more').on('click', function(e) {
			$(this).parents('.text-excerpt').hide();
			$(this).parents('.text-excerpt').siblings('.text-full').show();

			return;
		});

		$('.text-full .excerpt-text-less').on('click', function(e) {
			$(this).parents('.text-full').hide();
			$(this).parents('.text-full').siblings('.text-excerpt').show();

			return;
		});

		// Agenda Multi Day

		$('.day-switcher-container .agenda-days-switcher').on( 'click', function(e){
			e.preventDefault();
			var dayID = $(this).attr('href');
			if($(this).hasClass('active')){
			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				$('.agenda-day').removeClass('active');
				$(dayID).addClass('active');
			}
		});

		// Sneak Peak

		var peakspeed = "500";
		$('.sneak-peak-container .sneak-title').on( 'click', function(e){
			e.preventDefault();
			$thisParent = $(this).parents();
			$thisIndex = $(this).parents().index();
			// console.log($thisIndex);
			if($(this).hasClass('active')){
				// $(this).siblings('.sneak-peak-text').slideUp(peakspeed);
				// $(this).removeClass('active');
			} else {
				$($thisParent).parents().siblings('.sneak-image-container').children('.sneak-image-inner').children('.sneak-image').removeClass('active');
				$($thisParent).parents().siblings('.sneak-image-container').children('.sneak-image-inner').children('.sneak-image').eq($thisIndex).addClass('active');
				$($thisParent).siblings().children('.sneak-title').removeClass('active');
				$($thisParent).siblings().children('.sneak-peak-text').slideUp(peakspeed);
				$(this).siblings('.sneak-peak-text').slideDown(peakspeed);
				$(this).addClass('active');
			}
		});

		// Sneak Peak

		$('.experience-container .experience-text-container').on( 'click', function(e){
			e.preventDefault();
			$thisParent = $(this).parents();
			$thisIndex = $(this).index();
			// console.log($thisIndex);
			if($(this).hasClass('active')){
				// $(this).siblings('.sneak-peak-text').slideUp(peakspeed);
				// $(this).removeClass('active');
			} else {
				$($thisParent).siblings('.experience-image-container').children('.experience-image-inner').children('.experience-image').removeClass('active');
				$($thisParent).siblings('.experience-image-container').children('.experience-image-inner').children('.experience-image').eq($thisIndex).addClass('active');
				$(this).siblings('.experience-text-container').removeClass('active');
				$(this).addClass('active');
			}
		});

		// Services switcher module

		$('.switcher-container .module-switcher').on( 'click', function(e){
			e.preventDefault();
			$thisIndex = $(this).index();
			if($(this).hasClass('active')){
			} else {
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
				$('.module-container .switch-module').removeClass('active');
				$('.module-container .switch-module').eq($thisIndex).addClass('active');
			}
		});


		// Accordion + Expanding Blocks

		var speed = "500";
		var speedExpander = "1000";
		$('.expanding-block .expander-title').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('open')){
				$(this).siblings('.expanding-content').slideUp(speedExpander);
				$(this).removeClass('open');
			} else {
				$(this).siblings('.expanding-content').slideDown(speedExpander);
				$(this).addClass('open');
			}
		});


		$('.faq-item .question').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).next().slideUp(speed);
				$(this).removeClass('active');
				$(this).parents('.faq-container').removeClass('active');
			} else {
				$(this).next().slideDown(speed);
				$(this).addClass('active');
				$(this).parents('.faq-container').addClass('active');
				$(this).parents('.faq-container').siblings().removeClass('active');
				$(this).parents('.faq-container').siblings().children('.accordion-content').slideUp(speed);
				$(this).parents('.faq-container').siblings().children('.question').removeClass('active');
			}
		});

		$('.expand-all-text').on( 'click', function(e){
			if($(this).hasClass('open')){
				$('.accordion-content').slideUp(speed);
				$('.accordion-title').removeClass('open');
				$(this).removeClass('open');
				$(this).text('Expand all');
			} else {
				$('.accordion-content').slideDown(speed);
				$('.accordion-title').addClass('open');
				$(this).addClass('open');
				$(this).text('Close all');
			}
		});

		// NAV

		$('.parent-link').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).next('.child-container').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).next('.child-container').slideDown(300);
			}

		});

		$('a.nav').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$('header').removeClass('menu-open');
				$(this).parent('.buttonWrapper').removeClass('active');
				$(this).children('span.ham').removeClass('active');
				$('div.mobileMenu.mobileMenuMain').removeClass('active');
				$('li.main-dropdown > a').removeClass('active');
				$('.mobile-sub-menu').removeClass('active');
			} else {
				$(this).addClass('active');
				$('header').addClass('menu-open');
				$(this).parent('.buttonWrapper').addClass('active');
				$(this).children('span.ham').addClass('active');
				$('div.mobileMenu.mobileMenuMain').addClass('active');
				$('.mobileMenuMain').perfectScrollbar();
			}
		});

		$('a.navResources').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$('header').removeClass('menu-open');
				$(this).parent('.buttonWrapperResources').removeClass('active');
				$(this).children('span.ham').removeClass('active');
				$('div.mobileMenu.mobileMenuResources').removeClass('active');
			} else {
				$(this).addClass('active');
				$('header').addClass('menu-open');
				$(this).parent('.buttonWrapperResources').addClass('active');
				$(this).children('span.ham').addClass('active');
				$('div.mobileMenu.mobileMenuResources').addClass('active');
				$('.mobileMenuResource').perfectScrollbar();
			}
		});

		$('a.navSingleResources').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).parent('.buttonWrapperSingleResources').removeClass('active');
				$(this).children('span.ham').removeClass('active');
				$('.single-post-sticky').removeClass('sticky-open');
			} else {
				$(this).addClass('active');
				$(this).parent('.buttonWrapperSingleResources').addClass('active');
				$(this).children('span.ham').addClass('active');
				$('.single-post-sticky').addClass('sticky-open');
			}
		});

		$('a.close-menu').on( 'click', function(e){
			e.preventDefault();
			$('a.navResources').removeClass('active');
			$('header').removeClass('menu-open');
			$('.buttonWrapperResources').removeClass('active');
			$('span.ham').removeClass('active');
			$('div.mobileMenu.mobileMenuResources').removeClass('active');
			$('.buttonWrapper').removeClass('active');
			$('a.nav').removeClass('active');
			$('div.mobileMenu.mobileMenuMain').removeClass('active');
		});

		$('li.resource-mobile-dropdown > a').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).siblings('.sub-menu').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).siblings('.sub-menu').slideDown(300);
			}
		});



		$('li.main-dropdown > a').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).siblings('.mobile-sub-menu').removeClass('active');
			} else {
				$(this).addClass('active');
				$(this).siblings('.mobile-sub-menu').addClass('active');
			}
		});

		$('li.main-dropdown .mobile-sub-menu .mobile-menu-title').on( 'click', function(e){
			e.preventDefault();
			$('li.main-dropdown > a').removeClass('active');
			$('.mobile-sub-menu').removeClass('active');
		});

		// Mobile Sub Menu

		$('.main-menu-container ul > li.menu-item-has-children > a').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parent('li.menu-item-has-children').removeClass('active');
				$(this).siblings('ul.sub-menu').slideUp(300);
			} else {
				$(this).addClass('active');
				$(this).parent('li.menu-item-has-children').addClass('active');
				$(this).siblings('ul.sub-menu').slideDown(300);
			}
		});
	});


	$(window).on( 'scroll', function(){
		scrollAgenda();

		scrollRotate();

		scroll();

		if($('#animatedText').length ) {
			animatedText();
		}

		if($('.logo-ticker-tape').length ) {
			$('.logo-ticker-tape .band-container-backwards .moving-text').addClass('play');
		}

    });

	$(window).on('load',function (){

		match();
		outsideContainer();
		$('.loading-animation').addClass('loaded');
		timer = setTimeout(function(){
			$('.loading-animation').hide();
		}, 500);
		$('main').addClass('loaded');
		$('.banner-block').addClass('visible');

		if($('#yearButtons').length ) {
			var yearButtonTop = $('#yearButtons').offset().top - 80;
			$(window).on( 'scroll', function(){
				if ($(window).scrollTop() >= yearButtonTop ) {
					$('#yearButtons').addClass('sticky');
					$('.back-to-top').hide();
				} else {
					$('#yearButtons').removeClass('sticky');
					$('.back-to-top').show();
				}
			});
		}
	});

	$(window).on( 'resize', function(){
		match();
		outsideContainer();

		var viewportWidth = jQuery(window).width();
	    if (viewportWidth < 768) {
			$('.speakers-bottom.mobile-slider').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				arrows: false,
				dots: false
			});
	    } else {
	        $('.speakers-bottom.mobile-slider').slick('unslick');
	    }
	});

	function scroll() {
		var viewportWidth = jQuery(window).width();
		var scrollPos = $(document).scrollTop();
		if(scrollPos > 50) {
			$('header').addClass('scrolled');
		} else {
			$('header').removeClass('scrolled');
		}

		if($('body').hasClass('template-resources')){
			if (viewportWidth < 1024) {

			} else {
				if(scrollPos > 80) {
					$('header').addClass('resources-sticky');
				} else {
					$('header').removeClass('resources-sticky');
				}
			}
		} else {
			if(scrollPos > 0) {
				$('header').addClass('resources-sticky');
			} else {
				$('header').removeClass('resources-sticky');
			}
		}
		if (viewportWidth < 1024) {
			if(scrollPos > 80) {
				$('footer .container .footer-bottom .logo-container .back-to-top').addClass('visible');
			} else {
				$('footer .container .footer-bottom .logo-container .back-to-top').removeClass('visible');
			}
		}

		if($('body').hasClass('position')){
			if (viewportWidth < 1024) {
				var targetScroll = $('.position-title-column').offset().top + $('.position-title-column').outerHeight();
				if($(window).scrollTop() > targetScroll){
					$('.position-title-column .apply-container').addClass('sticky-bottom');
				} else {
					$('.position-title-column .apply-container').removeClass('sticky-bottom');
				}
			}
		}

		if($('.post-title-block').length ){
			if (viewportWidth > 1023) {
				var targetScroll = $('.post-title-block').offset().top + $('.post-title-block').outerHeight();
				if($(window).scrollTop() > targetScroll){
					$('.single-post-sticky').addClass('scrolled');
					$('.resources-sticky-menu').addClass('post-menu-scrolled');
				} else {
					$('.single-post-sticky').removeClass('scrolled');
					$('.resources-sticky-menu').removeClass('post-menu-scrolled');
				}
			}
		}

	}
	var lastScrollTop = 0;

	function scrollAgenda() {
		ww = $(window).width();
		var st = $(this).scrollTop();
		var $el = $('.day-switcher-container');
		var isPositionFixed = ($el.css('position') == 'fixed');
		if( ww > 767 ){
			if ($(this).scrollTop() > lastScrollTop){
				$el.css({'z-index': '100', 'height': '70px', 'background-color': '#121212'});
				$el.css({'top': '0px'});
				if ($(this).scrollTop() > 185 && !isPositionFixed ){
					$el.css({'position': 'fixed', 'top': '0px', 'z-index': '100'});
				}
				if ($(this).scrollTop() < 185 && isPositionFixed){
					$el.css({'position': 'absolute', 'top': '0px', 'z-index': '100'});
				}
			} else {
				if ($(this).scrollTop() < 210 && $(this).scrollTop() > 185 ){
					$el.css({'height': '70px'});
					$el.css({'position': 'fixed', 'top': '0px', 'z-index': '100'});
				} else if ($(this).scrollTop() < 185 ){
					$el.css({'position': 'absolute', 'top': '0px', 'z-index': '100'});
				} else {
					$el.css({'position': 'fixed', 'top': '70px', 'height': '35px'});
				}
			}
		} else {
			if ($(this).scrollTop() > lastScrollTop){
				$el.css({'z-index': '100', 'height': '60px'});
				$el.css({'top': '0px'});
				if ($(this).scrollTop() > 162 && !isPositionFixed ){
					$el.css({'position': 'fixed', 'top': '0px', 'z-index': '100'});
				}
				if ($(this).scrollTop() < 162 && isPositionFixed){
					$el.css({'position': 'absolute', 'top': '0px', 'z-index': '100'});
				}
			} else {
				if ($(this).scrollTop() < 162){
					$el.css({'height': '60px'});
					$el.css({'position': 'absolute', 'top': '0px', 'z-index': '5'});
				} else {
					$el.css({'position': 'fixed', 'top': '60px', 'height': '35px'});
				}
			}

		}


		lastScrollTop = st;
	}

	function isScrolledIntoView(el) {
	    var rect = el.getBoundingClientRect();
	    var elemTop = rect.top;
	    var elemBottom = rect.bottom;

	    // Only completely visible elements return true:
	    var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
	    // Partially visible elements return true:
	    // var isVisible = elemTop < window.innerHeight && elemBottom >= 0;
	    return isVisible;
	}

	function aos() {
		AOS.init({
			startEvent: 'load',
			disable: 'mobile',
			once: true
		});
	}

	function select2CopyClasses(data, container) {
	    if (data.element) {
	        $(container).addClass($(data.element).attr("class"));
	    }
	    return data.text;
	}

	function outsideContainer() {
		ww = $(window).width();
		container = $('.icon-slider-container .container').width();
		outsideContainerWidth = ww - container;
		slideWidthIcon = $('.icon-slider div.slide').width();
		arrowLeftPos = outsideContainerWidth / 2 - 66;
		arrowRightPos = outsideContainerWidth / 2 + 66;
		coverWidth = outsideContainerWidth / 2;
		$('.icon-slider-container .leftSlideCover').css('width', coverWidth);
	}

	function match() {
		$('section.team-block .column.team-column .column').matchHeight();
		$('.speaker.one-quarter').matchHeight();
		$('.megaMenu.eventsMenu .column').matchHeight();
		$('.megaMenu.resourcesMenu .column').matchHeight();
		$('.megaMenu.serviceMenu .column').matchHeight();
		$('.megaMenu.howHelpMenu .column').matchHeight();
		$('.megaMenu.whoHelpMenu .column').matchHeight();
		$('.mega-menu .column .icon-container').matchHeight();
		$('.column .case-study-text').matchHeight();
		$('.column .text-container .text').matchHeight();
		$('.press-release-item .container .column').matchHeight();
		$('.title-container > div').matchHeight();
		$('.text-title-list-inner .column').matchHeight();
		$('.item.market-trend-reports .item-column').matchHeight();
		$('.item.full-width .item-column').matchHeight();
		$('.post-article-container .left-column, .post-article-container .post-content').matchHeight();
	}

	function scrollRotate() {
	    var image = document.getElementById("rotatingImage");
		if(image){
			image.style.transform = "rotate(" + window.pageYOffset/5 + "deg)";
		}
	}

	function animatedText() {
		var textcontainer = document.getElementById("animatedText");
		var totalCount=$(textcontainer).children().length;
		var rect = textcontainer.getBoundingClientRect();
	    var elemTop = rect.top;
	    var elemBottom = rect.bottom;
		var scrollTop = $(window).scrollTop();
		var textHeight = elemBottom - elemTop;
		var letterTop = elemTop / totalCount;
		var letterScroll = letterTop / totalCount;
		var percentage = (textHeight / totalCount) * letterScroll;
		var letterIndex = (percentage - totalCount) * -1;

		$('#animatedText').children('span').each(function() {
			var textIndex = $(this).index();
			if( textIndex > letterIndex ){
				$(this).addClass('active');
			} else {
				$(this).removeClass('active');
			}
		});
	}

	function scrollProgressBar() {
	  var getMax = function () {
	    return $(document).height() - $(window).height();
	  };

	  var getValue = function () {
	    return $(window).scrollTop();
	  };

	  var progressBar = $(".progress-bar"),
	    max = getMax(),
	    value,
	    width;

	  var getWidth = function () {
	    // Calculate width in percentage
	    value = getValue();
	    width = (value / max) * 100;
	    width = width + "%";
	    return width;
	  };

	  var setWidth = function () {
	    progressBar.css({ width: getWidth() });
	  };

	  $(document).on("scroll", setWidth);
	  $(window).on("resize", function () {
	    // Need to reset max
	    max = getMax();
	    setWidth();
	  });
	}

})(window.jQuery);
