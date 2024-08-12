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
            class="col-xxl-6 col-xl-8 col-lg-8 col-lg-8 col-md-12 order-xxl-3 order-xl-2 order-lg-2 order-2 status-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3 bg-theme-yellow text-theme-dark">
                    <h6 class="mb-0 fw-bold">Complaints Status Report</h6>
                </div>
                <div class="chart-box">
                    <div class="complaintsPieChart" id="complaintsPieChart"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 col-xl-8 col-lg-8 col-lg-8 col-md-12 order-xxl-3 order-xl-2 order-lg-2 order-2 status-chart mb-3">
            <div class="chart-section">
                <div class=" chart-section-heading my-3 bg-theme-yellow text-theme-dark">
                    <h6 class="mb-0 fw-bold">Category Wise Complaints Report</h6>
                </div>
                <div class="chart-box">
                    <div class="categoryWiseComplaintsChart" id="categoryWiseComplaintsChart"></div>
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
    height: 350px;
}
</style>

<style>
#categoryWiseComplaintsChart {
    width: 100%;
    height: 500px;
}
</style>

<script>
function generatecategoryWiseComplaintsChart(dataset) {
    var categoryWiseComplaintsChart = am4core.create("categoryWiseComplaintsChart", am4charts.XYChart);
    categoryWiseComplaintsChart.hiddenState.properties.opacity = 0;

    // categoryWiseComplaintsChart.scrollbarY = new am4core.Scrollbar();
    // categoryWiseComplaintsChart.scrollbarX = new am4core.Scrollbar();

    if (dataset.length > 0) {
        categoryWiseComplaintsChart.data = dataset;
    } else {
        categoryWiseComplaintsChart.hidden = true;
        $("#categoryWiseComplaintsChart").html(
            '<div class="no-data-found">No Complaints found!</div>');
    }

    // categoryWiseComplaintsChart.paddingRight = 40;

    var categoryAxis = categoryWiseComplaintsChart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.grid.template.strokeOpacity = 0;
    categoryAxis.renderer.minGridDistance = 0;
    categoryAxis.renderer.labels.template.dy = 35;
    categoryAxis.renderer.tooltip.dy = 35;

    var valueAxis = categoryWiseComplaintsChart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.inside = true;
    valueAxis.renderer.labels.template.fillOpacity = 0.3;
    valueAxis.renderer.grid.template.strokeOpacity = 0;
    valueAxis.min = 0;
    valueAxis.cursorTooltipEnabled = false;
    valueAxis.renderer.baseGrid.strokeOpacity = 0;

    var series = categoryWiseComplaintsChart.series.push(new am4charts.ColumnSeries);
    series.dataFields.valueY = "steps";
    series.dataFields.categoryX = "name";
    series.tooltipText = "{valueY.value}";
    series.tooltip.pointerOrientation = "vertical";
    series.tooltip.dy = -6;
    series.columnsContainer.zIndex = 100;

    var columnTemplate = series.columns.template;
    columnTemplate.width = am4core.percent(50);
    columnTemplate.maxWidth = 66;
    columnTemplate.column.cornerRadius(60, 60, 10, 10);
    columnTemplate.strokeOpacity = 0;

    series.heatRules.push({
        target: columnTemplate,
        property: "fill",
        dataField: "valueY",
        min: am4core.color("#e5dc36"),
        max: am4core.color("#5faa46")
    });
    series.mainContainer.mask = undefined;

    var cursor = new am4charts.XYCursor();
    categoryWiseComplaintsChart.cursor = cursor;
    cursor.lineX.disabled = true;
    cursor.lineY.disabled = true;
    cursor.behavior = "none";

    var bullet = columnTemplate.createChild(am4charts.CircleBullet);
    bullet.circle.radius = 30;
    bullet.valign = "bottom";
    bullet.align = "center";
    bullet.isMeasured = true;
    bullet.mouseEnabled = false;
    bullet.verticalCenter = "bottom";
    bullet.interactionsEnabled = false;

    var hoverState = bullet.states.create("hover");
    var outlineCircle = bullet.createChild(am4core.Circle);
    outlineCircle.adapter.add("radius", function(radius, target) {
        var circleBullet = target.parent;
        return circleBullet.circle.pixelRadius + 10;
    })

    var image = bullet.createChild(am4core.Image);
    image.width = 60;
    image.height = 60;
    image.horizontalCenter = "middle";
    image.verticalCenter = "middle";
    image.propertyFields.href = "href";

    image.adapter.add("mask", function(mask, target) {
        var circleBullet = target.parent;
        return circleBullet.circle;
    })

    var previousBullet;
    categoryWiseComplaintsChart.cursor.events.on("cursorpositionchanged", function(event) {
        var dataItem = series.tooltipDataItem;

        if (dataItem.column) {
            var bullet = dataItem.column.children.getIndex(1);

            if (previousBullet && previousBullet != bullet) {
                previousBullet.isHover = false;
            }

            if (previousBullet != bullet) {

                var hs = bullet.states.getKey("hover");
                hs.properties.dy = -bullet.parent.pixelHeight + 30;
                bullet.isHover = true;

                previousBullet = bullet;
            }
        }
    })


}

function generatecomplaintsPieChart(dataset) {

    // console.log(dataset);
    // Create Complain Pie Chart Instance
    var complaintsPieChart = am4core.create("complaintsPieChart", am4charts
        .PieChart);


    complaintsPieChart.legend = new am4charts.Legend();
    complaintsPieChart.legend.position = "right";
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




// dummy data for categorywise complaints
// categoryWisComplaintseData = [{
//     "name": "Dry Cleaing",
//     "steps": 45688,
//     "href": "https://www.amcharts.com/wp-content/uploads/2019/04/monica.jpg"
// }, {
//     "name": "Wash Only",
//     "steps": 35781,
//     "href": "https://www.amcharts.com/wp-content/uploads/2019/04/joey.jpg"
// }, {
//     "name": "Iron Only",
//     "steps": 25464,
//     "href": "https://www.amcharts.com/wp-content/uploads/2019/04/ross.jpg"
// }, {
//     "name": "Wash & Iron",
//     "steps": 18788,
//     "href": "https://www.amcharts.com/wp-content/uploads/2019/04/phoebe.jpg"
// }];

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

            var categoryWisComplaintseData = result.complaintCountByService;

            //console.log(complaintStatus);

            am4core.ready(function() {

                // Chart Themes begin
                am4core.useTheme(am4themes_animated);
                // Chart Themes end

                // ################################################### complaintsPieChart CODE STARTS FROM HERE ################################################### //
                generatecomplaintsPieChart(complaintStatus);
                // ################################################### complaintsPieChart CODE ENDS HERE ################################################### //



                // ################################################### complaintsPieChart CODE STARTS FROM HERE ################################################### //
                generatecategoryWiseComplaintsChart(categoryWisComplaintseData);
                // ################################################### complaintsPieChart CODE ENDS HERE ################################################### //


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