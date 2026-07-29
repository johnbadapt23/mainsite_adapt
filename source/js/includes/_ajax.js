
    $pagination = false;

    $('[data-pagination]').on('click', 'a', function(e) {
        e.preventDefault();

        if(!$pagination) {
            $pagination = true;

            $(this).addClass('active').text($('[data-pagination]').data('loading'));

            $.ajax({
                type: 'GET',
                url: $(this).attr('href') + '#' + $('[data-pagination]').data('pagination'),
                dataType: "html",
                success: function(out){
                    result = $(out).find('[data-posts] [data-item]');
                    nextlink = $(out).find('[data-pagination] a').attr('href');

                    //$('[data-posts]').append(result.fadeIn(600));
                    $('[data-posts]').append( result ).isotope( 'appended', result );

                    $('[data-pagination] a').removeClass('active').text($('[data-pagination]').data('text'));

                    if (nextlink != undefined) {
                        $('[data-pagination] a').attr('href', nextlink);
                    } else {
                        $('[data-pagination]').remove();
                    }

                    $pagination = false;
                }
            });
        }
    });

    if ( !$('[data-pagination] a').length ) {
        $('[data-pagination]').remove();
    }
