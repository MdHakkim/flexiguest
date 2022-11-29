'use strict';

(function () {
    let chart;
    let cardColor, headingColor, axisColor = '#826af9',
        borderColor, radialTrackColor;

    // Color constant
    const chartColors = {
        column: {
            series1: '#ff5b5c',
            series2: '#39da8a',
            bg: '#f8d3ff'
        }
    };

    var series = getChartValues();
    var categories = getDateCategories('dates');
    var x_colors = getDateCategories('colors');

    var options = {
        series,
        chart: {
            fontFamily: 'IBM Plex Sans',
            type: "bar",
            height: 470,
            stacked: true,
            stackType: 'normal',
            parentHeightOffset: 0,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: false
            },
            events: {
                mounted: (chartContext, config) => {

                    setTimeout(() => {
                        addAnnotations(config);
                    });
                },
                updated: (chartContext, config) => {
                    setTimeout(() => {
                        addAnnotations(config);
                    });
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        dataLabels: {
            enabled: true
        },
        plotOptions: {
            bar: {
                columnWidth: '60%',
                horizontal: false,
                dataLabels: {
                    maxItems: 2
                }
            }
        },
        xaxis: {
            categories,
            axisTicks: {
                show: true
            },
            axisBorder: {
                show: true
            },
            // floating: true,
            labels: {
                style: {
                    colors: x_colors,
                    fontSize: '13px'
                },
                // maxHeight: 0,
                hideOverlappingLabels: false
            },
        },
        yaxis: {
            axisTicks: {
                show: true
            },
            axisBorder: {
                show: true
            },
            labels: {
                style: {
                    colors: '#677788',
                    fontSize: '13px'
                },
                hideOverlappingLabels: true,
            },
            max: parseInt($('#S_GRAPH_MAX option:eq(1)').val()),
            title: {
                text: 'No of Rooms',
                offsetX: -10,
                offsetY: 0,
                style: {
                    color: '#677788',
                    fontSize: '14px',
                    fontWeight: 'bold',
                }
            },
        },
        fill: {
            opacity: 1
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'start',
            labels: {
                colors: '#677788',
                useSeriesColors: false
            },
            fontSize: '13px',
            fontWeight: 'bold',
        },
        colors: [chartColors.column.series1, chartColors.column.series2],
        grid: {
            padding: {
                left: 13.5,
                right: 0
            },
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: $('#S_GRID_LINES').is(':checked')
                }
            }
        }
    };

    var addAnnotations = (config) => {
        var seriesTotals = config.globals.stackedSeriesTotals;

        chart.clearAnnotations();

        try {
            categories.forEach((category, index) => {
                seriesTotals[index] == undefined ? chart.clearAnnotations() : chart.addPointAnnotation({
                        y: seriesTotals[index],
                        x: category,
                        label: {
                            text: `${seriesTotals[index]}`
                        }
                    },
                    false
                );
            });
        } catch (error) {
            console.log(`Add point annotation error: ${error.message}`);
        }
    };

    chart = new ApexCharts(document.querySelector("#barChart"), options);
    chart.render();


    // Load Graph on click Submit or Reset

    const submitButton = document.getElementById("submitAdvSearch");
    const resetButton = document.getElementById("clearAdvSearch");

    async function submitFunction() {

        blockLoader('#barChart');

        chart.destroy();

        series = getChartValues();
        categories = getDateCategories('dates');
        x_colors = getDateCategories('colors');

        options.series = series;
        options.xaxis.categories = categories;
        options.xaxis.labels.style.colors = x_colors;
        options.yaxis[0].max = parseInt($('#S_GRAPH_MAX').val());
        options.grid.yaxis.lines.show = $('#S_GRID_LINES').is(':checked');
        options.chart.stackType = $('#S_PERCENT').is(':checked') ? '100%' : 'normal';

        options.xaxis.labels.rotateAlways = $('#S_NO_OF').val() == '16' ? true : false;
        options.xaxis.labels.rotate = $('#S_NO_OF').val() == '16' ? -45 : 0;

        chart = new ApexCharts(document.querySelector("#barChart"), options);
        chart.render();
    }

    submitButton.addEventListener("click", async () => {

        await submitFunction();
        blockLoader('#barChart');

    });


    async function resetFunction() {

        clearFormFields('.dt_adv_search');

        blockLoader('.dt_adv_search');
        blockLoader('#barChart');

        $("#S_START_DATE").datepicker("setDate", new Date());
        $('#S_BAR_DISPLAY,#S_TIME_PERIOD,#S_NO_OF,#S_GRAPH_MAX').each(function () {
            $(this).val($("#" + $(this).attr('id') + " option:" + ($(this).attr('id') == 'S_GRAPH_MAX' ? "eq(1)" : "first")).val());
            $(this).selectpicker('refresh');
        });
        $('#S_GRID_LINES').prop('checked', true);

        chart.destroy();

        series = getChartValues();
        categories = getDateCategories('dates');
        x_colors = getDateCategories('colors');

        options.series = series;
        options.xaxis.categories = categories;
        options.xaxis.labels.style.colors = x_colors;
        options.yaxis[0].max = parseInt($('#S_GRAPH_MAX').val());
        options.grid.yaxis.lines.show = $('#S_GRID_LINES').is(':checked');
        options.chart.stackType = 'normal';

        options.xaxis.labels.rotateAlways = false;
        options.xaxis.labels.rotate = 0;

        chart = new ApexCharts(document.querySelector("#barChart"), options);
        chart.render();

    }
    resetButton.addEventListener("click", async () => { // Click Clear button

        await resetFunction();
        blockLoader('.dt_adv_search');
        blockLoader('#barChart');

    });

})();

function getSearchValues() {
    var time_period = $('#S_TIME_PERIOD').val();
    var no_of = $('#S_NO_OF').val();
    var startDate = $('#S_START_DATE').val() ? $('#S_START_DATE').val() : new Date().toJSON().slice(0, 10);

    var freq = no_of < 24 ? 1 : (no_of < 90 ? 6 : 15);

    return {
        'time_period': time_period,
        'no_of': no_of,
        'startDate': startDate,
        'freq': freq
    };
}

function getDateCategories(type = 'dates') {

    var searchVals = getSearchValues();
    var time_period = searchVals['time_period'];

    var no_of = searchVals['no_of'] * time_period;
    var startDate = searchVals['startDate'];
    var freq = searchVals['freq'] * time_period;

    var arr = [];

    var i = 0;
    while (i < no_of) {
        arr.push(getFutureDate(startDate, i, type));
        i = i + freq;
    }

    if (i >= no_of && freq > 1) // Show last date 
        arr.push(getFutureDate(startDate, (no_of - 1), type));

    return arr;
}

function getFutureDate(start, ahead, type) {
    var d = new Date(start);
    d.setDate(d.getDate() + ahead);

    var dayOfWeek = d.getDay();

    var dateStr = type == 'dates' ? d.toLocaleString('default', { // Set Format - 01 Jan '23
        dateStyle: 'medium'
    }) : (type == 'colors' ? (dayOfWeek == 0 || dayOfWeek == 6 ? '#5a8dee' : '#677788') : ''); // Set Weekend Color

    return dateStr;
}

function getRand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}


function getOccupiedCount(date, ahead) {
    var occCounts = [];
    var d = new Date(date);
    d.setDate(d.getDate() + ahead);

    var dateStr = d.toLocaleString('default', {
        year: 'numeric'
    }) + '-' + d.toLocaleString('default', {
        month: '2-digit'
    }) + '-' + d.toLocaleString('default', {
        day: '2-digit'
    });

    $.ajax({
        url: mainUrl + '/HkRoomStatistics',
        async: false,
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            search_date: dateStr,
            for_graph: '1'
        },
        dataType: 'json',
    }).done(function (respn) {
        var rmCountData = respn[0];
        occCounts['Deduct'] = parseInt(rmCountData[0]['RTotRoomsDeduct']);
        occCounts['NonDeduct'] = parseInt(rmCountData[0]['RTotRoomsNonDeduct']);
        //alert('here_2' + occCount);        
    });

    return occCounts;
}

function getChartValues() {

    var searchVals = getSearchValues();
    var time_period = searchVals['time_period'];
    var no_of = searchVals['no_of'] * time_period;
    var startDate = searchVals['startDate'];
    var freq = searchVals['freq'] * time_period;

    var ded = [],
        nded = [];

    var i = 0;
    while (i < no_of) {
        var counts = getOccupiedCount(startDate, i);
        ded.push(counts['Deduct']);
        nded.push(counts['NonDeduct']);
        i = i + freq;
        //console.log('Ded Rooms at ' + startDate + ' + ' + i + ' days', getOccupiedCount(startDate, i, '1'));
    }

    if (i >= no_of && freq > 1) { // Show last date data
        counts = getOccupiedCount(startDate, i);
        ded.push(counts['Deduct']);
        nded.push(counts['NonDeduct']);
    }

    return [{
            name: 'Deduct',
            data: ded
        },
        {
            name: 'Non Deduct',
            data: nded
        }
    ];
}