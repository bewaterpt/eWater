$(() => {
    const card = $('<div>', {
        class: 'selection-card card col-md-4',
    });
    const cardH = $('<div>', {
        class: 'card-headers'
    })
    const cardB = $('<div>', {
        class: 'card-body'
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
                    dataType: 'html',
                    success: function(data) {
                        $("#autocomplete-list .loading").removeClass('show');
                        window.requestdata = data;
                        console.log(data)
                        $("#autocomplete-list ul").html(data).find('li a').on('click', function() {
                            const input = $('#autocomplete-list').siblings('input[type=hidden]');
                            let allFieldData = JSON.parse(input.val());
                            let data = $(this).attr();
                            data.text = $(this).text();
                            allFieldData[data["data-resource-id"]] = data;
                            input.val(JSON.stringify(allFieldData));
                            card.attr('id', 'address-' + data["data-type"] + '-' + data["data-resource-id"]);
                            let cardField = card.clone(true);
                            let cardHeader = cardH.clone(true);
                            let cardBody = cardB.clone(true);
                            cardBody.html(data.text);
                            $('<a href="#" class="remove float-right text-danger"><i class="fas fa-times"></i></a>').appendTo(cardHeader);
                            cardHeader.appendTo(cardField);
                            cardBody.appendTo(cardField);
                            $('#selection-list').append(cardField);

                            $('.card-headers a').on('click', function() {
                                addressToRemove = $(this).parents('.selection-card').attr('id').split('-').slice(1); // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/slice
                                let newData = Object.keys(data).filter((object) => { // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/filter
                                    return object["data-type"] != addressToRemove[0] && object["data-resource-id"] != addressToRemove[1];
                                });

                                $(this).parents('.selection-card').remove();
                            });

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
