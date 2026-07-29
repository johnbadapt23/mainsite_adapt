
    function getParameterByName(name) {
	    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
	    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
	}

    function escapeHtml(text) {
        'use strict';
        return text.replace(/[\"&'\/<>]/g, function (a) {
            return {
                '"': '&quot;', '&': '&amp;', "'": '&#39;',
                '/': '&#47;',  '<': '&lt;',  '>': '&gt;'
            }[a];
        });
    }

    function formatMoney(number, places, symbol, thousand, decimal) {
    	number = number || 0;
    	places = !isNaN(places = Math.abs(places)) ? places : 2;
    	symbol = symbol !== undefined ? symbol : "$";
    	thousand = thousand || ",";
    	decimal = decimal || ".";
    	var negative = number < 0 ? "-" : "",
    		i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
    		j = (j = i.length) > 3 ? j % 3 : 0;
    	returnVal = symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");

        if(returnVal != 'Infinity' && returnVal != "$NaN.N") {
            return returnVal;
        } else {
            return "$0.00"
        }
    }
