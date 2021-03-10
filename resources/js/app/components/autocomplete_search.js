$(() => {
    let t = null;
    const card = $('<div>', {
        class: 'selection-card border float-left p-0 mt-2',
    });
    const cardH = $('<div>', {
        class: 'card-header'
    });
    const cardB = $('<div>', {
        class: 'card-body'
    });
    const partialLabel = $('<label for="address-partial-check" class="partial-label mr-3">' + $('#translations .partial-label').text() + '</label>')
    const partialCheck = $('<input>', {
        type: 'checkbox',
        name: 'address-partial',
        id: 'address-partial-check'
    });
    const partialInput = $('<input>', {
        type: 'text',
        name: 'address-partial-text',
        class: 'd-none w-100 partial-input',
        required: false,
    });
    const partialInfo = $('<small class="partial-info form-text text-muted w-100 d-none">' + $('#translations .partial-info').text() + '</small>'); 
    const adjacentLabel = $('<label for="address-adjatent-check" class="adjacent-label mr-3">' + $('#translations .adjacent-label').text() + '</label>')
    const adjacentCheck = $('<input>', {
        type: 'checkbox',
        name: 'address-adjacent',
        id: 'address-adjatent-check',
    });
    const a = $("<a>", {
        href: '#',
        class: 'remove float-right text-danger',
    });
    const i = $('<i>', {
        class: 'fas fa-times',
    });
    
    a.append(i);
    a.on('click', function () {
        const addressToRemove = $(this).parents('.selection-card').attr('id').split('-').slice(1); // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/slice
        const inputData = JSON.parse(input.val());
        const newInputData = inputData.filter((entry) => {
            return entry.type != addressToRemove[0] && entry['data-resource-id'] != addressToRemove[1];
        });
        input.val(JSON.stringify(newInputData));
        $(this).parents('.selection-card').remove();
        input.trigger('change');
    });
    
    // Display partial door numbers 
    partialCheck.on('change', function () {
        if ($(this).prop('checked')) {
            $(this).siblings('.partial-input, .partial-info').removeClass('d-none');

        } else {
            $(this).siblings('.partial-input, .partial-info').addClass('d-none');
        }

        const resourceId = $(this).parents('.selection-card').attr('id').split('-').slice(1);
        const inputData = JSON.parse(input.val());
        const partialData = $(this).prop('checked');

        inputData.map((entry) => {
            if (entry['data-resource-id'] == resourceId[1] && entry['data-type'] == resourceId[0]) {
                entry.partial = partialData;
            }
        });

        input.val(JSON.stringify(inputData));
        input.trigger('change');
    });

    partialInput.on('keyup', function () {
        clearTimeout(t);
        t = setTimeout(() => {
            const resourceId = $(this).parents('.selection-card').attr('id').split('-').slice(1);
            const inputData = JSON.parse(input.val());
            const partialData = $(this).val();

            inputData.map((entry, i) => {
                console.log(entry);
                if (entry['data-resource-id'] == resourceId[1] && entry['data-type'] == resourceId[0]) {
                    entry['partial-text'] = partialData;
                }
            });
            console.log('Input data: ', inputData);
            input.val(JSON.stringify(inputData));
            console.log('Input value: ', input.val())
            input.trigger('change');
        }, 1000);
    });

    adjacentCheck.on('change', function () {
        const resourceId = $(this).parents('.selection-card').attr('id').split('-').slice(1);
        const inputData = JSON.parse(input.val());
        const adjacentData = $(this).prop('checked');

        inputData.map((entry, i) => {
            console.log(entry);
            if (entry['data-resource-id'] == resourceId[1] && entry['data-type'] == resourceId[0]) {
                entry.adjacent = adjacentData;
            }
        });

        input.val(JSON.stringify(inputData));
        input.trigger('change');
    });

    // cardB.append(adjacentLabel, adjacentCheck, partialLabel, partialCheck, partialInput);
    card.append(cardH);

    const loading = $('#autocomplete-list ul').html();
    const input = $('#autocomplete-list').siblings('input[type=hidden]');

    if ($('div[contenteditable=true].search.autocomplete').length > 0) {
        $('body').on('click', function (e) {
            if (!$("#autocomplete-list").hasClass('invisible') && !$(e.target).is('div[contenteditable=true].search.autocomplete')) {
                $("#autocomplete-list").addClass('invisible');
            } else if ($("#autocomplete-list").hasClass('invisible') && $(e.target).is('div[contenteditable=true].search.autocomplete') && $("#autocomplete-list ul").children().length > 1) { 
                $("#autocomplete-list").removeClass('invisible');
            }
        });

        $('div[contenteditable=true].search.autocomplete').on("keyup", function() {
            var query = $(this).text();

            if (window.addressSearchAjax && window.addressSearchAjax.readyState !== 4) {
                window.addressSearchAjax.abort();
            }

            $("#autocomplete-list ul").html(loading);
            $("#autocomplete-list, #autocomplete-list ul .loading").removeClass('invisible');

            if (query != '') {
                window.addressSearchAjax = $.ajax({
                    url: $(this).attr('data-ajax'),
                    method: "POST",
                    data: {query: query},
                    // contentType: 'json',
                    dataType: 'html',
                    success: (data) => {
                        // $("#autocomplete-list .loading").removeClass('show');
                        window.requestdata = data;
                        $("#autocomplete-list ul").html(data).find('li a').on('click', function() {
                            const allFieldData = JSON.parse(input.val());
                            const data = $(this).attr();
                            data.text = $(this).text().fullTrim();
                            delete data.href;
                            if (!allFieldData.some(item => item['data-resource-id'] === data['data-resource-id'])) {
                                allFieldData.push(data);
                                input.val(JSON.stringify(allFieldData));
                                input.trigger('change');
                                
                                const id = 'address-' + data["data-type"] + '-' + data["data-resource-id"];
                                const addressCard = card.clone(true).attr('id', id);
                                const addressCardB = cardB.clone(true);
                                const addressRemove = a.clone(true);
                                if (data['data-type'] != 'locality') {
                                    addressCardB.append(adjacentLabel.clone(true), adjacentCheck.clone(true), partialLabel.clone(true), partialCheck.clone(true), partialInput.clone(true), partialInfo.clone(true)).appendTo(addressCard);
                                }
                                addressCard.find('.card-header').append(data.text, addressRemove);
                                $('#selection-list').append(addressCard);
                            }
                        });

                        $("#autocomplete-list").removeClass('invisible');
                    }
                });
            } else {
                $("#autocomplete-list").addClass('invisible');
            }
        });
    }
});
