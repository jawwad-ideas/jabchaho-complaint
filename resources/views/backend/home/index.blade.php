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
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">All</small>
            <small type="button" data-value="day"
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">Day</small>
            <small type="button" data-value="week"
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">Week</small>
            <small type="button" data-value="month"
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">Month</small>
            <small type="button" data-value="3-months"
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">3
                Months</small>
            <small type="button" id="showDateFilterBox"
                class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold btn-outline-dark">
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
                            <h6 class="mb-0 text-dark">under Investigation</h6>
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

function generatecomplaintsPieChart(dataset) {

// console.log(dataset);
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
            sum += dataset[key]['count'];
        }
    }


    if (sum > 0) {

        let int = 0;

        const colors = ["#1ccab8", "#a01497", "#e64b55", "#508ff4", "#ffbf43", "#FA8072"];
        for (const key in dataset) {
            complaintsPieChart.data.push({
                category: dataset[key].name,
                value: dataset[key].count,
                color: colors[int++],
                statusId :key
                
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
        success: function(result) 
        {
            //result.complaintStatus[0]?result.complaintStatus[0].count:0
            
            $('#total-complaints').text(JSON.stringify(result.complaints));
            $('#duplicate-case').text(JSON.stringify(result.complaintStatus[1]?result.complaintStatus[1].count:0));
            $('#pending-for-approval').text(JSON.stringify(result.complaintStatus[2]?result.complaintStatus[2].count:0));
            $('#pending-at-finance').text(JSON.stringify(result.complaintStatus[3]?result.complaintStatus[3].count:0));
            $('#refunded').text(JSON.stringify(result.complaintStatus[4]?result.complaintStatus[4].count:0));
            $('#under-investigation').text(JSON.stringify(result.complaintStatus[5]?result.complaintStatus[5].count:0));
            $('#voucher-issued').text(JSON.stringify(result.complaintStatus[6]?result.complaintStatus[6].count:0));

            var complaintStatus = result.complaintStatus;

            console.log(complaintStatus);
 
            am4core.ready(function() {

                // Chart Themes begin
                am4core.useTheme(am4themes_animated);
                // Chart Themes end

                // ################################################### complaintsPieChart CODE STARTS FROM HERE ################################################### //
                generatecomplaintsPieChart(complaintStatus);
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