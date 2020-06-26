$(document).ready(() => {
    if ($("form#updateUser").length > 0) {
        $("form#updateUser").on('submit', (event) => {
            event.preventDefault()
            $('form#updateUser').find('#selectRight option').each((index, role) => {
                if (index === ($('form#updateUser').find('#selectRight option').length-1)) {
                    $('form#updateUser input#roles').val($('form#updateUser input#roles').val() + role.value)
                } else {
                    $('form#updateUser input#roles').val($('form#updateUser input#roles').val() + role.value + ', ')
                }
            });
            $('form#updateUser')[0].submit();
        });
    }
});
