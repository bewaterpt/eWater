
$(() => {
    let t = setInterval(() => {
        if ($('#datatable-users').length > 0) {
            $('#datatable-users').dataTable({
                responsive: true,
                order: [[ 1, "asc" ]],
                columnDefs: [
                    {
                        targets: $("#datatable-users").find("thead tr:first th.actions").index(),
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
