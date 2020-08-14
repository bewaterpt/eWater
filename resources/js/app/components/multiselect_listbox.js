$(document).ready(() => {
    if ($(".multiselect-listbox").length > 0) {
        $(".multiselect-listbox #btnContainer #addItems").on('click', (event) => {
            event.preventDefault();

            console.log(event.target);

            $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectRight")[0]).prop('selected', false);
            $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").remove();
        });

        $(".multiselect-listbox #btnContainer #removeItems").on('click', (event) => {
            event.preventDefault();

            console.log(event.target);

            $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectLeft")[0]).prop('selected', false);
            $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").remove();
        });
    }
});
