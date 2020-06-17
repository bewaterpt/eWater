$(document).ready(() => {

    if ($("#report-process-status").length > 0) {
        $("#report-process-status").DataTable({
            responsive: true,
            // ordering: false,
            searching: false,
            lengthChange: false,
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            }
        });
    }
});
