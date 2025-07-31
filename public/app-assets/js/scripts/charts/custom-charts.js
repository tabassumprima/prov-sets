var geoJsonMap;
var jsonData;
function reRender(data) {
    var currency = $('#jsonData').data('currency');
    var json = JSON.parse(data);
    jsonData = JSON.parse(data);
    if (json.length == 0) {
        var json = { stats: { totalPremium: 0, netClaims: 0, expenses: 0, netResult: 0 }, lossRatioChart: { data: 0, premium: 0, claims: 0 }, segmentBreakupChart: { labels: [], data: [] }, businessSnapshotChart: { labels: { labels: [] }, premium: { data: [] }, lossRatio: { data: [] }, claims: { data: [] } }, googleMap: {} };
    } else {
        var json = json;
    }

    // Set all values to 0 if json is empty
    if (json.length == 0) {
        json.stats.totalPremium = 0;
        json.stats.netClaims = 0;
        json.stats.expenses = 0;
        json.stats.netResult = 0;
        json.lossRatioChart.data = 0;
        json.lossRatioChart.premium = 0;
        json.lossRatioChart.claims = 0;
        json.segmentBreakupChart.labels = [];
        json.segmentBreakupChart.data = [];
        json.businessSnapshotChart.labels.labels = [];
        json.businessSnapshotChart.premium.data = [];
        json.businessSnapshotChart.lossRatio.data = [];
        json.businessSnapshotChart.claims.data = [];
        json.googleMap = {};
    }
    $('.total-premium').html(formatNumber(json.stats.totalPremium, null));
    $('.total-claims').html(formatNumber(json.stats.netClaims, null));
    $('.expenses').html(formatNumber(json.stats.expenses, null));
    $('.net-result').html(formatNumber(json.stats.netResult, null));
    $('.premium').html(formatNumber(json.lossRatioChart.premium, null));
    $('.claims').html(formatNumber(json.lossRatioChart.claims, null));

    // Remove shimmer
    toggleShimmer(false, 'card-stats');

    // Stats Cards End
    var flatPicker = $('.flat-picker'),
        // chart colors
        chartColors = {
            column: {
                series1: '#826af9',
                series2: '#d2b0ff',
                bg: '#f8d3ff'
            },
            success: {
                shade_100: '#7eefc7',
                shade_200: '#06774f'
            },
            donut: {
                series1: '#0054ff',
                series2: '#1965ff',
                series3: '#3276ff',
                series4: '#4c87ff',
                series5: '#6698ff',
                series6: '#7fa9ff',
            },
            area: {
                series3: '#a4f8cd',
                series2: '#60f2ca',
                series1: '#2bdac7'
            }
        };

    // loss-ratio-chart
    var $textHeadingColor = '#5e5873';
    var $white = '#fff';

    var $lossRatioChart = document.querySelector('#loss-ratio-chart');

    var lossRatioChartOptions;

    var lossRatioChart;

    lossRatioChartOptions = {
        chart: {
            height: 270,
            type: 'radialBar',
            filter: true
        },
        plotOptions: {
            radialBar: {
                size: 150,
                offsetY: 20,
                startAngle: -150,
                endAngle: 150,
                hollow: {
                    size: '65%'
                },
                track: {
                    background: $white,
                    strokeWidth: '100%'
                },
                dataLabels: {
                    name: {
                        offsetY: -5,
                        color: $textHeadingColor,
                        fontSize: '1rem'
                    },
                    value: {
                        offsetY: 15,
                        color: $textHeadingColor,
                        fontSize: '1.714rem',
                        formatter: function (val) {
                            return formatNumber(val, '%');
                        }
                    }
                }
            }
        },
        colors: [window.colors.solid.danger],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: [window.colors.solid.primary],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            dashArray: 8
        },
        series: [json.lossRatioChart.data],
        labels: ['Loss ratio']
    };
    lossRatioChart = new ApexCharts($lossRatioChart, lossRatioChartOptions);
    lossRatioChart.render();
    
    //Remove shimmer 
    toggleShimmer(false, 'loss-ratio-chart');

    // Sort the data in descending order
    var sortData = json.segmentBreakupChart.data.sort(function (a, b) {
        return b - a;
    });
    //segment-breakup-chart
    var segmentBreakupChart = document.querySelector('#segment-breakup-chart');
    var segmentBreakupChartConfig = {
        series: [{
            data: sortData,
        }],
        chart: {
        height: 350,
        type: 'donut'
        },
        legend: {
        show: false
        },
        labels: json.segmentBreakupChart.labels,
        series: sortData,
        colors: [
            chartColors.donut.series1,
            chartColors.donut.series2,
            chartColors.donut.series3,
            chartColors.donut.series4,
            chartColors.donut.series5,
            chartColors.donut.series6
        ],
        dataLabels: {
            enabled: true,
            formatter: function (val, opt) {
                return parseInt(val) + '%';
            }
        },
        plotOptions: {
        pie: {
            donut: {
            labels: {
                show: true,
                name: {
                fontSize: '2rem',
                fontFamily: 'Montserrat'
                },
                value: {
                fontSize: '1rem',
                fontFamily: 'Montserrat',
                formatter: function (val) {
                    var values = formatNumber(val, null);
                    return currency + " " + values;
                }
                },
                total: {
                show: true,
                fontSize: '1.5rem',
                label: 'Written Premium',
                formatter: function (w) {
                    var values = formatNumber(json.stats.totalPremium, null);
                    return currency + " " + values;
                }
                }
            }
            }
        }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    var value = formatNumber(val, null);
                    return currency + ' ' + value;
                }
            }
        },
        responsive: [
        {
            breakpoint: 992,
            options: {
            chart: {
                height: 380
            },
            legend: {
                position: 'bottom'
            }
            }
        },
        {
            breakpoint: 576,
            options: {
            chart: {
                height: 320
            },
            plotOptions: {
                pie: {
                donut: {
                    labels: {
                    show: true,
                    name: {
                        fontSize: '1.5rem'
                    },
                    value: {
                        fontSize: '1rem'
                    },
                    total: {
                        fontSize: '1.5rem'
                    }
                    }
                }
                }
            },
            legend: {
                show: false
            }
            }
        },
        {
            breakpoint: 420,
            options: {
            legend: {
                show: false
            }
            }
        }
        ]
    };
    if (typeof segmentBreakupChart !== undefined && segmentBreakupChart !== null) {
        var donutChart = new ApexCharts(segmentBreakupChart, segmentBreakupChartConfig);
        donutChart.render();
    }

    if (typeof segmentBreakupChart !== undefined && segmentBreakupChart !== null) {
        var donutChart = new ApexCharts(segmentBreakupChart, segmentBreakupChartConfig);
        donutChart.render();
    }

    //Remove shimmer 
    toggleShimmer(false, 'segment-breakup-chart');

    var monthNumbers = json.businessSnapshotChart.labels.labels;
    var labelsData = monthNumbers.map(monthNumber => getMonthName(monthNumber));
    var businessSnapshotChartChart = document.querySelector('#business-snapshot-chart'),
        businessSnapshotChartChartConfig = {
            series: [{
                name: 'Premium',
                type: 'column',
                data: json.businessSnapshotChart.premium.data,
                color: '#01A9C0',
            },
            {
                name: 'Loss Ratio',
                type: 'line',
                data: json.businessSnapshotChart.lossRatio.data,
                color: '#AF4B4E'

            },
            {
                name: 'Claim',
                type: 'column',
                data: json.businessSnapshotChart.claims.data,
                color: '#0084C2'
                

            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: [5, 2, 5],
                colors: ['transparent']
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1],
                formatter: function (value, { seriesIndex, dataPointIndex, w }) {
                    // Check if the series is a column series
                    if (w.config.series[seriesIndex].type === 'line') {
                        // Format the value with a percentage sign
                        var values = formatNumber(value, '%')
                        return values;
                    }
                    // For other series types, return the value as is
                    var values = formatNumber(value, null)
                    return values;
                },
            },
            labels: labelsData,
            xaxis: {
                type: 'month'
            },
            yaxis: [
                {
                    title: {
                        text: '',
                    },
                    labels: {
                        formatter: (value) => {
                            var values = formatNumber(value, null, 'short')
                            return values;

                        },
                    },
                },
                {
                    opposite: true,
                    title: {
                        text: '',
                    },
                    labels: {
                        formatter: (value) => {
                            var values = formatNumber(value, '%')
                            return values
                        },
                    },

                }

            ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (value, { seriesIndex, dataPointIndex, w }) {
                        if (w.config.series[seriesIndex].type === 'line') {
                            var values = formatNumber(value, '%')
                            return values;
                        }
                        var values = formatNumber(value, null)
                        return currency + ' ' + values;
                    }
                }
            }
        };
    if (typeof businessSnapshotChartChart !== undefined && businessSnapshotChartChart !== null) {
        var snapshotChart = new ApexCharts(businessSnapshotChartChart, businessSnapshotChartChartConfig);
        snapshotChart.render();
    }


    // Geojson
    // --------------------------------------------------------------------
    var pakistanData = {
        type: "FeatureCollection",
        features: json.googleMap,
        bbox: [
            60.89943695068354,
            23.702915191650447,
            77.8430786132813,
            37.09701156616211
        ]
    };

    if ($('#geojson').length) {
        clearTable();
        if (geoJsonMap) {
            // If a map already exists, remove it before initializing a new one
            geoJsonMap.remove();
        }
        geoJsonMap = L.map('geojson').setView([30.3753, 69.3451], 5);
        // Get Color
        function getColor(d) {
            return d > 200
                ? '#470203'
                : d > 100
                    ? '#ab2224'
                    : d > 80
                        ? '#cc3537'
                        : d > 20
                            ? '#f24e50'
                              : d > 10
                              ? '#f24e50'
                              : d > 3
                                ? '#f24e50'
                            : '#aacc95';
        }
        // GeoJSON layer with style
        L.geoJson(pakistanData, {
            style: function (feature) {
                return {
                    fillColor: getColor(feature.properties.density),
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7,
                };
            },
            onEachFeature: function (feature, layer) {
                var density = feature.properties.density ? feature.properties.density.toFixed() + '%' : 'N/A';
                if (density != 'N/A') {
                    updateTable(feature.properties.NAME_2, feature.properties.NAME_1, density, feature.properties.density);
                }
                layer.on({
                    mouseover: function (e) {
                        // Add a popup on hover
                        var density = feature.properties.density ? feature.properties.density.toFixed() + '%' : 'N/A';

                        var popupContent = '<b>' + feature.properties.NAME_3 + '</b><br>Loss Ratio: ' + density;

                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseout: function (e) {
                        // Close the popup when not hovering
                        layer.closePopup();
                    }
                });
            }
        }).addTo(geoJsonMap);

        // Set Zoom
        geoJsonMap.setZoom(6);

        // Tile layer
        L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/?sensor=false&amp;language=en"">OpenStreetMap</a>',
            maxZoom: 15
        }).addTo(geoJsonMap);

        
        // Remove shimmer
        toggleShimmer(false, 'geojson');
    }

    // Destroy existing charts if they exist
    if (typeof lossRatioChart !== 'undefined' && lossRatioChart !== null) {
        lossRatioChart.destroy();
    }

    if (typeof donutChart !== 'undefined' && donutChart !== null) {
        donutChart.destroy();
    }

    if (typeof snapshotChart !== 'undefined' && snapshotChart !== null) {
        snapshotChart.destroy();
    }

    // Recreate and render charts with updated data
    lossRatioChart = new ApexCharts($lossRatioChart, lossRatioChartOptions);
    lossRatioChart.render();

    if (typeof segmentBreakupChart !== 'undefined' && segmentBreakupChart !== null) {
        donutChart = new ApexCharts(segmentBreakupChart, segmentBreakupChartConfig);
        donutChart.render();
    }

    if (typeof businessSnapshotChartChart !== 'undefined' && businessSnapshotChartChart !== null) {
        snapshotChart = new ApexCharts(businessSnapshotChartChart, businessSnapshotChartChartConfig);
        snapshotChart.render();
    }
};

// Render Business Snapshot
function renderBusinessSnapshot() {

    if (jsonData.length == 0) {
        var json = { businessSnapshotChart: { labels: { labels: [] }, premium: { data: [] }} };
    } else {
        var json = jsonData;
    }

    // Remove shimmer
    toggleShimmer(true, 'business-snapshot-chart');

    // Set all values to 0 if json is empty
    if (json.length == 0) {
        json.businessSnapshotChart.labels.labels = [];
        json.businessSnapshotChart.premium.data = [];
        json.businessSnapshotChart.lossRatio.data = [];
        json.businessSnapshotChart.claims.data = [];
    }

    setTimeout(() => {
        var businessSnapshotChartsTabsPremiumEl = document.querySelector('#businessSnapshotChartsTabsPremium');
        var businessSnapshotChartsTabsPremium;
        var businessSnapshotChartsTabsPremiumConfig;

        var businessSnapshotChartsTabsClaimsEl = document.querySelector('#businessSnapshotChartsTabsClaims');
        var businessSnapshotChartsTabsClaims;
        var businessSnapshotChartsTabsClaimsConfig;

        var businessSnapshotChartsTabsLossRatioEl = document.querySelector('#businessSnapshotChartsTabsLossRatio');
        var businessSnapshotChartsTabsLossRatio;
        var businessSnapshotChartsTabsLossRatioConfig;
        var jsonValue = {
            data: [
                {
                    id: 1,
                    chart_data: json.businessSnapshotChart.premium.data,
                    active_option: 2
                },
                {
                    id: 2,
                    chart_data: json.businessSnapshotChart.claims.data,
                    active_option: 5
                },
                {
                    id: 3,
                    chart_data: json.businessSnapshotChart.lossRatio.data,
                    active_option: 4
                }
            ]
        };
        // Earning Chart JSON data
        var businessSnapshotChartsChart = jsonValue;
        // Business Snapshot Tabs Premium
        // --------------------------------------------------------------------
        businessSnapshotChartsTabsPremiumConfig = businessSnapshotBarChart(
            businessSnapshotChartsChart['data'][0]['chart_data'],
            businessSnapshotChartsChart['data'][0]['active_option'], 1
        );
        businessSnapshotChartsTabsPremium = new ApexCharts(businessSnapshotChartsTabsPremiumEl, businessSnapshotChartsTabsPremiumConfig);
        businessSnapshotChartsTabsPremium.render();
        // Business Snapshot Tabs Claims
        // --------------------------------------------------------------------
        businessSnapshotChartsTabsClaimsConfig = businessSnapshotBarChart(
            businessSnapshotChartsChart['data'][1]['chart_data'],
            businessSnapshotChartsChart['data'][1]['active_option'], 2
        );
        businessSnapshotChartsTabsClaims = new ApexCharts(businessSnapshotChartsTabsClaimsEl, businessSnapshotChartsTabsClaimsConfig);
        businessSnapshotChartsTabsClaims.render();
        // Business Snapshot Tabs lossRatio
        // --------------------------------------------------------------------
        businessSnapshotChartsTabsLossRatioConfig = businessSnapshotBarChart(
            businessSnapshotChartsChart['data'][2]['chart_data'],
            businessSnapshotChartsChart['data'][2]['active_option'], 3
        );
        businessSnapshotChartsTabsLossRatio = new ApexCharts(businessSnapshotChartsTabsLossRatioEl, businessSnapshotChartsTabsLossRatioConfig);
        businessSnapshotChartsTabsLossRatio.render();

        // Destroy
        if (typeof businessSnapshotChartsTabsPremiumEl !== undefined && businessSnapshotChartsTabsPremiumEl !== null) {
            businessSnapshotChartsTabsPremium.destroy();
        }
        if (typeof businessSnapshotChartsTabsLossRatioEl !== undefined && businessSnapshotChartsTabsLossRatioEl !== null) {
            businessSnapshotChartsTabsLossRatio.destroy();
        }
        if (typeof businessSnapshotChartsTabsClaimsEl !== undefined && businessSnapshotChartsTabsClaimsEl !== null) {
            businessSnapshotChartsTabsClaims.destroy();
        }

        // Re-render
        if (typeof businessSnapshotChartsTabsPremiumEl !== undefined && businessSnapshotChartsTabsPremiumEl !== null) {
            businessSnapshotChartsTabsPremium = new ApexCharts(businessSnapshotChartsTabsPremiumEl, businessSnapshotChartsTabsPremiumConfig);
            businessSnapshotChartsTabsPremium.render();
        }
        if (typeof businessSnapshotChartsTabsLossRatioEl !== undefined && businessSnapshotChartsTabsLossRatioEl !== null) {
            businessSnapshotChartsTabsLossRatio = new ApexCharts(businessSnapshotChartsTabsLossRatioEl, businessSnapshotChartsTabsLossRatioConfig);
            businessSnapshotChartsTabsLossRatio.render();
        }
        if (typeof businessSnapshotChartsTabsClaimsEl !== undefined && businessSnapshotChartsTabsClaimsEl !== null) {
            businessSnapshotChartsTabsClaims = new ApexCharts(businessSnapshotChartsTabsClaimsEl, businessSnapshotChartsTabsClaimsConfig);
            businessSnapshotChartsTabsClaims.render();
        }
    }, 500);

    // Remove shimmer
    toggleShimmer(false, 'business-snapshot-chart');
    
}

// Business Snapshot Tabs Function
function businessSnapshotBarChart(arrayData, highlightData, id) {

    var monthNumbers = jsonData.businessSnapshotChart.labels.labels;
    var labelsData = monthNumbers.map(monthNumber => getMonthName(monthNumber));

    let cardColor, labelColor, shadeColor, legendColor, borderColor;

    cardColor = "#2f3349";
    labelColor = "#a5a3ae";
    legendColor = "#6f6b7d";
    borderColor = '#dbdade';
    shadeColor = '';

    const earningReportBarChartOpt = {
        chart: {
            height: 258,
            parentHeightOffset: 0,
            type: 'bar',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '36%',
                borderRadius: 7,
                distributed: true,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        grid: {
            show: false,
            padding: {
                top: 0,
                bottom: 0,
                left: -10,
                right: -10
            }
        },
        colors: ['#4c87ff'],
        dataLabels: {
            enabled: true,
            formatter: (value) => {
                if (id == 3) {
                    var values = formatNumber(value, '%')
                } else {
                    var values = formatNumber(value, null, 'short')
                }
                return values;

            },
            offsetY: -25,
            style: {
                colors: [legendColor],
            }
        },
        series: [
            {
                data: arrayData
            }
        ],
        legend: {
            show: false
        },
        tooltip: {
            enabled: false,
            followCursor: true,
            shared: true,
            intersect: false,
            y: {
                formatter: function (value, { seriesIndex, dataPointIndex, w }) {
                    if (id == 3) {
                        var values = formatNumber(value, '%')
                    } else {
                        var values = formatNumber(value, null, 'short')
                    }
                    return currency + ' ' + values;
                }
            }
        },
        xaxis: {
            categories: labelsData,
            axisBorder: {
                show: true,
                color: borderColor
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '13px',
                }
            }
        },
        yaxis: {
            labels: {
                offsetX: -15,
                formatter: function (val) {
                    if (id == 3) {
                        var values = formatNumber(val, '%')
                    } else {
                        var values = formatNumber(val, null, 'short')
                    }
                    return values;
                },
                style: {
                    fontSize: '13px',
                    colors: labelColor,
                },
                min: 0,
                max: 60000,
                tickAmount: 6
            }
        },
        responsive: [
            {
                breakpoint: 1441,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: '41%'
                        }
                    }
                }
            },
            {
                breakpoint: 590,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: '61%',
                            borderRadius: 5
                        }
                    },
                    yaxis: {
                        labels: {
                            show: false
                        }
                    },
                    grid: {
                        padding: {
                            right: 0,
                            left: -20
                        }
                    },
                    dataLabels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: '400'
                        }
                    }
                }
            }
        ]
    };
    return earningReportBarChartOpt;
}

// Get Month Name
function getMonthName(monthNumber) {
    var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Ensure the month number is within a valid range
    if (monthNumber >= 1 && monthNumber <= monthNames.length) {
        return monthNames[monthNumber - 1];
    } else {
        return 'Invalid Month Number';
    }
}

// Format Number
function formatNumber(value, opt, short = null) {
    if (isNaN(value)) {
        return "Invalid number";
    }
    if (opt == '%') {
        return (value).toFixed() + '%';
    }
    else if (value >= 1e9) {
        // Billion
        if (short == null) {
            return (value / 1e9).toFixed(2) + " Billion";
        } else {
            return (value / 1e9).toFixed(0) + " B";
        }
    } else if (value >= 1e6) {
        // Million
        if (short == null) {
            return (value / 1e6).toFixed(2) + " Million";
        } else {
            return (value / 1e6).toFixed(0) + " M";
        }
    } else if (value >= 1e5) {
        // Lac (100,000)
        return (value / 1e5).toFixed(2) + " Lac";
    } else if (value >= 1e3) {
        // Thousand
        return (value / 1e3).toFixed(2) + " K";
    } else {
        // Regular number
        return value.toString();
    }
}

// Function to update the table
function updateTable(name, name2, density, value) {
    var tableBody = document.querySelector('.card-transaction .card-body.data');
    var transactionItem = document.createElement('div');
    transactionItem.className = 'transaction-item';
    var capitalizedFirstLetter = name.charAt(0).toUpperCase();
    var colorClass = value > 100 ? 'text-danger' : 'text-success';

    transactionItem.innerHTML = `
        <div class="media">
            <div class="mr-1">
                <div class="avatar-content">
                    <span class="avatar-logo">
                        <span class="initial-icon">${capitalizedFirstLetter}</span>
                    </span>
                </div>
            </div>
            <div class="media-body">
                <h6 class="transaction-title">${name}</h6>
                <small>${name2}</small>
            </div>
        </div>
        <div class="font-weight-bolder ${colorClass}">${density}</div>
    `;

    tableBody.appendChild(transactionItem);

    var transactionItems = Array.from(tableBody.querySelectorAll('.transaction-item'));

    transactionItems.sort(function(a, b) {
        var aValue = parseFloat(a.querySelector('.font-weight-bolder').textContent);
        var bValue = parseFloat(b.querySelector('.font-weight-bolder').textContent);
        return bValue - aValue;
    });

    transactionItems.forEach(function(item) {
        tableBody.appendChild(item);
    });

    feather.replace(); // Refresh Feather Icons
}

// Function to clear the table
function clearTable() {
    var tableBody = document.querySelector('.card-transaction .card-body.data');
    tableBody.innerHTML = '';
}