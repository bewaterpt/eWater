"use strict";

var _vue = _interopRequireDefault(require("vue"));

var _laraform = _interopRequireDefault(require("laraform"));

var _this = void 0;

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

_vue["default"].use(_laraform["default"]); // Setup ajax headers


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
}); // Prevent unwanted scrolling of the page when clicking JavaScript handled links

function stopSpontaneousSrcolling() {
  $('a[href="#"]').on("click", function (e) {
    e.preventDefault();
  });
}

stopSpontaneousSrcolling(); // TinyMCE Langs

require('./config/tinymce/lang/pt_PT');

$('button[type="submit"]').on('click', function (e) {
  $(e.target).find('button[type="submit"]').attr('disabled', true);
  return true;
});

$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
    name = this.name.replace(/(\[\])$/, '');

    if (o[name]) {
      if (!o[name].push) {
        o[name] = [o[name]];
      }

      o[name].push(this.value || '');
    } else {
      o[name] = this.value || '';
    }
  });
  return o;
};

$(function () {
  $('[data-toggle="popover"]').popover({
    html: true,
    title: function title() {
      console.log(this);
      return $(document).find('#' + this.id + ' .popover').find('#title').html();
    },
    content: function content() {
      return $(document).find('#' + this.id + ' .popover').find('#content').html();
    }
  });
  $('[data-onload]').each(function (i, el) {
    customOnload(el, $(el).attr('data-onload'));
  });
});
$(function () {
  if ($('textarea.text-editor').length > 0) {
    var editor_config = {
      path_absolute: "/",
      selector: "textarea.text-editor",
      language: 'pt_PT',
      menubar: false,
      statusbar: false,
      plugins: ["advlist autolink lists link image charmap print preview hr anchor pagebreak", "searchreplace visualblocks visualchars code", "insertdatetime media nonbreaking table directionality", "emoticons template paste textpattern"],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
      relative_urls: false,
      file_browser_callback: function file_browser_callback(field_name, url, type, win) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
        var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;

        if (type == 'image') {
          cmsURL = cmsURL + "&type=Images";
        } else {
          cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.open({
          file: cmsURL,
          title: 'Filemanager',
          width: x * 0.8,
          height: y * 0.8,
          resizable: "yes",
          close_previous: "no"
        });
      }
    };
    tinyMCE.init(editor_config);
    window.mce = tinyMCE.init(editor_config); // $('#formNextStatus').on('sumbit', () => {
    //     $('#inputComment').val(quill.root.innerHTML);
    // });
  }
});
$(function () {
  $('table[id^=datatable] tfoot .dt-search').find('select, input').each(function () {
    $(_this).on('keyup change', function () {
      performDTsearch($(_this).parents('table')[0].id, _this.value, $(_this).parent('td').index());
    });
  });

  function performDTsearch(tableId, searchVal, index) {
    var _this2 = this;

    dataTable = $('#' + tableId).DataTable();
    jQTable = $(tableId);
    jQTable.find('tfoot .dt-search').each(function () {
      dataTable.column($(_this2).index()).search('');
    });
    dataTable.column(index).search(searchVal).draw();
  }
});

$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
    var name = this.name.replace(/(?=\S)(\[\]$)/, '').replace(/(?=\S)(-)(?<=\S)/, '_');

    if (o[name]) {
      if (!o[name].push) {
        o[name] = [o[name]];
      }

      o[name].push(this.value || '');
    } else {
      o[name] = [this.value || ''];
    }
  });
  return o;
};

$(function () {
  var t = setInterval(function () {
    if ($('#datatable-users').length > 0) {
      $('#datatable-users').dataTable({
        responsive: true,
        order: [[1, "asc"]],
        columnDefs: [{
          targets: $("#datatable-users").find("thead tr:first th.actions").index(),
          orderable: false
        }],
        language: {
          url: "/config/dataTables/lang/" + window.lang + ".json"
        }
      });
      clearInterval(t);
    }
  }, 1000);
});
$(function () {
  $('#modalTeamUsers').on('show.bs.modal', function (event) {
    var dataId = '';

    if (typeof $(event.relatedTarget).data('id') !== 'undefined') {
      dataId = $(event.relatedTarget).data('id');
    }

    $(event.target).find('#content .body').html("");
    $(event.target).find('#modal-spinner').removeClass('d-none');
    $currAjax = $.ajax({
      method: 'POST',
      url: '/teams/get-users',
      data: JSON.stringify({
        id: dataId,
        raw: false
      }),
      contentType: 'json',
      success: function success(response) {
        response = JSON.parse(response);
        $(event.target).find('#content .body').html(response.content);
        $(event.target).find('#modal-spinner').addClass('d-none');
      },
      error: function error(jqXHR, status, _error) {
        $(event.target).find('#modal-spinner').addClass('d-none');
        alert(_error);
      },
      complete: function complete() {
        $(event.target).find('#modal-spinner').addClass('d-none');
      }
    });
  });

  if ($('#teams-colorpicker').find('input').val() !== '') {
    $('#teams-colorpicker').colorpicker({});
  } else {
    $('#teams-colorpicker').colorpicker({
      color: getRandomVibrantColor(20)
    });
  }
});
$(function () {
  var t = setInterval(function () {
    if ($('#datatable-teams').length > 0) {
      $('#datatable-teams').dataTable({
        responsive: true,
        order: [[1, "asc"]],
        columnDefs: [{
          targets: $("#datatable-teams").find("thead tr:first th.actions").index(),
          orderable: false
        }],
        language: {
          url: "/config/dataTables/lang/" + window.lang + ".json"
        }
      });
      clearInterval(t);
    }
  }, 1000);
});
$(function () {
  if ($('#calls-interruption-create').length > 0) {
    $('input[name=scheduled]').on('change', function (event) {
      console.log(event.target.value);
      $.ajax({
        url: '/interruptions/get-motive-list',
        data: {
          'scheduled': event.target.value
        },
        method: 'POST',
        dataType: 'json',
        success: function success(response) {
          if (response.status == 200) {
            $('#inputMotive').find('option').remove();
            response.motives.forEach(function (motive) {
              $('#inputMotive').append("<option value=\"".concat(motive.id, "\">").concat(motive.name, "</option>"));
            });
          }
        }
      });
    });
  }
});
$(function () {
  var loadingHTML = '<div class="loading-cpage">' + '<svg id="load" x="0px" y="0px" viewBox="0 0 150 150">' + '<circle id="loading-inner" cx="75" cy="75" r="60"/>' + '</svg>' + '</div>';

  if ($("#datatable-interruptions").length > 0) {
    window.datatable_calls = $("#datatable-interruptions").DataTable({
      responsive: true,
      searching: true,
      order: [[1, "desc"]],
      columnDefs: [{
        targets: 'sorting-disabled',
        orderable: false
      }, {
        targets: 'text-center',
        className: "text-center"
      }, {
        targets: 'limit-w-35',
        width: '35%'
      }, {
        targets: 'limit-w-45',
        width: '45%'
      }, {
        targets: 'limit-w-15',
        width: '15%'
      }],
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
      columns: [{
        data: 'actions',
        name: 'actions',
        "class": 'actions text-center px-0 sorting-disabled',
        searchable: false,
        sortable: false
      }, {
        data: 'work_id',
        name: 'work_id',
        searchable: true
      }, {
        data: 'start_date',
        name: 'start_date',
        searchable: true
      }, {
        data: 'affected_area',
        name: 'affected_area',
        searchable: true
      }, {
        data: 'reinstatement_date',
        name: 'reinstatement_date',
        searchable: true
      }, // {data: 'coordinates', name: 'coordinates', searchable: true},
      {
        data: 'scheduled',
        name: 'scheduled',
        searchable: false,
        "class": 'sorting-disabled text-center'
      } // {data: 'outono_id', name: 'outono_id', searchable: false, calss: 'sorting-disabled text-center'},
      ],
      drawCallback: function drawCallback(settings) {
        var data = this.api().ajax.json(); // if(data) {
        //     checkRecordNumber(this, data);
        // }
      },
      fnRowCallback: function fnRowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (aData.trashed) {
          $('td', nRow).css('opacity', '0.8').css('background-color', '#ff717136');
        }
      } // initComplete:function( settings, json){
      //     checkRecordNumber(this, json);
      // }

    });
  }

  if ($("#datatable-scheduled-interruptions").length > 0) {
    window.datatable_calls = $("#datatable-shceduled-interruptions").DataTable({
      responsive: true,
      searching: true,
      order: [[1, "desc"]],
      columnDefs: [{
        targets: 'sorting-disabled',
        orderable: false
      }],
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
      columns: [{
        data: 'actions',
        name: 'actions',
        "class": 'actions text-center px-0 sorting-disabled',
        searchable: false,
        sortable: false
      }, {
        data: 'work_id',
        name: 'work_id',
        searchable: true
      }, {
        data: 'start_date',
        name: 'start_date',
        searchable: true
      }, {
        data: 'affected_area',
        name: 'affected_area',
        searchable: true
      }, {
        data: 'reinstatement_date',
        name: 'reinstatement_date',
        searchable: true
      }, {
        data: 'coordinates',
        name: 'coordinates',
        searchable: true
      }],
      drawCallback: function drawCallback(settings) {
        var data = this.api().ajax.json();
      } // initComplete:function( settings, json){
      //     checkRecordNumber(this, json);
      // }

    });
  }

  if ($("#datatable-unscheduled-interruptions").length > 0) {
    window.datatable_calls = $("#datatable-unshceduled-interruptions").DataTable({
      responsive: true,
      searching: true,
      order: [[1, "desc"]],
      columnDefs: [{
        targets: 'sorting-disabled',
        orderable: false
      }],
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
      columns: [{
        data: 'actions',
        name: 'actions',
        "class": 'actions text-center px-0 sorting-disabled',
        searchable: false,
        sortable: false
      }, {
        data: 'work_id',
        name: 'work_id',
        searchable: true
      }, {
        data: 'start_date',
        name: 'start_date',
        searchable: true
      }, {
        data: 'affected_area',
        name: 'affected_area',
        searchable: true
      }, {
        data: 'reinstatement_date',
        name: 'reinstatement_date',
        searchable: true
      }, {
        data: 'coordinates',
        name: 'coordinates',
        searchable: true
      }],
      drawCallback: function drawCallback(settings) {
        var data = this.api().ajax.json();
      }
    });
  }
});
$(function () {
  var fields = {
    text: $('#fieldText'),
    select: $('#fieldSelect'),
    textarea: $('#fieldTextarea'),
    file: $('#fieldFile'),
    checkbox: $('#fieldMultiChoice'),
    radio: $('#fieldUniqueChoice')
  };
  console.log($('#remove-field'));

  if ($('#create-custom-form').length > 0) {
    /**
     * Block to add a field
     *
     * User clicks on the field type he wants to add and this block appends a clone of the template to
     */
    $('#add-field a').on('click', function (event) {
      link = $(event.currentTarget);
      $('#field-container').append(fields[link.attr('data-type')].clone(true));
    });
    /**
     * Block to remove a field
     *
     * User clicks on the cross and field configuration is removed
     */

    $('[id^="field"] a#remove-field').on('click', function (event) {
      link = $(event.currentTarget);
      console.log(link);
      link.parents('.field').remove();
    });
    /**
     * Block to insert options in select item list
     *
     * User writes as many options as he/she wants, block separates them by comma (,) and adds each to the select element.
     *
     * @todo Add ability to remove items.
     */

    $('[id^="field"] a#insert-option').on('click', function (event) {
      var link = $(event.currentTarget);
      var options = link.siblings('input').val().split(',');
      $(options).each(function (i, item) {
        link.siblings('select').append('<option value="' + link.siblings('select').find('option').length + '">' + item.trim() + '</option>');
      });
      $(event.currentTarget).siblings('input').val('').trigger('focus');
    });
    /**
     * Block to capture the form submit and process the data
     */

    $('#create-form').on('submit', function (event) {
      event.preventDefault();
      form = event.currentTarget;
      jQForm = $(event.currentTarget);
      jQForm.find('.select-field').each(function (i, select) {
        input = $(select).parents('.field').find('input[name="options[]"]');
        console.log('Index: ', i);
        input.val('');
        $(select).find('option').each(function (i, item) {
          console.log('Options: ', $(select).find('option'));
          console.log('Length: ', $(select).find('option').length);
          console.log(i === $(select).find('option').length - 1);

          if (i == $(select).find('option').length - 1) {
            input.val(input.val() + $(item).text() + ':' + item.value);
          } else {
            input.val(input.val() + $(item).text() + ':' + item.value + ',');
          }
        });
      });
      var formData = jQForm.serializeObject();
      console.log('Form Data: ', formData);
      $.ajax({
        method: 'POST',
        url: '/test',
        data: JSON.stringify(formData),
        beforeSend: function beforeSend(request) {
          request.setRequestHeader("Content-type", 'application/json');
        }
      });
      console.log(formData);
    });
  }
});
$(function () {
  if ($('#settings-permissions').length > 0) {
    var getPermissionAttributes = function getPermissionAttributes(element) {
      element = element.split('][');
      element[0] = element[0].replace('[', '');
      element[1] = element[1].replace(']', '');
      return {
        id: element[0],
        route: element[1]
      };
    };

    var updateAjaxPermissions = function updateAjaxPermissions(dataToSubmit, event) {
      $.ajax({
        url: window.origin + '/permissions/update',
        method: "POST",
        data: JSON.stringify({
          data: dataToSubmit
        }),
        dataType: 'json'
      }).done(function (response) {
        $(event.target).find('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
        $(event.target).find('button[type="submit"]').find('.btn-text').removeClass('d-none');
      });
    };

    $('#settings-permissions').on('submit', function (event) {
      event.preventDefault();
      $(event.target).find('button[type="submit"]').find('#spinner, #spinner-text').removeClass('d-none');
      $(event.target).find('button[type="submit"]').find('.btn-text').addClass('d-none');
      var dataToSubmit = [];
      $("#settings-permissions .permission-value .square.green").each(function (index) {
        if ($(this).prop('checked')) {
          var element_attributes = getPermissionAttributes($(this).attr('name'));
          dataToSubmit.push({
            id: element_attributes.id,
            route: element_attributes.route
          });
        }
      }); // Ajax to server

      updateAjaxPermissions(dataToSubmit, event);
    });
  }
});
$(function () {
  if ($(".multiselect-listbox").length > 0) {
    $(".multiselect-listbox #btnContainer #addItems").on('click', function (event) {
      event.preventDefault();
      console.log(event.target);
      $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectRight")[0]).prop('selected', false);
      $(event.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").remove();
    });
    $(".multiselect-listbox #btnContainer #removeItems").on('click', function (event) {
      event.preventDefault();
      console.log(event.target);
      $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").appendTo($(event.target).closest(".multiselect-listbox").find("#selectLeft")[0]).prop('selected', false);
      $(event.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").remove();
    });
  }

  var form = $($('.multiselect-listbox')[0]).parent('form');
  console.log(form);
  form.on('submit', function (event) {
    event.preventDefault();
    form.find('.multiselect-listbox').each(function (index, multiselect) {
      form.find('input#' + $(multiselect).attr('data-field')).val('');
      $(multiselect).find('#selectRight option').each(function (index, item) {
        if (index === $(multiselect).find('#selectRight option').length - 1) {
          form.find('input#' + $(multiselect).attr('data-field')).val(form.find('input#' + $(multiselect).attr('data-field')).val() + item.value);
        } else {
          form.find('input#' + $(multiselect).attr('data-field')).val(form.find('input#' + $(multiselect).attr('data-field')).val() + item.value + ', ');
        }
      });
    });
    form[0].submit();
  });
});
$(function () {
  $("a[data-toggle='tooltip']").tooltip({
    html: true
  });
});

var _require = require("leaflet"),
    Evented = _require.Evented;

$(function () {
  var error = false;
  var kmError = false;
  var today = new Date();

  if ($('#daily-reports-create').length > 0) {
    if ($('#inputDatetime').val() === '') {
      $('#inputDatetime').val(ISODateString(today)).attr('max', ISODateString(today));
    }
  }

  if ($('#daily-reports-create').length > 0 || $('#daily-reports-edit').length > 0) {
    /**
     * Gets the selected article info
     *
     * @param {Event} event - the inherited event that called the function
     */
    var getArticleInfo = function getArticleInfo(event) {
      $.ajax({
        type: 'POST',
        url: '/daily-reports/article/get-info',
        data: {
          id: $(event.target).val()
        },
        dataType: 'json',
        success: function success(data) {
          console.log('Data: ', data);

          if (data.article.fixo == 1) {
            $(event.target).closest('tr').find('#inputUnitPrice').val(parseFloat(data.article.precoUnitario).toFixed(2)).prop('readonly', true);
          } else {
            $(event.target).closest('tr').find('#inputUnitPrice').val(0).prop('read-only', true);
          }
        }
      });
    };

    /**
     * Removes desired table row
     *
     * @param {Event} event - the inherited event that called the function
     */
    var removeRow = function removeRow(event) {
      $(event.target).closest('tr').remove();
    };

    var removeWork = function removeWork(event) {
      $(event.target).closest('.card.work:not(#original-work)').remove();
    };

    var addRow = function addRow(event) {
      var tr = $(event.target).parents('.card.work').find('table#report-lines tbody tr:last-child').clone();
      console.log(tr);
      tr.find('input').val('').prop('readonly', false);
      tr.find(':not(td:first-child) input[type="number"]').val(0);
      tr.removeClass('first');
      tr.find('#removeRow').on('click', function (e) {
        removeRow(e);
      }); // if (tr.find('#inputDatetime').val() === '') {
      //     tr.find('#inputDatetime').val(ISODateString(today));
      // }

      tr.find('#info').tooltip({
        html: true,
        title: function title() {
          return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html();
        }
      });
      console.log(tr[0]);
      $(event.target).parents('.card.work').find('table#report-lines tbody').append(tr); // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

      $('a[href="#"]').on('click', function (event) {
        event.preventDefault();
      });
    };

    var formatAndSendReportData = function formatAndSendReportData() {
      var editing = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      clearAjaxErrors();

      if (
      /*(window.verifyingWork && window.verifyingWork.readyState === 4) ||*/
      true || editing) {
        var data = {
          plate: $('input[name="plate"]').val(),
          km_departure: $('input[name="km-departure"]').val(),
          km_arrival: $('input[name="km-arrival"]').val(),
          comment: $('textarea').val(),
          datetime: $('#inputDatetime').val(),
          team: $('#inputTeam').children('option:selected').val()
        };
        var workNumbers = $('div.card.work input.work-number').map(function (_, work) {
          return work.value;
        }).get();

        if (new Date(data.datetime) > today) {
          throw new Error($('#errors #invalidDate').text());
        }

        if ($('[data-error=true]').length > 0 || workNumbers.indexOf('0') > -1) {
          throw new Error($('#errors #invalidWorkNumber').text());
        }

        if (editing) {
          data.id = $('#reportId').text();
        }

        var totalKm = data.km_arrival - data.km_departure;
        var userInsertedKm = 0;
        var rows = {};
        $('div.card.work').each(function (workIndex, work) {
          console.log('Work: ', work);
          var workNum = $(work).find('input.work-number').val();

          if (workNum === 0) {
            throw new Error($('#errors #unexpectedError'));
          }

          rows[workNum] = {};
          $(work).find('tbody tr').each(function (trIndex, tr) {
            rows[workNum][trIndex] = {};
            rows[workNum][trIndex]['driven_km'] = $(work).find('input.driven-km').val();
            $(tr).find('input:not(.work-number), select').each(function (inputIndex, input) {
              if (input.name !== 'driven_km') {
                if (input.name === 'quantity') {
                  rows[workNum][trIndex][input.name] = parseFloat(parseFloat(input.value).toFixed(2));
                } else {
                  rows[workNum][trIndex][input.name] = input.value;
                }
              }
            });
          });
        });
        $(document).find('.card.work .card-header input[name=driven_km]').each(function (inputIndex, input) {
          userInsertedKm += parseInt(input.value);
        });
        console.log('Total: ', totalKm);
        console.log('Inserted: ', userInsertedKm);
        console.log(Math.abs(userInsertedKm - totalKm));

        if (userInsertedKm - totalKm < 0 && kmError == false) {
          kmError = true;
          throw new Error($('#errors #inferiorKm').text());
        } else if (userInsertedKm - totalKm > 0) {
          throw new Error($('#errors #superiorKm').text());
        } else if (userInsertedKm - totalKm < 0 && $('#inputComment').val().length < 15) {
          throw new Error($('#errors #inferiorKmWarn').text());
        }

        data.rows = rows;

        if (!window.createOrEditReportRequest || window.createOrEditReportRequest.readyState === 4) {
          window.createOrEditReportRequest = $.ajax({
            method: 'POST',
            url: $('#report').attr('action'),
            data: JSON.stringify(data),
            contentType: 'json',
            success: function success(response) {
              $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
              $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
              window.location.replace(response);
            },
            error: function error(jqXHR, status, _error2) {
              console.log(_error2);

              if (jqXHR.responseJSON.exception) {
                logAjaxError(_error2);
                throw new Error(_error2.message);
              } else {
                console.log(jqXHR.responseJSON);
                processAjaxErrors(jqXHR.responseJSON.errors);
              }

              console.log(status); // console.log("Error: ", error);

              $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
              $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
            },
            complete: function complete() {
              $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
              $('button[type="submit"]').find('.btn-text').removeClass('d-none');
            }
          });
        }
      } else {
        throw new Error($('#errors #waitForWorkCheck').text());
      }
    };

    var checkWorkExists = function checkWorkExists(evt) {
      error = false;
      var data = {
        id: $(evt.target).val()
      };

      if (data.id !== "") {
        if (window.verifyingWork && window.verifyingWork.readyState !== 4) {
          window.verifyingWork.abort();
        }

        window.verifyingWork = $.ajax({
          method: 'POST',
          url: '/works/work-exists',
          data: JSON.stringify(data),
          contentType: 'json',
          success: function success(response) {
            response = JSON.parse(response);
            console.log("Response: ", response);

            if (response.value === false) {
              $(evt.target).parent().popover({
                html: true,
                title: function title() {
                  return $(document).find('#' + this.id + ' .popover').find('#title').html();
                },
                content: function content() {
                  return $(document).find('#' + this.id + ' .popover').find('#content').html();
                }
              });
              $(evt.target).parent().find('.popover #content').html($('#errors .' + response.reason).html());
              $(evt.target).addClass('border-danger').addClass('bg-flamingo').attr('data-error', true).focus();
              $('.popover:not(.popover-data)').addClass('popover-danger');
            } else {
              $(evt.target).removeClass('border-danger').removeClass('bg-flamingo').removeAttr('data-error');
              $(evt.target).parent().popover('dispose');
            }
          },
          error: function error(jqXHR, status, _error3) {}
        });
      } else {
        $(evt.target).removeClass('border-danger').removeClass('bg-flamingo').removeAttr('data-error');
        $(evt.target).parent().popover('dispose');
      }
    };

    setInterval(function () {
      var userInsertedKm = 0;
      var totalKm = $('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val();
      $(document).find('.card.work .card-header input[name=driven_km]').each(function (inputIndex, input) {
        userInsertedKm += parseInt(input.value);
      });

      if (userInsertedKm - totalKm < 0) {
        $("#warnings #superiorKmErr").addClass('d-none');
        $("#warnings #inferiorKmWarn").removeClass('d-none');
      } else if (userInsertedKm - totalKm > 0) {
        $("#warnings #inferiorKmWarn").addClass('d-none');
        $("#warnings #superiorKmErr").removeClass('d-none');
      } else {
        $("#warnings #superiorKmErr").addClass('d-none');
        $("#warnings #inferiorKmWarn").addClass('d-none');
      }
    }, 1000);
    $('#addRow').on('click', function (event) {
      addRow(event);
    });
    $('a.remove-work').on('click', function (event) {
      removeWork(event);
    });
    $('a#removeRow').on('click', function (event) {
      removeRow(event);
    });
    $('a.add-work').on('click', function (event) {
      console.log('Target: ', event.target);
      var work = $(event.target).parents('.card').find('.work').last().clone();
      work.removeAttr('id');
      var trs = work.find('table#report-lines tbody tr');
      trs.each(function (index, tr) {
        if (trs.length - (index + 1) == 0) {
          return false;
        }

        $(tr).remove();
      });
      work.find('#addRow').on('click', function (event) {
        addRow(event);
      });
      console.log('Work: ', work);
      work.find('a.remove-work').on('click', function (event) {
        removeWork(event);
      });
      var tr = work.find('table#report-lines tbody tr:last-child');
      tr.find('input').val('').prop('readonly', false);
      tr.find(':not(td:first-child) input[type="number"]').val(0);
      tr.removeClass('first');
      tr.find('#removeRow').on('click', function (event) {
        removeLine(event);
      }); // if (tr.find('#inputDatetime').val() === '') {
      //     tr.find('#inputDatetime').val(ISODateString(today));
      // }

      tr.find('#info').tooltip({
        html: true,
        title: function title() {
          return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html();
        }
      });
      $(work).find('input.work-number').on('keydown keyup change', function (evt) {
        checkWorkExists(evt);
      }); // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

      $(event.target).parents('.card').find('.work').last().after(work);
      $('a[href="#"]').click(function (event) {
        event.preventDefault();
      });
    });
    $(".info-tooltip").tooltip({
      html: true,
      title: function title() {
        return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html();
      }
    });
    $('div.card.work input.work-number').on('keyup', function (evt) {
      checkWorkExists(evt);
    });
    $('#report').on('submit', function (event) {
      event.preventDefault();
      event.stopPropagation();
      $('button[type="submit"]').find('#spinner, #spinner-text').removeClass('d-none');
      $('button[type="submit"]').find('.btn-text').addClass('d-none');

      if (!error) {
        try {
          if ($('#daily-reports-edit').length > 0) {
            formatAndSendReportData(true);
          } else {
            formatAndSendReportData();
          }
        } catch (error) {
          $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
          $('button[type="submit"]').find('.btn-text').removeClass('d-none');
          alert(error.message);
          return;
        }
      } else {
        $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
        $('button[type="submit"]').find('.btn-text').removeClass('d-none');
      }
    });
    $(document).on('change keyup', 'input[name="km-departure"], input[name="km-arrival"]', function (event) {
      $('#total-km-holder #value').text(parseInt($('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val()));

      if (parseInt($('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val()) < 0) {
        $('#total-km-holder').addClass('text-danger');
      } else {
        $('#total-km-holder').removeClass('text-danger');
      }
    });
    $(document).on('change keyup', 'input[name="quantity"]', function (event) {
      console.log('Fired');
      var totalHours = 0;
      $('input[name="quantity"]').each(function (index, field) {
        totalHours += parseFloat($(field).val()) || 0;
      });

      if (totalHours > 0) {
        $('#total-hour-holder').find('#value').text(decimalToTimeValue(parseFloat(totalHours).toFixed(2)));
      }

      if (parseInt($('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val()) < 0) {
        $('#total-km-holder').addClass('text-danger');
      } else {
        $('#total-km-holder').removeClass('text-danger');
      }
    });
  }

  if ($('#daily-reports-view').length > 0) {
    $('#modalComment').on('show.bs.modal', function (event) {
      var dataId = '';

      if (typeof $(event.relatedTarget).data('id') !== 'undefined') {
        dataId = $(event.relatedTarget).data('id');
      }

      $(event.target).find('#content .body').html("");
      $(event.target).find('#modal-spinner').removeClass('d-none');
      $currAjax = $.ajax({
        method: 'POST',
        url: '/daily-reports/process-status/get-comment',
        data: JSON.stringify({
          id: dataId
        }),
        contentType: 'json',
        success: function success(response) {
          response = JSON.parse(response);
          $(event.target).find('#content .body').html(response.content);
          $(event.target).find('#modal-spinner').addClass('d-none');
        },
        error: function error(jqXHR, status, _error4) {
          $(event.target).find('#modal-spinner').addClass('d-none');
          alert(_error4);
        },
        complete: function complete() {
          $(event.target).find('#modal-spinner').addClass('d-none');
        }
      });
    });
  }

  $("#cancel-report").on("click", function (event) {
    event.preventDefault();

    if (confirm($("#prompts .cancel-report").text())) {
      window.location.replace($(event.currentTarget).attr('href'));
    }
  });

  if ($('#clear-date-field').length > 0) {
    $('#clear-date-field').on('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      $(event.currentTarget).siblings('input').val('');

      if (window.datatable_reports) {
        window.datatable_reports.column($(event.target).parents('th').index()).search('').draw();
      }
    });
  }
});
$(function () {
  if ($("#report-process-status").length > 0) {
    $("#report-process-status").DataTable({
      responsive: true,
      order: [[3, "desc"]],
      searching: false,
      lengthChange: false,
      language: {
        url: "/config/dataTables/lang/" + window.lang + ".json"
      }
    });
  }

  if ($("#datatable-reports").length > 0) {
    var _$$DataTable;

    window.datatable_reports = $("#datatable-reports").DataTable((_$$DataTable = {
      responsive: true,
      searching: true,
      order: [[1, "desc"]],
      columnDefs: [{
        targets: 'sorting-disabled',
        orderable: false
      }]
    }, _defineProperty(_$$DataTable, "searching", true), _defineProperty(_$$DataTable, "bFilter", false), _defineProperty(_$$DataTable, "columnDefs", [{
      targets: 'sorting-disabled',
      orderable: false
    }]), _defineProperty(_$$DataTable, "lengthChange", true), _defineProperty(_$$DataTable, "language", {
      paginate: {
        previous: '<i class="fa fa-angle-left"></i>',
        next: '<i class="fa fa-angle-right"></i>'
      },
      sProcessing: loadingHTML,
      sEmptyTable: "No Records",
      url: "/config/dataTables/lang/" + window.lang + ".json"
    }), _defineProperty(_$$DataTable, "autoWidth", false), _defineProperty(_$$DataTable, "processing", true), _defineProperty(_$$DataTable, "serverSide", true), _defineProperty(_$$DataTable, "ajax", '/daily-reports'), _defineProperty(_$$DataTable, "columns", [{
      data: 'actions',
      name: 'actions',
      "class": 'actions text-center px-0 sorting_disabled',
      searchable: false,
      sortable: false
    }, {
      data: 'id',
      name: 'id',
      searchable: true
    }, {
      data: 'status',
      name: 'current_status',
      sortable: false
    }, {
      data: 'quantity',
      name: 'quantity',
      searchable: false,
      sortable: false
    }, {
      data: 'driven_km',
      name: 'driven_km',
      searchable: true
    }, {
      data: 'team',
      name: 'team_id',
      searchable: true
    }, {
      data: 'date',
      name: 'date',
      searchable: true
    }, {
      data: 'info',
      name: 'info',
      searchable: false
    }]), _defineProperty(_$$DataTable, "drawCallback", function drawCallback(settings) {// var data = this.api().ajax.json();
      // console.log('Settings: ', settings);
      // console.log('Api: ', this.api());
    }), _$$DataTable));
    var t = null;
    $('#datatable-reports').find('thead .filter-col').each(function (i, el) {
      $(el).on('change keyup', function (evt) {
        window.datatable_reports.column(i).search($(el).is('select') ? $(el).find('option:selected').val() : el.value);
        console.log($(el).is('select') ? $(el).find('option:selected').val() : el.value);
        clearTimeout(t);
        t = setTimeout(function () {
          window.datatable_reports.draw();
        }, 500);
      });
    });
  }
});

var _require2 = require("tinymce"),
    triggerSave = _require2.triggerSave;

function getmonthlyWaitTimeInfo() {
  return new Promise(function (resolve, reject) {
    window.monthlyWaitTimeInfoAjax = $.ajax({
      method: 'POST',
      url: '/calls/get_monthly_wait_time_info',
      contentType: 'json',
      data: JSON.stringify(queryData),
      success: function success(response) {
        chartData = JSON.parse(response);
        window.monthlyWaitTimeInfoChart.data = {
          labels: chartData.labels,
          datasets: [{
            label: $("#labels #maxMonthlyWaitTime").text(),
            data: chartData.max,
            backgroundColor: 'rgba(43, 132, 99, 0.2)',
            borderColor: 'rgba(43, 132, 99, 0.2)',
            borderWidth: 1
          }, {
            label: $("#labels #averageMonthlyWaitTime").text(),
            data: chartData.avg,
            backgroundColor: 'rgba(43, 34, 200, 0.2)',
            borderColor: 'rgba(43, 34, 200, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }, {
            label: $("#labels #weightedAverageMonthlyWaitTime").text(),
            data: chartData.wavg,
            backgroundColor: 'rgba(200, 34, 43, 0.2)',
            borderColor: 'rgba(200, 34, 43, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }]
        };
        window.monthlyWaitTimeInfoChart.update();
        resolve(true);
      },
      error: function error(jqXHR, status, _error5) {
        reject(_error5.message);
      }
    });
  });
}

function getMonthlyCallVolume() {
  return new Promise(function (resolve, reject) {
    window.monthlyCallNumberAjax = $.ajax({
      method: 'POST',
      url: '/calls/get_monthly_call_number_info',
      contentType: 'json',
      data: JSON.stringify(queryData),
      success: function success(response) {
        chartData = JSON.parse(response);
        window.monthlyCallNumberInfoChart.data = {
          labels: chartData.labels,
          datasets: [{
            label: $("#labels #monthlyTotalCalls").text(),
            data: chartData.total,
            backgroundColor: 'rgba(43, 132, 99, 0.2)',
            borderColor: 'rgba(43, 132, 99, 0.2)',
            borderWidth: 1
          }, {
            label: $("#labels #monthlyFrontOfficeCalls").text(),
            data: chartData.frontOffice,
            backgroundColor: 'rgba(43, 34, 200, 0.2)',
            borderColor: 'rgba(43, 34, 200, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }, {
            label: $("#labels #monthlyGenericCalls").text(),
            data: chartData.generic,
            backgroundColor: 'rgba(200, 34, 43, 0.2)',
            borderColor: 'rgba(200, 34, 43, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }, {
            label: $("#labels #monthlyInternalCalls").text(),
            data: chartData.internal,
            backgroundColor: 'rgba(200, 34, 140, 0.2)',
            borderColor: 'rgba(200, 34, 140, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }]
        };
        window.monthlyCallNumberInfoChart.update();
        window.monthlyLostCallNumberInfoChart.data = {
          labels: chartData.labels,
          datasets: [{
            label: $("#labels #monthlyTotalLostCalls").text(),
            data: chartData.totalLost,
            backgroundColor: 'rgba(43, 132, 99, 0.2)',
            borderColor: 'rgba(43, 132, 99, 0.2)',
            borderWidth: 1
          }, {
            label: $("#labels #monthlyFrontOfficeLostCalls").text(),
            data: chartData.frontOfficeLost,
            backgroundColor: 'rgba(43, 34, 200, 0.2)',
            borderColor: 'rgba(43, 34, 200, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }, {
            label: $("#labels #monthlyGenericLostCalls").text(),
            data: chartData.genericLost,
            backgroundColor: 'rgba(200, 34, 43, 0.2)',
            borderColor: 'rgba(200, 34, 43, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }, {
            label: $("#labels #monthlyInternalLostCalls").text(),
            data: chartData.internalLost,
            backgroundColor: 'rgba(200, 34, 140, 0.2)',
            borderColor: 'rgba(200, 34, 140, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false
          }]
        };
        window.monthlyLostCallNumberInfoChart.update();
        resolve(true);
      },
      error: function error(jqXHR, status, _error6) {
        reject(_error6.message);
      }
    });
  });
}

$(function () {
  if ($('#calls-pbx-create').length > 0) {
    $('#calls-pbx-create .show-password').on('mousedown mouseup', function (evt) {
      if ($(evt.target).parent('a').siblings('input').attr('type') === 'password') {
        $(evt.target).parent('a').siblings('input').attr('type', 'text');
      } else {
        $(evt.target).parent('a').siblings('input').attr('type', 'password');
      }
    });
  }

  if ($("#calls-dashboard").length > 0) {
    var monthlyWaitTimeInfoCtx = $("#monthlyWaitTimeInfo")[0].getContext('2d');
    var monthlyCallNumberInfoCtx = $("#monthlyCallNumberInfo")[0].getContext('2d');
    var monthlyLostCallNumberInfoCtx = $("#monthlyLostCallNumberInfo")[0].getContext('2d');
    queryData = {
      inbound: false,
      dates: false
    };
    window.monthlyWaitTimeInfoChart = new Chart(monthlyWaitTimeInfoCtx, {
      type: 'bar',
      options: {
        title: {
          display: true,
          text: $("#titles #minMaxExternalMonthlyWaitTime").text()
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              userCallback: function userCallback(item) {
                return decimalSecondsToTimeValue(item);
              }
            }
          }]
        }
      }
    });
    window.monthlyCallNumberInfoChart = new Chart(monthlyCallNumberInfoCtx, {
      type: 'bar',
      options: {
        title: {
          display: true,
          text: $("#titles #totalCallsByTypeAndMonthExcludeLost").text()
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true // userCallback: (item) => {
              //     return decimalSecondsToTimeValue(item);
              // }

            }
          }]
        }
      }
    });
    window.monthlyLostCallNumberInfoChart = new Chart(monthlyLostCallNumberInfoCtx, {
      type: 'bar',
      options: {
        title: {
          display: true,
          text: $("#titles #totalLostCallsByTypeAndMonth").text()
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true // userCallback: (item) => {
              //     return decimalSecondsToTimeValue(item);
              // }

            }
          }]
        }
      }
    });
    getmonthlyWaitTimeInfo()["catch"](function (msg) {
      alert(msg);
    });
    getMonthlyCallVolume()["catch"](function (msg) {
      alert(msg);
    });
    $('#export a').on('click', function () {
      $.ajax({
        method: 'GET',
        url: $(this).attr('href'),
        dataType: 'json',
        success: function success(result, status, xhr) {
          var disposition = xhr.getResponseHeader('content-disposition');
          var matches = /"([^"]*)"/.exec(disposition);
          var filename = matches != null && matches[1] ? matches[1] : xhr.getResponseHeader('X-ewater-filename'); // The actual download

          var blob = new Blob([result], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          });
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          $('#modalSpinner').modal('hide');
        }
      });
    });
    $('#refetchCallData').on('click', function (evt) {
      if (!$(evt.currentTarget).attr('data-disabled')) {
        $.ajax({
          url: '/calls/refetch',
          success: function success(response) {
            response = JSON.parse(response);

            if (response.status === 200) {
              window.datatable_calls.draw();
              getmonthlyWaitTimeInfo()["catch"](function (msg) {
                alert(msg);
              });
              getMonthlyCallVolume()["catch"](function (msg) {
                alert(msg);
              });
            } else {
              alert(response.message);
            }

            $('#modalSpinner').modal('hide');
          },
          error: function error(err) {
            console.log(err);
            alert(err.responseText);
          }
        });
      }
    });
    $('#clearDate').on('click', function (evt) {
      // console.log(evt.currentTarget);
      if ($(evt.currentTarget).siblings('input').val() != "") {
        $(evt.currentTarget).tooltip({
          html: true
        });
        $(evt.currentTarget).siblings('input').val("").trigger('change');
      }
    });
    var updating = false;
    var cdri = setInterval(function () {
      // console.log(updating);
      $.ajax({
        url: 'check-call-record-update-state',
        method: 'GET',
        dataType: 'json',
        headers: {
          'Access-Control-Allow-Origin': 'localhost'
        },
        success: function success(response) {
          // console.log(response);
          if (parseInt(response.updating) === 1) {
            $('#refetchCallData').addClass('spining').attr('data-disabled', true);

            if (parseInt(response.current) !== 0) {
              $('#cdrUpdateState').removeClass('d-none').addClass('d-inline');
              $('#cdrUpdateState .current').text(response.current);
              $('#cdrUpdateState .total').text(response.total);
            }

            updating = true;
          } else {
            $('#cdrUpdateState').addClass('d-none').removeClass('d-inline');
            $('#refetchCallData').removeClass('disabled spining').removeAttr('data-disabled').tooltip('dispose');

            if (updating) {
              window.datatable_calls.draw();
              getmonthlyWaitTimeInfo()["catch"](function (msg) {
                alert(msg);
              });
              getMonthlyCallVolume()["catch"](function (msg) {
                alert(msg);
              });
              updating = false;
            }
          }
        },
        error: function error(_error7) {
          clearInterval(cdri);
        }
      });
    }, 2000);
  }
});
var loadingHTML = '<div class="loading-cpage">' + '<svg id="load" x="0px" y="0px" viewBox="0 0 150 150">' + '<circle id="loading-inner" cx="75" cy="75" r="60"/>' + '</svg>' + '</div>';
$(function () {
  if ($("#datatable-pbx").length > 0) {
    $("#datatable-pbx").DataTable({
      responsive: true,
      order: [[1, "desc"]],
      // ordering: false,
      columnDefs: [{
        targets: 'actions',
        orderable: false
      }],
      lengthChange: true,
      language: {
        url: "/config/dataTables/lang/" + window.lang + ".json"
      }
    });
  }

  if ($("#datatable-calls").length > 0) {
    var _$$DataTable2;

    window.datatable_calls = $("#datatable-calls").DataTable((_$$DataTable2 = {
      responsive: true,
      searching: true,
      order: [[0, "desc"]],
      bFilter: false,
      columnDefs: [{
        targets: 'sorting-disabled',
        orderable: false
      }],
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
      columns: [// {data: 'actions',name: 'actions', class: 'actions text-center px-0 sorting_disabled', searchable: false, sortable: false},
      {
        data: 'timestart',
        name: 'timestart',
        searchable: true
      }, {
        data: 'callfrom',
        name: 'callfrom',
        searchable: true
      }, {
        data: 'callto',
        name: 'callto',
        searchable: true
      }, {
        data: 'callduration',
        name: 'callduration',
        searchable: true
      }, {
        data: 'talkduration',
        name: 'talkduration',
        searchable: true
      }, {
        data: 'waitduration',
        name: 'waitduration',
        searchable: true
      }, {
        data: 'status',
        name: 'status',
        searchable: true
      }, {
        data: 'type',
        name: 'type',
        searchable: true
      }]
    }, _defineProperty(_$$DataTable2, "language", {
      url: "/config/dataTables/lang/" + window.lang + ".json"
    }), _defineProperty(_$$DataTable2, "drawCallback", function drawCallback(settings) {
      var data = this.api().ajax.json(); // if(data){
      //     checkRecordNumber(this, data);
      // }
    }), _$$DataTable2));
    var t = null;
    $('#datatable-calls').find('thead .filter-col').each(function (i, el) {
      $(el).on('change keyup', function (evt) {
        console.log(i);
        console.log(evt.target);
        window.datatable_calls.column(i).search($(el).is('select') ? $(el).find('option:selected').val() : el.value);
        clearTimeout(t);
        t = setTimeout(function () {
          window.datatable_calls.draw();
        }, 500);
      });
    }); // $('#datatable-calls').find('thead th').each((i, el) => {
    //     $(el).on('click', (evt) => {
    //         if ($(evt.target).is('.filter-col')) {
    //             evt.preventDefault();
    //             evt.stopPropagation();
    //         }
    //     });
    // });
  }

  function checkRecordNumber() {
    var table = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $("table[id^='datatable_']");
    var json = arguments.length > 1 ? arguments[1] : undefined;

    if (_typeof(table) !== 'object') {
      if (table.length === 1) {
        table = $(table);
      } else {
        console.error("Multiple Tables are not yet supported by the function checkRecordNumber in initDatatables.js");
      }
    }

    if (json) {
      if (json.recordsTotal > 0) {
        $(table).addClass('visible');
        $(table).parent().parent().find('.default-dt-create').addClass('visually--hidden');
        $(table).parent().parent().find('.customers-dt-create').addClass('visually--hidden');
        $(table).parent().parent().find('.default-dt').addClass('visually--hidden');
        $(table).find('.elements-actions-area').removeClass('visually--hidden');
        $(table).find('.cpage_empty_table').removeClass('visually--hidden');
      } else {
        $(table).removeClass('visible');
        $(table).parent().parent().find('.default-dt-create').removeClass('visually--hidden');
        $(table).parent().parent().find('.default-dt').removeClass('visually--hidden');
        $(table).parent().parent().find('.customers-dt-create').removeClass('visually--hidden');
        $(table).find('.elements-actions-area').addClass('visually--hidden');
        $(table).find('.cpage_empty_table').addClass('visually--hidden');
      }
    } else if (!_typeof(table) === 'object') {
      console.error("Parameter table must be either a table Id or the table object, check if you're missing the parameter or missing a #, or contact the developer of the site to report this bug");
    } else {
      console.error("You're missing parameter JSON or it is empty at function checkRecordNumber in file initDatatables.js, please check your code to make sure it is there, or contact the developer of the site to report this bug");
    }
  }
});
$(function () {
  var t = setInterval(function () {
    if ($('#datatable-roles').length > 0) {
      $('#datatable-roles').dataTable({
        responsive: true,
        order: [[1, "asc"]],
        columnDefs: [{
          targets: $("#datatable-roles").find("thead tr:first th.actions").index(),
          orderable: false
        }],
        language: {
          url: "/config/dataTables/lang/" + window.lang + ".json"
        }
      });
      clearInterval(t);
    }
  }, 1000);
});
$(function () {
  if ($('#datatable-motives').length > 0) {
    var _$$DataTable3;

    window.datatable_motives = $('#datatable-motives').DataTable((_$$DataTable3 = {
      responsive: true,
      searching: true,
      order: [[1, "desc"]]
    }, _defineProperty(_$$DataTable3, "searching", true), _defineProperty(_$$DataTable3, "bFilter", false), _defineProperty(_$$DataTable3, "columnDefs", [{
      targets: 'sorting-disabled',
      orderable: false
    }]), _defineProperty(_$$DataTable3, "lengthChange", true), _defineProperty(_$$DataTable3, "language", {
      paginate: {
        previous: '<i class="fa fa-angle-left"></i>',
        next: '<i class="fa fa-angle-right"></i>'
      },
      sProcessing: loadingHTML,
      sEmptyTable: "No Records",
      url: "/config/dataTables/lang/" + window.lang + ".json"
    }), _defineProperty(_$$DataTable3, "autoWidth", false), _defineProperty(_$$DataTable3, "processing", true), _defineProperty(_$$DataTable3, "serverSide", true), _defineProperty(_$$DataTable3, "ajax", '/interruptions/motives'), _defineProperty(_$$DataTable3, "columns", [{
      data: 'actions',
      name: 'actions',
      "class": 'actions text-center px-0 sorting_disabled',
      searchable: false,
      sortable: false
    }, {
      data: 'name',
      name: 'name',
      searchable: true
    }, {
      data: 'scheduled',
      name: 'scheduled',
      searchable: false
    }]), _defineProperty(_$$DataTable3, "drawCallback", function drawCallback(settings) {// var data = this.api().ajax.json();
      // console.log('Settings: ', settings);
      // console.log('Api: ', this.api());
    }), _defineProperty(_$$DataTable3, "fnRowCallback", function fnRowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      console.log('Last Index: ', aData.trashed);

      if (aData.trashed) {
        $('td', nRow).css('opacity', '0.8').css('background-color', '#ff717136');
      }
    }), _$$DataTable3));
  }
});
$(function () {
  if ($('#map').length > 0) {
    var onMapClick = function onMapClick(e) {
      popup.setLatLng(e.latlng).setContent("You clicked the map at " + e.latlng.toString()).openOn(map);
    };

    var littleton = L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.'),
        denver = L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.'),
        aurora = L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.'),
        golden = L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.');
    var cities = L.layerGroup([littleton, denver, aurora, golden]);
    var streets = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoiYmV3YXRlci1kZXYiLCJhIjoiY2tpeWU4dGIwNDJleDJ5cWptcjJqamt1aSJ9.jo7MJzmi_-zqWUX3KJACYg'
    });
    var grayscale = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/light-v10',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoiYmV3YXRlci1kZXYiLCJhIjoiY2tpeWU4dGIwNDJleDJ5cWptcjJqamt1aSJ9.jo7MJzmi_-zqWUX3KJACYg'
    });
    var map = L.map('map', {
      center: [39.73, -104.99],
      zoom: 10,
      layers: [grayscale, cities]
    });
    var baseMaps = {
      "Grayscale": grayscale,
      "Streets": streets
    };
    var overlayMaps = {
      "Cities": cities
    };
    L.control.layers(baseMaps, overlayMaps).addTo(map);
    var marker = L.marker([51.5, -0.10]).addTo(map);
    marker.bindPopup("<b>Hello world!</b><br>I am a popup.");
    var geojsonFeature = {
      "type": "Feature",
      "properties": {},
      "geometry": {
        "type": "Polygon",
        "coordinates": [[[-0.105743408203125, 51.51515248101072], [-0.08909225463867188, 51.50489601254001], [-0.06505966186523438, 51.51322956905176], [-0.07055282592773438, 51.524765823244685], [-0.105743408203125, 51.51515248101072]]]
      }
    };
    var geoJsonLayer = L.geoJSON().addTo(map);
    geoJsonLayer.addData(geojsonFeature);
    var popup = L.popup();
    map.on('click', onMapClick);
  }
});
