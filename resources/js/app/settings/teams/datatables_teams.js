
$(document).ready(() => {
    let t = setInterval(() => {
        if ($('#datatable-teams').length > 0) {
            $('#datatable-teams').dataTable({
                columnDefs: [
                    {
                        targets: $("#datatable-teams").find("thead tr:first th.actions").index(),
                        orderable: false,
                    }
                ],
            });
            clearInterval(t);
        }
    }, 1000);
});
