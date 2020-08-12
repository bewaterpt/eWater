
$(document).ready(() => {
    let t = setInterval(() => {
        if ($('#datatable-teams').length > 0) {
            $('#datatable-teams').dataTable({
                responsive: true,
                order: [[ 1, "asc" ]],
                columnDefs: [
                    {
                        targets: $("#datatable-teams").find("thead tr:first th.actions").index(),
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
