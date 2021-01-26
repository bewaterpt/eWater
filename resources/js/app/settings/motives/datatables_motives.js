$(() => {
    if ($('#datatable-motives').length > 0) {
        window.datatable_motives = $('#datatable-motives').DataTable({
            responsive: true,
            searching: true,
            order: [[ 1, "desc" ]],
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
            ajax: '/interruptions/motives',
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting_disabled', searchable: false, sortable: false},
                {data: 'name', name: 'name', searchable: true},
                {data: 'scheduled', name: 'scheduled', searchable: false},
            ],
            drawCallback: (settings) => {

                // var data = this.api().ajax.json();

                // console.log('Settings: ', settings);
                // console.log('Api: ', this.api());
            },
            // serverData: function (sSource, aoData, fnCallback) {
            //     aoData.push({ "name": "", "value": "my_value" } );
            //     // etc
            //     $.getJSON( sSource, aoData, function (json) { fnCallback(json) } );
            // }
            // initComplete:function( settings, json){
            //     checkRecordNumber(this, json);
            // }
        });
    }
})
