$(() => {
    let loadingHTML = '<div class="loading-cpage">'+
                          '<svg id="load" x="0px" y="0px" viewBox="0 0 150 150">'+
                              '<circle id="loading-inner" cx="75" cy="75" r="60"/>'+
                          '</svg>'+
                      '</div>';

    if ($("#datatable-interruptions").length > 0) {
        window.datatable_calls = $("#datatable-interruptions").DataTable({
            responsive: true,
            searching: true,
            order: [[ 1, "desc" ]],
            columnDefs: [
                {
                    targets: 'sorting-disabled',
                    orderable: false,
                },
                {
                    targets: 'text-center',
                    className: "text-center",
                },
                {
                    targets: 'limit-w-35',
                    width: '35%',
                },
                {
                    targets: 'limit-w-45',
                    width: '45%',
                },
                {
                    targets: 'limit-w-15',
                    width: '15%',
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
            ajax: window.location.pathname,
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting-disabled', searchable: false, sortable: false},
                {data: 'work_id', name: 'work_id', searchable: true},
                {data: 'start_date', name: 'start_date', searchable: true},
                {data: 'affected_area', name: 'affected_area', searchable: true},
                {data: 'reinstatement_date', name: 'reinstatement_date', searchable: true},
                // {data: 'coordinates', name: 'coordinates', searchable: true},
                {data: 'scheduled', name: 'scheduled', searchable: false, class: 'sorting-disabled text-center'},
                // {data: 'outono_id', name: 'outono_id', searchable: false, calss: 'sorting-disabled text-center'},
            ],
            drawCallback: function(settings){

                var data = this.api().ajax.json();

                // if(data) {
                //     checkRecordNumber(this, data);
                // }
            },
            fnRowCallback: ( nRow, aData, iDisplayIndex, iDisplayIndexFull ) => {
                if (aData.trashed) {
                    $('td', nRow).css('opacity', '0.8').css('background-color', '#ff717136');
                }
            },
            // initComplete:function( settings, json){
            //     checkRecordNumber(this, json);
            // }
        });
    }

    if ($("#datatable-scheduled-interruptions").length > 0) {
        window.datatable_calls = $("#datatable-shceduled-interruptions").DataTable({
            responsive: true,
            searching: true,
            order: [[ 1, "desc" ]],
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
            ajax: '/interruptions/scheduled',
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting-disabled', searchable: false, sortable: false},
                {data: 'work_id', name: 'work_id', searchable: true},
                {data: 'start_date', name: 'start_date', searchable: true},
                {data: 'affected_area', name: 'affected_area', searchable: true},
                {data: 'reinstatement_date', name: 'reinstatement_date', searchable: true},
                {data: 'coordinates', name: 'coordinates', searchable: true},
            ],
            drawCallback: function(settings){
                var data = this.api().ajax.json();
            },
            // initComplete:function( settings, json){
            //     checkRecordNumber(this, json);
            // }
        });
    }

    if ($("#datatable-unscheduled-interruptions").length > 0) {
        window.datatable_calls = $("#datatable-unshceduled-interruptions").DataTable({
            responsive: true,
            searching: true,
            order: [[ 1, "desc" ]],
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
            ajax: '/interruptions/unscheduled',
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting-disabled', searchable: false, sortable: false},
                {data: 'work_id', name: 'work_id', searchable: true},
                {data: 'start_date', name: 'start_date', searchable: true},
                {data: 'affected_area', name: 'affected_area', searchable: true},
                {data: 'reinstatement_date', name: 'reinstatement_date', searchable: true},
                {data: 'coordinates', name: 'coordinates', searchable: true},
            ],
            drawCallback: function(settings){

                var data = this.api().ajax.json();

            },
        });
    }

    $("#submit_button").on("click", function(){
        this.disabled = true;

    });
});

