$(() => {

    if ($("#report-process-status").length > 0) {
        $("#report-process-status").DataTable({
            responsive: true,
            order: [[ 3, "desc" ]],
            searching: false,
            lengthChange: false,
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            }
        });
    }

    if ($("#reports").length > 0) {
        $("#reports").DataTable({
            responsive: true,
            order: [[ 1, "desc" ]],
            // ordering: false,
            columnDefs: [
                {
                    targets: 'sorting-disabled',
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
