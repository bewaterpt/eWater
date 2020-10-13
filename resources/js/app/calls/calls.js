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
        let monthlyCallNumberInfoCtx = $("#monthlyCallNumberInfo")[0].getContext('2d');
        let monthlyLostCallNumberInfoCtx = $("#monthlyLostCallNumberInfo")[0].getContext('2d');
        queryData = {
            inbound: false,
            dates: false,
        }

        window.monthlyWaitTimeInfoAjax = $.ajax({
            method: 'POST',
            url: '/calls/get_monthly_wait_time_info',
            contentType: 'json',
            data: JSON.stringify(queryData),
            success: (response) => {
                chartData = JSON.parse(response);

                console.log(chartData.test_values);
                window.monthlyWaitTimeInfoChart = new Chart(monthlyWaitTimeInfoCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
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
                            {
                                label: $("#labels #averageMonthlyWaitTime").text(),
                                data: chartData.wavg,
                                backgroundColor: 'rgba(200, 34, 43, 0.2)',
                                borderColor: 'rgba(200, 34, 43, 1)',
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
            },
            error: (jqXHR, status, error) => {

            },
        });

        window.monthlyCallNumberAjax = $.ajax({
            method: 'POST',
            url: '/calls/get_monthly_call_number_info',
            contentType: 'json',
            data: JSON.stringify(queryData),
            success: (response) => {
                console.log(response);
                chartData = JSON.parse(response);

                console.log(chartData.test_values);
                window.monthlyCallNumberInfoChart = new Chart(monthlyCallNumberInfoCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: $("#labels #monthlyTotalCalls").text(),
                                data: chartData.total,
                                backgroundColor: 'rgba(43, 132, 99, 0.2)',
                                borderColor: 'rgba(43, 132, 99, 0.2)',
                                borderWidth: 1
                            },
                            {
                                label: $("#labels #monthlyFrontOfficeCalls").text(),
                                data: chartData.frontOffice,
                                backgroundColor: 'rgba(43, 34, 200, 0.2)',
                                borderColor: 'rgba(43, 34, 200, 1)',
                                borderWidth: 1,
                                type: 'line',
                                fill: false,
                            },
                            {
                                label: $("#labels #monthlyGenericCalls").text(),
                                data: chartData.wavg,
                                backgroundColor: 'rgba(200, 34, 43, 0.2)',
                                borderColor: 'rgba(200, 34, 43, 1)',
                                borderWidth: 1,
                                type: 'line',
                                fill: false,
                            },
                            {
                                label: $("#labels #monthlyInternalCalls").text(),
                                data: chartData.wavg,
                                backgroundColor: 'rgba(200, 34, 140, 0.2)',
                                borderColor: 'rgba(200, 34, 140, 1)',
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

                window.monthlyLostCallNumberInfoChart = new Chart(monthlyLostCallNumberInfoCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: $("#labels #monthlyTotalLostCalls").text(),
                                data: chartData.totalLost,
                                backgroundColor: 'rgba(43, 132, 99, 0.2)',
                                borderColor: 'rgba(43, 132, 99, 0.2)',
                                borderWidth: 1
                            },
                            {
                                label: $("#labels #monthlyFrontOfficeLostCalls").text(),
                                data: chartData.frontOfficeLost,
                                backgroundColor: 'rgba(43, 34, 200, 0.2)',
                                borderColor: 'rgba(43, 34, 200, 1)',
                                borderWidth: 1,
                                type: 'line',
                                fill: false,
                            },
                            {
                                label: $("#labels #monthlyGenericLostCalls").text(),
                                data: chartData.genericLost,
                                backgroundColor: 'rgba(200, 34, 43, 0.2)',
                                borderColor: 'rgba(200, 34, 43, 1)',
                                borderWidth: 1,
                                type: 'line',
                                fill: false,
                            },
                            {
                                label: $("#labels #monthlyInternalLostCalls").text(),
                                data: chartData.internalLost,
                                backgroundColor: 'rgba(200, 34, 140, 0.2)',
                                borderColor: 'rgba(200, 34, 140, 1)',
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
            },
            error: (jqXHR, status, error) => {

            },
        });

        $('#export a').on('click', function() {
            $.ajax({
                method: 'GET',
                url: $(this).attr('href'),
                contentType: 'json',
                success: function(result, status, xhr) {

                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : xhr.getResponseHeader('X-ewater-filename'));

                    // The actual download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                    $('#modalExport').modal('hide');
                }
            });
        });
    }
});
