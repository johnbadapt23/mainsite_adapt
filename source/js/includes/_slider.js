
	var slider = $('[data-slider]');
	slider.flexslider({
		animation: 'fade', //String: Select your animation type, "fade" or "slide"
		direction: 'horizontal', //String: Select the sliding direction, "horizontal" or "vertical"
		useCSS:true,
		touch:true,
		slideshow: true, //Boolean: Animate slider automatically
		slideshowSpeed: slider.data('speed'), //Integer: Set the speed of the slideshow cycling, in milliseconds
		animationSpeed: slider.data('duration'), //Integer: Set the speed of animations, in milliseconds
		animationDuration: slider.data('duration'), //Integer: Set the speed of animations, in milliseconds
		directionNav: slider.data('show-direction'), //Boolean: Create navigation for previous/next navigation? (true/false)
		controlNav: slider.data('show-controls'), //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
		keyboardNav: false, //Boolean: Allow slider navigating via keyboard left/right keys
		mousewheel: false, //Boolean: Allow slider navigating via mousewheel
		prevText: '', //String: Set the text for the "previous" directionNav item
		nextText: '', //String: Set the text for the "next" directionNav item
		pausePlay: false, //Boolean: Create pause/play dynamic element
		pauseText: 'Pause', //String: Set the text for the "pause" pausePlay item
		playText: 'Play', //String: Set the text for the "play" pausePlay item
		randomize: false, //Boolean: Randomize slide order
		slideToStart: 0, //Integer: The slide that the slider should start on. Array notation (0 = first slide)
		animationLoop: true, //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
		pauseOnAction: true, //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
		pauseOnHover: false, //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
		controlsContainer: '', //Selector: Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.
		manualControls: '', //Selector: Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
		minItems: 1,
		start: function(object){

			slider.addClass('active');
			slider.on('click', 'li', function(e) {
				slider.flexslider('next');
			});
			slider.on('click', 'a', function(e) {
				e.stopPropagation();
			});

		},
		before: function(){},
		after: function(){},
		end: function(){}
	});
