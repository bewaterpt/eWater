$(() => {
    const card = $('<div>', {
        class: 'selection-card card',
    });
    const cardH = $('<div>', {
        class: 'card-header'
    })
    const selections = {};
    let id = 1;
    const loading = $('#autocomplete-list ul').html();

    if ($('div[contenteditable=true].search.autocomplete').length > 0) {
        $('body').on('click', function (e) {
            if ($("#autocomplete-list").hasClass('show') && !$(e.target).is('div[contenteditable=true].search.autocomplete')) {
                $("#autocomplete-list").removeClass('show');
            }
        });

        $('div[contenteditable=true].search.autocomplete').on('click', function () {
            if ($("#autocomplete-list ul").children().length > 1) {
                $("#autocomplete-list").addClass('show');
            }
        });

        $('div[contenteditable=true].search.autocomplete').on("keyup", function() {
            var query = $(this).text();

            if (window.addressSearchAjax && window.addressSearchAjax.readyState !== 4) {
                window.addressSearchAjax.abort();
            }

            $("#autocomplete-list ul").html(loading);
            $("#autocomplete-list, #autocomplete-list ul .loading").addClass('show');

            if (query != '') {
                window.addressSearchAjax = $.ajax({
                    url: $(this).attr('data-ajax'),
                    method: "POST",
                    data: {query: query},
                    // contentType: 'json',
                    dataType: 'html',
                    success: (data) => {
                        $("#autocomplete-list .loading").removeClass('show');
                        window.requestdata = data;
                        console.log(data)
                        $("#autocomplete-list ul").html(data).find('li a').on('click', function() {
                            const input = $('#autocomplete-list').siblings('input[type=hidden]');
                            let allFieldData = JSON.parse(input.val());
                            let data = $(this).attr();
                            data.text = $(this).text();
                            allFieldData.push(data);
                            console.log(allFieldData);
                            input.val(JSON.stringify(allFieldData));
                            input.trigger('change');
                        });
                        $("#autocomplete-list").addClass('show');
                    }
                });
            } else {
                $("#autocomplete-list").removeClass('show');
            }
        });
    }
});
