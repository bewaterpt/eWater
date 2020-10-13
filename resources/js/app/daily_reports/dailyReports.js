$(() => {

    let error = false;
    let kmError = false;
    let today = new Date();

    if($('#daily-reports-create').length > 0) {
        if ($('#inputDatetime').val() === '') {
            $('#inputDatetime').val(ISODateString(today)).attr('max', ISODateString(today));
        }
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

        setInterval(() => {
            let userInsertedKm = 0;
            let totalKm = $('input[name="km-arrival"]').val() - $('input[name="km-departure"]').val();
            $(document).find('.card.work .card-header input[name=driven_km]').each((inputIndex, input) => {
                userInsertedKm += parseInt(input.value);
            });

            if(userInsertedKm - totalKm < 0) {
                $("#warnings #superiorKmErr").addClass('d-none');
                $("#warnings #inferiorKmWarn").removeClass('d-none');
            } else if(userInsertedKm - totalKm > 0) {
                $("#warnings #inferiorKmWarn").addClass('d-none');
                $("#warnings #superiorKmErr").removeClass('d-none');
            } else {
                $("#warnings #superiorKmErr").addClass('d-none');
                $("#warnings #inferiorKmWarn").addClass('d-none');
            }
        }, 1000);

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
            // if (tr.find('#inputDatetime').val() === '') {
            //     tr.find('#inputDatetime').val(ISODateString(today));
            // }

            tr.find('#info').tooltip({
                html: true,
                title: function() {
                    return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html()
                },
            });

            console.log(tr[0]);
            $(event.target).parents('.card.work').find('table#report-lines tbody').append(tr);
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });
        }

        function formatAndSendReportData(editing = false) {
            if ((window.verifyingWork && window.verifyingWork.readyState === 4) || editing) {
                let data = {
                    plate: $('input[name="plate"]').val(),
                    km_departure: $('input[name="km-departure"]').val(),
                    km_arrival: $('input[name="km-arrival"]').val(),
                    comment: $('textarea').val(),
                    datetime: $('#inputDatetime').val(),
                    team: $('#inputTeam').children('option:selected').val(),
                }

                let workNumbers = $('div.card.work input.work-number').map((_, work) => work.value).get();

                if(new Date(data.datetime) > today) {
                    throw new Error($('#errors #invalidDate').text());
                }

                if($('[data-error=true]').length > 0 || workNumbers.indexOf('0') > -1) {
                    throw new Error($('#errors #invalidWorkNumber').text());
                }

                if(editing) {
                    data.id = $('#reportId').text();
                }

                let totalKm = data.km_arrival - data.km_departure;
                let userInsertedKm = 0;
                let rows = {};

                $('div.card.work').each((workIndex, work) => {
                    console.log('Work: ', work);
                    let workNum = $(work).find('input.work-number').val();
                    if (workNum === 0) {
                        throw new Error($('#errors #unexpectedError'));
                    }
                    rows[workNum] = {};
                    $(work).find('tbody tr').each((trIndex, tr) => {
                        rows[workNum][trIndex] = {};
                        rows[workNum][trIndex]['driven_km'] = $(work).find('input.driven-km').val()
                        $(tr).find('input:not(.work-number), select').each((inputIndex, input) => {
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

                $(document).find('.card.work .card-header input[name=driven_km]').each((inputIndex, input) => {
                    userInsertedKm += parseInt(input.value);
                });

                console.log('Total: ', totalKm)
                console.log('Inserted: ', userInsertedKm)
                console.log(Math.abs(userInsertedKm - totalKm));

                if(userInsertedKm - totalKm < 0 && kmError == false) {
                    kmError = true;
                    throw new Error($('#errors #inferiorKm').text());
                } else if(userInsertedKm - totalKm > 0) {
                    throw new Error($('#errors #superiorKm').text());
                } else if (userInsertedKm - totalKm < 0 && $('#inputComment').val().length < 15) {
                    throw new Error($('#errors #inferiorKmWarn').text());
                }

                data.rows = rows;

                if(!window.createReportRequest) {
                    window.createReportRequest = $.ajax({
                        method: 'POST',
                        url: $('#report').attr('action'),
                        data: JSON.stringify(data),
                        contentType: 'json',
                        success: (response) => {
                            $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                            $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                            window.location.replace(response);
                        },
                        error: (jqXHR, status, error) => {
                            $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                            $('#report button[type="submit"]').find('.btn-text').removeClass('d-none');
                            throw new Error(error.message);
                        },
                        complete: () => {
                            $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                            $('button[type="submit"]').find('.btn-text').removeClass('d-none');
                        },
                    });
                }
            } else {
                throw new Error($('#errors #waitForWorkCheck').text());
            }
        }

        function checkWorkExists(evt) {
            error = false;
            let data = {
                id: $(evt.target).val()
            }

            if (data.id !== "") {
                if(window.verifyingWork && window.verifyingWork.readyState !== 4) {
                    window.verifyingWork.abort();
                }

                window.verifyingWork = $.ajax({
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
                                    return $(document).find('#' + this.id + ' .popover').find('#title').html()
                                },
                                content: function() {
                                    return $(document).find('#' + this.id + ' .popover').find('#content').html()
                                },
                            });
                            $(evt.target).parent().find('.popover #content').html($('#errors .' + response.reason).html());
                            $(evt.target).addClass('border-danger').addClass('bg-flamingo').attr('data-error', true).focus();
                            $('.popover:not(.popover-data)').addClass('popover-danger');
                        } else {
                            $(evt.target).removeClass('border-danger').removeClass('bg-flamingo').removeAttr('data-error');
                            $(evt.target).parent().popover('dispose');
                        }
                    },
                    error: (jqXHR, status, error) => {

                    },
                });
            } else {
                $(evt.target).removeClass('border-danger').removeClass('bg-flamingo').removeAttr('data-error');
                $(evt.target).parent().popover('dispose');
            }
        }

        $('#addRow').on('click', (event) => {
            addRow(event);
        });

        $('a.remove-work').on('click', (event) => {
            removeWork(event);
        });

        $('a#removeRow').on('click', (event) => {
            removeRow(event);
        })

        $('a.add-work').on('click', (event) => {
            console.log('Target: ', event.target);
            let work = $(event.target).parents('.card').find('.work').last().clone();
            work.removeAttr('id');

            let trs = work.find('table#report-lines tbody tr');
            trs.each((index, tr) => {
                if (trs.length - (index + 1) == 0) {
                    return false
                }
                $(tr).remove();
            });

            work.find('#addRow').on('click', (event) => {
                addRow(event);
            });
            console.log('Work: ', work);
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
            // if (tr.find('#inputDatetime').val() === '') {
            //     tr.find('#inputDatetime').val(ISODateString(today));
            // }

            tr.find('#info').tooltip({
                html: true,
                title: function() {
                    return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html()
                },
            });

            $(work).find('input.work-number').on('keydown keyup', (evt) => {
                checkWorkExists(evt);
            });
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

            $(event.target).parents('.card').find('.work').last().after(work);

            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });
        });

        $(".info-tooltip").tooltip({
            html: true,
            title: function() {
                return $(document).find('#' + this.id + '-tooltip .tooltip').find('#title').html()
            },
        });

        $('div.card.work input.work-number').on('keyup', (evt) => {
            checkWorkExists(evt);
        });



        $('#report').on('submit', (event) => {
            event.preventDefault();
            event.stopPropagation();

            $('button[type="submit"]').find('#spinner, #spinner-text').removeClass('d-none');
            $('button[type="submit"]').find('.btn-text').addClass('d-none');

            if(!error) {
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
                    return
                }
            } else {
                $('button[type="submit"]').find('#spinner, #spinner-text').addClass('d-none');
                $('button[type="submit"]').find('.btn-text').removeClass('d-none');
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

        $(document).on('change keyup', 'input[name="quantity"]', (event) => {
            console.log('Fired');

            var totalHours = 0;

            $('input[name="quantity"]').each(function (index, field) {
                totalHours += parseFloat($(field).val()) || 0;
            });

            if (totalHours > 0) {
                $('#total-hour-holder').find('#value').text(decimalToTimeValue(parseFloat(totalHours).toFixed(2)));
            }

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

    $("#cancel-report").on("click", (event) => {
        event.preventDefault();
        if(confirm($("#prompts .cancel-report").text())) {
            window.location.replace($(event.target).parent('a#cancel-report').attr('href'));
        }
    });
});
