
$(document).ready(() => {
    let t = setInterval(() => {
        if ($('#datatable-users').length > 0) {
            $('#datatable-users').dataTable({
                columnDefs: [
                    {
                        targets: $("#datatable-users").find("thead tr:first th.actions").index(),
                        orderable: false,
                    }
                ],
            });
            clearInterval(t);
        }
    }, 1000);
});
