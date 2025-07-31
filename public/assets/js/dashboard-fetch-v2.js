

let outputType = "gross"
const updateOutputType = (value) => outputType = value

const isDefined = (value) => value !== undefined && value !== null;

let isLoading = false;


const formatNumber = (num) => {
    if (num == 0) {
        return '-';
    }
    // Round to two decimal places
    let roundedNum = Math.abs(num).toFixed(2);

    // Check if the original number was negative
    let isNegative = num < 0;

    // Determine the suffix and the scale
    if (Math.abs(num) >= 1e9) {
        roundedNum = (Math.abs(num) / 1e9).toFixed(2) + ' B';
    } else if (Math.abs(num) >= 1e6) {
        roundedNum = (Math.abs(num) / 1e6).toFixed(2) + ' M';
    } else if (Math.abs(num) >= 1e3) {
        roundedNum = (Math.abs(num) / 1e3).toFixed(2) + ' T';
    }

    // If the original number was negative, prepend the minus sign
    return isNegative ? `(${roundedNum})` : roundedNum;
}
// console.log("🚀 ~ parsedJsonDataSample:", parsedJsonDataSample)

let jsonData = isDefined($('#jsonData').attr('data-json')) && JSON.parse($('#jsonData').attr('data-json'))
let currencySymbol = $('#jsonData').attr('data-currency-symbol') || null;

const refactorData = (rawData, type) => {
    const cardsData = type === "gross" ? rawData?.gross_data?.card_data : rawData?.net_data?.card_data

    const businessSnapshot = type === "gross" ? rawData?.gross_data?.business_snapshot?.data : rawData?.net_data?.business_snapshot?.data
    const businessSnapshotCategories = type === "gross" ? rawData?.gross_data?.business_snapshot?.category : rawData?.net_data?.business_snapshot?.category

    const ratioData = type === "gross" ? rawData?.gross_data?.ratio_data : rawData?.net_data?.ratio_data
    const updatedRatioData = ratioData?.map(ed => (
        { ...ed, amount: (ed?.amount * 100).toFixed(2) }))
    const updatedBS = businessSnapshot?.map(bs =>
        bs.type === 'line'
            ? { ...bs, data: bs.data.map(d => (d * 100).toFixed(2)) }
            : bs
    );
    const profitabilityData = type === "gross" ? rawData?.gross_data?.uwProfitiblity : rawData?.net_data?.uwProfitiblity



    const writtenPremiumData = type === "gross"
        ? { ...rawData?.gross_data?.writtenPremium, data: rawData?.gross_data?.writtenPremium?.data.sort(function (a, b) { return b - a }) }
        : { ...rawData?.net_data?.writtenPremium, data: rawData?.net_data?.writtenPremium?.data.sort(function (a, b) { return a-b }) }
    return { cardsData, updatedBS, businessSnapshotCategories, updatedRatioData, profitabilityData, writtenPremiumData }
}
let refactoredData = isDefined(jsonData) ? refactorData(jsonData, "gross") : []



// * Stat Cards Start
const statCards = [$("#card-1"), $("#card-2"), $("#card-3"), $("#card-4")];

const cardLoadingStart = () => {
    statCards.forEach((stat) => {
        const h4 = stat.find("h4");
        const h5 = stat.find("h5");
        const avatar = stat.find(".avatar");

        h4.text("loading").addClass("card-shimmer");
        h5.text("loading").addClass("card-shimmer");
        avatar.addClass("card-shimmer");
    });
}
const cardLoadingEnd = () => {
    statCards.forEach((stat) => {
        const h4 = stat.find("h4");
        const h5 = stat.find("h5");
        const avatar = stat.find(".avatar");

        h4.removeClass("card-shimmer");
        h5.removeClass("card-shimmer");
        avatar.removeClass("card-shimmer");
    })
}

const setCardValue = (data) => {
    let cardDataKeys;

    if (data && typeof data === 'object' && Object.keys(data).length > 0) {
        cardDataKeys = Object?.keys(data);
    }
    statCards.forEach((stat, i) => {
        const h4 = stat.find("h4");
        const h5 = stat.find("h5");
        const h3 = stat.find("h3");

        if (data && typeof data === 'object' && Object.keys(data).length > 0) {
            const { amount, desc } = data[cardDataKeys[i]] || {};
            h4.text(currencySymbol + " " + formatNumber(amount));
            h5.text(desc);
        } else {
            // The object is empty or undefined/null
        }
    })
}

// * Stat Cards Ends



// * Revenue Report Starts

const revenewGraphLoadingStart = () => {
    $('#revenue-loading').css('display', 'flex');
    $('#totalRevenueChart').css('display', 'none');

}
const revenewGraphLoadingEnd = () => {

    $('#revenue-loading').css('display', 'none');
    $('#totalRevenueChart').css('display', 'flex');
}
// const setRevenewGraphValue = (series, categories) => {
//     ApexCharts.exec('revenue-report', 'updateSeries', series, true);
//     ApexCharts.exec('revenue-report', 'updateOptions', { xaxis: { categories } }, true, true);
// }
let totalRevenueChart = null;
const renderRevenewGraph = (data, category) => {
    const allValues = data
    .filter((series, i) => i !== 2) 
    .flatMap(series => series.data);
// const combinedMax = Math.max(...allValues);
    const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
        totalRevenueChartOptions = {
            series: data,
            // series: refactoredData?.updatedBS,
            chart: {
                id: "revenue-report",
                height: 400,
                width: "100%",
                parentHeightOffset: 0,
                stacked: false,
                type: 'line',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            tooltip: {
                enabled: true,
                y: { formatter: (value, { seriesIndex }) => (seriesIndex === 2) ? (value + "%") : formatNumber(value) },
            },
            plotOptions: { bar: { horizontal: false, columnWidth: 30, borderRadius: 500, endingShape: 'rounded' } },
            colors: ["#003399", "#4D80C1", "#fba51a", "#80B3DF", "#6699D0", "#1A4DA3", "#99CCEE", "#B3E6FF"],
            dataLabels: {
                enabled: true, enabledOnSeries: [2],
                formatter: function (value, { seriesIndex, dataPointIndex, w }) { return `${value}%` },
                textAnchor: 'middle', distributed: false, offsetX: 0, offsetY: -10,
                style: { fontSize: '10px', fontFamily: 'Montserrat, sans-serif', colors: ["#FFF"] },
                background: { enabled: true, foreColor: '#000', padding: 4, borderRadius: 2, borderWidth: 1, borderColor: '#fff', opacity: 0.9, dropShadow: { enabled: false } },
            },
            stroke: { curve: 'straight', width: [0, 0, 3], lineCap: 'round', colors: ["#fba51a"] },
            legend: {
                show: true,
                horizontalAlign: 'right',
                position: 'top',
                fontFamily: 'Montserrat',
                markers: { height: 12, width: 12, radius: 12, offsetX: -3, offsetY: 2 },
                labels: { colors: "#444" },
                itemMargin: { horizontal: 10, vertical: 2 }
            },
            grid: { show: true, padding: { bottom: -8, top: 20 } },
            xaxis: {
                categories: category,
                labels: { style: { fontSize: '12px', colors: "#444", fontFamily: 'Montserrat', fontWeight: "400" } },
                axisTicks: { show: false }, axisBorder: { show: false }
            },
            yaxis: [
                { min:0,  axisTicks: { show: false }, axisBorder: { show: false }, labels: { show: false}, seriesName: "Net Revenue" },
                { min:0, axisTicks: { show: false }, axisBorder: { show: false }, labels: { show: false }, seriesName: "Net Revenue" },
                { axisTicks: { show: false }, axisBorder: { show: false, }, labels: { show: false }, seriesName: "2", opposite: true }
            ],
            responsive: [
                { breakpoint: 1700, options: { plotOptions: { bar: { columnWidth: '43%' } } } },
                { breakpoint: 1441, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 422 } } },
                { breakpoint: 1300, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                { breakpoint: 1025, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 390 } } },
                { breakpoint: 991, options: { plotOptions: { bar: { columnWidth: '38%' } } } },
                { breakpoint: 850, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                {
                    breakpoint: 449, options: {
                        plotOptions: { bar: { columnWidth: '73%' } },
                        chart: { height: 360 },
                        xaxis: { labels: { offsetY: -5 } },
                        legend: { show: true, horizontalAlign: 'right', position: 'top', itemMargin: { horizontal: 10, vertical: 0 } }
                    }
                },
                {
                    breakpoint: 394,
                    options: {
                        plotOptions: { bar: { columnWidth: '88%' } },
                        legend: { show: true, horizontalAlign: 'center', position: 'bottom', markers: { offsetX: -3, offsetY: 0 }, itemMargin: { horizontal: 10, vertical: 5 } }
                    }
                }
            ],
            states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } },
            markers: {
                size: 5,
                strokeColors: '#fff', strokeWidth: 2, strokeOpacity: 0.9, strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [],
                shape: "circle",
                radius: 2,
                offsetX: 0,
                offsetY: 0,
                onClick: (e) => { },
                onDblClick: (e) => { },
                showNullDataPoints: true,
                hover: { size: 8, sizeOffset: 3 }
            }
        };

    if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
        if (!totalRevenueChart) {
            totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
            totalRevenueChart.render();
        } else {
            totalRevenueChart.updateOptions({
                series: refactoredData?.updatedBS,
                xaxis: {
                    categories: refactoredData?.businessSnapshotCategories
                }
            });
        }
    }
}

// * Revenue Report Ends


// * UW Profitibility Starts

const profitibilityGraphLoadingStart = () => {
    $('#profitibility-loading').css('display', 'flex');
    $('#uwProfitibility').css('display', 'none');

}
const profitibilityGraphLoadingEnd = () => {
    $('#profitibility-loading').css('display', 'none');
    $('#uwProfitibility').css('display', 'flex');
}
// const setProfitibilityGraphValue = (series, categories) => {
//     ApexCharts.exec('profitibility-graph', 'updateSeries', series, true);
//     ApexCharts.exec('profitibility-graph', 'updateOptions', { xaxis: { categories } }, true, true);
// }

let probabilityChart = null;
const renderProfitibilityGraph = (data, category) => {
    const uwProbabilitytEl = document.querySelector('#uwProfitibility'),
        uwProbability = {
            series: data,
            // series: [refactoredData?.profitabilityData?.data[0]],
            chart: {
                id: "profitibility-graph",
                height: 400,
                parentHeightOffset: 0,
                stacked: false,
                type: 'bar',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            tooltip: {
                enabled: true, enabledOnSeries: [0],
                y: { formatter: (value) => formatNumber(value) },
            },
            colors: ["#4D80C1"],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: 30,
                    borderRadius: 500,
                    endingShape: "rounded",
                    colors: { ranges: [{ from: -Infinity, to: 0, color: "#003399" }, { from: 0.01, to: Infinity, color: "#4D80C1" },], },
                    dataLabels: { position: "top" }
                }
            },

            dataLabels: {
                enabled: true, enabledOnSeries: [0],
                formatter: (value) => formatNumber(value), textAnchor: 'middle', distributed: false, offsetX: 0, offsetY: -24,
                style: { fontSize: '10px', fontFamily: 'Montserrat, sans-serif', colors: ["#FFF"] },
                background: { enabled: true, foreColor: '#444', padding: 4, borderRadius: 2, borderWidth: 0, borderColor: '#444', opacity: 0.9, dropShadow: { enabled: false } },
            },
            stroke: { curve: 'straight', width: 0, lineCap: 'round', },
            legend: { show: false },
            grid: { show: true, padding: { bottom: -8, top: 20 } },
            xaxis: {
                categories: category,
                labels: { style: { fontSize: '12px', colors: "#444", fontFamily: 'Montserrat', fontWeight: "400" } },
                axisTicks: { show: false },
                axisBorder: { show: false }
            },
            yaxis: [
                {
                    axisTicks: { show: false },
                    axisBorder: { show: false },
                    labels: { show: false },
                    seriesName: "UW Profitibility"
                },

            ],
            responsive: [
                { breakpoint: 1700, options: { plotOptions: { bar: { columnWidth: '43%' } } } },
                { breakpoint: 1441, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 422 } } },
                { breakpoint: 1300, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                { breakpoint: 1025, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 390 } } },
                { breakpoint: 991, options: { plotOptions: { bar: { columnWidth: '38%' } } } },
                { breakpoint: 850, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                {
                    breakpoint: 449,
                    options: {
                        plotOptions: { bar: { columnWidth: '73%' } }, chart: { height: 360 },
                        xaxis: { labels: { offsetY: -5 } },
                        legend: { show: true, horizontalAlign: 'right', position: 'top', itemMargin: { horizontal: 10, vertical: 0 } }
                    }
                },
                {
                    breakpoint: 394,
                    options: {
                        plotOptions: { bar: { columnWidth: '88%' } },
                        legend: { show: true, horizontalAlign: 'center', position: 'bottom', markers: { offsetX: -3, offsetY: 0 }, itemMargin: { horizontal: 10, vertical: 5 } }
                    }
                }
            ],
            states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } },
        };

    if (typeof uwProbabilitytEl !== undefined && uwProbabilitytEl !== null) {
        if (!probabilityChart) {
            probabilityChart = new ApexCharts(uwProbabilitytEl, uwProbability);
            probabilityChart.render();
        } else {
            probabilityChart.updateOptions({
                series: [refactoredData?.profitabilityData?.data[0]],
                xaxis: { categories: refactoredData?.profitabilityData?.category }
            });
        }
    }
}
// * UW Profitibility Ends

// * Expense 1 Starts

const expense1LoadingStart = () => {
    $('#expense-chart-1-loading').css('display', 'flex');
    $('#expensesChart0').css('display', 'none');

}
const expense1LoadingEnd = () => {
    $('#expense-chart-1-loading').css('display', 'none');
    $('#expensesChart0').css('display', 'flex');
}

let expensesRadialChart1 = null;
const renderExpense1Graph = (data, label) => {
    const cardLabel = $(`#expenses-chart-0 .chartlabel`)
    cardLabel.text(label)
    const expensesRadialChart1El = document.querySelector(`#expensesChart0`),

        expensesRadialChart1Config = {
            chart: { id: "expense-chart-1", height: 250, sparkline: { enabled: false }, parentHeightOffset: 0, type: 'radialBar' },
            colors: ["#4D80C1"],
            series: data,
            // series: [radialChart?.amount],
            plotOptions: {
                radialBar: {
                    offsetY: 0, startAngle: -90, endAngle: 90, hollow: { size: '75%' },
                    track: { strokeWidth: '45%', background: "#e4e4e4" },
                    dataLabels: {
                        name: { show: false },
                        value: { fontSize: '20px', fontFamily: "Montserrat, san-serif", color: "#444", fontWeight: 500, offsetY: -5 }
                    }
                }
            },
            grid: { show: false, padding: { bottom: 5 } },
            stroke: { lineCap: 'round' },
            labels: ['Progress'],
        };

    if (typeof expensesRadialChart1El !== undefined && expensesRadialChart1El !== null) {
        if (!expensesRadialChart1) {
            expensesRadialChart1 = new ApexCharts(expensesRadialChart1El, expensesRadialChart1Config);
            expensesRadialChart1.render();
        }
        else {
            expensesRadialChart1.updateOptions({
                series: data,
                labels: [label]
            }, true, true);

        }
    }
}
// * Expense 1 Ends

// * Expense 2 Starts

const expense2LoadingStart = () => {
    $('#expense-chart-2-loading').css('display', 'flex');
    $('#expensesChart1').css('display', 'none');

}
const expense2LoadingEnd = () => {
    $('#expense-chart-2-loading').css('display', 'none');
    $('#expensesChart1').css('display', 'flex');
}

let expensesRadialChart2 = null;
const renderExpense2Graph = (data, label) => {
    const cardLabel = $(`#expenses-chart-1 .chartlabel`)
    cardLabel.text(label)
    const expensesRadialChart2El = document.querySelector(`#expensesChart1`),

        expensesRadialChart2Config = {
            chart: { id: "expense-chart-2", height: 250, sparkline: { enabled: false }, parentHeightOffset: 0, type: 'radialBar' },
            colors: ["#4D80C1"],
            series: data,
            // series: [radialChart?.amount],
            plotOptions: {
                radialBar: {
                    offsetY: 0, startAngle: -90, endAngle: 90, hollow: { size: '75%' },
                    track: { strokeWidth: '45%', background: "#e4e4e4" },
                    dataLabels: {
                        name: { show: false },
                        value: { fontSize: '20px', fontFamily: "Montserrat, san-serif", color: "#444", fontWeight: 500, offsetY: -5 }
                    }
                }
            },
            grid: { show: false, padding: { bottom: 5 } },
            stroke: { lineCap: 'round' },
            labels: ['Progress'],
        };

    if (typeof expensesRadialChart2El !== undefined && expensesRadialChart2El !== null) {
        if (!expensesRadialChart2) {
            expensesRadialChart2 = new ApexCharts(expensesRadialChart2El, expensesRadialChart2Config);
            expensesRadialChart2.render();
        }
        else {
            expensesRadialChart2.updateOptions({
                series: [refactoredData?.updatedRatioData[1]?.amount],
                labels: [refactoredData?.updatedRatioData[1]?.desc]
            })
        }
    }
}
// * Expense 2 Ends



// * Written Premium Starts

const writtenPremiumListLoadingStart = () => {
    $('#writtenPremium-loading').css('display', 'flex');
    $('#writtenPremiumList').empty().append("<li class='list-shimmer'></li>".repeat(6));


}
const writtenPremiumListLoadingEnd = () => {
    $('#writtenPremium-loading').css('display', 'none');
    $('#writtenPremiumList').empty()
}
// const setWrittenPremiumGraphValue = (series, categories) => {
//     ApexCharts.exec('writtenPremium-graph', 'updateSeries', series, true);
//     ApexCharts.exec('writtenPremium-graph', 'updateOptions', { xaxis: { categories } }, true, true);
// }

let writtenPremiumChart = null;
const renderWrittenPremium = (data) => {
    const writtenPremiumChartEl = document.querySelector('#writtenPremiumChart'),
        writtenPremiumChartConfig = {
            series: data,
            // series: [{ data: refactoredData?.writtenPremiumData.data }],
            chart: { id: "writtenPremium-graph", height: 400, parentHeightOffset: 0, stacked: false, type: 'bar', toolbar: { show: false }, zoom: { enabled: false } },
            tooltip: { enabled: true, y: { formatter: value => formatNumber(value) } },
            plotOptions: { bar: { borderRadius: 4, borderRadiusApplication: 'end', horizontal: true, columnWidth: 30, borderRadius: 500, endingShape: 'rounded', } },
            // colors: ["#4D80C1", "#80B3DF", "#3366B2", "#6699D0", "#1A4DA3", "#99CCEE", "#B3E6FF"],
            colors: [({ dataPointIndex }) => (dataPointIndex == 2) ? "#003399" : "#4D80C1"],
            dataLabels: {
                enabled: true, enabledOnSeries: [0],
                formatter: (value) => formatNumber(value),
                textAnchor: 'middle', distributed: false, offsetX: 0, offsetY: 0,
                style: { fontSize: '10px', fontFamily: 'Montserrat, sans-serif', colors: ["#FFF"] },
                background: { enabled: true, foreColor: '#000', padding: 4, borderRadius: 2, borderWidth: 1, borderColor: '#fff', opacity: 0.9, dropShadow: { enabled: false } },
            },
            grid: { show: true, padding: { bottom: -8, top: 20 } },
            xaxis: {
                categories: refactoredData?.writtenPremiumData?.categories,
                labels: { show: false },
                axisTicks: { show: false }, axisBorder: { show: false }
            },
            yaxis: [
                {
                    axisTicks: { show: false }, axisBorder: { show: false },
                    labels: { show: true, style: { fontSize: '10px', colors: "#444", fontFamily: 'Montserrat', fontWeight: "500" } },
                },
            ],
            responsive: [
                { breakpoint: 1700, options: { plotOptions: { bar: { columnWidth: '43%' } } } },
                { breakpoint: 1441, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 422 } } },
                { breakpoint: 1300, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                { breakpoint: 1025, options: { plotOptions: { bar: { columnWidth: '50%' } }, chart: { height: 390 } } },
                { breakpoint: 991, options: { plotOptions: { bar: { columnWidth: '38%' } } } },
                { breakpoint: 850, options: { plotOptions: { bar: { columnWidth: '50%' } } } },
                {
                    breakpoint: 449,
                    options: {
                        plotOptions: { bar: { columnWidth: '73%' } },
                        chart: { height: 360 },
                        xaxis: { labels: { offsetY: -5 } },
                        legend: { show: true, horizontalAlign: 'right', position: 'top', itemMargin: { horizontal: 10, vertical: 0 } }
                    }
                },
                {
                    breakpoint: 394,
                    options: {
                        plotOptions: { bar: { columnWidth: '88%' } },
                        legend: { show: true, horizontalAlign: 'center', position: 'bottom', markers: { offsetX: -3, offsetY: 0 }, itemMargin: { horizontal: 10, vertical: 5 } }
                    }
                }
            ],
            states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } },
        };

    if (typeof writtenPremiumChartEl !== undefined && writtenPremiumChartEl !== null) {
        if (!writtenPremiumChart) {
            writtenPremiumChart = new ApexCharts(writtenPremiumChartEl, writtenPremiumChartConfig);
            writtenPremiumChart.render();
        } else {
            writtenPremiumChart.updateOptions({
                series: data,
                xaxis: refactoredData?.writtenPremiumData?.categories,
            });
        }
    }
}


// * Written Premium Ends

function updateLoadingState(outputChanged) {
    if (isLoading) {
        cardLoadingStart()
        revenewGraphLoadingStart()
        expense1LoadingStart()
        expense2LoadingStart()
        profitibilityGraphLoadingStart()
        writtenPremiumListLoadingStart()

        // writtenPremiumGraphLoadingStart()
        // console.log("isloading")
    } else {
        cardLoadingEnd()
        if (isDefined(refactoredData?.cardsData)) {
            setCardValue(refactoredData?.cardsData)
        }

        revenewGraphLoadingEnd()
        if (isDefined(refactoredData?.updatedBS)) {
            renderRevenewGraph(refactoredData?.updatedBS, refactoredData?.businessSnapshotCategories)
            if (outputChanged) {
                // ApexCharts.exec('revenue-report', 'updateSeries', refactoredData?.updatedBS, true);
                ApexCharts.exec('revenue-report', 'updateOptions', { series: refactoredData?.updatedBS, xaxis: { categories: refactoredData?.businessSnapshotCategories } }, true, true);

            }
        }

        expense1LoadingEnd()

        if (isDefined(refactoredData?.updatedRatioData)) {
            renderExpense1Graph([refactoredData?.updatedRatioData[0]?.amount], refactoredData?.updatedRatioData[0]?.desc)
            if (outputChanged) {
                ApexCharts.exec('expense-chart-1', 'updateSeries', [refactoredData?.updatedRatioData[0]?.amount], true);
                $(`#expenses-chart-0 .chartlabel`).text(refactoredData?.updatedRatioData[0]?.desc)

            }
        }
        expense2LoadingEnd()

        if (isDefined(refactoredData?.updatedRatioData)) {
            renderExpense2Graph([refactoredData?.updatedRatioData[1]?.amount], refactoredData?.updatedRatioData[1]?.desc)
            if (outputChanged) {
                ApexCharts.exec('expense-chart-2', 'updateSeries', [refactoredData?.updatedRatioData[1]?.amount], true);
                $(`#expenses-chart-1 .chartlabel`).text(refactoredData?.updatedRatioData[1]?.desc)
            }
        }

        profitibilityGraphLoadingEnd()
        if (isDefined(refactoredData?.profitabilityData?.data[0])) {
            renderProfitibilityGraph([refactoredData?.profitabilityData?.data[0]], refactoredData?.profitabilityData?.category)
            if (outputChanged) {
                ApexCharts.exec('profitibility-graph', 'updateOptions', { series: [refactoredData?.profitabilityData?.data[0]], xaxis: { categories: refactoredData?.profitabilityData?.category } }, true, true);

            }
        }

        writtenPremiumListLoadingEnd()
        if (isDefined(refactoredData?.writtenPremiumData.data)) {
            const renderderWrittenPremiumList = () => {
                const data = refactoredData?.writtenPremiumData?.data
                const categories = refactoredData?.writtenPremiumData?.categories
                console.log(refactoredData);
                if (data?.length > 0) {
                    $("#writtenPremiumList").empty();

                    data?.map((d, i) => {
                        $("#writtenPremiumList").append(`<li><span class="category">${categories[i]}</span><span class="data">${currencySymbol} ${formatNumber(data[i])}</span></li>`)
                    })
                } else {
                    $("#writtenPremiumList").empty();
                    $("#writtenPremiumList").append('<li>No records found</li>')
                }
            }
            renderderWrittenPremiumList()
            if (outputChanged) {
                renderderWrittenPremiumList()


            }
            // $("#writtenPremiumList").text(JSON.stringify(refactoredData?.writtenPremiumData?.data))
            // renderWrittenPremium([{ data: refactoredData?.writtenPremiumData.data }], refactoredData?.writtenPremiumData.categories)
            // if (outputChanged) {
            //     ApexCharts.exec('writtenPremium-graph', 'updateOptions', { series: [{ data: refactoredData?.writtenPremiumData.data }], xaxis: { categories: refactoredData?.writtenPremiumData.categories } }, true, true);

            // }
        }



    }
}

function intialValues() {
    updateLoadingState()

}
intialValues()


form_data = $('#dashboard').serializeArray()

const updateFormData = (data) => {
    form_data = data
}

function compareArrays(arr1, arr2) {
    let isOutputTypeIdChanged = false;
    let isOtherValueChanged = false;

    // Convert arrays to maps for easier comparison
    const map1 = new Map(arr1.map(obj => [obj.name, obj.value]));
    const map2 = new Map(arr2.map(obj => [obj.name, obj.value]));

    // Check if "output_type_id" has changed
    if (map1.get("output_type_id") !== map2.get("output_type_id")) {
        isOutputTypeIdChanged = true;
        updateOutputType(arr2?.filter(arr => arr.name === "output_type_id")[0]?.value)
        refactoredData = refactorData(jsonData, arr2?.filter(arr => arr.name === "output_type_id")[0]?.value)
        updateLoadingState(true)

    }

    const fieldsToIgnore = ['is_update'];

    // Check for other changes or additions
    for (const [key, value] of map1.entries()) {
        if (fieldsToIgnore.includes(key)) {
            continue;
        }

        if (key !== "output_type_id" && (!map2.has(key) || map2.get(key) !== value)) {
            isOtherValueChanged = true;
            break;
        }
    }

    for (const [key, value] of map2.entries()) {
        if (fieldsToIgnore.includes(key)) {
            continue;
        }

        if (key !== "output_type_id" && !map1.has(key)) {
            isOtherValueChanged = true;
            break;
        }
    }

    return { isOutputTypeIdChanged, isOtherValueChanged };
}


// Filter Button Handler
$('#filters').on('click', function () {
    const currentFormData = $('#dashboard').serializeArray();
    const $button = $(this);
    $button.prop('disabled', true);

    // * 1.  add logic here to prevent api call if filters are not changed 
    // * 2.  add logic to update data and not call the api if only output type is changed
    // fetch_data();
    const { isOutputTypeIdChanged, isOtherValueChanged } = compareArrays(form_data, $('#dashboard').serializeArray())
    updateFormData($('#dashboard').serializeArray())
    const outputType = currentFormData.find(item => item.name === 'output_type_id')?.value;
    updateTitle(outputType);

    // console.log(form_data);
    if (isOutputTypeIdChanged && !isOtherValueChanged) {
        $button.prop('disabled', false); 
    } else {
        // filterReset();
        fetch_data().finally(() => {
            $button.prop('disabled', false);
        });
    
    }

});

// Handling 'All' in portfolio dropdown
$('#portfolio').on('changed.bs.select', function (e, clickedIndex, isSelected) {
    if (isResetting) {
        return;
    }
    var selectedOptions = $(this).val();
    var allOptionValue = 'All';
    var $this = $(this);

    if (selectedOptions.includes(allOptionValue)) {
        if (clickedIndex !== null && $this.find('option').eq(clickedIndex).val() === allOptionValue) {
            $this.val([allOptionValue]).selectpicker('refresh');
        } else {
            $this.find('option[value="' + allOptionValue + '"]').prop('selected', false);
            $this.selectpicker('refresh');
        }
    } else {
        $this.find('option[value="' + allOptionValue + '"]').prop('selected', false);
        $this.selectpicker('refresh');
    }
});
// Reset Button Handler
$('#reset').on('click', function () {
    filterReset();
});

// Fetch Data
function fetch_data() {
    isLoading = true;
    updateLoadingState();


    modal = $('#fetch_records').modal('show')
    // chartTypes.forEach(chartType => {
    //     toggleShimmer(true, chartType);
    // });
    form_data.push({ name: 'is_update', value: isUpdate });
    const outputType = form_data.find(field => field.name === 'output_type_id')?.value || 'gross';
    route = $('#route').data('route');
    $.ajax({
        type: 'get',
        async: true,
        url: route,
        data: form_data,
        success: function (data) {
            jsonData = data;
            refactoredData = refactorData(data, outputType)
            displayData(data);
            isLoading = false;
            updateLoadingState(true);

        },
        error: function (err) {
            isLoading = false;
            updateLoadingState();

            $('.error-wrapper').show();
            $('.error').html(err.responseJSON['message']);
            setTimeout(function () {
                $('#fetch_records').modal('hide');
            }, 1000);


        }
    });
    modal = $('#fetch_records').modal('hide');

}
// Set filters
function displayFilter(filters) {
    $('#accounting-year').val(filters['accounting_year_id']).change()
    $('#portfolio').val(filters['portfolio_id']).change()
    $('#branch').val(filters['branch_id']).change()
    $('#business-type').val(filters['business_type_id']).change()
}

// Reset Filters
var isUpdate = true;
var isResetting = false;
function filterReset() {
    isUpdate = false;
    isResetting = true;
    $('#accounting-year').val($('#accounting-year option:eq(0)').val()).change();
    $('#branch').val('All').change();
    $('#business-type').val($('#business-type option:eq(0)').val()).change()
    $('#portfolio').val('All').selectpicker('refresh').change();
    $('#output-type').val('gross').selectpicker('refresh').trigger('change');

    setTimeout(function () {
        // Update form_data after resetting the filters
        form_data = $('#dashboard').serializeArray();
        fetch_data();
        isResetting = false;
        isUpdate = true;
    }, 500);
}

// Set Filters
function setFilter() {
    $('#accounting-year').val($('#accounting-year option:eq(0)').val()).change();
    $('#portfolio').val('All').change()
    $('#branch').val('All').change()
    $('#business-type').val($('#business-type option:eq(0)').val()).change()
}

// Set Accounting Years
function setAccountingYear() {
    var date = $("#accounting-year option:selected").text();
    var last = Number(date.slice(-4));
    $('.selected-date').html(last);
}

// Display Data
function displayData(data) {
    modal = $('#fetch_records').modal('show')
    setAccountingYear();
    setTimeout(function () {
        $('#jsonData').attr('data-json', data);

        modal = $('#fetch_records').modal('hide');
        // chartTypes.forEach(chartType => {
        //      toggleShimmer(false, chartType);
        // });
    }, 700);
}

    const outputTitle = document.getElementById('outputTitle');
    const outputTypeDropdown = document.getElementById('output-type');

    updateTitle(outputTypeDropdown.value);

    outputTypeDropdown.addEventListener('change', function () {
        initialOutputType = outputTypeDropdown.value;
    });

function updateTitle(outputType) {
    const title = outputType === 'net' ? 'Reinsurance Ceded' : 'Written Premium';
    outputTitle.textContent = title;  
}
