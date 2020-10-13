$(() => {
    $('table[id^=datatable] tfoot .dt-search').find('select, input').each(() => {
        $(this).on('keyup change', () => {
            performDTsearch($(this).parents('table')[0].id, this.value, $(this).parent('td').index());
        });
    });

    function performDTsearch(tableId, searchVal, index) {
        dataTable = $('#' + tableId).DataTable();
        jQTable = $(tableId);

        jQTable.find('tfoot .dt-search').each(() => {
            dataTable.column($(this).index()).search('');
        });

        dataTable.column(index).search(searchVal).draw();
    }
});
