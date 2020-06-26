$(document).ready(() => {
    if ($("form#updateStatus").length > 0) {
        $("form#updateStatus").on('submit', (event) => {
            event.preventDefault()
            $('form#updateStatus').find('#selectRight option').each((index, role) => {
                if (index === ($('form#updateStatus').find('#selectRight option').length-1)) {
                    $('form#updateStatus input#roles').val($('form#updateStatus input#roles').val() + role.value)
                } else {
                    $('form#updateStatus input#roles').val($('form#updateStatus input#roles').val() + role.value + ', ')
                }
            });
            $('form#updateStatus')[0].submit();
        });
    }
});
