$(document).ready(() => {
    if ($("#multiselect-listbox").length > 0) {
        $("#multiselect-listbox #btnContainer #addItems").on('click', (event) => {
            event.preventDefault();

            $("#multiselect-listbox #selectLeft").find(":selected").appendTo($("#multiselect-listbox #selectRight")[0]).prop('selected', false);
            $("#multiselect-listbox #selectLeft").find(":selected").remove();
        });

        $("#multiselect-listbox #btnContainer #removeItems").on('click', (event) => {
            event.preventDefault();

            $("#multiselect-listbox #selectRight").find(":selected").appendTo($("#multiselect-listbox #selectLeft")[0]).prop('selected', false);
            $("#multiselect-listbox #selectRight").find(":selected").remove();
        });
    }
});
