let loadingHTML = '<div class="loading-cpage">'+
                          '<svg id="load" x="0px" y="0px" viewBox="0 0 150 150">'+
                              '<circle id="loading-inner" cx="75" cy="75" r="60"/>'+
                          '</svg>'+
                      '</div>';

function ISODateString(d){
    function pad(n){return n<10 ? '0'+n : n}
    return d.getUTCFullYear()+'-'
    + pad(d.getUTCMonth()+1)+'-'
    + pad(d.getUTCDate())
    // + 'T'+pad(d.getUTCHours())+':'
    // + pad(d.getUTCMinutes());
    // + pad(d.getUTCSeconds())+'Z'
}

function ISODateTimeString(d){
    function pad(n){return n<10 ? '0'+n : n}
    return d.getUTCFullYear()+'-'
    + pad(d.getUTCMonth()+1)+'-'
    + pad(d.getUTCDate())
    + 'T'+pad(d.getUTCHours())+':'
    + pad(d.getUTCMinutes()) + ':'
    + pad(d.getUTCSeconds());
}

function setMaxDate(el) {
    $(el).attr('max', ISODateString(new Date()));
}

function setMaxDateTime(el) {
    $(el).attr('max', ISODateTimeString(new Date()));
}

function customOnload(el, type) {
    if (type == 'date') {
        setMaxDate(el);
    } else if (tyoe == 'datetime') {
        setMaxDateTime(el);
    }
}

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

function getRandomBrightColor(brightness) {
    function randomChannel(brightness) {
        var r = 255-brightness;
        var n = 0|((Math.random() * r) + brightness);
        var s = n.toString(16);
        return (s.length==1) ? '0'+s : s;
    }
    return '#' + randomChannel(brightness) + randomChannel(brightness) + randomChannel(brightness);
};

function getRandomVibrantColor(stepLimit) {
    // This function generates vibrant, "evenly spaced" colours (i.e. no clustering). This is ideal for creating easily distinguishable vibrant markers in Google Maps and other apps.
    // Adam Cole, 2011-Sept-14
    // HSV to RBG adapted from: http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
    var r, g, b;
    var h = Math.random(stepLimit) / Math.random();
    var i = ~~(h * 6);
    var f = h * 6 - i;
    var q = 1 - f;
    switch(i % 6){
        case 0: r = 1; g = f; b = 0; break;
        case 1: r = q; g = 1; b = 0; break;
        case 2: r = 0; g = 1; b = f; break;
        case 3: r = 0; g = q; b = 1; break;
        case 4: r = f; g = 0; b = 1; break;
        case 5: r = 1; g = 0; b = q; break;
    }
    var c = "#" + ("00" + (~ ~(r * 255)).toString(16)).slice(-2) + ("00" + (~ ~(g * 255)).toString(16)).slice(-2) + ("00" + (~ ~(b * 255)).toString(16)).slice(-2);
    return (c);
};

function decimalToTimeValue(totalHours, calculateDays, calculateSeconds) {
    const sInDay = 86400;
	const sInHr = 3600;
	const sInMin = 60;
	let totalSeconds = 0;
	let days = 0, hours = 0, minutes = 0, seconds = 0;

	totalSeconds = totalHours * sInHr;

    if(calculateDays) {
        if (totalSeconds >= sInDay) {
            days = totalSeconds / sInDay;
            totalSeconds = totalSeconds % sInDay;
        }
    }

	if (totalSeconds >= sInHr) {
		hours = totalSeconds / sInHr;
		totalSeconds = totalSeconds % sInHr;
	}

	if (totalSeconds >= sInMin) {
		minutes = totalSeconds / sInMin;
		totalSeconds = totalSeconds % sInMin;
	}

	seconds = totalSeconds;

	return (calculateDays ? parseInt(days) + "d": "") + (hours < 10 ? "0" : "") + parseInt(hours) + (minutes < 10 ? ":0" : ":") + parseInt(minutes) + (calculateSeconds ? (seconds < 10 ? ":0" : ":") + parseInt(seconds) : "" );
}

function decimalSecondsToTimeValue(totalSeconds) {
    const sInDay = 86400;
	const sInHr = 3600;
	const sInMin = 60;
	let days = 0, hours = 0, minutes = 0, seconds = 0;

	if (totalSeconds >= sInHr) {
		hours = totalSeconds / sInHr;
		totalSeconds = totalSeconds % sInHr;
	}

	if (totalSeconds >= sInMin) {
		minutes = totalSeconds / sInMin;
		totalSeconds = totalSeconds % sInMin;
	}

	seconds = totalSeconds;

    return (hours !== 0 ? (hours < 10 ? "0" : "") + parseInt(hours) + ':' : '') + (minutes < 10 ? "0" : "") + parseInt(minutes) + ':' + (seconds < 10 ? "0" : "") + parseInt(seconds);
}

function normalizeFormFieldsForPost(serializedFields) {
    // placeholder
}

function processAjaxErrors(errors) {
    console.log('Errors: ', errors);

    $('#ajax-errors').find('ul').html('');

    if (Object.keys(errors).length !== 0) {
        Object.values(errors).forEach((e, i) => {
            $('#ajax-errors').find('ul').append('<li>' + e + '</li>').removeClass('d-none');
        });
    } else {
        $('#ajax-errors').find('ul').removeClass('d-none');
    }
}

function logAjaxError(error) {
    $.ajax([

    ])
}
