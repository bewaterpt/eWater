$(() => {
    if ($('#calls-pbx-create').length > 0) {
        $('#calls-pbx-create .show-password').on('mousedown mouseup', (evt) => {
            if ($(evt.target).parent('a').siblings('input').attr('type') === 'password') {
                $(evt.target).parent('a').siblings('input').attr('type', 'text');
            } else {
                $(evt.target).parent('a').siblings('input').attr('type', 'password');
            }
        });
    }

    if($("#calls-dashboard").length > 0) {
        let monthlyWaitTimeInfoCtx = $("#monthlyWaitTimeInfo")[0].getContext('2d');

        queryData = {
            inbound: true,
            dates: false,
        }

        window.monthlyWaitTimeInfoAjax = $.ajax({
            method: 'POST',
            url: '/calls/get_monthly_wait_time_info',
            contentType: 'json',
            data: JSON.stringify(queryData),
            success: (response) => {
                chartData = JSON.parse(response);
                window.monthlyWaitTimeInfoChart = new Chart(monthlyWaitTimeInfoCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            // {
                            //     label: $("#labels #minMonthlyWaitTime").text(),
                            //     data: chartData.min,
                            //     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            //     borderColor: 'rgba(255, 99, 132, 1)',
                            //     borderWidth: 1
                            // },
                            {
                                label: $("#labels #maxMonthlyWaitTime").text(),
                                data: chartData.max,
                                backgroundColor: 'rgba(43, 132, 99, 0.2)',
                                borderColor: 'rgba(43, 132, 99, 0.2)',
                                borderWidth: 1
                            },
                            {
                                label: $("#labels #averageMonthlyWaitTime").text(),
                                data: chartData.avg,
                                backgroundColor: 'rgba(43, 34, 200, 0.2)',
                                borderColor: 'rgba(43, 34, 200, 1)',
                                borderWidth: 1,
                                type: 'line',
                                fill: false,
                            },
                        ],
                    },
                    options: {
                        title: {
                            display: true,
                            text: $("#titles #minMaxExternalMonthlyWaitTime").text(),
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    userCallback: (item) => {
                                        return decimalSecondsToTimeValue(item);
                                    }
                                }
                            }]
                        }
                    }
                });
                // if (response.value === false) {
                //     $(evt.target).parent().popover({
                //         html: true,
                //         title: function() {
                //             return $(document).find('#' + this.id + ' .popover').find('#title').html()
                //         },
                //         content: function() {
                //             return $(document).find('#' + this.id + ' .popover').find('#content').html()
                //         },
                //     });
                //     $(evt.target).parent().find('.popover #content').html($('#errors .' + response.reason).html());
                //     $(evt.target).addClass('border-danger').addClass('bg-flamingo').attr('data-error', true).focus();
                //     $('.popover:not(.popover-data)').addClass('popover-danger');
                // } else {
                //     $(evt.target).removeClass('border-danger').removeClass('bg-flamingo').removeAttr('data-error');
                //     $(evt.target).parent().popover('dispose');
                // }
            },
            error: (jqXHR, status, error) => {

            },
        });
    }
})
