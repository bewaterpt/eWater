$(document).ready(() => {
    if ($("form#updateUser").length > 0) {
        $("form#updateUser").on('submit', (event) => {
            event.preventDefault()

            $('form#updateUser').find('.multiselect-listbox').each((index, multiselect) => {
                $('form#updateUser input#'+$(multiselect).attr('data-field')).val('');
                $(multiselect).find('#selectRight option').each((index, item) => {
                    if (index === ($(multiselect).find('#selectRight option').length-1)) {
                        $('form#updateUser input#'+$(multiselect).attr('data-field')).val($('form#updateUser input#'+$(multiselect).attr('data-field')).val() + item.value)
                    } else {
                        $('form#updateUser input#'+$(multiselect).attr('data-field')).val($('form#updateUser input#'+$(multiselect).attr('data-field')).val() + item.value + ', ')
                    }
                });
            })
           $('form#updateUser')[0].submit();
        });
    }
});
