$(document).ready(() => {

    if ($('#settings-permissions').length > 0) {
        $('#settings-permissions').on('submit', function(event) {
            event.preventDefault();

            $(event.target).find('button[type="submit"]').find('#spinner, #spinner-text').removeClass('d-none');
            $(event.target).find('button[type="submit"]').find('.btn-text').addClass('d-none');

            var dataToSubmit = [];

            $("#settings-permissions .permission-value .square.green").each(function(index) {
                if($(this).prop('checked')) {
                    var element_attributes = getPermissionAttributes($(this).attr('name'));

                    dataToSubmit.push({
                        id : element_attributes.id,
                        route : element_attributes.route
                    });
                }
            });


            // Ajax to server
            updateAjaxPermissions(dataToSubmit, event);
        });

        function getPermissionAttributes(element){
            element = element.split('][');
            element[0] = element[0].replace('[','');
            element[1] = element[1].replace(']','');

            return {
                id : element[0],
                route : element[1]
            };
        }

        function updateAjaxPermissions(dataToSubmit, event){
            $.ajax({
                url: window.origin + '/permissions/update',
                method: "POST",
                data: JSON.stringify({data : dataToSubmit}),
                dataType: 'json'
            }).done(function (response) {
                $(event.target).find('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                $(event.target).find('button[type="submit"]').find('.btn-text').removeClass('d-none');
            });
        }
    }
})
