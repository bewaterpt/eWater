let loadingHTML = '<div class="loading-cpage">'+
                          '<svg id="load" x="0px" y="0px" viewBox="0 0 150 150">'+
                              '<circle id="loading-inner" cx="75" cy="75" r="60"/>'+
                          '</svg>'+
                      '</div>';

$(() => {
    if ($("#datatable-pbx").length > 0) {
        $("#datatable-pbx").DataTable({
            responsive: true,
            order: [[ 1, "desc" ]],
            // ordering: false,
            columnDefs: [
                {
                    targets: 'actions',
                    orderable: false,
                }
            ],
            lengthChange: true,
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            }
        });
    }

    if ($("#datatable-calls").length > 0) {
        window.datatable_calls = $("#datatable-calls").DataTable({
            responsive: true,
            searching: true,
            order: [[ 0, "desc" ]],
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
            ajax: '/calls',
            // lengthMenu: [[10, 50, 100], [10, 50, 100]],
            // displayLength: 10,
            // pagingType: 'simple',
            columns: [
                // {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting_disabled', searchable: false, sortable: false},
                {data: 'timestart', name: 'timestart', searchable: true},
                {data: 'callfrom', name: 'callfrom', searchable: true},
                {data: 'callto', name: 'callto', searchable: true},
                {data: 'callduration', name: 'callduration', searchable: true},
                {data: 'talkduration', name: 'talkduration', searchable: true},
                {data: 'waitduration', name: 'waitduration', searchable: true},
                {data: 'status', name: 'status', searchable: true},
                {data: 'type', name: 'type', searchable: true}
            ],
            language: {
                url: "/config/dataTables/lang/" + window.lang + ".json"
            },
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
        });

        let t = null;

        $('#datatable-calls').find('thead .filter-col').each((i, el) => {
            $(el).on('change keyup', (evt) => {
                console.log(i)
                console.log(evt.target)
                window.datatable_calls.column(i).search(($(el).is('select') ? $(el).find('option:selected').val() : el.value));
                clearTimeout(t);
                t = setTimeout(() => {
                    window.datatable_calls.draw();
                }, 500);
            });
        });

        // $('#datatable-calls').find('thead th').each((i, el) => {
        //     $(el).on('click', (evt) => {
        //         if ($(evt.target).is('.filter-col')) {
        //             evt.preventDefault();
        //             evt.stopPropagation();
        //         }
        //     });
        // });
    }

    function checkRecordNumber(table = $("table[id^='datatable_']"), json){

        if(typeof(table) !== 'object'){
            if(table.length === 1){
                table = $(table);
            }else{
                console.error("Multiple Tables are not yet supported by the function checkRecordNumber in initDatatables.js");
            }
        }

        if(json){
            if( json.recordsTotal > 0 ){
                $(table).addClass('visible');
                $(table).parent().parent().find('.default-dt-create').addClass('visually--hidden');
                $(table).parent().parent().find('.customers-dt-create').addClass('visually--hidden');
                $(table).parent().parent().find('.default-dt').addClass('visually--hidden');
                $(table).find('.elements-actions-area').removeClass('visually--hidden');
                $(table).find('.cpage_empty_table').removeClass('visually--hidden');
            }else{
                $(table).removeClass('visible');
                $(table).parent().parent().find('.default-dt-create').removeClass('visually--hidden');
                $(table).parent().parent().find('.default-dt').removeClass('visually--hidden');
                $(table).parent().parent().find('.customers-dt-create').removeClass('visually--hidden');
                $(table).find('.elements-actions-area').addClass('visually--hidden');
                $(table).find('.cpage_empty_table').addClass('visually--hidden');
            }
        }else if(!typeof(table) === 'object'){
            console.error("Parameter table must be either a table Id or the table object, check if you're missing the parameter or missing a #, or contact the developer of the site to report this bug");
        }else{
            console.error("You're missing parameter JSON or it is empty at function checkRecordNumber in file initDatatables.js, please check your code to make sure it is there, or contact the developer of the site to report this bug");
        }
    }


});
