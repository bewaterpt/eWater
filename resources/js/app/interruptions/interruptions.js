const { data } = require("jquery");

$(() => {
    if($('#interruption-create, #interruption-edit').length > 0){
        $('input[name=scheduled]').on('change', (event) => {
            console.log(event.target.value);
            $.ajax({
                url: '/interruptions/get-motive-list',
                data: {'scheduled': event.target.value},
                method: 'POST',
                contentType: 'json',
                dataType: 'json',
                success: (response) => {
                    if(response.status == 200){
                        $('#inputMotive').find('option').remove();
                        response.motives.forEach((motive) => {
                            $('#inputMotive').append(`<option value="${motive.id}">${motive.name}</option>`);
                        });
                    }
                }
            });
        });

        $("form").find('input:not([name=address-adjacent]):not([name=address-partial]):not([name=address-partial-text])').on('change', function() {
            let data = $("form").serializeObject();
            data._token = data._token.slice(1);
            console.log('Form Data: ', data);
            window.getAffectedAreaText = $.ajax({
                url: '/interruptions/generate_text',
                method: 'POST',
                data: {data},
                dataType: 'json',
                success: (data) => {
                    if (data.status === 200) {
                        tinyMCE.get('inputAffectedArea').setContent(data.text);
                    } else {
                        // @todo Program bootbox to present error
                    }
                }
            });
        });
    }
});
