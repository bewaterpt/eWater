$(() => {
    if($('#calls-interruption-create').length > 0){
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
    }
});
