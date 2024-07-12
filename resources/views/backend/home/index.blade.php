@extends('backend.layouts.app-master')

@section('content')
<style>
@media (min-width:1025px) {
    .col-xl-3 {
        width: 20% !important;
    }
}

.bg-blissful-sky {
    background-image: linear-gradient(to top, #7f7fd5 0%, #86a8e7 50%, #91eae4 100%) !important;
}

.widget-heading {
    font-size: 14px;
}

.card-body {
    font-size: 20px;
    line-height: 100px;
}

.custom-d-none {
    display: none !important;
}
</style>
<!-- <script src="{!! url('assets/js/charts/chart.min.js') !!}"></script> -->
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Dashboard</h3>
    </div>
    <div class="text-lg-end text-center position-relative">
        <div class="btn-group chart-filter-btns mt-lg-0 mt-4" role="group">
            <small type="button" id="showDateFilterBox"
                class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold">
                Custom</small>

            <small type="button" data-value=""
                class="btn btn-sm rounded bg-theme-green-light me-2 filters border-0 text-theme-green fw-bold">All</small>
            <small type="button" data-value="day"
                class="btn btn-sm rounded bg-theme-green-light me-2 filters border-0 text-theme-green fw-bold">Day</small>
            <small type="button" data-value="week"
                class="btn btn-sm rounded bg-theme-green-light me-2 filters border-0 text-theme-green fw-bold">Week</small>
            <small type="button" data-value="month"
                class="btn btn-sm rounded bg-theme-green-light me-2 filters border-0 text-theme-green fw-bold">Month</small>
            <small type="button" data-value="3-months"
                class="btn btn-sm rounded bg-theme-green-light me-2 filters border-0 text-theme-green fw-bold">3
                Months</small>
        </div>
        <div id="dateFilterBox" class="my-4 shadow rounded p-4" style="display:none;">
            <div class=" row d-flex justify-content-between mt-1 text-start">

                <div class="col-lg-6">
                    <small>From:</small>
                    <input type="hidden" value="" name="val" />
                    <input type="date" class="form-control p-2 start-date" name="created_at_from" value="">
                </div>

                <div class="col-lg-6">
                    <small>To:</small>
                    <input type="date" class="form-control p-2 end-date" name="created_at_to" value="">
                </div>
                <div class="col-lg-12 text-end border-top border-white pt-2 mt-4">
                    <button type="submit"
                        class="btn btn-sm rounded bg-theme-green mt-2 border-0 text-white fw-bold d-inline-flex align-items-center custom-date-search"
                        data-value="custom-date-search">
                        <i class="fa fa-solid fa fa-search me-2"></i> Search
                    </button>
                    <a href="{{ route('home.index') }}"><small type="button"
                            class="btn btn-sm rounded bg-theme-green mt-2 border-0 text-white fw-bold d-inline-flex align-items-center "><i
                                class="fa fa-solid fa-arrows-rotate me-2"></i> Clear</small></a>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="page-content bg-white p-5 px-2">

    <!-- total complain charts and counts -->
    <!-- <div class="row justify-content-center"> -->
    <div class="row ">

        <div
            class="col-xxl-2 col-xl-4 col-lg-4 col-md-12 col-sm-12 order-xxl-1 order-xl-1 order-xl-1 order-lg-1 order-1 count-chart mb-3">
            <div class="d-flex flex-column  align-items-center">
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-circle-info fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-complaints"></h1>
                            <h6 class="mb-0 text-white">Total Complaints</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-clipboard-check fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-resolved"></h1>
                            <h6 class="mb-0 text-white">Resolved</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-user-check fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-registered"></h1>
                            <h6 class="mb-0 text-white">Registered</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-spinner fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-inprocess"></h1>

                            <h6 class="mb-0 text-white">Inprocess</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-regular fa-hourglass fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-onhold"></h1>
                            <h6 class="mb-0 text-white">On Hold</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-green px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-check-double fa-2x text-white mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-white" id="total-closed"></h1>
                            <h6 class="mb-0 text-white">Closed</h6>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div
            class="col-xxl-7 col-xl-12 col-lg-12 col-md-12 col-sm-12 order-xxl-2 order-xl-3 order-lg-3 order-3 mna-mpa-chart mb-3">
            <div class="chart-section">
                @if(Auth::user()->hasRole('admin'))
                <div class=" chart-section-heading my-3">
                    <div class="floating-form-section p-2 d-lg-flex align-items-between justify-content-center gap-1">
                        <div class="form-floating">
                            <select class="form-select filters-by-mpa" id="filters-by-mpa" data-value="filter-by-mpa">
                                <option selected value="">Select MPA</option>
                                @if(!empty($mpaList))
                                @foreach($mpaList as $mpa)
                                <option value="{{ trim(Arr::get($mpa, 'id')) }}">
                                    {{ trim(Arr::get($mpa, 'name')) }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            <label for="floatingSelectGrid" class="text-dark">Complaints To MPA</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select filters-by-mna" id="filters-by-mna" data-value="filter-by-mna">
                                <option selected value="">Select MNA</option>
                                @if(!empty($mnaList))
                                @foreach($mnaList as $mna)
                                <option value="{{ trim(Arr::get($mna, 'id')) }}">
                                    {{ trim(Arr::get($mna, 'name')) }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            <label for="floatingSelectGrid" class="text-dark">Complaints To MNA</label>
                        </div>
                    </div>

                </div>
                @endif

                <!-- @if(Auth::user()->hasRole('admin'))
                <div class=" chart-section-heading my-3 d-lg-flex align-items-center justify-content-center gap-1">
                    <h6 class="mb-lg-0 mb-3 fw-bold">Complaints By Mna</h6>
                    <select class="form-select filters-by-mna" id="filters-by-mna" data-value="filter-by-mna">
                        <option selected value="">select Mna</option>
                        @if(!empty($mnaList))
                            @foreach($mnaList as $mna)
                                <option value="{{ trim(Arr::get($mna, 'id')) }}">
                                    {{ trim(Arr::get($mna, 'name')) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    
                </div>
            @endIf -->


                <div
                    class="custom-d-none chart-section-heading my-3 d-lg-flex align-items-center justify-content-center gap-1">
                    <h6 class="mb-lg-0 mb-3 fw-bold">Complaints</h6>
                    @if(Auth::user()->hasRole('admin'))
                    <button onclick="updateChartTypeMnaMpa('mna',this)" type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold updateChartyTypeMnaMpa Active">
                        Mna Wise
                    </button>

                    <button onclick="updateChartTypeMnaMpa('mpa',this)" type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold updateChartyTypeMnaMpa ">
                        Mpa Wise
                    </button>
                    @endif
                </div>
                <div class="chart-box">
                    <div id="complaintChartMnaMpaWise" class="complaintChartMnaMpaWise"></div>
                </div>
            </div>
        </div>

        <div
            class="col-xxl-3 col-xl-8 col-lg-8 col-lg-8 col-md-12 order-xxl-3 order-xl-2 order-lg-2 order-2 status-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3">
                    <h6 class="mb-0 fw-bold">Complaints Status Report</h6>
                </div>
                <div class="chart-box">
                    <div class="complaintsPieChart" id="complaintsPieChart"></div>
                </div>
            </div>
        </div>

        <div
            class="col-xxl-10 offset-xxl-2 col-lg-12  col-md-12 order-xxl-4 order-xl-4 order-lg-4 order-4 category-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3">
                    <h6 class="mb-0 fw-bold">Complaints Category Wise</h6>
                </div>
                <div class="chart-box">
                    <div id="categoryWiseComplaintsChart" class="categoryWiseComplaintsChart"></div>
                </div>
            </div>
        </div>
    </div>



</div>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/sliceGrouper.js"></script>



<style>
.no-data-found {
    font-size: 16px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
<style>
#complaintChartMnaMpaWise {
    width: 100%;
    height: 500px;
}
</style>

<style>
#complaintsPieChart {
    width: 100%;
    height: 500px;
}
</style>

<style>
#categoryWiseComplaintsChart {
    width: 100%;
    height: 300px;
}
</style>

<script>
var complaintCountsMnaMpa = {}
am4core.options.autoDispose = true;

function generateMnaMpaComplaintsChart(dataset) {

    // Create complaintChartMnaMpaWise instance
    var complaintChartMnaMpaWise = am4core.create("complaintChartMnaMpaWise", am4charts.XYChart3D);
    // Binding Data
    if (dataset.length > 0) {
        complaintChartMnaMpaWise.data = dataset;
    } else {
        complaintChartMnaMpaWise.hidden = true;
        $("#complaintChartMnaMpaWise").html('<div class="no-data-found">No Complaints found!</div>');
    }

    complaintChartMnaMpaWise.responsive.enabled = true;

    // Create axes
    let categoryAxis = complaintChartMnaMpaWise.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.labels.template.disabled = true;
    categoryAxis.tooltip = false;

    let valueAxis = complaintChartMnaMpaWise.yAxes.push(new am4charts.ValueAxis());
    valueAxis.tooltip = false;

    // Create series
    var complaintChartMnaMpaWiseSeries = complaintChartMnaMpaWise.series.push(new am4charts.ColumnSeries3D());
    complaintChartMnaMpaWiseSeries.dataFields.valueY = "count";

    complaintChartMnaMpaWiseSeries.dataFields.categoryX = "name";
    complaintChartMnaMpaWiseSeries.dataFields.categoryZ = "image";
    complaintChartMnaMpaWiseSeries.columns.template.tooltipHTML = `
    <div style="display: flex; align-items: center;">
        <div style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
            <img src="{categoryZ}" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <div style="margin-left: 10px;">
            <strong>{categoryX}</strong> has <strong>{valueY}</strong> Complaints
        </div>
    </div>`;
    complaintChartMnaMpaWiseSeries.columns.template.fillOpacity = .8;
    complaintChartMnaMpaWiseSeries.tooltip.label.maxWidth = 250;
    complaintChartMnaMpaWiseSeries.tooltip.label.fontSize = 12;

    complaintChartMnaMpaWiseSeries.tooltip.label.wrap = true;
    complaintChartMnaMpaWiseSeries.tooltip.keepTargetHover = true;

    var columnTemplate = complaintChartMnaMpaWiseSeries.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;
    columnTemplate.stroke = am4core.color("#FFFFFF");

    columnTemplate.adapter.add("fill", function(fill, target) {
        return complaintChartMnaMpaWise.colors.getIndex(target.dataItem.index);
    });

    columnTemplate.adapter.add("stroke", function(stroke, target) {
        return complaintChartMnaMpaWise.colors.getIndex(target.dataItem.index);
    });

    complaintChartMnaMpaWise.cursor = new am4charts.XYCursor();
    complaintChartMnaMpaWise.cursor.behavior = "none";
    complaintChartMnaMpaWise.cursor.lineX.strokeOpacity = 0;
    complaintChartMnaMpaWise.cursor.lineY.strokeOpacity = 0;

    complaintChartMnaMpaWise.events.on("hit", function(event) {
        console.log(complaintChartMnaMpaWiseSeries.tooltipDataItem);
    })

    complaintChartMnaMpaWise.logo.disabled = true;

    if (window.innerWidth < 768) {
        columnTemplate.width = am4core.percent(80);
    }
}

function updateChartTypeMnaMpa(type, elem) {

    $(elem).addClass('Active').siblings().removeClass('Active');

    complaintChartMnaMpaWise.data = complaintCountsMnaMpa[type];
    console.log(complaintChartMnaMpaWise.data)
    generateMnaMpaComplaintsChart(complaintChartMnaMpaWise.data)
}

// Function to generate a random color
function getRandomColor() {

    const letters = '89ABCDEF'; // Using only the bright range
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * letters.length)];
    }
    return color;
}


function generatecomplaintsPieChart(dataset) {


    // Create Complain Pie Chart Instance
    var complaintsPieChart = am4core.create("complaintsPieChart", am4charts
        .PieChart);
    complaintsPieChart.legend = new am4charts.Legend();
    complaintsPieChart.legend.maxHeight = 200;
    complaintsPieChart.legend.scrollable = true;
    complaintsPieChart.legend.fontSize = 12;
    complaintsPieChart.legend.valueLabels.template.align = "left";
    complaintsPieChart.legend.valueLabels.template.textAlign = "start";

    complaintsPieChart.legend.labels.template.text =
        "{category}: ";

    // Binding Data

    var sum = 0;
    for (var key in dataset) {
        if (dataset.hasOwnProperty(key)) {
            sum += dataset[key];
        }
    }

    console.log(sum);

    if (sum > 0) {

        let int = 0;

        const colors = ["#1ccab8", "#a01497", "#e64b55", "#508ff4", "#ffbf43"];
        for (const category in dataset) {
            complaintsPieChart.data.push({
                category: category,
                value: dataset[category],
                color: colors[int++],
            });
            if (int == colors.length) {
                int = 0;
            }
        }

        console.log(complaintsPieChart.data);


        // complaintsPieChart.data = [{
        //         category: "Registered",
        //         value: dataset.Registered,
        //         color: "#ee4b82"
        //     },
        //     {
        //         category: "In process",
        //         value: dataset.Inprocess,
        //         color: "#508ff4"
        //     },
        //     {
        //         category: "Hold",
        //         value: dataset.Hold,
        //         color: "#e64b55"
        //     },
        //     {
        //         category: "Resolved",
        //         value: dataset.Resolved,
        //         color: "#1ccab8"
        //     },
        //     {
        //         category: "Closed",
        //         value: dataset.Closed,
        //         color: "#ffbf43"
        //     },
        // ];
    } else {
        complaintsPieChart.hidden = true;
        $("#complaintsPieChart").html(
            '<div class="no-data-found">No Complaints found!</div>');
    }


    // Add and configure Series
    var complaintsPieChartSeries = complaintsPieChart.series.push(new am4charts
        .PieSeries());


    complaintsPieChartSeries.dataFields.value = "value";
    complaintsPieChartSeries
        .dataFields.category = "category";
    complaintsPieChartSeries.slices.template
        .stroke = am4core.color("#fff");
    complaintsPieChartSeries.slices.template
        .strokeOpacity = 1;
    complaintsPieChartSeries.slices.template.propertyFields
        .fill = "color";
    complaintsPieChartSeries.slices.template.cornerRadius = 1;


    complaintsPieChartSeries.slices.template.tooltipText =
        // "{category}: [bold]{value.percent.formatNumber('0.00')}%[/] [bold]({value}) Complaints[/]";
        "{category}: [bold]{value} Complaints[/]";
    complaintsPieChartSeries.labels
        .template.disabled = true;
    complaintsPieChartSeries.ticks.template.disabled =
        true;


    // This creates initial animation
    complaintsPieChartSeries.hiddenState.properties.opacity =
        1;
    complaintsPieChartSeries.hiddenState.properties.endAngle = -
        90;
    complaintsPieChartSeries.hiddenState.properties.startAngle = -90;

    // complaintsPieChart.radius = am4core.percent();
    complaintsPieChart.innerRadius = am4core.percent(10);
    complaintsPieChart.logo
        .disabled = true;
    complaintsPieChart.hiddenState.properties.radius = am4core
        .percent(0);

}

// Create categoryWiseComplaintsChart instance
function generateCategoryWiseChart(dataset) {

    var categoryWiseComplaintsChart = am4core.create("categoryWiseComplaintsChart",
        am4core.Container);
    categoryWiseComplaintsChart.width = am4core.percent(
        100);
    categoryWiseComplaintsChart.height = am4core.percent(
        100);
    categoryWiseComplaintsChart.layout =
        "horizontal";
    categoryWiseComplaintsChart.logo.disabled = true;


    // Create categoryWiseComplaintsPieChart instance
    var categoryWiseComplaintsPieChart = categoryWiseComplaintsChart.createChild(
        am4charts.PieChart3D);
    categoryWiseComplaintsPieChart.width = am4core
        .percent(45);
    categoryWiseComplaintsPieChart.hiddenState.properties.opacity = 0;



    // Binding data
    // categoryWiseComplaintsPieChart.data = dataset;

    if (dataset.length > 0) {
        categoryWiseComplaintsPieChart.data = dataset;
    } else {
        categoryWiseComplaintsChart.hidden = true;
        $("#categoryWiseComplaintsChart").html(
            '<div class="no-data-found">No Complaints found!</div>');
    }


    categoryWiseComplaintsPieChart.innerRadius = am4core.percent(
        50);
    categoryWiseComplaintsPieChart.depth = 15;



    // Add and configure series
    var categoryWiseComplaintsPieChartSeries = categoryWiseComplaintsPieChart.series
        .push(new am4charts
            .PieSeries3D());
    categoryWiseComplaintsPieChartSeries.dataFields.value =
        "value";
    categoryWiseComplaintsPieChartSeries.dataFields.category =
        "category";
    categoryWiseComplaintsPieChartSeries.slices.template.propertyFields
        .fill =
        "color";
    categoryWiseComplaintsPieChartSeries.labels.template.disabled =
        true;
    categoryWiseComplaintsPieChartSeries.slices.template.cornerRadius =
        5;
    categoryWiseComplaintsPieChartSeries.colors.step = 3;

    // Set up labels
    var centerLabel1 = categoryWiseComplaintsPieChart.seriesContainer.createChild(
        am4core.Label);
    centerLabel1.text = "";
    centerLabel1.horizontalCenter =
        "middle";
    centerLabel1.fontSize = 35;
    centerLabel1.fontWeight =
        600;
    centerLabel1.dy = -30;

    var centerLabel2 = categoryWiseComplaintsPieChart.seriesContainer.createChild(
        am4core.Label);
    centerLabel2.text = "";
    centerLabel2.horizontalCenter =
        "middle";
    centerLabel2.fontSize = 12;
    centerLabel2.dy = 20;


    // Auto-select first slice on load
    categoryWiseComplaintsPieChart.events.on("ready", function(ev) {
        categoryWiseComplaintsPieChartSeries.slices.getIndex(0).isActive =
            true;
    });

    // Set up toggling events
    categoryWiseComplaintsPieChartSeries.slices.template.events.on("toggled",
        function(
            ev) {
            if (ev.target.isActive) {

                // Untoggle other slices
                categoryWiseComplaintsPieChartSeries.slices.each(function(
                    slice) {
                    if (slice != ev.target) {
                        slice.isActive = false;
                    }
                });

                // Update column chart
                categoryWiseComplaintsColumnChartSeries.appeared = false;
                categoryWiseComplaintsColumnChart.data = ev.target.dataItem
                    .dataContext.breakdown;
                categoryWiseComplaintsColumnChartSeries.fill = ev.target.fill;
                categoryWiseComplaintsColumnChartSeries.reinit();

                // Update labels
                centerLabel1.text = categoryWiseComplaintsPieChart
                    .numberFormatter
                    .format(ev.target
                        .dataItem
                        .values.value.percent, "#.'%'");
                centerLabel1.fill = ev.target.fill;

                centerLabel2.text = ev.target.dataItem.category;
            }
        });


    // Create categoryWiseComplaintsColumnChart instance
    var categoryWiseComplaintsColumnChart = categoryWiseComplaintsChart.createChild(
        am4charts.XYChart);

    // Create axes
    var categoryWiseComplaintsColumnChartCategoryAxis =
        categoryWiseComplaintsColumnChart.yAxes.push(new am4charts
            .CategoryAxis());
    categoryWiseComplaintsColumnChartCategoryAxis
        .dataFields.category = "category";
    categoryWiseComplaintsColumnChartCategoryAxis
        .renderer.grid.template.location =
        0;
    categoryWiseComplaintsColumnChartCategoryAxis.renderer.inversed = true;


    categoryWiseComplaintsColumnChartCategoryAxis.renderer.minGridDistance = 20;
    const categoryWiseComplaintsColumnChartCategoryAxisLabelTemplate =
        categoryWiseComplaintsColumnChartCategoryAxis.renderer.labels
        .template;
    categoryWiseComplaintsColumnChartCategoryAxisLabelTemplate
        .fontSize =
        12;
    categoryWiseComplaintsColumnChartCategoryAxis.renderer.labels.template
        .truncate =
        false;
    categoryWiseComplaintsColumnChartCategoryAxis.renderer.labels.template
        .maxWidth =
        150;

    var categoryWiseComplaintsColumnChartValueAxis =
        categoryWiseComplaintsColumnChart
        .xAxes.push(new am4charts
            .ValueAxis());


    // Create series
    var categoryWiseComplaintsColumnChartSeries = categoryWiseComplaintsColumnChart
        .series
        .push(new am4charts
            .ColumnSeries());
    categoryWiseComplaintsColumnChartSeries.dataFields
        .valueX = "value";
    categoryWiseComplaintsColumnChartSeries.dataFields
        .categoryY = "category";
    categoryWiseComplaintsColumnChartSeries.columns
        .template.strokeWidth = 0;
    categoryWiseComplaintsColumnChartSeries.columns
        .template.propertyFields.fill =
        "color";
    categoryWiseComplaintsColumnChartSeries.columns.template.tooltipText =
        " {categoryY} has [bold]{valueX}[/] Complaints";
    categoryWiseComplaintsColumnChartSeries.tooltip.label.fontSize = 12;
    categoryWiseComplaintsColumnChartSeries.tooltip.label.maxWidth = 150;


    categoryWiseComplaintsColumnChartSeries.tooltip.label.wrap = true;
    categoryWiseComplaintsColumnChartSeries.tooltip.keepTargetHover = true;

    // categoryWiseComplaintsColumnChartSeries
    //     .tooltip = false;

    var categoryWiseComplaintsColumnChartvalueLabel =
        categoryWiseComplaintsColumnChartSeries.columns.template
        .createChild(am4core.Label);
    categoryWiseComplaintsColumnChartvalueLabel
        .text =
        "[bold]{valueX}[/] Complaints";
    categoryWiseComplaintsColumnChartvalueLabel
        .fontSize = 12;
    categoryWiseComplaintsColumnChartvalueLabel.valign =
        "middle";
    categoryWiseComplaintsColumnChartvalueLabel.dx = 10;



    if (window.innerWidth <= 1024) {
        categoryWiseComplaintsChart.layout =
            "vertical";

        categoryWiseComplaintsPieChart.width = am4core
            .percent(50);
        categoryWiseComplaintsPieChart.innerRadius = am4core.percent(
            25);
        categoryWiseComplaintsPieChart.depth = 10

        categoryWiseComplaintsColumnChart.width = am4core
            .percent(100);

        categoryWiseComplaintsColumnChartCategoryAxis.renderer.labels
            .template.disabled = true


        // Set up labels
        var centerLabel1 = categoryWiseComplaintsPieChart.seriesContainer.createChild(
            am4core.Label);
        centerLabel1.text = "";
        centerLabel1.isMeasured = false;
        centerLabel1.x = 80;
        centerLabel1.y = -40;
        centerLabel1.fontSize = 35;
        centerLabel1.fontWeight =
            600;

        var centerLabel2 = categoryWiseComplaintsPieChart.seriesContainer.createChild(
            am4core.Label);
        centerLabel2.text = "";
        // centerLabel2 = am4core.width = am4core.percent(50);
        centerLabel2.maxWidth = 100;


        centerLabel2.wrap = true;
        centerLabel2.fontSize = 12;
        centerLabel2.align = "center";
        centerLabel2.isMeasured = false;
        centerLabel2.x = 80;
        centerLabel2.y = 0;
    }

}

function getCountData(filterValue, userId = null, customStartDate = null, customEndDate = null, usertype = null) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".loader").show();
    $.ajax({
        url: "{{route('get.count.data')}}",
        dataType: 'json',
        method: 'post',
        data: {
            filterValue: filterValue,
            userId: userId,
            customStartDate: customStartDate,
            customEndDate: customEndDate,
            usertype: usertype
        },
        success: function(result) {
            $('#total-users').text(JSON.stringify(result.users));
            $('#total-complainants').text(JSON.stringify(result.complainants));
            $('#total-complaints').text(JSON.stringify(result.complaints));
            $('#total-registered').text(JSON.stringify(result.Registered));
            $('#total-inprocess').text(JSON.stringify(result.Inprocess));
            $('#total-onhold').text(JSON.stringify(result.Hold));
            $('#total-resolved').text(JSON.stringify(result.Resolved));
            $('#total-closed').text(JSON.stringify(result.Closed));

            // var mnaComplaints = result.mnaCount;
            // var mpaComplaintsCount = result.mpaComplaintsCount;
            var categoryComplaintCount = result.categoryComplaintCount;
            var complaintStatus = result.complaintStatus;

            var defaultAvatarImage = '/assets/images/default/profile-large.jpg';

            var hasRole = result.hasRole.toString().toLowerCase();
            //mna mpa role
            if (hasRole == "mpa" || hasRole == "mna") {
                var resultType = hasRole + "ComplaintsCount";
                complaintCountsMnaMpa = {
                    [hasRole]: result[resultType],
                }
                if ((complaintCountsMnaMpa && complaintCountsMnaMpa[hasRole] && complaintCountsMnaMpa[
                        hasRole].length)) {
                    $("#personProfileImage").attr("src", complaintCountsMnaMpa[hasRole][0].image != null ?
                        complaintCountsMnaMpa[hasRole][0].image : defaultAvatarImage);
                } else {
                    $("#personProfileImage").attr("src", defaultAvatarImage);
                }
            }
            //admin role
            else {
                // hasRole = 'mnampa';
                const mergedArray = [...result.mnaComplaintsCount, ...result.mpaComplaintsCount];
                complaintCountsMnaMpa = {
                    [hasRole]: mergedArray,
                }
                // if ((complaintCountsMnaMpa && complaintCountsMnaMpa[hasRole] && complaintCountsMnaMpa[hasRole].length)) {

                //     $("#personProfileImage").attr("src", complaintCountsMnaMpa[hasRole][0].image != null ?
                //         complaintCountsMnaMpa[hasRole][0].image : defaultAvatarImage);
                // }
            }

            //if any value selected from mna mpa dropdown
            if ($(".filters-by-mna").val() !== null && $(".filters-by-mna").val() !== '' && typeof $(
                    ".filters-by-mna").val() !== 'undefined') {

                if ((complaintCountsMnaMpa && complaintCountsMnaMpa[hasRole] && complaintCountsMnaMpa[
                        hasRole].length)) {

                    $("#personProfileImage").attr("src", complaintCountsMnaMpa[hasRole][0].image != null ?
                        complaintCountsMnaMpa[hasRole][0].image : defaultAvatarImage);
                } else {
                    $("#personProfileImage").attr("src", defaultAvatarImage);
                }


            } else if ($(".filters-by-mpa").val() !== null && $(".filters-by-mpa").val() !== '' && typeof $(
                    ".filters-by-mna").val() !== 'undefined') {
                // Check if filters-by-mpa has a value if mna is null
                if ((complaintCountsMnaMpa && complaintCountsMnaMpa[hasRole] && complaintCountsMnaMpa[
                        hasRole].length)) {

                    $("#personProfileImage").attr("src", complaintCountsMnaMpa[hasRole][0].image != null ?
                        complaintCountsMnaMpa[hasRole][0].image : defaultAvatarImage);
                } else {
                    $("#personProfileImage").attr("src", defaultAvatarImage);
                }
            }


            // console.log(hasRole);
            // console.log(complaintCountsMnaMpa);
            am4core.ready(function() {

                // Chart Themes begin
                am4core.useTheme(am4themes_animated);
                // Chart Themes end

                // ################################################### complaintsPieChart CODE STARTS FROM HERE ################################################### //
                generatecomplaintsPieChart(complaintStatus);
                // ################################################### complaintsPieChart CODE ENDS HERE ################################################### //

                // ################################################### complaintChartMnaMpaWise CODE START FROM HERE ################################################### //
                generateMnaMpaComplaintsChart(complaintCountsMnaMpa[hasRole]);
                // ################################################### complaintChartMnaMpaWise CODE ENDS HERE ################################################### //


                // ################################################### categoryWiseComplaintsChart CODE STARTS FROM HERE ################################################### //
                generateCategoryWiseChart(categoryComplaintCount);
                // ################################################### categoryWiseComplaintsChart CODE ENDS HERE ################################################### //


                $(".loader").hide();

            });

        },
        error: function(data, textStatus, errorThrown) {
            $(".loader").hide();
            console.log(JSON.stringify(data));
        }
    });
}


$("#showDateFilterBox").click(function() {
    $("#dateFilterBox").toggle();
});

$(document).on('click', '.filters', function() {

    $('.filters').removeClass('btn-primary');
    $('.filters').addClass('btn-outline-primary');

    $(this).addClass('btn-primary');
    $(this).removeClass('btn-outline-primary');

    let filterValue = $(this).attr("data-value");

    if ($(".filters-by-mna").val() !== null && $(".filters-by-mna").val() !== '' && typeof $(".filters-by-mna")
        .val() !== 'undefined') {
        selectedUser = $(".filters-by-mna").val();
    } else if ($(".filters-by-mpa").val() !== null && $(".filters-by-mpa").val() !== '' && typeof $(
            ".filters-by-mpa").val() !== 'undefined') {
        // Check if filters-by-mpa has a value if mna is null
        selectedUser = $(".filters-by-mpa").val();
    } else {
        // Set null if both are null
        selectedUser = null;
    }

    getCountData(filterValue, selectedUser);

});


$(document).on('change', '.filters-by-mna', function() {

    let userId = $(".filters-by-mna").val();
    $(".filters-by-mpa").prop("selectedIndex", 0);
    getCountData('', userId, '', '', 'user_id');

});


$(document).on('change', '.filters-by-mpa', function() {

    let userId = $(".filters-by-mpa").val();
    $(".filters-by-mna").prop("selectedIndex", 0);
    getCountData('', userId, '', '', 'mpa_id');

});

$(document).on('click', '.custom-date-search', function() {

    let customStartDate = $(".start-date").val();
    let customEndDate = $(".end-date").val();
    $(".custom-date-search").prop("selectedIndex", 0);
    getCountData('', '', customStartDate, customEndDate);

});

getCountData('');


$(document).on('click', '.widgetUrl', function() {

    var filterValue = '';

    let DataUrl = $(this).attr("data-url");

    var firstBtnPrimary = $(".filters.btn-primary").first();
    if (firstBtnPrimary.length > 0) {
        var filterValue = firstBtnPrimary.data("value");
    }

    if (DataUrl.indexOf("?") === -1) {
        DataUrl += "?dashboard_filter=" + filterValue;
    } else {
        DataUrl += "&dashboard_filter=" + filterValue;
    }

    window.location.href = DataUrl;

});


$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

@endsection