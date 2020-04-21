
$(document).ready(() => {
    let t = setInterval(() => {
        if ($('#datatable-users').length > 0) {
            $('datatable-users').dataTable();
            clearInterval(t);
        }
    }, 1000);
});
