$(() => {
    if ($('#calls-pbx-create')) {
        $('#calls-pbx-create .show-password').on('mousedown mouseup', (evt) => {
            if ($(evt.target).parent('a').siblings('input').attr('type') === 'password') {
                $(evt.target).parent('a').siblings('input').attr('type', 'text');
            } else {
                $(evt.target).parent('a').siblings('input').attr('type', 'password');
            }
        });
    }
})
