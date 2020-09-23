$(() => {

    if ($("#datatable-pbx").length > 0) {
        $("#datatable-pbx").DataTable({
            responsive: true,
            order: [[ 1, "desc" ]],
            // ordering: false,
            columnDefs: [
                {
                    targets: $("#datatable-pbx").find("thead tr:first th.actions").index(),
                    orderable: false,
                }
            ],
            lengthChange: true,
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            }
        });
    }
});
