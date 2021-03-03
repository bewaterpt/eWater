$(() => {
    if ($('div[contenteditable=true].search.autocomplete').length > 0) {
        $('div[contenteditable=true].search.autocomplete').on("keyup", function() {
            var query = $(this).text();
            console.log("HEY!")

            if (query != '') {
                $.ajax({
                    url: $(this).attr('data-ajax'),
                    method: "POST",
                    data: {query:query},
                    dataType: 'json',
                    success: function(data) {
                        window.requestdata = data;
                        console.log(data);
                        $(data.hmtl).appendTo("#autocomplete-list ul");
                    }
                });
            }
        });
    }
});
