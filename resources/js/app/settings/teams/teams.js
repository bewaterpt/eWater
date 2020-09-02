$(document).ready(() => {

    $('#modalTeamUsers').on('show.bs.modal', (event) => {
        var dataId = '';

        if (typeof $(event.relatedTarget).data('id') !== 'undefined') {
            dataId = $(event.relatedTarget).data('id');
        }

        $(event.target).find('#content .body').html("");
        $(event.target).find('#modal-spinner').removeClass('d-none');

        $currAjax = $.ajax({
            method: 'POST',
            url: '/teams/get-users',
            data: JSON.stringify({ id: dataId, raw: false }),
            contentType: 'json',
            success: (response) => {
                response = JSON.parse(response);
                $(event.target).find('#content .body').html(response.content);
                $(event.target).find('#modal-spinner').addClass('d-none');
            },
            error: (jqXHR, status, error) => {
                $(event.target).find('#modal-spinner').addClass('d-none');
                alert(error);
            },
            complete: () => {
                $(event.target).find('#modal-spinner').addClass('d-none');
            },
        });
    });

    if($('#teams-colorpicker').find('input').val() !== '') {
        $('#teams-colorpicker').colorpicker({});
    } else {
        $('#teams-colorpicker').colorpicker({
            color: window.getRandomVibrantColor(20)
        });
    }
});
