"use strict";

var _vue = _interopRequireDefault(require("vue"));

var _laraform = _interopRequireDefault(require("laraform"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

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
  $('a[href="#"]').click(function (event) {
    event.preventDefault();
  });
}

stopSpontaneousSrcolling(); // TinyMCE Langs

require('./config/tinymce/lang/pt_PT');

$('button[type="submit"]').on('click', function (e) {
  $(e.target).find('button[type="submit"]').attr('disabled', true);
  return true;
});
$(document).ready(function () {
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
    window.mce = tinyMCE.init(editor_config);
    ; // $('#formNextStatus').on('sumbit', () => {
    //     $('#inputComment').val(quill.root.innerHTML);
    // });
  }
});
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
$(function () {
  var error = false;
  var today = new Date();

  function ISODateString(d) {
    function pad(n) {
      return n < 10 ? '0' + n : n;
    }

    return d.getUTCFullYear() + '-' + pad(d.getUTCMonth() + 1) + '-' + pad(d.getUTCDate()); // + 'T'+pad(d.getUTCHours())+':'
    // + pad(d.getUTCMinutes());
    // + pad(d.getUTCSeconds())+'Z'
  }

  if ($('#daily-reports-create').length > 0) {
    if ($('#inputDatetime').val() === '') {
      $('#inputDatetime').val(ISODateString(today));
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

      $('a[href="#"]').click(function (event) {
        event.preventDefault();
      });
    };

    var formatAndSendReportData = function formatAndSendReportData() {
      var editing = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      if (window.verifyingWork && window.verifyingWork.readyState === 4 || editing) {
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

        if (Math.abs(userInsertedKm - totalKm) !== 0) {
          throw new Error($('#errors #differentKm').text());
        }

        data.rows = rows;

        if (!window.createReportRequest) {
          window.createReportRequest = $.ajax({
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
              $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
              $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
              throw new Error(_error2.message);
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
      var work = $(event.target).parents('.card').find('.card.work:last-of-type').clone();
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
      $(work).find('input.work-number').on('keydown keyup', function (evt) {
        checkWorkExists(evt);
      }); // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

      $(event.target).parents('.card').find('.card.work:last-of-type').after(work);
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
      window.location.replace($(event.target).parent('a#cancel-report').attr('href'));
    }
  });
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

  if ($("#reports").length > 0) {
    $("#reports").DataTable({
      responsive: true,
      order: [[1, "desc"]],
      // ordering: false,
      columnDefs: [{
        targets: $("#reports").find("thead tr:first th.actions").index(),
        orderable: false
      }],
      lengthChange: true,
      language: {
        url: "/config/dataTables/lang/" + window.lang + ".json"
      }
    });
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
