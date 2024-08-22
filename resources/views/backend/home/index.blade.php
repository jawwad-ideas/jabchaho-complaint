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
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Dashboard</h3>
    </div>
    <div class="text-lg-end text-center position-relative">
        <div class="btn-group chart-filter-btns mt-lg-0 mt-4" role="group">
            <small type="button" data-value=""
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold">All</small>
            <small type="button" data-value="day"
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold">Day</small>
            <small type="button" data-value="week"
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold">Week</small>
            <small type="button" data-value="month"
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold">Month</small>
            <small type="button" data-value="3-months"
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold">3
                Months</small>
            <small type="button" id="showDateFilterBox"
                class="btn btn-sm rounded bg-theme-yellow me-2  border-0 text-theme-dark fw-bold">
                Custom</small>
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
                        class="btn btn-sm rounded bg-theme-yellow mt-2 border-0 text-dark fw-bold d-inline-flex align-items-center custom-date-search"
                        data-value="custom-date-search">
                        <i class="fa fa-solid fa fa-search me-2"></i> Search
                    </button>
                    <a href="{{ route('home.index') }}"><small type="button"
                            class="btn btn-sm rounded bg-theme-yellow mt-2 border-0 text-dark fw-bold d-inline-flex align-items-center "><i
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
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-circle-info fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="total-complaints"></h1>
                            <h6 class="mb-0 text-dark">Total Complaints</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-clipboard-check fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="duplicate-case"></h1>
                            <h6 class="mb-0 text-dark">Duplicate Case</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-user-check fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="pending-for-approval"></h1>
                            <h6 class="mb-0 text-dark">Pending for approval</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-spinner fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="pending-at-finance"></h1>

                            <h6 class="mb-0 text-dark">Pending at Finance</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-regular fa-hourglass fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="refunded"></h1>
                            <h6 class="mb-0 text-dark">Refunded</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-check-double fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="under-investigation"></h1>
                            <h6 class="mb-0 text-dark">Under Investigation</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-sticky-note fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="voucher-issued"></h1>
                            <h6 class="mb-0 text-dark">Voucher Issued</h6>
                        </div>
                    </div>
                </div>

                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-check-double fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="hold"></h1>
                            <h6 class="mb-0 text-dark">Hold</h6>
                        </div>
                    </div>
                </div>

                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-check-double fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="resolved"></h1>
                            <h6 class="mb-0 text-dark">Resolved</h6>
                        </div>
                    </div>
                </div>

                <div class="stats-card border bg-theme-yellow px-2 w-100">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-check-double fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="closed"></h1>
                            <h6 class="mb-0 text-dark">Closed</h6>
                        </div>
                    </div>
                </div>


            </div>

        </div>


        <div
            class="col-xxl-4 col-xl-8 col-lg-8 col-lg-8 col-md-12 order-xxl-3 order-xl-2 order-lg-2 order-2 status-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3 bg-theme-yellow text-theme-dark">
                    <h6 class="mb-0 fw-bold">Complaints Status Report</h6>
                </div>
                <div class="chart-box">
                    <div class="complaintsPieChart" id="complaintsPieChart"></div>
                </div>
            </div>
        </div>

        <div
            class="col-xxl-6 col-xl-8 col-lg-8 col-lg-8 col-md-12 order-xxl-3 order-xl-2 order-lg-2 order-2 status-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3 bg-theme-yellow text-theme-dark">
                    <h6 class="mb-0 fw-bold">Service Wise Complaints Report</h6>
                </div>
                <div class="chart-box">
                    <div class="serviceWiseComplaintsChart" id="serviceWiseComplaintsChart"></div>
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
#complaintsPieChart {
    width: 100%;
    height: 500px;
}
</style>

<style>
#serviceWiseComplaintsChart {
    width: 100%;
    height: 500px;
}
</style>

<script>
function generateServiceWiseComplaintsChart(dataset) {
    console.log(dataset);
    var serviceWiseComplaintsChart = am4core.create("serviceWiseComplaintsChart", am4charts.XYChart);
    serviceWiseComplaintsChart.logo.disabled = true;
    if (dataset.length > 0) {
        serviceWiseComplaintsChart.data = dataset;
    } else {
        serviceWiseComplaintsChart.hidden = true;
        $("#serviceWiseComplaintsChart").html(
            '<div class="no-data-found">No Complaints found!</div>');
    }

    serviceWiseComplaintsChart.scrollbarX = new am4core.Scrollbar();

    // chart bg color
    serviceWiseComplaintsChart.plotContainer.background.fill = am4core.color(
        "#f5f5f5");
    serviceWiseComplaintsChart.plotContainer.background.fillOpacity = 0.5;






    // Create axes
    // category axis
    var serviceWiseComplaintsChartCategoryAxis = serviceWiseComplaintsChart.xAxes.push(new am4charts.CategoryAxis());
    serviceWiseComplaintsChartCategoryAxis.dataFields.category = "name";
    serviceWiseComplaintsChartCategoryAxis.renderer.grid.template.location = 0;
    serviceWiseComplaintsChartCategoryAxis.renderer.minGridDistance = 10;
    serviceWiseComplaintsChartCategoryAxis.renderer.labels.template.horizontalCenter = "right";
    serviceWiseComplaintsChartCategoryAxis.renderer.labels.template.verticalCenter = "middle";
    serviceWiseComplaintsChartCategoryAxis.renderer.labels.template.rotation = 270;
    serviceWiseComplaintsChartCategoryAxis.tooltip.disabled = true;
    serviceWiseComplaintsChartCategoryAxis.renderer.minHeight = 110;
    // serviceWiseComplaintsChartCategoryAxis.renderer.grid.template.disabled = true; // Disable background grid

    // value axis
    var serviceWiseComplaintsChartValueAxis = serviceWiseComplaintsChart.yAxes.push(new am4charts.ValueAxis());
    serviceWiseComplaintsChartValueAxis.renderer.minWidth = 50;
    // serviceWiseComplaintsChartValueAxis.renderer.grid.template.disabled = true; // Disable background grid
    serviceWiseComplaintsChartValueAxis.renderer.labels.template.disabled = true;
    serviceWiseComplaintsChartValueAxis.min = 0;


    // Create series
    var serviceWiseComplaintsChartSeries = serviceWiseComplaintsChart.series.push(new am4charts.ColumnSeries());
    serviceWiseComplaintsChartSeries.sequencedInterpolation = true;
    serviceWiseComplaintsChartSeries.dataFields.valueY = "count";
    serviceWiseComplaintsChartSeries.dataFields.categoryX = "name";
    serviceWiseComplaintsChartSeries.tooltipText = "{categoryX}: {valueY} Complaints";
    serviceWiseComplaintsChartSeries.columns.template.strokeWidth = 0;

    serviceWiseComplaintsChartSeries.tooltip.pointerOrientation = "vertical";

    serviceWiseComplaintsChartSeries.columns.template.column.cornerRadiusTopLeft = 10;
    serviceWiseComplaintsChartSeries.columns.template.column.cornerRadiusTopRight = 10;
    serviceWiseComplaintsChartSeries.columns.template.column.fillOpacity = 1;

    // on hover, make corner radiuses bigger
    var serviceWiseComplaintsChartHoverState = serviceWiseComplaintsChartSeries.columns.template.column.states.create(
        "hover");
    // serviceWiseComplaintsChartHoverState.properties.cornerRadiusTopLeft = 0;
    // serviceWiseComplaintsChartHoverState.properties.cornerRadiusTopRight = 0;
    serviceWiseComplaintsChartHoverState.properties.fillOpacity = 0.8;

    const colors = ["#4b49ac", "#fa9fab", "#fe4747", "#508ff4", "#ffbf43", "#ab47bc"];

    serviceWiseComplaintsChartSeries.columns.template.propertyFields.fill = "color";

    serviceWiseComplaintsChart.data.forEach((item, index) => {
        item.color = colors[index % colors.length];
    });

    // Cursor
    serviceWiseComplaintsChart.cursor = new am4charts.XYCursor();
    // Disable the hover ruler (vertical line)
    serviceWiseComplaintsChart.cursor.lineX.disabled = true;

    // Disable the hover ruler (horizontal line)
    serviceWiseComplaintsChart.cursor.lineY.disabled = true;

    // // Add bullets
    var serviceWiseComplaintsChartBullet = serviceWiseComplaintsChartSeries.bullets.push(new am4charts.Bullet());
    var serviceWiseComplaintsChartImage = serviceWiseComplaintsChartBullet.createChild(am4core.Image);
    serviceWiseComplaintsChartImage.horizontalCenter = "middle";
    serviceWiseComplaintsChartImage.verticalCenter = "bottom";
    serviceWiseComplaintsChartImage.dy = 20;
    serviceWiseComplaintsChartImage.y = am4core.percent(100);
    serviceWiseComplaintsChartImage.propertyFields.href = "image";
    serviceWiseComplaintsChartImage.tooltipText = serviceWiseComplaintsChartSeries.columns.template.tooltipText;
    serviceWiseComplaintsChartImage.filters.push(new am4core.DropShadowFilter());

    serviceWiseComplaintsChartImage.width = 50;
    serviceWiseComplaintsChartImage.height = 50;

    serviceWiseComplaintsChartValueAxis.tooltip.disabled = true;

}


function generatecomplaintsPieChart(dataset) {

    // console.log(dataset);
    // Create Complain Pie Chart Instance
    var complaintsPieChart = am4core.create("complaintsPieChart", am4charts
        .PieChart);


    complaintsPieChart.legend = new am4charts.Legend();
    complaintsPieChart.legend.position = "bottom";
    complaintsPieChart.legend.valign = "middle";
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
            sum += dataset[key]['count'];
        }
    }


    if (sum > 0) {

        let int = 0;

        const colors = ["#4b49ac", "#fa9fab", "#fe4747", "#508ff4", "#ffbf43", "#ab47bc"];
        for (const key in dataset) {
            complaintsPieChart.data.push({
                category: dataset[key].name,
                value: dataset[key].count,
                color: colors[int++],
                statusId: key

            });
            if (int == colors.length) {
                int = 0;
            }
        }

        console.log(complaintsPieChart.data);

    } else {
        complaintsPieChart.hidden = true;
        $("#complaintsPieChart").html(
            '<div class="no-data-found">No Complaints found!</div>');
    }


    // Add and configure Series
    var complaintsPieChartSeries = complaintsPieChart.series.push(new am4charts
        .PieSeries());


    complaintsPieChartSeries.dataFields.value = "value";
    complaintsPieChartSeries.dataFields.statusId = "statusId";
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
    complaintsPieChart.radius = am4core.percent(80);
    complaintsPieChart.innerRadius = am4core.percent(10);
    complaintsPieChart.logo
        .disabled = true;
    complaintsPieChart.hiddenState.properties.radius = am4core
        .percent(0);


    // Set the Slice Click Event and set the url as per status

    complaintsPieChartSeries.slices.template.events.on("hit", function(event) {
        let statusCategory = event.target.dataItem.category;
        let statusValue = event.target.dataItem.value;
        let statusId = event.target.dataItem.statusId;

        // alert("Clicked on: " + statusCategory + " with value: " + statusValue);

        // let statusId = 1;
        let ComplaintsFilterUrl = "{{ route('complaints.index') }}?complaint_status_id=" + statusId;
        window.location.href = ComplaintsFilterUrl;
    });

}


function getCountData(filterValue, customStartDate = null, customEndDate = null) {
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
            customStartDate: customStartDate,
            customEndDate: customEndDate,
        },
        success: function(result) {
            //result.complaintStatus[0]?result.complaintStatus[0].count:0

            $('#total-complaints').text(JSON.stringify(result.complaints));
            $('#duplicate-case').text(JSON.stringify(result.complaintStatus[1] ? result.complaintStatus[1]
                .count : 0));
            $('#pending-for-approval').text(JSON.stringify(result.complaintStatus[2] ? result
                .complaintStatus[2].count : 0));
            $('#pending-at-finance').text(JSON.stringify(result.complaintStatus[3] ? result.complaintStatus[
                3].count : 0));
            $('#refunded').text(JSON.stringify(result.complaintStatus[4] ? result.complaintStatus[4].count :
                0));
            $('#under-investigation').text(JSON.stringify(result.complaintStatus[5] ? result
                .complaintStatus[5].count : 0));
            $('#voucher-issued').text(JSON.stringify(result.complaintStatus[6] ? result.complaintStatus[6]
                .count : 0));
            $('#hold').text(JSON.stringify(result.complaintStatus[7] ? result.complaintStatus[7].count :
                0));
            $('#resolved').text(JSON.stringify(result.complaintStatus[8] ? result.complaintStatus[8].count :
                0));
            $('#closed').text(JSON.stringify(result.complaintStatus[9] ? result.complaintStatus[9].count :
                0));

            var complaintStatus = result.complaintStatus;

            var serviceWisComplaintsData = result.complaintCountByService;

            //console.log(complaintStatus);

            am4core.ready(function() {

                // Chart Themes begin
                am4core.useTheme(am4themes_animated);
                // Chart Themes end

                // ################################################### complaintsPieChart CODE STARTS FROM HERE ################################################### //
                generatecomplaintsPieChart(complaintStatus);
                // ################################################### complaintsPieChart CODE ENDS HERE ################################################### //

                // ################################################### Service Wise Complaints Chart CODE STARTS FROM HERE ################################################### //
                generateServiceWiseComplaintsChart(serviceWisComplaintsData);
                // ################################################### Service Wise Complaints Chart CODE STARTS FROM HERE ################################################### //
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

    $('.filters').removeClass('btn-dark');
    $('.filters').addClass('bg-theme-dark-300');

    $(this).addClass('btn-dark');
    $(this).removeClass('bg-theme-dark-300');

    let filterValue = $(this).attr("data-value");

    getCountData(filterValue);

});




$(document).on('click', '.custom-date-search', function() {

    let customStartDate = $(".start-date").val();
    let customEndDate = $(".end-date").val();
    $(".custom-date-search").prop("selectedIndex", 0);
    getCountData('', customStartDate, customEndDate);

});

getCountData('');
</script>

@endsection