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

    if ($("#datatable-reports").length > 0) {
        window.datatable_reports = $("#datatable-reports").DataTable({
            responsive: true,
            order: [[ 1, "desc" ]],
            // ordering: false,
            columnDefs: [
                {
                    targets: 'sorting-disabled',
                    orderable: false,
                }
            ],
            searching: true,
            bFilter: false,
            columnDefs: [
                {
                    targets: 'sorting-disabled',
                    orderable: false,
                }
            ],
            lengthChange: true,
            language: {
                paginate: {
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>'
                },
                sProcessing: loadingHTML,
                sEmptyTable: "No Records",
                url: "/config/dataTables/lang/" + window.lang + ".json"
            },
            autoWidth: false,
            processing: true,
            serverSide: true,
            // searchPanes: true,
            ajax: '/daily-reports',
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                // {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting_disabled', searchable: false, sortable: false},
                {data: 'actions', name: 'actions', searchable: true},
                {data: 'id', name: 'id', searchable: true},
                {data: 'status', name: 'status', searchable: true},
                {data: 'quantity', name: 'quantity', searchable: true},
                {data: 'driven_km', name: 'driven_km', searchable: true},
                {data: 'team', name: 'team_id', searchable: true},
                {data: 'date', name: 'entry_date', searchable: true},
                {data: 'info', name: 'info', searchable: true}
            ],
            drawCallback: function(settings){

                var data = this.api().ajax.json();

                // if(data){
                //     checkRecordNumber(this, data);
                // }
            },
            // serverData: function (sSource, aoData, fnCallback) {
            //     aoData.push({ "name": "", "value": "my_value" } );
            //     // etc
            //     $.getJSON( sSource, aoData, function (json) { fnCallback(json) } );
            // }
            // initComplete:function( settings, json){
            //     checkRecordNumber(this, json);
            // }
            lengthChange: true,
        });

        let t = null;

        $('#datatable-reports').find('thead .filter-col').each((i, el) => {
            $(el).on('change keyup', (evt) => {
                window.datatable_reports.column(i).search(($(el).is('select') ? $(el).find('option:selected').val() : el.value));
                clearTimeout(t);
                t = setTimeout(() => {
                    window.datatable_reports.draw();
                }, 500);
            });
        });
    }
});
