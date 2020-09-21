
$(() => {
    let t = setInterval(() => {
        if ($('#datatable-roles').length > 0) {
            $('#datatable-roles').dataTable({
                responsive: true,
                order: [[ 1, "asc" ]],
                columnDefs: [
                    {
                        targets: $("#datatable-roles").find("thead tr:first th.actions").index(),
                        orderable: false,
                    }
                ],
                language: {
                    url: "/config/dataTables/lang/" + window.lang + ".json"
                }
            });
            clearInterval(t);
        }
    }, 1000);
});
