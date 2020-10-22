$(() => {
    let fields = {
        text: $('#fieldText'),
        select: $('#fieldSelect'),
        textarea: $('#fieldTextarea'),
        file: $('#fieldFile'),
        checkbox: $('#fieldMultiChoice'),
        radio: $('#fieldUniqueChoice'),
    }

    console.log($('#remove-field'));

    if ($('#create-custom-form').length > 0) {

        /**
         * Block to add a field
         *
         * User clicks on the field type he wants to add and this block appends a clone of the template to
         */
        $('#add-field a').on('click', (event) => {
            link = $(event.currentTarget);
            $('#field-container').append(fields[link.attr('data-type')].clone(true));
        });

        /**
         * Block to remove a field
         *
         * User clicks on the cross and field configuration is removed
         */
        $('[id^="field"] a#remove-field').on('click', (event) => {
            link = $(event.currentTarget);
            console.log(link);
            link.parents('.field').remove();
        });

        /**
         * Block to insert options in select item list
         *
         * User writes as many options as he/she wants, block separates them by comma (,) and adds each to the select element.
         *
         * @todo Add ability to remove items.
         */
        $('[id^="field"] a#insert-option').on('click', (event) => {
            let link = $(event.currentTarget);
            let options = link.siblings('input').val().split(',')

            $(options).each((i, item) => {
                link.siblings('select').append('<option value="' + link.siblings('select').find('option').length + '">' + item.trim() + '</option>');
            });
            $(event.currentTarget).siblings('input').val('').trigger('focus');
        })

        /**
         * Block to capture the form submit and process the data
         */
        $('#create-form').on('submit', (event) => {
            event.preventDefault();

            form = event.currentTarget;
            jQForm = $(event.currentTarget);

            $(jQForm).find('.select-field').each((i, select) => {
                input = $(select).parents('.field').find('input[name="options[]"]')
                console.log('Index: ', i);
                input.val('');
                $(select).find('option').each((i, item) => {
                    console.log('Options: ', $(select).find('option'))
                    console.log('Length: ', $(select).find('option').length)
                    console.log(i === $(select).find('option').length - 1)
                    if (i == $(select).find('option').length - 1) {
                        input.val(input.val() + $(item).text() + ':' + item.value)
                    } else {
                        input.val(input.val() + $(item).text() + ':' + item.value + ',')
                    }
                });
            });

            let formData = jQForm.serializeObject();

            $.ajax({
                method: 'POST',
                url: '/test',
                data: JSON.stringify(formData)
            });

            console.log(formData);
        });
    }
});
