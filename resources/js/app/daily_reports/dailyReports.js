const { readyException } = require("jquery");
const { reject } = require("lodash");

$(document).ready(() => {

    let error = false;
    let today = new Date();

    function ISODateString(d){
        function pad(n){return n<10 ? '0'+n : n}
        return d.getUTCFullYear()+'-'
        + pad(d.getUTCMonth()+1)+'-'
        + pad(d.getUTCDate())
        // + 'T'+pad(d.getUTCHours())+':'
        // + pad(d.getUTCMinutes());
        // + pad(d.getUTCSeconds())+'Z'
    }

    if($('#daily-reports-create').length > 0 || $('#daily-reports-edit').length > 0) {
        /**
         * Gets the selected article info
         *
         * @param {Event} event - the inherited event that called the function
         */
        function getArticleInfo(event) {
            $.ajax({
                type: 'POST',
                url: '/daily-reports/article/get-info',
                data: { id: $(event.target).val() },
                dataType: 'json',
                success: (data) => {
                    console.log('Data: ', data);
                    if(data.article.fixo == 1) {
                        $(event.target).closest('tr').find('#inputUnitPrice').val(parseFloat(data.article.precoUnitario).toFixed(2)).prop('readonly', true);
                    } else {
                        $(event.target).closest('tr').find('#inputUnitPrice').val(0).prop('read-only', true);
                    }
                }
            });
        }

        /**
         * Removes desired table row
         *
         * @param {Event} event - the inherited event that called the function
         */
        function removeRow(event) {
            $(event.target).closest('tr').remove();
        }

        function removeWork(event) {
            $(event.target).closest('.card.work:not(#original-work)').remove();
        }

        function addRow (event) {
            let tr = $(event.target).parents('.card.work').find('table#report-lines tbody tr:last-child').clone();
            console.log(tr);
            tr.find('input').val('').prop('readonly', false);
            tr.find(':not(td:first-child) input[type="number"]').val(0);
            tr.removeClass('first');
            tr.find('#removeRow').on('click', (e) => {
                removeRow(e);
            });
            if (tr.find('#inputDatetime').val() === '') {
                tr.find('#inputDatetime').val(ISODateString(today));
            }
            console.log(tr[0]);
            $(event.target).parents('.card.work').find('table#report-lines tbody').append(tr);
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });
        }

        function formatAndSendReportData() {
            let data = {
                plate: $('input[name="plate"]').val(),
                km_departure: $('input[name="km-departure"]').val(),
                km_arrival: $('input[name="km-arrival"]').val(),
                comment: $('textarea').val(),
                datetime: $('#inputDatetime').val(),
                team: $('#inputTeam').children('option:selected').val(),
            }

            let totalKm = data.km_arrival - data.km_departure;
            let userInsertedKm = 0;
            let rows = {};

            $('div.card.work').each((workIndex, work) => {
                console.log('Work: ', work);
                let workNum = $(work).find('input.work-number').val();
                let kmInserted = false;
                rows[workNum] = {};
                $(work).find('tbody tr').each((trIndex, tr) => {
                    rows[workNum][trIndex] = {};
                    rows[workNum][trIndex]['driven-km'] = $(work).find('input.driven-km').val()
                    $(document).find('.card.work input:not(.work-number), select').each((inputIndex, input) => {
                        if (input.name !== 'driven-km') {
                            rows[workNum][trIndex][input.name] = input.value;
                        }
                    });
                });
            });

            $(document).find('.card.work .card-header input[name=driven-km]').each((inputIndex, input) => {
                userInsertedKm += parseInt(input.value);
            });

            console.log('Total: ', totalKm)
            console.log('Inserted: ', userInsertedKm)
            console.log(Math.abs(userInsertedKm - totalKm));

            if(Math.abs(userInsertedKm - totalKm) !== 0) {
                throw new Error($('#errors #differentKm')[0].innerText);
            }

            data.rows = rows;

            $.ajax({
                method: 'POST',
                url: $('#report').attr('action'),
                data: JSON.stringify(data),
                contentType: 'json',
                success: (response) => {
                    $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                    $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                    window.location.replace(response);
                },
                error: (jqXHR, status, error) => {
                    $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                    $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                    throw new Error(error.message);
                },
                complete: () => {
                    $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                    $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                },
            });
        }

        $('#addRow').on('click', (event) => {
            addRow(event);
        });

        $('a.remove-work').on('click', (event) => {
            removeWork(event);
        });

        $('a.add-work').on('click', (event) => {
            let work = $(event.target).parents('.card').find('.card.work:last-of-type').clone();
            console.log(work);
            work.removeAttr('id');

            let trs = work.find('table#report-lines tbody tr');
            trs.each((index, tr) => {
                console.log(index);
                if (trs.length - (index + 1) == 0) {
                    return false
                }
                $(tr).remove();
            });

            work.find('#addRow').on('click', (event) => {
                addRow(event);
            });

            work.find('a.remove-work').on('click', (event) => {
                removeWork(event);
            });

            let tr = work.find('table#report-lines tbody tr:last-child');
            tr.find('input').val('').prop('readonly', false);
            tr.find(':not(td:first-child) input[type="number"]').val(0);
            tr.removeClass('first');
            tr.find('#removeRow').on('click', (event) => {
                removeLine(event);
            });
            if (tr.find('#inputDatetime').val() === '') {
                tr.find('#inputDatetime').val(ISODateString(today));
            }
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

            $(event.target).parents('.card').find('.card.work:last-of-type').after(work);

            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });
        });
        if ($('#inputDatetime').val() === '') {
            $('#inputDatetime').val(ISODateString(today));
        }

        $('div.card.work input.work-number').on('focusout', (evt) => {
            error = false;
            let data = {
                id: $(evt.target).val()
            }

            $.ajax({
                method: 'POST',
                url: '/works/work-exists',
                data: JSON.stringify(data),
                contentType: 'json',
                success: (response) => {
                    response = JSON.parse(response);
                    console.log("Response: ", response);
                    if (response.value === false) {
                        $(evt.target).parent().popover({
                            html: true,
                            title: function() {
                                console.log(this);
                                return $(document).find('#' + this.id + ' .popover').find('#title').html()
                            },
                            content: function() {
                                return $(document).find('#' + this.id + ' .popover').find('#content').html()
                            },
                        });
                        $(evt.target).parent().find('.popover #content').html($('#errors .' + response.reason).html());
                        $(evt.target).addClass('border-danger').addClass('bg-flamingo').focus();
                        $('.popover:not(.popover-data)').addClass('popover-danger');
                    } else {
                        $(evt.target).removeClass('border-danger').removeClass('bg-flamingo');
                        $(evt.target).parent().popover('dispose');
                    }
                },
                error: (jqXHR, status, error) => {
                    $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                    $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                },
            });
        });

        $('#report').on('submit', (event) => {
            event.preventDefault();

            $('#report button[type="submit"]').find('#spinner, #spinner-text').removeClass('d-none');
            $('#report button[type="submit"]').find('.btn-text').addClass('d-none');
            if(!error) {
                try {
                    formatAndSendReportData();
                } catch (error) {
                    $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                    $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                    alert(error.message);
                    return
                }
            } else {
                $('#report button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
            }
        });

        $(document).on('change keyup', 'input[name="km-departure"], input[name="km-arrival"]', (event) => {
            $('#total-km-holder #value').text(parseInt(($('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val())));

            if (parseInt(($('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val())) < 0) {
                $('#total-km-holder').addClass('text-danger');
            } else {
                $('#total-km-holder').removeClass('text-danger');
            }
        });
    }

    if($('#daily-reports-view').length > 0) {

        $('#modalComment').on('show.bs.modal', (event) => {

            var dataId = '';

            if (typeof $(event.relatedTarget).data('id') !== 'undefined') {
                dataId = $(event.relatedTarget).data('id');
            }

            $(event.target).find('#content .body').html("");
            $(event.target).find('#modal-spinner').removeClass('d-none');

            $currAjax = $.ajax({
                method: 'POST',
                url: '/daily-reports/process-status/get-comment',
                data: JSON.stringify({ id: dataId }),
                contentType: 'json',
                success: (response) => {
                    response = JSON.parse(response);
                    $(event.target).find('#content .body').html(response.content);
                    $(event.target).find('#modal-spinner').addClass('d-none');
                },
                error: (jqXHR, status, error) => {
                    $(event.target).find('#modal-spinner').addClass('d-none');
                    alert(error);
                },
                complete: () => {
                    $(event.target).find('#modal-spinner').addClass('d-none');
                },
            });
        });
    }
});
