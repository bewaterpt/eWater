$(() => {
    if($('#interruption-create').length > 0){

        $('input[name=scheduled]').on('change', (event) => {
            console.log(event.target.value);
            $.ajax({
                url: '/interruptions/get-motive-list',
                data: {'scheduled': event.target.value},
                method: 'POST',
                dataType: 'json',
                success: (response) => {
                    if(response.status == 200){
                        $('#inputMotive').find('option').remove();
                        response.motives.forEach((motive)=>{
                            $('#inputMotive').append(`<option value="${motive.id}">${motive.name}</option>`);
                        })
                    }

                }
            });
        });
        $('#inputAddress').on("keyup", function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "/interruptions/fetch",
                    method: "POST",
                    data: {query:query},
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        $('#addressList').fadeIn();
                        // $('#addressList').html(data);
                    }
                })
            }
        })
    }
});
