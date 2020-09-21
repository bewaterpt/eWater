$(() => {
    if ($(".multiselect-listbox").length > 0) {
        $(".multiselect-listbox #btnContainer #addItems").on('click', (event) => {
            event.preventDefault();

            console.log(event.target);

            $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectRight")[0]).prop('selected', false);
            $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").remove();
        });

        $(".multiselect-listbox #btnContainer #removeItems").on('click', (event) => {
            event.preventDefault();

            console.log(event.target);

            $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectLeft")[0]).prop('selected', false);
            $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").remove();
        });
    }

    let form = $($('.multiselect-listbox')[0]).parent('form');
    console.log(form);

    form.on('submit', (event) => {
        event.preventDefault()


        form.find('.multiselect-listbox').each((index, multiselect) => {
            form.find('input#'+$(multiselect).attr('data-field')).val('');
            $(multiselect).find('#selectRight option').each((index, item) => {
                if (index === ($(multiselect).find('#selectRight option').length-1)) {
                    form.find('input#'+$(multiselect).attr('data-field')).val(form.find('input#'+$(multiselect).attr('data-field')).val() + item.value)
                } else {
                    form.find('input#'+$(multiselect).attr('data-field')).val(form.find('input#'+$(multiselect).attr('data-field')).val() + item.value + ', ')
                }
            });
        })
        form[0].submit();
    });
});
