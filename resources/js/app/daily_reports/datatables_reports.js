$(document).ready(() => {

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
            // ordering: false,
            columnDefs: [
                {
                    targets: $("#reports").find("thead tr:first th").length,
                    orderable: false,
                }
            ],
            lengthChange: false,
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            }
        });
    }
});
