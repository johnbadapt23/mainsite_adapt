
    if ( $('[data-blocks]') ) {
        var expanding = $('[data-blocks]');
        var cols = expanding.data('cols-desktop');

        if ( $(window).width() < 768 ) {
            cols = expanding.data('cols-mobile');
        } else if ( $(window).width() < 1024 ) {
            cols = expanding.data('cols-tablet');
        } else {
            cols = expanding.data('cols-desktop');
        }

    	expanding.on('click', '[data-expand]', function(e) {
    		e.preventDefault();

            var expanding = $('[data-blocks]');
            var items = expanding.find('[data-item]');
            var element = $(this).closest('[data-item]');
            var index =  element.index();
            var total = expanding.find('[data-item]').length;
    		var position = Math.ceil((index+1) / cols);
    		var appendPos = (position * cols) - 1;
    		if(appendPos > (total-1)) {
    			appendPos = total-1;
    		}

    		if(element.attr('data-open') == '1') {
    			expanding.find('> [data-expanded]').slideUp(expanding.data('transition'), function () {
    				expanding.find('> [data-expanded]').remove();
    			});
                element.attr('data-open', '0');
                expanding.attr('data-open', '0');
    		} else {
    			if(expanding.attr('data-open') != '1') {
    				block = expanding.find('[data-item]').eq(index).find('[data-expanded]').clone();
    				block.insertAfter('[data-item]:eq('+appendPos+')').hide();
    				expanding.find('> [data-expanded]').slideDown(expanding.data('transition'));
                    element.attr('data-open', '1');
    				expanding.attr('data-open', '1');
    			} else {
        			expanding.find('> [data-expanded]').slideUp(expanding.data('transition'), function () {
        				expanding.find('> [data-expanded]').remove();

                        items.attr('data-open', '0');
                        element.attr('data-open', '1');

        				block = expanding.find('[data-item]').eq(index-1).find('[data-expanded]').clone();
        				block.insertAfter('[data-item]:eq('+appendPos+')').hide();
        				expanding.find('> [data-expanded]').slideDown(expanding.data('transition'));
        			});
    			}
    		}
        });

    	expanding.on('click', '[data-close]', function(e) {
    		e.preventDefault();

            expanding.find('[data-item][data-open="1"] [data-expand]').trigger('click');
        });
    }

	$(window).resize(function() {
        if ( $(window).width() < 768 ) {
            cols = expanding.data('cols-mobile');
        } else if ( $(window).width() < 1024 ) {
            cols = expanding.data('cols-tablet');
        } else {
            cols = expanding.data('cols-desktop');
        }
	});
