(function($){

	// Coalesces a handler so it runs at most once per animation frame,
	// instead of once per raw scroll event (which can fire many times per
	// frame on trackpads/high-polling-rate mice). Still updates every frame
	// (~60fps), so nothing looks different -- it just skips redundant reads
	// of offset()/outerHeight() etc. that would otherwise re-run multiple
	// times before the browser even repaints.
	function rafThrottle(fn) {
		var ticking = false;
		return function() {
			var args = arguments, ctx = this;
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(function() {
					fn.apply(ctx, args);
					ticking = false;
				});
			}
		};
	}

	$(document).ready(function (){

		// STANDARD
		@@include('includes/_maps.js')

		match();
		outsideContainer();
		aos();

		if($('.progress-container').length ){
			scrollProgressBar();
		}

		if($('section.list-block').length ){
			bindMobileToggles();
		}

		// BACK TO TOP

		$('.back-to-top').on('click', function (e) {
	        e.preventDefault();
			$('html, body').animate({ scrollTop: 0}, 1000);
		});

		$('.back-to-top-sticky').on('click', function (e) {
	        e.preventDefault();
			$('html, body').animate({ scrollTop: 0}, 1000);
		});

		// FOOTER LINK REVEAL
		if($(window).width() < 767) {
			$('.footer-column-title-wrapper').on('click', function (e) {
		        e.preventDefault();
				if($(this).hasClass('active')){
					$(this).removeClass('active');
					$(this).siblings('.footer-link-container-wrapper').slideUp(300);
				} else {
					$(this).parents('.footer-column').siblings('.footer-column').children('.footer-column-title-wrapper').removeClass('active');
					$(this).parents('.footer-column').siblings('.footer-column').children('.footer-link-container-wrapper').slideUp(300);
					$(this).addClass('active');
					$(this).siblings('.footer-link-container-wrapper').slideDown(300);
				}
			});
		}

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

		// services mobile 

		$('.mobile-services-dropdown').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
				$(this).parent('.services-container').removeClass('active');
			} else {
				$(this).addClass('active');
				$(this).parent('.services-container').addClass('active');
			}
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

		// SCROLL UP TO SEE FULL MENU

		var lastScrollTop = 0;
		$(window).scroll(function(event){
		   var st = $(this).scrollTop();
		   if (st > lastScrollTop){
		        $('header').removeClass('scrolled-up');
				$('#yearButtons').removeClass('scrolled-up');
		   } else {
		        $('header').addClass('scrolled-up');
				$('#yearButtons').addClass('scrolled-up');
		   }
		   lastScrollTop = st;
		});

		// parallax

		$(window).on('scroll', rafThrottle(function() {
			var scrollTop = $(window).scrollTop();
			var windowHeight = $(window).height();
			var $parallax = $('.parallax-image-module');
			if (!$parallax.length) return;

			var moduleOffset = $parallax.offset().top;
			var moduleHeight = $parallax.outerHeight();
			if ($(window).width() <= 767) {
				var parallaxSpeed = 0.2;
			} else {
				var parallaxSpeed = 0.6;
			}

			// Calculate how much of the module is in view
			var distanceIntoView = (scrollTop + windowHeight) - moduleOffset;

			if (distanceIntoView > 0 && scrollTop < moduleOffset + moduleHeight) {
				// How much of the module is in view relative to the viewport height
				var progress = distanceIntoView / (windowHeight + moduleHeight); // normalized between 0 and ~1

				var marginShift = progress * scrollTop * parallaxSpeed;

				$parallax.css({
					'margin-top': -marginShift + 'px'
				});
			} else {
				// If not in view, reset
				// $parallax.css({
				// 	'margin-top': '0px'
				// });
			}
		}));






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
		$('body').on('click', '.slide-out-bio', function(e) {		
			e.preventDefault();
			var $href = $(this).attr('href');
			if($('.full-bio' + $href).hasClass('active')){
				$('.full-bio' + $href).removeClass( 'active' );
				$('.click-overlay').removeClass('active');
				$('html').removeClass('overflow-hidden');
			} else {
				$('html').addClass('overflow-hidden');
				$('.full-bio' + $href).addClass( 'active' );
				$('.full-bio' + $href).siblings().removeClass( 'active' );
				$('.full-bio' + $href).next('.click-overlay').addClass('active');
			}
		});

		// HIDE FULL BIO
		$('body').on('click', '.close-bio', function(e) {
			$('.full-bio').removeClass('active');
			$('.click-overlay').removeClass('active');
			$('html').removeClass('overflow-hidden');
		});
		$('body').on('click', '.click-overlay', function(e) {
			$('.full-bio').removeClass('active');
			$('.click-overlay').removeClass('active');
			$('html').removeClass('overflow-hidden');
		});


		// SHOW story slide out
		$('body').on('click', '.slide-out-story-button', function(e) {		
			e.preventDefault();
			var $href = $(this).attr('href');
			if($('.slide-out-story-item' + $href).hasClass('active')){
				$('.slide-out-story-item' + $href).removeClass( 'active' );
				$('.click-overlay').removeClass('active');
				$('html').removeClass('overflow-hidden');
			} else {
				$('html').addClass('overflow-hidden');
				$('.slide-out-story-item' + $href).addClass( 'active' );
				$('.slide-out-story-item' + $href).siblings().removeClass( 'active' );
				$('.click-overlay').addClass('active');
			}
		});

		// HIDE story slide out
		$('body').on('click', '.close-story', function(e) {
			$('.slide-out-story-item').removeClass('active');
			$('.click-overlay').removeClass('active');
			$('html').removeClass('overflow-hidden');
		});
		$('body').on('click', '.click-overlay', function(e) {
			$('.slide-out-story-item').removeClass('active');
			$('.click-overlay').removeClass('active');
			$('html').removeClass('overflow-hidden');
		});

		// Post read more for authors

		$(".speaker-details-excerpt").html(function(){
		  var text= $(this).text().trim().split(" ");
		  var last = text.pop();
		  return text.join(" ") + (text.length > 0 ? " <span class='speaker-excerpt-see-all'>" + last + "</span>" : last);
		});

		$('.speaker-excerpt-see-all').on('click', function(e) {
			$(this).parents('.speaker-details-excerpt').hide();
			$(this).parents('.speaker-details-excerpt').siblings('.speaker-details').slideDown(300);

			return;
		});

		$('.speaker-details-less').on('click', function(e) {
			$(this).parents('.speaker-details').slideUp(300);
			$(this).parents('.speaker-details').siblings('.speaker-details-excerpt').show();

			return;
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

		var menutimer;
		// mega menu

		$('.dropdown > a').on('mouseover',function (e){
			if ($(window).width() >= 1024) {
				clearTimeout(menutimer);
				$(this).parents('.dropdown').siblings('.dropdown').removeClass('active');
				$(this).parents('.dropdown').addClass('active');
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

		$('.dropdown > a').on('mouseout',function (e){
			if ($(window).width() >= 1024) {
				menutimer= setTimeout(function(){
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
				}, 500);
			}
		});

		$('.megaMenu').on('mouseover',function (e){
			if ($(window).width() >= 1024) {
				clearTimeout(menutimer);
			}
		});

		$('.megaMenu').on('mouseout',function (e){
			if ($(window).width() >= 1024) {
				menutimer = setTimeout(function(){
					$('.megaMenu').removeClass('active');
					$('.dropdown').removeClass('active');
				}, 500);
			}
		});

		// Services Menu hovers

		$(".services-hover").on("mouseenter", function () {
			// Remove 'active' class from all services-hover and services-content elements
			$(".services-hover, .services-content").removeClass("active");

			// Add 'active' class to the hovered element
			$(this).addClass("active");

			// Activate the corresponding content
			if ($(this).hasClass("it-leaders-switch")) {
				$("#itLeaders").addClass("active");
			} else if ($(this).hasClass("tech-leaders-switch")) {
				$("#techVendors").addClass("active");
			}
		});

		// Scroll To Button

		$('.scroll-to-button').on( 'click', function(e){
			e.preventDefault();
			$section = $(this).attr('href');
			if($(window).width() > 900) {
		    	$('html, body').animate({ scrollTop: $($section).offset().top - 80 }, 1000);
			} else {
				$('html, body').animate({ scrollTop: $($section).offset().top - 60 }, 1000);
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

		$( "#mobilesearch" ).keyup(function() {
		  	$('.search-clear').addClass('active');
		});

		$( "#search" ).keyup(function() {
		  	$('.close-clear-container').addClass('active');
		});

		$('.search-close').on( 'click', function(e){
			e.preventDefault();
			$('.search-dropdown').removeClass('active');
		});

		$('.backTop').on('click', function(e) {
			$('html, body').animate({ scrollTop: $('body').offset().top - 0}, 1000);
		});

		// inclusions 

		$('.inclusion-trigger').on('click', function(e) {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).text('View all inclusions');
				$(this).siblings('.inclusions-container').slideUp(300);	
			} else {
				$(this).addClass('active');
				$(this).text('View fewer inclusions');
				$(this).siblings('.inclusions-container').slideDown(300);
			}
		});

		$('.slide .tooltip-icon').on('click', function(e) {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).siblings('.tooltip').removeClass('active');
			} else {
				$(this).addClass('active');
				$(this).siblings('.tooltip').addClass('active');
			}
		});	
		
		$('.slide .tooltip-close').on('click', function(e) {
			$('.tooltip').removeClass('active');			
		});

		// Post article images popup

		// var images = $('.article-content .post-text img');
		// $(images).each(function() {
		//    var imageSrc = $(this).attr('src');
		//    $(this).wrap('<a class="post-popup" href="'+ imageSrc +'"></a>');
		//    $(this).parents('a.post-popup').append('<span class="enlarge-image"></span>');
		// });

		$('.post-popup').magnificPopup({
			type: 'image',
			mainClass: 'mfp-post-img'
		});

		$('.formPopupHubspot').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'form-container-preview'
			});
		});

		$('.formPopupHubspotHome').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'home-animation-popup'
			});
		});

		$('.formPopupHubspotSpeaker').each(function(){
			$(this).magnificPopup({
				type: 'inline',
				mainClass: 'home-animation-popup'
			});
		});

		$('a.form-popup-button').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-registration'
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

		$('a.apply-button').magnificPopup({
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

		$('a.speaker-popup-single').magnificPopup({
			type: 'inline',
			mainClass: 'mfp-speakers',
			preloader: false
		});

		$('.register-scroll-button').magnificPopup({
			type: 'inline',
			preloader: false,
			mainClass: 'mfp-registration',
			callbacks: {
			  open: function() {
				  $(window).trigger('resize');
			  }
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

		$('.download-popup-button').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.container').siblings('.downloadPopupContainer').children('.downloadPopup'),
					type: 'inline'
				},
				mainClass: 'download-container',
				callbacks: {
				  open: function() {
					  $(window).trigger('resize');
				  }
	  	  		}
			});
		});


		$('.download-popup-button-multi').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).siblings('.downloadPopupContainer').children('.downloadPopup'),
					type: 'inline'
				},
				mainClass: 'download-container',
				callbacks: {
				  open: function() {
					  $(window).trigger('resize');
				  }
	  	  		}
			});
		});


		$('.resources-popup-button').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).siblings('.resourcesPopupContainer').children('.resourcesPopup'),
					type: 'inline'
				},
				disableOn: 0,
				mainClass: 'download-container',
				callbacks: {
				  open: function() {
					  $(window).trigger('resize');
				  }
				}
			});
		});

		$(document).on('click', '.pause-autoplay', function(e) {
			e.preventDefault();

			var $video = $(this).siblings('video').get(0);

			if ($video) {
				if ($video.paused) {
				$video.play();
				$(this).removeClass('paused'); // optional styling toggle
				} else {
				$video.pause();
				$(this).addClass('paused');
				}
			}
		});

		// resources switcher

		$(document).on("click",'ul.download-switch-container.dropdown-select li.download-switch.active',function(e) {
			if($(this).hasClass('open')){
				$(this).removeClass('open');
				$(this).siblings('li').removeClass('show');
				$(this).parents('ul.download-switch-container.dropdown-select').removeClass('open');
				if($('span.download-switch-span').hasClass('text-red')){
				} else {
					$('span.download-switch-span').addClass('text-red');
				}
			} else {
				$(this).siblings('li').addClass('show');
				$(this).addClass('open');
				$(this).parents('ul.download-switch-container.dropdown-select').addClass('open');
			}
		});

		$(document).on("click",'a.download-switcher',function(e) {
			e.preventDefault();
		    var href = $(this).attr('href');
		    $(href).addClass("active");
			$('li.download-switch').removeClass("show");
			$('li.download-switch').removeClass("open");
			if($('span.download-switch-span').hasClass('text-red')){
			} else {
				$('span.download-switch-span').addClass('text-red');
			}
			$(this).parents('li').parents('ul.download-switch-container.dropdown-select').removeClass('open');
			$(href).siblings().removeClass("active");
			$(this).parents('li').addClass("active");
		    $(this).parents('li').siblings().removeClass("active");
		});


		$('.formPopupCardButton').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.twoColumnCard').children('.cardPopupContainer').children('.cardPopup'),
					type: 'inline'
				},
				mainClass: 'form-container'
			});
		});

		$('.formPopupCardTextButton').each(function(){
			$(this).magnificPopup({
				items: {
					src: $(this).parent('.textContainer').parent('.twoColumnCard').children('.cardPopupContainer').children('.cardPopup'),
					type: 'inline'
				},
				mainClass: 'form-container'
			});
		});


		// Generic Register form hidden fields

		if($('.webinar-register-form').length ){

			var hiddenName = $('.hidden-name').text();
			var hiddenEvent = $('.hidden-event').text();
			var hiddenDate = $('.hidden-date').text();
			var hiddenID = $('.hidden-id').text();
			var genericForm = $('.webinar-register-form .form-container form');
			setTimeout(function(){
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').attr('value', hiddenName);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').val(hiddenName).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').attr('value', hiddenEvent);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').val(hiddenEvent).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').attr('value', hiddenDate);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').val(hiddenDate).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').attr('value', hiddenID);
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').val(hiddenID).change();
			}, 2000);

			if($('.client-communication-title').length ){
				var communicationTitle = $('.client-communication-title').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_method').children('label').children('span').html(communicationTitle);
				}, 2000);
			}

			if($('.client-communication-text').length ){
				var communicationText = $('.client-communication-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_method').children('legend.hs-field-desc').html(communicationText);
				}, 2000);
			}

			if($('.gift-opt-in-text').length ){
				var giftOptIn = $('.gift-opt-in-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_gift_opt_in').children('legend.hs-field-desc').html(giftOptIn);
				}, 2000);
			}

			if($('.marketing-text').length ){
				var marketingOptIn = $('.marketing-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_single_client_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(marketingOptIn);
				}, 2000);
			}

			if($('.critical-text').length ){
				var criticalLabel = $('.critical-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable_').children('label').children('span').html(criticalLabel);
				}, 2000);
			}

			if($('.critical-help-text').length ){
				var criticalHelp = $('.critical-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable_').children('legend').html(criticalHelp);
				}, 2000);
			}

			if($('.umbrella-help-text').length ){
				var umbrellaHelp = $('.umbrella-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('legend.hs-field-desc').html(umbrellaHelp);
				}, 2000);
			}

			if($('.umbrella-text').length ){
				var umbrellaOptIn = $('.umbrella-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(umbrellaOptIn);
				}, 2000);
			}

			if($('.newsletter-help-text').length ){
				var newsletterHelp = $('.newsletter-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_newsletter_opt_in ').children('legend.hs-field-desc').html(newsletterHelp);
				}, 2000);
			}
		}

		// GTM Map hovers

		$('.map-container .card-hover-container').each(function() {
			var $container = $(this);
			var $trigger = $container.find('.card-hover-trigger');
			var $card = $container.find('.gtm-card');

			$trigger.on('mouseenter', function() {
				$('.map-container .card-hover-trigger, .map-container .gtm-card').removeClass('active');
				$trigger.addClass('active');
				$card.addClass('active');
			});
		});

		// Customer events scroll magic

		const $scrollContainers = $('.fixed-scroller-inner');

		if ($scrollContainers.length && $(window).width() > 767) {
			// Ensure GSAP and ScrollMagic are loaded -- moved inside this guard
			// because GSAP is now only enqueued on the templates that actually
			// use it (see my_enqueue_scripts() in functions.php). Referencing
			// TweenLite/ScrollMagic unconditionally at the top level broke
			// every other page with "TweenLite is not defined" once GSAP
			// stopped loading site-wide.
			TweenLite.defaultEase = Linear.easeNone;
			var controllerTeam = new ScrollMagic.Controller();

			$scrollContainers.each(function() {
				const $scrollContainer = $(this);
				const $featuredContainerOuter = $scrollContainer.closest('.fixed-scroller');
				const $featuredContainer = $scrollContainer.closest('.fixed-scroller-container');
				const $teamMembers = $scrollContainer.find('.fixed-scroll-item');
				const memberWidth = 965;
				const gapWidth = 32;
				const totalWidth = ($teamMembers.length * (memberWidth + gapWidth));

				$scrollContainer.css('width', totalWidth + 'px');

				// Get the width of the featured-scroller-container
				const containerWidth = $featuredContainer.length ? $featuredContainer.width() : $(window).width();
				const endScroll = Math.max(0, totalWidth - containerWidth); // Ensure endScroll is not negative

				// Create a TimelineMax instance
				var tl = new TimelineMax();

				// Add the horizontal scroll animation to the timeline
				tl.to($scrollContainer[0], { x: -endScroll, ease: 'none' });

				// Horizontal scroll scene
				new ScrollMagic.Scene({
					triggerElement: $featuredContainerOuter[0],
					triggerHook: 0,
					duration: endScroll
				})
				.setPin($featuredContainer[0])
				.setTween(tl)
				.addTo(controllerTeam);

				// Vertical scroll continuation
				new ScrollMagic.Scene({
					triggerElement: $featuredContainerOuter[0],
					triggerHook: 0,
					duration: $(window).height()
				})
				.setTween(gsap.to($featuredContainer[0], { height: 'auto', ease: 'none' }))  // GSAP 3 syntax
				.addTo(controllerTeam);
			});

			// Handle window resize to destroy scenes if width drops below 767px
			$(window).on('resize', function() {
				if ($(window).width() <= 767) {
					$scrollContainers.each(function() {
						const $scrollContainer = $(this);
						const horizontalScene = $scrollContainer.data('horizontalScene');
						const verticalScene = $scrollContainer.data('verticalScene');

						if (horizontalScene) horizontalScene.destroy(true);
						if (verticalScene) verticalScene.destroy(true);
					});
				}
			});

			AOS.refresh();
		}

		// scrolling grow text
		var $scrollingContainers = $('.scrolling-container');

		if ($scrollingContainers.length && $(window).width() > 767) {
			// Ensure GSAP and ScrollMagic are loaded -- moved inside this guard
			// for the same reason as the customer-events scroll magic block
			// above (GSAP is conditionally enqueued now, not site-wide).
			var controllerText = new ScrollMagic.Controller();

			$scrollingContainers.each(function() {
				var $scrollContainer = $(this);
				var $fixedScrollerContainer = $scrollContainer.closest('.map-fixed-scroller-container');
				var $columns = $scrollContainer.find('.column');
				var $titles = $scrollContainer.find('.growing-title');

				// Ensure there are columns to work with
				if ($columns.length === 0) {
					console.error('No .column elements found.');
					return;
				}

				// Calculate the total width needed for horizontal scrolling
				var memberWidth = $columns.outerWidth(true);
				var gapWidth = 32; // Adjust this if necessary
				var columnCount = $columns.length - 1;
				var totalWidth = (columnCount * (memberWidth + gapWidth)) - gapWidth; // Subtract gapWidth as it's added at the end

				// Set the width of the scrolling container
				$scrollContainer.css('width', totalWidth + 'px');

				// Create GSAP timeline for horizontal scrolling
				var tlHorizontal = new TimelineMax();
				tlHorizontal.to($scrollContainer[0], 1, { x: -totalWidth, ease: Linear.easeNone });

				// Create ScrollMagic scene for horizontal scrolling
				new ScrollMagic.Scene({
					triggerElement: $fixedScrollerContainer[0],
					triggerHook: 0,
					duration: totalWidth
				})
				.setPin($fixedScrollerContainer[0])
				.setTween(tlHorizontal)
				.addTo(controllerText);

				// Create GSAP animations for each title
				$titles.each(function() {
					var $title = $(this);
					var titleWidth = $title.outerWidth(true);
					var titleOffset = $title.position().left; // Offset relative to the container

					// Create GSAP ScrollTrigger for each title
					gsap.to($title[0], {
						fontSize: '160px', // End font size
						ease: 'none',
						scrollTrigger: {
							trigger: $title[0],
							containerAnimation: tlHorizontal, // Link to horizontal scroll animation
							start: "center 100%",
							end: "center 10%",
							scrub: true
						}
					});
				});

			});

			// Handle window resize to destroy scenes if width drops below 767px
			$(window).on('resize', function() {
				if ($(window).width() <= 767) {
					controllerText.destroy(true);
				}
			});
		}

	// Customer Stories hero slideblock
		
		var $slidesStory = $('.hero-slider-container .storyslide');
		var currentIndexStory = 0;

		function setActiveSlideStory(index) {
			$slidesStory.removeClass('active');
			$slidesStory.eq(index).addClass('active');
			currentIndexStory = index;
		}

		$slidesStory.on('click', function() {
			var index = $(this).index();
			setActiveSlideStory(index);
		});

		setActiveSlideStory(0);


		// Customer Events Sliders 

		// Two column speaker image slider
		
		var $slides = $('.customer-events-image-slider .slide');
		var currentIndex = 0;
		var autoplayInterval;

		function setActiveSlide(index) {
			$slides.removeClass('active');
			$slides.eq(index).addClass('active');
			// resetProgressBar($slides.eq(index));
			currentIndex = index;
		}

		function autoplaySlides() {
			autoplayInterval = setInterval(function() {
				var nextIndex = (currentIndex + 1) % $slides.length;
				setActiveSlide(nextIndex);
			}, 5000); // 5 seconds per slide
		}

		function resetAutoplay() {
			clearInterval(autoplayInterval);
			autoplaySlides();
		}

		function resetProgressBar($slide) {
			var $progressInner = $slide.find('.progress-inner');

			// Reset width to 0
			$progressInner.css('width', '0');

			// Force reflow to flush the style change
			void $progressInner[0].offsetWidth;

			// Then set to 100% to trigger the transition
			$progressInner.css('width', '100%');
		}


		$slides.on('click', function() {
			var index = $(this).index();
			setActiveSlide(index);
			resetAutoplay();
		});

		// Start autoplay initially
		setActiveSlide(0);
		autoplaySlides();
		
		// Speaker Text Slide


		// Two column speaker image slider
		var $slidesSpeakerImage = $('.speaker-slider-image-outer .speaker-slide-image');
		var $slidesSpeakerText = $('.speaker-slider-text-outer .speaker-slide-text');
		var currentIndexSpeaker = 0;
		var autoplayIntervalSpeaker;

		// Function to set the active slide
		function setActiveSlideSpeaker(index) {
			$slidesSpeakerImage.removeClass('active');
			$slidesSpeakerText.removeClass('active');
			
			$slidesSpeakerImage.eq(index).addClass('active');
			$slidesSpeakerText.eq(index).addClass('active');
			
			currentIndexSpeaker = index;
		}

		// Autoplay functionality for screens larger than 767px
		function autoplaySlidesSpeaker() {
			if ($(window).width() > 767) {
				autoplayIntervalSpeaker = setInterval(function() {
					var nextIndexSpeaker = (currentIndexSpeaker + 1) % $slidesSpeakerImage.length;
					setActiveSlideSpeaker(nextIndexSpeaker);
				}, 6000); // 5 seconds per slide
			}
		}

		// Reset autoplay (clear and restart) for screens larger than 767px
		function resetAutoplaySpeaker() {
			if ($(window).width() > 767) {
				clearInterval(autoplayIntervalSpeaker);
				autoplaySlidesSpeaker();
			}
		}

		// Stop autoplay for small screens
		function stopAutoplaySpeaker() {
			clearInterval(autoplayIntervalSpeaker);
		}

		// Start autoplay initially if screen is larger than 767px
		$(window).on('load resize', function() {
			if ($(window).width() > 767) {
				autoplaySlidesSpeaker();
			} else {
				stopAutoplaySpeaker();  // Stop autoplay on smaller screens
			}
		});

		// Click event for slides, active on all screens
		$slidesSpeakerText.on('click', function() {
			var index = $(this).index();
			setActiveSlideSpeaker(index);
			if ($(window).width() > 767) {
				resetAutoplaySpeaker();  // Reset autoplay if applicable
			}
		});

		// speaker filter mobile 

		$('.filter-container .mobile-trigger').on('click', function() {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.filter-container form').removeClass('active');
			} else {
				$(this).addClass('active');
				$('.filter-container form').addClass('active');
			}
		});
		

		// Speaker Ajax 

		// Pagination event (with preventDefault)
		$('body').on('click', '.speaker-pagination-container a', function(e) {
			e.preventDefault(); // Prevent navigation
			var page = $(this).attr('href').split('/page/')[1]; // Get the page number
			if (!page) {
				page = 1; // Default to page 1 if not found
			}
			fetchSpeakers(page); // Fetch speakers for the selected page
		});

		// Filter event (change event on checkboxes)
		$('#speakerFilter').on('change', 'input[type="checkbox"]', function() {
			fetchSpeakers(1); // Fetch speakers with selected filters (starting from page 1)
		});

		// Function to get selected filters. Returns an empty array when
		// nothing is checked -- the AJAX handler (filter_speakers_callback
		// in functions.php) treats an empty expertise list as "no filter
		// applied" and shows every post. This used to fall back to grabbing
		// every checkbox's value when none were checked, which meant "no
		// filter" was actually sent as "every expertise term selected at
		// once" -- combined with the tax_query's AND operator (a post must
		// have ALL the sent terms), that matched zero posts, since no single
		// post is tagged with every expertise term.
		function getSelectedFilters() {
			var selectedExpertise = $('#speakerFilter input:checked');

			return selectedExpertise.map(function() {
				return this.value;
			}).get(); // Convert jQuery object to array of values
		}

		// Function to fetch speakers based on selected filters and pagination
		function fetchSpeakers(page) {
			page = page || 1; // Default page to 1 if undefined
			var expertise = getSelectedFilters(); // Get selected filters (empty array if none checked)
			var data = {
				action: 'filter_speakers',
				paged: page, // Pass paged as parameter
				expertise: expertise
			};

			// AJAX request to fetch filtered speakers
			$.ajax({
				url: ajaxobject.ajaxurl,
				type: 'POST',
				data: data,
				success: function(response) {
					var jsonResponse = typeof response === 'object' ? response : JSON.parse(response);

					// Update speakers container
					if (jsonResponse.speakers) {
						$('#speakers-container').html(jsonResponse.speakers);
					} else {
						$('#speakers-container').html('<p>No speakers found.</p>');
					}

					// Update pagination and set active state
					updatePagination(jsonResponse.pagination, page);

					// Scroll to the top of the .speaker-filter-inner with offset
					var $filterInner = $('.speaker-filter-inner');
					if ($filterInner.length) {
						$('html, body').animate({
							scrollTop: $filterInner.offset().top - 100 // Offset by 100px
						}, 0); // 500ms animation duration
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
				}
			});
		}

		// Function to update pagination and set active state
		function updatePagination(paginationHtml, currentPage) {
			$('.page-navi-container').html(paginationHtml);

			// Update active state for the current page
			$('.speaker-pagination-container a').removeClass('active');
			$('.speaker-pagination-container a[href*="/page/' + currentPage + '"]').addClass('active'); // Correct selector

			// Ensure pagination links have correct structure (/page/2)
			$('.speaker-pagination-container a').each(function() {
				var href = $(this).attr('href');
				if (href && href.indexOf('admin-ajax.php') !== -1) {
					var paged = href.match(/paged=(\d+)/);
					if (paged && paged[1]) {
						$(this).attr('href', '/page/' + paged[1]); // Replace with /page/X
					}
				}
			});
		}

		// Partner Ajax 

		// Pagination event (with preventDefault)
		$('body').on('click', '.partner-pagination-container a', function(e) {
			e.preventDefault(); // Prevent navigation
			var page = $(this).attr('href').split('/page/')[1]; // Get the page number
			if (!page) {
				page = 1; // Default to page 1 if not found
			}
			fetchPartners(page); // Fetch speakers for the selected page
		});

		// Filter event (change event on checkboxes)
		$('#partnerFilter').on('change', 'input[type="checkbox"]', function() {
			fetchPartners(1); // Fetch speakers with selected filters (starting from page 1)
		});

		// Function to get selected filters, or all filters if none are checked
		function getSelectedFiltersPartners() {
			var selectedExpertisePartners = $('#partnerFilter input:checked');

			// If none are selected, get all filter values
			if (selectedExpertisePartners.length === 0) {
				selectedExpertisePartners = $('#partnerFilter input');
			}

			return selectedExpertisePartners.map(function() {
				return this.value;
			}).get(); // Convert jQuery object to array of values
		}

		// Function to fetch speakers based on selected filters and pagination
		function fetchPartners(page) {
			page = page || 1; // Default page to 1 if undefined
			var expertise = getSelectedFiltersPartners(); // Get selected filters
			var data = {
				action: 'filter_partners',
				paged: page, // Pass paged as parameter
				expertise: expertise.length > 0 ? expertise : 'all' // If no filters, send 'all'
			};

			// AJAX request to fetch filtered 
			$.ajax({
				url: ajaxobject.ajaxurl,
				type: 'POST',
				data: data,
				success: function(response) {
					var jsonResponse = typeof response === 'object' ? response : JSON.parse(response);

					// Update speakers container
					if (jsonResponse.partners) {
						$('#partners-container').html(jsonResponse.partners);
					} else {
						$('#partners-container').html('<p>No partners found.</p>');
					}

					// Update pagination and set active state
					updatePaginationPartners(jsonResponse.pagination, page);

					// Scroll to the top of the .speaker-filter-inner with offset
					var $filterInnerPartners = $('.partners-filter-inner');
					if ($filterInnerPartners.length) {
						$('html, body').animate({
							scrollTop: $filterInnerPartners.offset().top - 100 // Offset by 100px
						}, 0); // 500ms animation duration
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
				}
			});
		}

		// Function to update pagination and set active state
		function updatePaginationPartners(paginationHtml, currentPage) {
			$('.page-navi-container').html(paginationHtml);

			// Update active state for the current page
			$('.partner-pagination-container a').removeClass('active');
			$('.partner-pagination-container a[href*="/page/' + currentPage + '"]').addClass('active'); // Correct selector

			// Ensure pagination links have correct structure (/page/2)
			$('.partner-pagination-container a').each(function() {
				var href = $(this).attr('href');
				if (href && href.indexOf('admin-ajax.php') !== -1) {
					var paged = href.match(/paged=(\d+)/);
					if (paged && paged[1]) {
						$(this).attr('href', '/page/' + paged[1]); // Replace with /page/X
					}
				}
			});
		}

		// Edge Partner Ajax 

		// Pagination event (with preventDefault)
		$('body').on('click', '.edge-partner-pagination-container a', function(e) {
			e.preventDefault(); // Prevent navigation
			var page = $(this).attr('href').split('/page/')[1]; // Get the page number
			if (!page) {
				page = 1; // Default to page 1 if not found
			}
			fetchPartners(page); // Fetch speakers for the selected page
		});

		// Filter event (change event on checkboxes)
		$('#edgePartnerFilter').on('change', 'input[type="checkbox"]', function() {
			edgeFetchPartners(1); // Fetch speakers with selected filters (starting from page 1)
		});

		// Function to get selected filters, or all filters if none are checked
		function getSelectedFiltersEdgePartners() {
			var selectedExpertiseEdgePartners = $('#edgePartnerFilter input:checked');

			// If none are selected, get all filter values
			if (selectedExpertiseEdgePartners.length === 0) {
				selectedExpertiseEdgePartners = $('#edgePartnerFilter input');
			}

			return selectedExpertiseEdgePartners.map(function() {
				return this.value;
			}).get(); // Convert jQuery object to array of values
		}

		// Function to fetch speakers based on selected filters and pagination
		function edgeFetchPartners(page) {
			page = page || 1; // Default page to 1 if undefined
			var expertise = getSelectedFiltersEdgePartners(); // Get selected filters
			var data = {
				action: 'edge_filter_partners',
				paged: page, // Pass paged as parameter
				expertise: expertise.length > 0 ? expertise : 'all' // If no filters, send 'all'
			};

			// AJAX request to fetch filtered 
			$.ajax({
				url: ajaxobject.ajaxurl,
				type: 'POST',
				data: data,
				success: function(response) {
					var jsonResponse = typeof response === 'object' ? response : JSON.parse(response);

					// Update speakers container
					if (jsonResponse.partners) {
						$('#edge-partners-container').html(jsonResponse.partners);
					} else {
						$('#edge-partners-container').html('<p>No partners found.</p>');
					}

					// Update pagination and set active state
					updatePaginationEdgePartners(jsonResponse.pagination, page);

					// Scroll to the top of the .speaker-filter-inner with offset
					var $filterInnerPartners = $('.partners-filter-inner');
					if ($filterInnerPartners.length) {
						$('html, body').animate({
							scrollTop: $filterInnerPartners.offset().top - 100 // Offset by 100px
						}, 0); // 500ms animation duration
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
				}
			});
		}

		// Function to update pagination and set active state
		function updatePaginationEdgePartners(paginationHtml, currentPage) {
			$('.page-navi-container').html(paginationHtml);

			// Update active state for the current page
			$('.edge-partner-pagination-container a').removeClass('active');
			$('.edge-partner-pagination-container a[href*="/page/' + currentPage + '"]').addClass('active'); // Correct selector

			// Ensure pagination links have correct structure (/page/2)
			$('.edge-partner-pagination-container a').each(function() {
				var href = $(this).attr('href');
				if (href && href.indexOf('admin-ajax.php') !== -1) {
					var paged = href.match(/paged=(\d+)/);
					if (paged && paged[1]) {
						$(this).attr('href', '/page/' + paged[1]); // Replace with /page/X
					}
				}
			});
		}
		
		// Author fake pagination

		if ($('.author-posts-listing').length) {
			// Hide all post groups except the first one
			$('.post-group').hide();
			$('.post-group[data-group="1"]').show();

			// Create pagination links dynamically
			var groupCount = $('.post-group').length;
			var paginationHtml = '<div class="pagination-links">';
			for (var i = 1; i <= groupCount; i++) {
				paginationHtml += '<button type="button" class="pagination-link" data-page="' + i + '">' + i + '</button> ';
			}
			paginationHtml += '</div>';
			$('.pagination-container').html(paginationHtml);

			// Handle pagination link click event
			$('.pagination-link').on('click', function(e) {
				e.preventDefault();
				var page = $(this).data('page');

				// Hide all post groups and show the selected group
				$('.post-group').hide();
				$('.post-group[data-group="' + page + '"]').show();

				// Update active link style (optional)
				$('.pagination-link').removeClass('active');
				$(this).addClass('active');
			});

			// Set the first pagination link as active initially
			$('.pagination-link[data-page="1"]').addClass('active');
		}

		// EVR switcher

		
		$('.evr-switch-column .switch-container').on('click', function() {
			const index = $(this).index(); // Get the index of the clicked container
			const $imageContainers = $('.evr-image-container');
			const $switchContainers = $('.evr-switch-column .switch-container');

			// Reset all image containers
			$imageContainers.removeClass('active').css({
				'z-index': '',
				'width': '',
				'left': '',
				'top': ''
			});

			// Apply styles based on the clicked index
			if (index === 0) { // First container active
				$imageContainers.eq(0).addClass('active').css({
					'z-index': 3,
					'width': '100%',
					'left': '0px',
					'top': '0'
				});
				$imageContainers.eq(1).css({
					'z-index': 2,
					'width': 'calc(100% - 40px)',
					'left': '20px',
					'top': '-12px'
				});
				$imageContainers.eq(2).css({
					'z-index': 1,
					'width': 'calc(100% - 80px)',
					'left': '40px',
					'top': '-24px'
				});
			} else if (index === 1) { // Second container active
				$imageContainers.eq(0).css({
					'z-index': -1,
					'width': 'calc(100% - 40px)',
					'left': '0px',
					'top': 'px'
				});
				$imageContainers.eq(1).addClass('active').css({
					'z-index': 3,
					'width': '100%',
					'left': '0px',
					'top': '0'
				});
				$imageContainers.eq(2).css({
					'z-index': 2,
					'width': 'calc(100% - 40px)',
					'left': '20px',
					'top': '-12px'
				});
			} else if (index === 2) { // Third container active
				$imageContainers.eq(0).css({
					'z-index': -1,
					'width': 'calc(100% - 80px)',
					'left': '0px',
					'top': '0px'
				});
				$imageContainers.eq(1).css({
					'z-index': -1,
					'width': 'calc(100% - 40px)',
					'left': '0px',
					'top': '0px'
				});
				$imageContainers.eq(2).addClass('active').css({
					'z-index': 3,
					'width': '100%',
					'left': '0px',
					'top': '0'
				});
			}

			// Reset all switch containers
			$switchContainers.removeClass('active');

			// Activate the clicked switch container
			$(this).addClass('active');
		});

		// EVR progress tracking

		var $trackingLine = $('.steps-container .tracking-line');
		var $steps = $('.steps-container .step');
		var $window = $(window);

		$window.on('scroll', rafThrottle(function () {
			var scrollTop = $window.scrollTop();
			var windowHeight = $window.height();
			var windowWidth = $window.width();
			var isSmallScreen = windowWidth <= 767;

			if (!$steps.length || !$trackingLine.length) return;

			$steps.each(function () {
				var $step = $(this);
				var stepOffset = $step.offset().top;
				var stepHeight = $step.outerHeight();
				
				// Trigger step activation earlier on small screens
				var stepMidPoint = stepOffset + stepHeight / (isSmallScreen ? 1.5 : 2);

				if (scrollTop + windowHeight / (isSmallScreen ? 1.2 : 2) >= stepMidPoint - 24) {
					$step.find('.step-counter').addClass('active');
				} else {
					$step.find('.step-counter').removeClass('active');
				}
			});

			// Update tracking line height
			var firstStepTop = $steps.first().offset().top + 56;
			var lastStepBottom = $steps.last().offset().top + $steps.last().outerHeight() + 180;

			var maxLineHeight = lastStepBottom - firstStepTop;
			var scrolledDistance = scrollTop + windowHeight / (isSmallScreen ? 1.2 : 2) - firstStepTop;
			var newLineHeight = Math.min(maxLineHeight, Math.max(0, scrolledDistance));

			if (isSmallScreen) {
				newLineHeight += 175; // Faster growth for smaller screens
			}

			if (scrollTop + windowHeight > firstStepTop && scrollTop < lastStepBottom) {
				$trackingLine.css({
					'height': newLineHeight + 'px',
					'top': isSmallScreen ? '-175px' : '0px'
				});
			} else if (scrollTop + windowHeight / 2 <= firstStepTop) {
				$trackingLine.css('height', '0px');
			} else {
				$trackingLine.css('height', (lastStepBottom - firstStepTop + (isSmallScreen ? 175 : 0)) + 'px');
			}
		}));

		// Form popup slider

		$('.form-popup-slider').on('afterChange', function(event, slick, currentSlide){
			var slidesToShow = slick.slickGetOption('slidesToShow');
			var totalSlides = slick.slideCount - slidesToShow + 1; // Adjust based on slidesToShow
			var progress = ((currentSlide) / (totalSlides - 1)) * 100;

			$('.progress-bar-form-popup').css('width', progress + '%');		
		});

		// Initialize progress on load
		$('.form-popup-slider').on('init', function(event, slick){
			$('.progress-bar-form-popup').css('width', '0%');

			if (typeof FormCraftsPopup !== 'undefined' && typeof FormCraftsPopup.scanPage === 'function') {
				FormCraftsPopup.scanPage();
			}
		});

		$('.form-popup-slider').slick({
			slidesToShow: 2,
			slidesToScroll: 1,
			arrows: true,
			infinite: false,
			responsive: [
			  {
			   breakpoint: 1023,
			   settings: {
				slidesToShow: 1,
			   }
			  }	
			]	
		});

		if (typeof FormCraftsPopup !== 'undefined' && typeof FormCraftsPopup.scanPage === 'function') {
			FormCraftsPopup.scanPage();
		}

		// Full suite slider 

		$('.full-suite-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			infinite: false
		});

		$('.card-container.card-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: false
		});

		$('.full-suite-slider').on('afterChange', function(event, slick, currentSlide){
			var slidesToShow = slick.slickGetOption('slidesToShow');
			var totalSlides = slick.slideCount - slidesToShow + 1; // Adjust based on slidesToShow
			var progress = ((currentSlide) / (totalSlides - 1)) * 100;

			$('.progress-bar-form-suite').css('width', progress + '%');		
		});

		if ($('.large-quote-slide-container').length) {

			var $sliderLargeQuote = $('.large-quote-slide-container');
			var $timerLargeQuote = $('.quote-slider-timer-inner');
			var autoplaySpeedLargeQuote = 8000; // 8 seconds
			var timerLargeQuote;

			// Init slick
			$sliderLargeQuote.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: true,
				fade: true,
				autoplay: true,
				autoplaySpeed: autoplaySpeedLargeQuote,
				pauseOnHover: false,
				pauseOnFocus: false
			});

			// Start progress bar
			function startProgress() {
				clearTimeout(timerLargeQuote);
				$timerLargeQuote.stop(true).css({ width: 0 }).animate(
					{ width: '100%' },
					autoplaySpeedLargeQuote,
					'linear'
				);
			}

			// Restart bar on init + after every slide change
			$sliderLargeQuote.on('init reInit afterChange', function() {
				startProgress();
			});

			// Reset bar if user clicks nav
			$sliderLargeQuote.on('beforeChange', function() {
				$timerLargeQuote.stop(true).css({ width: 0 });
			});

			// Run once after first init
			startProgress();
		}

		// if ($('.auto-card-slider').length) {

		// 	var $autoCardSlider = $('.auto-card-slider');

		// 	$autoCardSlider.slick({
		// 		slidesToShow: 3,
		// 		slidesToScroll: 1,
		// 		infinite: true,
		// 		arrows: false,
		// 		autoplay: true,
		// 		autoplaySpeed: 0, 
		// 		speed: 8000,
		// 		cssEase: 'linear',
		// 		pauseOnHover: true,
		// 		pauseOnFocus: true,
		// 	});

		// 	// Pause on hover anywhere inside the slider
		// 	$autoCardSlider.on("mouseenter", function () {
		// 		$autoCardSlider.slick("slickPause");
		// 	});

		// 	$autoCardSlider.on("mouseleave", function () {
		// 		$autoCardSlider.slick("slickPlay");
		// 	});
		// }


		$('.gtm-icon-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: false
		});

		if ($('.left-text-link-slider').length) {

			$('.left-text-link-slider').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				infinite: true,
				autoplay: true,
				autoplaySpeed: 0,      // no delay between transitions
				speed: 16000,           // controls how fast it scrolls (higher = slower)
				cssEase: 'linear',     // key for constant movement
				pauseOnHover: false,
				pauseOnFocus: false
			});
		}

		// GTM mobile map scroller

		$('.mobile-gtm-card-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			infinite: true,           // loop
			autoplay: true,           // autoplay
			autoplaySpeed: 3000,      // 3 seconds per card
			speed: 300,               // 300ms transition
			fade: true,               // fade effect
			cssEase: 'linear'         // smoother fade
		});
		

		// Handle the slide link clicks
		$('.icon-slide-link').on('click', function(e) {
			e.preventDefault();

			// Remove 'active' class from all slide links
			$('.icon-slide-link').removeClass('active');

			// Add 'active' class to the clicked link
			$(this).addClass('active');

			// Get the index of the clicked link
			var slideIndexIcon = $(this).index();

			// Scroll to the corresponding slide
			$('.gtm-icon-slider').slick('slickGoTo', slideIndexIcon);
		});

		// Optional: Add the 'active' class to the first slide link by default
		$('.icon-slide-link').first().addClass('active');
		

		// story landing slider mobile 

		$('.mobile-story-slider').slick({
		  // centerMode: false,
		  arrows: false,
		  dots: true,
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
			 breakpoint: 767,
			 settings: {
			   slidesToShow: 1
			 }
		   }
		  ]
		});

		// Slide Stack
			var track = $('.slider-track');
			var trackSlides = track.find('.slide');
			var contentSlides = $('.slider-content .slide');
			var DURATION = 300; // transition duration
			var busy = false;

			var positions = [
				{ y: -60, scale: 0.75, z: 5 },
				{ y: -48, scale: 0.8, z: 6 },
				{ y: -36, scale: 0.85, z: 7 },
				{ y: -24, scale: 0.9, z: 8 },
				{ y: -12, scale: 0.95, z: 9 },
				{ y: 0, scale: 1, z: 10 }
			];

			// ensure positions match slide count
			if (trackSlides.length < positions.length) {
				positions = positions.slice(positions.length - trackSlides.length);
			}

			/* -----------------------------------------------------
			⭐ FIX: Reverse the initial DOM order so highest-numbered
			slide is on top (closest to index 0)
			----------------------------------------------------- */
			track.append(track.children().get().reverse());
			trackSlides = track.find('.slide');
			/* ----------------------------------------------------- */

			function applyPositions(){
				trackSlides.each(function(i){
					var pos = positions[i];
					$(this).css({
						transform:'translateY(' + pos.y + 'px) scale(' + pos.scale + ')',
						'z-index': pos.z,
						opacity: 1
					});
				});

				// sync content
				var activeNumber = trackSlides.eq(-1).data('slide-number');
				contentSlides.removeClass('active')
					.filter('[data-slide-number="'+activeNumber+'"]')
					.addClass('active');
			}

			applyPositions();

			function prevSlide(){
				if(busy) return;
				busy = true;

				var front = trackSlides.eq(0);

				// Step 1: fade out front slide
				front.css('opacity', 0);

				setTimeout(function(){

					// Step 2: move front slide to back in DOM
					front.appendTo(track);

					// refresh trackSlides
					trackSlides = track.find('.slide');

					// Apply positions
					applyPositions();

					busy = false;
				}, DURATION);
			}

			function nextSlide(){
				if(busy) return;
				busy = true;

				var back = trackSlides.last();

				// Step 1: fade out back slide
				back.css('opacity', 0);

				setTimeout(function(){

					// Step 2: move back slide to front in DOM
					back.prependTo(track);

					// refresh trackSlides
					trackSlides = track.find('.slide');

					// Apply positions
					applyPositions();

					busy = false;
				}, DURATION);
			}

			$('.slide-stack .slide-next').on('click', nextSlide);
			$('.slide-stack .slide-prev').on('click', prevSlide);



		// category search

		$('.search-button-stories').on('click', function() {
			$('.stories-listing .search-container').slideDown(300);
			$('.stories-listing .search-button-container').addClass('search-open');
		});

		$('.search-button-stories-close').on('click', function() {
			var currentUrl = window.location.href;
			var hasQueryParams = currentUrl.includes('?');

			if (!hasQueryParams) {
				$('.stories-listing .search-container').slideUp(300);
				$('.stories-listing .search-button-container').removeClass('search-open');
			} else {
				var baseUrl = currentUrl.split('?')[0];
				var newUrl = baseUrl + '?sub-category=all';
				window.location.href = newUrl;
			}
		});

		$('#subCategorySelect').on('change', function() {
			const url = $(this).val();
			if (url) {
				window.location.href = url;
			}
		});

		// Category slider 

		const $storySlider = $('.category-slider-container');		

		$storySlider.on('afterChange', function(event, slick, currentSlide){
			$('.slide-count').text(currentSlide + 1);
		});

		$storySlider.slick({
			arrows: false,
			dots: true,
			infinite: false,
			autoplay: false,
			slidesToShow: 3,
			focusOnSelect: true,
			responsive: [
				{
					breakpoint: 1023,
					settings: {
						slidesToShow: 2
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

		// Handle the slide link clicks
		$('.slide-link').on('click', function(e) {
			e.preventDefault();

			// Remove 'active' class from all slide links
			$('.slide-link').removeClass('active');

			// Add 'active' class to the clicked link
			$(this).addClass('active');

			// Get the index of the clicked link
			var slideIndex = $(this).index();

			// Scroll to the corresponding slide
			$('.full-suite-slider').slick('slickGoTo', slideIndex);
		});

		// Optional: Add the 'active' class to the first slide link by default
		$('.slide-link').first().addClass('active');

		// Landing slider 
	
		$('.column-slider-container').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: true,
			fade: true,
			swipe: false,
			swipeToSlide: false,
			speed: 500,
			cssEase: "linear",
			arrows: true,
			responsive: [
			  {
			   breakpoint: 767,
			   settings: {
				fade: false
			   }
			  }	
			]		
		});

		$('.column-slider-container').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.column-slider-container button.slick-prev').addClass('active');
		});

		$('.sponsors-slider-container').slick({
			dots: false,
			arrows: false,
			infinite: true,			
			slidesToShow: 1,
			adaptiveHeight: true
		});


		$('.resources-container.resources-slider').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			infinite: false,
			arrows: true,
			dots: false,
			responsive: [
			  {
			   breakpoint: 767,
			   settings: "unslick"
			 }
			]
		});		

		$('.resources-container.resources-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.resources-container.resources-slider button.slick-prev').addClass('active');
		});

		// Generic Register form hidden fields

		if($('.webinar-register-form').length ){

			var hiddenName = $('.hidden-name').text();
			var hiddenEvent = $('.hidden-event').text();
			var hiddenDate = $('.hidden-date').text();
			var hiddenID = $('.hidden-id').text();
			var genericForm = $('.webinar-register-form .form-container form');
			setTimeout(function(){
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').attr('value', hiddenName);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_name').children('div.input').children('input').val(hiddenName).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').attr('value', hiddenEvent);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_title').children('div.input').children('input').val(hiddenEvent).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').attr('value', hiddenDate);
				$('.webinar-register-form .form-container form').find('.hs-hidden_event_date').children('div.input').children('input').val(hiddenDate).change();
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').attr('value', hiddenID);
				$('.webinar-register-form .form-container form').find('.hs-hidden_sf_id').children('div.input').children('input').val(hiddenID).change();
			}, 2000);

			if($('.client-communication-title').length ){
				var communicationTitle = $('.client-communication-title').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_method').children('label').children('span').html(communicationTitle);
				}, 2000);
			}

			if($('.client-communication-text').length ){
				var communicationText = $('.client-communication-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_method').children('legend.hs-field-desc').html(communicationText);
				}, 2000);
			}

			if($('.gift-opt-in-text').length ){
				var giftOptIn = $('.gift-opt-in-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_gift_opt_in').children('legend.hs-field-desc').html(giftOptIn);
				}, 2000);
			}

			if($('.marketing-text').length ){
				var marketingOptIn = $('.marketing-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_single_client_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(marketingOptIn);
				}, 2000);
			}

			if($('.critical-text').length ){
				var criticalLabel = $('.critical-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable_').children('label').children('span').html(criticalLabel);
				}, 2000);
			}

			if($('.critical-help-text').length ){
				var criticalHelp = $('.critical-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_what_is_your_most_critical_issue_to_discuss_with_your_peers_at_this_roundtable_').children('legend').html(criticalHelp);
				}, 2000);
			}

			if($('.umbrella-help-text').length ){
				var umbrellaHelp = $('.umbrella-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('legend.hs-field-desc').html(umbrellaHelp);
				}, 2000);
			}

			if($('.umbrella-text').length ){
				var umbrellaOptIn = $('.umbrella-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_client_communication_opt_in').children('.input').children('ul').children('li').children('label').children('span').html(umbrellaOptIn);
				}, 2000);
			}

			if($('.newsletter-help-text').length ){
				var newsletterHelp = $('.newsletter-help-text').html();
				setTimeout(function(){
					$('.webinar-register-form .form-container form').find('.hs_newsletter_opt_in ').children('legend.hs-field-desc').html(newsletterHelp);
				}, 2000);
			}
		}

		// Delegate slider
		$('.delegate-slides').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			infinite: true,
			arrows: true,
			autoplay: false,
		    autoplaySpeed: 2000,
			dots: true,
			swipeToSlide: true,
			responsive: [
			  {
				breakpoint: 1200,
				settings: {
				  slidesToShow: 4,
				}
			  },
			  {
				breakpoint: 1023,
				settings: {
				  slidesToShow: 3,
				}
			  },			  
			  {
			   breakpoint: 767,
			   settings: {
				 slidesToShow: 1,
				 arrows: false,
			   }
			 }
			]
		});

		// Company slider 
		var $companyProgress = $('.company-progress');
		$('.company-slide-container').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			infinite: true,
			arrows: true,
			autoplay: false,
			centerMode: true,
			centerPadding: '60px',
		    autoplaySpeed: 2000,
			dots: false,
			swipeToSlide: true,
			responsive: [
			  {
				breakpoint: 1023,
				settings: {
				  slidesToShow: 1,
				}
			  },			  
			  {
			   breakpoint: 767,
			   settings: {
				 slidesToShow: 1,				 
				 centerMode: false,
			   }
			 }
			]
		});

		// Update progress bar width based on slide change
		$('.company-slide-container').on('afterChange', function(event, slick, currentSlide) {
			var totalSlides = slick.$slides.length;
			var progressPercentage = ((currentSlide + 1) / totalSlides) * 100;

			// Animate the width of the progress bar
			$companyProgress.css({
				width: progressPercentage + '%'
			});
		});

		// Initialize the progress bar
		$('.company-slide-container').on('init', function(event, slick) {
			var totalSlides = slick.$slides.length;
			var progressPercentage = (1 / totalSlides) * 100;

			$companyProgress.css({
				width: progressPercentage + '%'
			});
		});

		$('.company-slide-container').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
			var slidesLength = slick.$slides.length - 1;
			var isCurrentFirstOrLast = currentSlide === 0 || currentSlide === slidesLength;
			var isNextFirstOrLast = nextSlide === 0 || nextSlide === slidesLength;

			if (isCurrentFirstOrLast && isNextFirstOrLast) {
				var nextClone = $(event.currentTarget).find('.slick-cloned.slick-active');
				setTimeout(function() {
					nextClone.addClass('slick-current');
				}, 100);
			}
		});

		$('.company-slide-container').on('click', '.slick-slide', function(event) {
			var slideIndex = $(this).data('slick-index'); // Get the index of the clicked slide
			$('.company-slide-container').slick('slickGoTo', slideIndex); // Go to the clicked slide
		});
		

		// Topics Industry Switcher 

		// $('#topicIndustrySwitch').change(function() {
		// 	console.log('change')
		// 	if ($(this).is(':checked')) {
		// 		console.log('checked');
		// 		// Checkbox is checked, so activate industries and deactivate topics
		// 		$('.switch-title.industries').addClass('active');
		// 		$('.switch-title.topics').removeClass('active');
		// 	} else {
		// 		// Checkbox is not checked, so activate topics and deactivate industries
		// 		$('.switch-title.topics').addClass('active');
		// 		$('.switch-title.industries').removeClass('active');
		// 	}
		// });

		// Filter with checkboxes
		var $industySwitch = $('input[type="checkbox"]#topicIndustrySwitch');

		$industySwitch.change(function() {
			
			if ($industySwitch.is(':checked')) {
				$('.switch-title.industries').addClass('active');
				$('.switch-title.topics').removeClass('active');
				$('.bottom-container .topics-container').removeClass('active');
				$('.bottom-container .industries-container').addClass('active');
			} else {
				$('.switch-title.topics').addClass('active');
				$('.switch-title.industries').removeClass('active');	
				$('.bottom-container .topics-container').addClass('active');
				$('.bottom-container .industries-container').removeClass('active');		
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


		if($('.scrolling-card').length){
			$(window).on('scroll', rafThrottle(function() {
				$('.scrolling-card').each(function() {
					var cardTop = $(this).offset().top;
					var windowScrollTop = $(window).scrollTop();
					var windowHeight = $(window).height();
					var halfwayPoint = windowScrollTop + (windowHeight / 2);

					if (cardTop < halfwayPoint && cardTop + $(this).outerHeight() > windowScrollTop) {
						$(this).addClass('active');
					} else {
						$(this).removeClass('active');
					}
				});
			}));
		}


		// Sticky slider cards

		if ($('.slider-scrolling-content').length && $(window).width() > 767) {
			var $bgContainers = $('.slider-bg-container.bg-container');
			var $scrollingNav = $('.slide-nav-container .slide-nav-item');
			$(window).on('scroll', rafThrottle(function() {
				$('.slider-scrolling-content').each(function(index) {
					var $this = $(this);
					var elementTop = $this.offset().top;
					var windowScrollTop = $(window).scrollTop();
					var windowHeight = $(window).height();
					var halfwayPoint = windowScrollTop + (windowHeight / 2);

					if (elementTop < halfwayPoint && elementTop + $this.outerHeight() > windowScrollTop) {						
						$bgContainers.removeClass('active');
						$scrollingNav.removeClass('active');
						$bgContainers.eq(index).addClass('active');
						$scrollingNav.eq(index).addClass('active');						
					}
				});

				var $slideNavContainer = $('.slide-nav-container');
				var stickyContainerTop = $('.sticky-slider-container').offset().top;
				var stickyContainerBottom = stickyContainerTop + $('.sticky-slider-container').outerHeight();
				var windowScrollTop = $(window).scrollTop();
				var windowHeight = $(window).height();

				// Check if the page is within the slider section
				if (windowScrollTop >= stickyContainerTop && windowScrollTop < stickyContainerBottom - windowHeight ) {
					$slideNavContainer.css({
						position: 'fixed',
						top: '0',
						bottom: 'auto'
					});
				} else if (windowScrollTop >= stickyContainerBottom - windowHeight) {
					$slideNavContainer.css({
						position: 'absolute',
						top: 'auto',
						bottom: '0'					
					});
				
				} else {
					// Return it to normal flow if outside the section
					$slideNavContainer.css({
						position: 'absolute',
						top: '0',
						bottom: 'auto'
					});
				}
			}));

			 // Click event handler for $scrollingNav
			$scrollingNav.on('click', function() {
				var index = $(this).index(); // Get the index of the clicked nav item
				var targetContent = $('.slider-scrolling-content').eq(index); // Find the matching content element
				
				if (targetContent.length) {
					var targetOffset = targetContent.offset().top; // Get the top position of the target content

					// Animate scrolling to the target element
					$('html, body').animate({
						scrollTop: targetOffset
					}, 1000); // Adjust the duration for smooth scrolling
				}
			});
		}

		// Sticky market cards


		var $cards = $('.market-sticky-card');
		var $navItems = $('.market-sticky-cards .side-bar-navigation li');
		var $cardOffsetTop = 80;

		function updateActiveCard() {
			var scrollMid = $(window).scrollTop() + ($(window).height() / 2);

			var currentCard = null;

			$cards.each(function () {
				var $card = $(this);
				var offsetTop = $card.offset().top;
				var offsetBottom = offsetTop + $card.outerHeight();

				if (scrollMid >= offsetTop && scrollMid <= offsetBottom) {
					currentCard = $card;
					return false; // break
				}
			});

			if (currentCard) {
				var id = currentCard.data('card');

				// Remove active classes
				$cards.removeClass('active');
				$navItems.removeClass('active');

				// Add active classes to the matching card + nav item
				currentCard.addClass('active');
				$navItems.filter('[data-card="' + id + '"]').addClass('active');
			}
		}

		$navItems.on('click', function (e) {
			e.preventDefault();

			var id = $(this).data('card');
			var $targetCard = $cards.filter('[data-card="' + id + '"]');

			if ($targetCard.length) {

				$('html, body').animate({
					scrollTop: $targetCard.offset().top - $cardOffsetTop
				}, 500);

			}
		});

		// Trigger on scroll + initial load
		$(window).on('scroll', rafThrottle(updateActiveCard));
		updateActiveCard();

		// Flexible content image after code

		var images = $('.content-inner .content p img');
		$(images).each(function() {
		   $(this).wrap('<span class="content-image-container"></span>');
		});

		// Quote / Thumbail slider

		if ( $('.quote-slider-module').length ) {
			var numSlick = 0;
			$('.quote-slider-module').each(function() {
				var $sliderModuleNumber = $(this).addClass('slider-'+numSlick);
				var $thumbNails = $($sliderModuleNumber).siblings('.quote-slider-thumbnails').addClass('thumbnail-'+numSlick);
		   		var $sliderModule = $($sliderModuleNumber).on('init', function(slick) {
				   $($sliderModuleNumber).fadeIn(1000);
			   }).slick({
				   slidesToShow: 1,
				   slidesToScroll: 1,
				   arrows: false,
				   autoplay: true,
				   autoplaySpeed: 7000,
				   infinite: true,
				   fade: true,
		   		   speed: 500,
		   	       cssEase: "linear",
			   });

			   if($($thumbNails).hasClass('three-slides')){
				   var $slider2 = $($thumbNails).on('init', function(slick) {
					  $($thumbNails).fadeIn(1000);
				  }).slick({
					  slidesToShow: 3,
					  slidesToScroll: 1,
					  autoplay: false,
					  asNavFor: $sliderModuleNumber,
					  dots: false,
					  centerMode: false,
					  focusOnSelect: true
				  });
			   }

			   if($($thumbNails).hasClass('four-slides')){
				   var $slider2 = $($thumbNails).on('init', function(slick) {
					  $($thumbNails).fadeIn(1000);
				  }).slick({
					  slidesToShow: 4,
					  slidesToScroll: 1,
					  autoplay: false,
					  asNavFor: $sliderModuleNumber,
					  dots: false,
					  centerMode: false,
					  focusOnSelect: true
				  });
			   }

			   if($($thumbNails).hasClass('five-slides')){
				   var $slider2 = $($thumbNails).on('init', function(slick) {
					  $($thumbNails).fadeIn(1000);
				  }).slick({
					  slidesToShow: 5,
					  slidesToScroll: 1,
					  autoplay: false,
					  asNavFor: $sliderModuleNumber,
					  dots: false,
					  centerMode: false,
					  focusOnSelect: true
				  });
			   }

			   if($($thumbNails).hasClass('six-slides')){
				   var $slider2 = $($thumbNails).on('init', function(slick) {
					  $($thumbNails).fadeIn(1000);
				  }).slick({
					  slidesToShow: 6,
					  slidesToScroll: 1,
					  autoplay: false,
					  asNavFor: $sliderModuleNumber,
					  dots: false,
					  centerMode: false,
					  focusOnSelect: true
				  });
			   }

			//remove active class from all thumbnail slides
			$($thumbNails).find('.slick-slide').removeClass('slick-active');

			//set active class to first thumbnail slides
			$($thumbNails).find('.slick-slide').eq(0).addClass('slick-active');

			var $progressBarQuote = $($sliderModuleNumber).siblings().children('.progress-bar').addClass('progress-'+numSlick);
			var $progressBarActive = $($sliderModuleNumber).siblings().children('.active-bar').addClass('active-'+numSlick);
			// On before slide change match active thumbnail to current slide
			$($sliderModuleNumber).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
			   var mySlideNumber = nextSlide;
			   $($thumbNails).find('.slick-slide').removeClass('slick-active');
			   $($thumbNails).find('.slick-slide').eq(mySlideNumber).addClass('slick-active');
			   var calc = ( (nextSlide) / (slick.slideCount) ) * 100;
			   var left = ( (nextSlide) / (slick.slideCount) ) * 100;
			   $progressBarQuote.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			   $progressBarActive.css('left', left + '%');
			   $($thumbNails).find('.slick-slide').eq(currentSlide).removeClass('slick-current');
			   $($thumbNails).find('.slick-slide').eq(nextSlide).addClass('slick-current');
			   $($progressBarQuote).children('.progress-inner').eq(currentSlide).removeClass('animate');
			    $($progressBarQuote).children('.progress-inner').eq(nextSlide).addClass('animate');
			});

			numSlick++

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
			var calc = ( (nextSlide + 1) / (slick.slideCount ) ) * 100;
			$progressBarIcon.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
			$progressBarLabelIcon.text( calc + '% completed' );
		});

		$slickIconElement.slick({
		  // centerMode: false,
		  arrows: true,
		  dots: false,
		  infinite: false,
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
			autoplay: true,
			autoplaySpeed: 3000,
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
		      var prev_slick_img = $('.peer-featured-slider .slick-current').prev('.peer-slide').find('.video-container .bg-container img').attr('src');
		      $('.prev-slick-img img').attr('src', prev_slick_img);
		      // For next img
		      var prev_next_slick_img = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.video-container .bg-container img').attr('src');
			  var prev_next_playtime = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.video-container .bg-container .video-play-time').html();
			  var next_slick_content = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.item-content-container').html();
		      $('.next-slick-img img').attr('src', prev_next_slick_img);
			  $('.next-slick-img .bg-container .video-play-time').html(prev_next_playtime);
			  $('.slider-preview .next-content').html(next_slick_content);
		    }
		    function get_next_slick_img() {
		      // For next img
		      var next_slick_img = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.video-container .bg-container img').attr('src');
			  var next_slick_content = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.item-content-container').html();
			  var prev_next_playtime = $('.peer-featured-slider .slick-current').next('.peer-slide').find('.video-container .bg-container .video-play-time').html();

		      $('.next-slick-img img').attr('src', next_slick_img);
			  $('.next-slick-img .bg-container .video-play-time').html(prev_next_playtime);
			  $('.slider-preview .next-content').html(next_slick_content);
		      // For prev img
		      var next_prev_slick_img = $('.peer-featured-slider .slick-current').prev('.video-container .bg-container img').find('img').attr('src');
		      $('.prev-slick-img img').attr('src', next_prev_slick_img);
		    }
		}
	    // End

		// Expert feature slider with preview slide
		  // prev/next/preview thumbnails images for slick slider
	   $('.expert-featured-slider').slick({
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
			 $('.expert-slider-preview').append('<div class="expert-next-slick-img video-container slick-thumb-nav"><div class="bg-container"><img src=""><span class="opacity-overlay"></span><span class="video-play-time"></span><span class="video-button"></div></div></div><div class="expert-next-content"></div>');
			 get_prev_slick_img_expert();
			 get_next_slick_img_expert();

		   }, 500);
		   $(document).on('click', '.expert-featured-slider .slick-prev', function() {
			 get_prev_slick_img_expert();
		   });
		   $(document).on('click', '.expert-featured-slider .slick-next', function() {
			   get_next_slick_img_expert();
		   });
		   $('.expert-featured-slider').on('swipe', function(event, slick, direction) {
			 if (direction == 'left') {
			   get_prev_slick_img_expert();
			 }
			 else {
			   get_next_slick_img_expert();
			 }
		   });
		   $('.expert-featured-slider').on('click', 'li button', function() {
			 var li_no = $(this).parent('li').index();
			 if ($(this).parent('li').index() > li_no) {
			   get_prev_slick_img_expert()
			 }
			 else {
			   get_next_slick_img_expert()
			 }
		   });

		   function get_prev_slick_img_expert() {
			 // For prev img
			 var prev_slick_img_expert = $('.expert-featured-slider .slick-current').prev('.expert-slide').find('.video-container .bg-container img').attr('src');
			 $('.prev-slick-img img').attr('src', prev_slick_img_expert);
			 // For next img
			 var prev_next_slick_img_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.video-container .bg-container img').attr('src');
			 var prev_next_playtime_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.video-container .bg-container .video-play-time').html();
			 var next_slick_content_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.item-content-container').html();
			 $('.expert-next-slick-img img').attr('src', prev_next_slick_img_expert);
			 $('.expert-next-slick-img .bg-container .video-play-time').html(prev_next_playtime_expert);
			 $('.expert-slider-preview .expert-next-content').html(next_slick_content_expert);
		   }
		   function get_next_slick_img_expert() {
			 // For next img
			 var next_slick_img_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.video-container .bg-container img').attr('src');
			 var next_slick_content_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.item-content-container').html();
			 var prev_next_playtime_expert = $('.expert-featured-slider .slick-current').next('.expert-slide').find('.video-container .bg-container .video-play-time').html();

			 $('.expert-next-slick-img img').attr('src', next_slick_img_expert);
			 $('.expert-next-slick-img .bg-container .video-play-time').html(prev_next_playtime_expert);
			 $('.expert-slider-preview .expert-next-content').html(next_slick_content_expert);
			 // For prev img
			 var next_prev_slick_img_expert = $('.expert-featured-slider .slick-current').prev('.video-container .bg-container img').find('img').attr('src');
			 $('.expert-prev-slick-img img').attr('src', next_prev_slick_img_expert);
		   }
	   }
	   // End

		// Staff Slider

		var $slickElement = $('.staff-slider.desktop');

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

		$('.staff-slider.desktop').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.staff-slider.desktop button.slick-prev').addClass('active');
		});

		// Staff Slider

		var $slickElementMobile = $('.staff-slider.mobile');

		/**
		 * FIX JUMPING ANIMATION
		 * Set special animation class on first or last clone.
		 */
		$slickElementMobile.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
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

		$slickElementMobile.on('afterChange', function (event, slick, currentSlide, nextSlide) {
		    $('.slick-current-clone-animate', $slickElementMobile).removeClass('slick-current-clone-animate');
		    $('.slick-current-clone-animate', $slickElementMobile).removeClass('slick-current-clone-animate');
		});

		$slickElementMobile.slick({
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

		$('.staff-slider.mobile').on('beforeChange', function(event, slick, currentSlide, nextSlide){
			$('.staff-slider.mobile button.slick-prev').addClass('active');
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
		  infinite: false,
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

		// Values slider mobile
		//
		// var $slickValuesElement = $('.mobile-values-slider');
		//
		// var $progressBarValues = $('.mobile-values-slider-progress');
		// var $progressBarLabelValues = $( '.slider__label' );
		//
		// $slickValuesElement.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		// 	var calc = ( (nextSlide + 1) / (slick.slideCount ) ) * 100;
		// 	$progressBarValues.css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
		// 	$progressBarLabelValues.text( calc + '% completed' );
		// });
		//
		// $slickValuesElement.slick({
		//   // centerMode: false,
		//   arrows: true,
		//   dots: false,
		//   infinite: false,
		//   // centerMode: true,
		//   // centerPadding: paddingWidthPx,
		//   autoplay: false,
		//   slidesToShow: 1,
		//   focusOnSelect: true
		// });

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

		$('.scroll-to').on( 'click', function(e){
		    e.preventDefault();
		    var target = this.hash;
		    $target = $(target);
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

		var yearButtons = $('.year-button');
		var eventItems = $('.event-item');

		// Check if year buttons and event items exist
		if (yearButtons.length && eventItems.length) {
			// Function to update the active button based on the year
			function updateActiveButton(year) {
				yearButtons.each(function () {
					if ($(this).data('date') === year) {
						$(this).addClass('active');
					} else {
						$(this).removeClass('active');
					}
				});
			}

			// Listen to scroll events to update the active year button
			$(window).on('scroll', rafThrottle(function () {
				var currentYear = null;
				var windowTop = $(window).scrollTop() + 81; // Adjusted to be 81 pixels from the top

				// Find the closest event item to 81 pixels from the top of the window
				eventItems.each(function () {
					var itemTop = $(this).offset().top;
					if (itemTop <= windowTop) {
						currentYear = $(this).data('date');
					} else {
						return false; // Exit the loop once we pass the first item above 81 pixels from the top
					}
				});

				if (currentYear) {
					updateActiveButton(currentYear);
				} else {
					// If no item is found above, check the nearest item below
					eventItems.each(function () {
						var itemTop = $(this).offset().top;
						if (itemTop > windowTop) {
							currentYear = $(this).data('date');
							return false; // Exit the loop once we find the first item below 81 pixels from the top
						}
					});

					if (currentYear) {
						updateActiveButton(currentYear);
					}
				}
			}));

			// Initial check on page load
			var initialYear = eventItems.first().data('date');
			updateActiveButton(initialYear);
		}


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

		var speed = "350";
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
				$(this).next().slideUp(speed, 'swing');
				$(this).removeClass('active');
				$(this).parents('.faq-container').removeClass('active');
			} else {
				$(this).next().slideDown(speed, 'swing');
				$(this).addClass('active');
				$(this).parents('.faq-container').addClass('active');
				$(this).parents('.faq-container').siblings().removeClass('active');
				$(this).parents('.faq-container').siblings().children('.accordion-content').slideUp(speed);
				$(this).parents('.faq-container').siblings().children('.question').removeClass('active');
			}
		});

		$('.expand-all-text').on( 'click', function(e){
			if($(this).hasClass('open')){
				$('.accordion-content').slideUp(speed, 'swing');
				$('.accordion-title').removeClass('open');
				$(this).removeClass('open');
				$(this).text('Expand all');
			} else {
				$('.accordion-content').slideDown(speed, 'swing');
				$('.accordion-title').addClass('open');
				$(this).addClass('open');
				$(this).text('Close all');
			}
		});

		$('.gtm-icon-accordion-item .gtm-icon-title').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).next().slideUp(speed, 'swing');
				$(this).removeClass('active');
			} else {
				$(this).next().slideDown(speed, 'swing');
				$(this).addClass('active');
			}
		});

		// Read more

		$('.text-container.text-excerpt .read-more').on( 'click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).siblings('.text').children('p:first').removeClass('open');
				$(this).siblings('.text').children('p').not(":first").slideUp(300);
				$(this).siblings('.text').children('ul').slideUp(300);
				$(this).siblings('.text').children('ol').slideUp(300);
				$(this).removeClass('active');
				$(this).text('More');
			} else {
				$(this).siblings('.text').children('p:first').addClass('open');
				$(this).siblings('.text').children('p').slideDown(300);
				$(this).siblings('.text').children('ul').slideDown(300);
				$(this).siblings('.text').children('ol').slideDown(300);
				$(this).addClass('active');
				$(this).text('Less');
			}
		});

		$('.text-container.text-excerpt').each(function() {
			var paragraphCount = $(this).children('.text').children('p').length;
			$(this).children('.read-more').hide();
			$(this).children('.text').children('p').not(":first").hide();
			$(this).children('.text').children('ul').hide();
			$(this).children('.text').children('ol').hide();

			if (paragraphCount > 1) {
			    $(this).children('.read-more').show();
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
				$('html').removeClass('fixed');
				$(this).parent('.buttonWrapper').removeClass('active');
				$(this).children('span.ham').removeClass('active');
				$('div.mobileMenu.mobileMenuMain').removeClass('active');
				$('li.main-dropdown > a').removeClass('active');
				$('.mobile-sub-menu').removeClass('active');
			} else {
				$(this).addClass('active');
				$('header').addClass('menu-open');
				$('html').addClass('fixed');
				$(this).parent('.buttonWrapper').addClass('active');
				$(this).children('span.ham').addClass('active');
				$('div.mobileMenu.mobileMenuMain').addClass('active');
				// $('.mobileMenuMain').perfectScrollbar();
			}
		});

	});
	var formLastScrollTop = 0;
	var $growContainer = $('.grow-container');

	$(window).on( 'scroll', function(){
		if($('body').hasClass('template-landing')){

		} else {
			scroll();
			scrollAgenda();
		}
		
		scrollRotate();		

		if($('#animatedText').length ) {
			animatedText();
		}

		if($('.logo-ticker-tape').length ) {
			$('.logo-ticker-tape .band-container-backwards .moving-text').addClass('play');
		}

		if ($('.expanding-form-module').length) {
            var $container = $('.expanding-form-module .column-container');
            var containerTop = $container.offset().top; // Get the top position of the container
            var containerHeight = $container.outerHeight(); // Get the height of the container
            var scrollTop = $(window).scrollTop(); // Current scroll position
            var windowHeight = $(window).height(); // Height of the window

            // Check if the container is partially in the viewport
            var containerInView = (scrollTop + windowHeight > containerTop) && (scrollTop < containerTop + containerHeight);

            if (containerInView) {
                // Calculate how far the container is from the top of the viewport
                var distanceScrolled = scrollTop + windowHeight - containerTop; 
                // Calculate the new width percentage
                var shrinkWidth = Math.max(0, 100 - (distanceScrolled / (containerHeight / 100))); 

                // Determine scroll direction
                if (scrollTop > formLastScrollTop) {
                    // Scrolling down: shrink the grow container
                    $growContainer.css('width', shrinkWidth + 'vw');
                } else {
                    // Scrolling up: reset to full width if the top of the container is below the viewport
                    if (scrollTop + windowHeight < containerTop) {
                        $growContainer.css('width', '100vw');
                    }
                }
            } else {
                // Reset width if not in viewport
                if (scrollTop < containerTop) {
                    $growContainer.css('width', '100vw'); // Reset to full width when above the container
                }
            }

            // Update the last scroll position
            formLastScrollTop = scrollTop;
        }

    });

	$(window).on('load',function (){
		$('.loading-animation').addClass('loaded');
		timer = setTimeout(function(){
			$('.loading-animation').hide();
		}, 500);
		$('main').addClass('loaded');
		$('.banner-block').addClass('visible');
		
		match();
		if($('.icon-slider-container').length){
			outsideContainer();
		}
		

		// $('main.landing').perfectScrollbar('destroy');
		// $('body.template-landing').perfectScrollbar('destroy');

		if($('#yearButtons').length ) {
			var yearButtonTop = $('#yearButtons').offset().top - 0;
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

		function scrollToEventItem(hash) {
			if (hash) {
				var target = $(hash);
				if (target.length) {
					$('html, body').animate({
						scrollTop: target.offset().top - 80
					}, 1000);
					return false; // Prevent default anchor click behavior
				}
			}
			return true; // Allow default anchor click behavior if no hash or target not found
		}	
		
		// Smooth scroll to event item on page load if hash is present and .events-listing exists
		function handleEventListingScroll() {
			if ($('.events-listing').length || $('.partners-events-listing').length) {
				scrollToEventItem(window.location.hash);
			}
		}

		// Listen for hash changes (e.g., user navigating with back/forward buttons)
		$(window).on('hashchange', function() {
			handleEventListingScroll();
		});

		if ($('.events-listing').length || $('.partners-events-listing').length) {
			handleEventListingScroll();
		}
	});

	$(window).on( 'resize', function(){
		match();
		if($('.icon-slider-container').length){
			outsideContainer();
		}		
	});

	function scroll() {
		var viewportWidth = jQuery(window).width();
		var scrollPos = $(document).scrollTop();
		if(scrollPos > 100) {
			$('header').addClass('scrolled');
			timer = setTimeout(function(){
				$('header').addClass('scrolled-fixed');
			}, 300);
		} else {
			$('header').removeClass('scrolled');
			$('header').removeClass('scrolled-fixed');
		}


		if (viewportWidth < 1024) {

		} else {
			if(scrollPos > 80) {
				$('header').addClass('resources-sticky');
			} else {
				$('header').removeClass('resources-sticky');
			}
		}

		if(scrollPos > 80) {
			$('footer .container .footer-bottom .logo-container .back-to-top').addClass('visible');
		} else {
			$('footer .container .footer-bottom .logo-container .back-to-top').removeClass('visible');
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
		// $('.webinar-column').matchHeight();
		$('.thank-you-banner .column').matchHeight();
		$('.information-column').matchHeight();
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
		// $('.post-article-container .left-column, .post-article-container .post-content').matchHeight();
	}

	function scrollRotate() {
	    var image = document.getElementById("rotatingImage");
		if(image){
			image.style.transform = "rotate(" + window.pageYOffset/5 + "deg)";
		}
	}

	function animatedText() {
		var textcontainer = document.getElementById("animatedText");
		var totalCount = $(textcontainer).children().length;
		var rect = textcontainer.getBoundingClientRect();
		var elemTop = rect.top;
		var elemBottom = rect.bottom;
		var scrollTop = $(window).scrollTop();
		var textHeight = elemBottom - elemTop;

		// Define an offset in pixels to trigger the animation earlier
		var triggerOffset = window.innerHeight * 0.6; // Start animation 60% before reaching the top
		var activationSpread = 2;
		var letterTop = (elemTop - triggerOffset) / totalCount;
		var letterScroll = letterTop / totalCount;
		var percentage = (textHeight / totalCount) * letterScroll;
		var letterIndex = ((percentage - totalCount) * -1) / activationSpread;

		$('#animatedText').children('span').each(function() {
			var textIndex = $(this).index();
			if (textIndex > letterIndex) {
				$(this).addClass('active');
			} else {
				$(this).removeClass('active');
			}
		});
	}

	function select2(){
		if($('form').hasClass('hs-form')){
		} else {
			if($('form').hasClass('mepr-form')){
				$('select').select2();
			} else {
				$('select').select2({minimumResultsForSearch: -1});
			}
		}
	}

function animateOverlappingCards() {
    var $cards = $('.overlapping-card-wrapper');
    var scrollTop = $(window).scrollTop();
    var stickyTop = 56; // px offset for sticky
    var spacing = 25;   // vertical offset between cards
    var maxShrink = 0.08; // maximum shrink per card

    $cards.each(function(i) {
        var $card = $(this).find('.overlapping-card');
        var scale = 1;

        // Loop through all following cards to determine how much to shrink previous ones
        for (var j = i + 1; j < $cards.length; j++) {
            var $nextCard = $cards.eq(j);
            var nextOffset = $nextCard.offset().top; // absolute offset
            var viewportTrigger = $(window).height() / 2; // start shrinking when next card is halfway in view

            // progress: 0 → 1 as next card approaches stickyTop
            var progress = Math.min(Math.max((viewportTrigger - (nextOffset - scrollTop)) / viewportTrigger, 0), 1);

            // Shrink current and all previous cards based on progress
            scale -= maxShrink * progress;
        }

        // Offset each card vertically by spacing
        var yOffset = spacing * i;

        $card.css('transform', 'translateY(' + yOffset + 'px) scale(' + scale + ')');

    });
}

if ($('.overlapping-card-wrapper').length && $(window).width() > 767) {
    $(window).on('scroll resize', rafThrottle(animateOverlappingCards));
    $(document).ready(animateOverlappingCards);
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

	  $(document).on("scroll", rafThrottle(setWidth));
	  $(window).on("resize", function () {
	    // Need to reset max
	    max = getMax();
	    setWidth();
	  });
	}


    function bindMobileToggles() {

        if ($(window).width() <= 767) {

            $(".list-container .item .title-column")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();

                    var item = $(this).closest(".item");
                    var titleCol = item.find(".title-column");
                    var infoCol = item.find(".more-info");

                    titleCol.toggleClass("active");
                    infoCol.toggleClass("active");
                });

            $(".list-container .item .more-info a").on("click", function(e){
                e.stopPropagation();
            });

        } else {

            $(".list-container .item .title-column").off("click");

        }
    }

    $(window).on("resize", function(){
        bindMobileToggles();
    });


})(window.jQuery);
