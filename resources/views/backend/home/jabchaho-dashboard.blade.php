@extends('backend.layouts.app-master')

@section('content')
<style>
@media (min-width:1025px) {
    .col-xl-3 {
        width: 20% !important;
    }
}
.m-r-10 {
    margin-right: 10px;
}
.btn-dark {
    color: #FFF;
}
.w-180px {
    width: 180px;
}

.m-b-10.stats-card:last-child {
    margin-bottom: 20px !important;
    margin-right: 0;
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

.pagination {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}
.pagination button {
    margin: 0 5px;
    padding: 5px 10px;
}

tr[data-url] {
  cursor: pointer;
  transition: background-color 0.3s;
}

tr[data-url]:hover {
  background-color: #f0f0f0;
}
</style>

<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Dashboard</h3>
    </div>
    <div class="text-lg-end text-center position-relative">
        <div class="btn-group chart-filter-btns mt-lg-0 mt-4" role="group">
            <small type="button" data-value=""
                class="btn btn-sm rounded bg-theme-yellow me-2 filters border-0 text-theme-dark fw-bold btn-dark">All</small>
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
        <input type="hidden" id='filtersValue' />
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
                    <a href="{{ route('jabchaho-dashboard.index') }}"><small type="button"
                            class="btn btn-sm rounded bg-theme-yellow mt-2 border-0 text-dark fw-bold d-inline-flex align-items-center "><i
                                class="fa fa-solid fa-arrows-rotate me-2"></i> Clear</small></a>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="page-content bg-white p-5 px-2">

    <div class="row ">

        <div
            class="col-xxl-2 col-xl-4 col-lg-4 col-md-12 col-sm-12 order-xxl-1 order-xl-1 order-xl-1 order-lg-1 order-1 count-chart mb-3 w-100">
            <div class="d-flex align-items-center flex-wrap">
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-circle-info fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="total-orders"></h1>
                            <h6 class="mb-0 text-dark">Total Orders</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px" onclick="handleClick('{{ route('orders.index') }}/2')" style="cursor: pointer;">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-clipboard-check fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="completed-orders"></h1>
                            <h6 class="mb-0 text-dark">Completed Order</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0" onclick="handleClick('{{ route('orders.index') }}/1')" style="cursor: pointer;">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-spinner fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="orders-in-process"></h1>
                            <h6 class="mb-0 text-dark">Orders in Process</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-atom fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="before-wash"></h1>

                            <h6 class="mb-0 text-dark">Before Wash Image</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-regular fa-clipboard fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="after-wash"></h1>
                            <h6 class="mb-0 text-dark">After Wash Image</h6>
                        </div>
                    </div>
                </div>


                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-regular fa-envelope-open fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="before-email-count"></h1>
                            <h6 class="mb-0 text-dark">Before Email Count</h6>
                        </div>
                    </div>
                </div>


                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-regular fa-envelope fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="after-email-count"></h1>
                            <h6 class="mb-0 text-dark">After Email Count</h6>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>


    <div class="row mb-3">
        <div class="col-lg-12 d-flex flex-wrap">
            
            <div class="col-sm-3 px-2">
                <input type="text" class="form-control p-2" autocomplete="off" name="name" id="name" value="" placeholder="Name">
            </div>
            <div class="col-sm-3 px-2">
                <input type="text" class="form-control p-2" autocomplete="off" name="telephone" id="telephone" value="" placeholder="Telephone">
            </div>
            <div class="col-sm-3 px-2">
                <input type="text" class="form-control p-2" autocomplete="off" name="order_number" id="order_number"  value="" placeholder="Order Number">
            </div>
            <div class="col-sm-3 px-2 ">
                <select class="form-select p-2" id="location_type" name="location_type">
                    <option value=''>Location</option>
                    @if(!empty(config('constants.laundry_location_type')) )
                        @foreach(config('constants.laundry_location_type') as $key => $option )
                                <option value="{{$key}}">{{$option}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-3 px-2">
                <button type="submit"
                        class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1 custom-order-search"
                        id="consult">
                    <span>Search</span>
                    <i alt="Search" class="fa fa-search"></i>
                </button>
                <a href=""
                    class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                    <span>Clear</span>
                    <i class="fa fa-solid fa-arrows-rotate"></i></a>
            </div>
        </div>
    </div>

    <div class="table-scroll-hr">
        <table class="table table-bordered table-striped table-compact " id="clickableTable">
            <thead>
                <tr>
                    <th scope="col">Order#</th> 
                    <th scope="col">Location</th> 
                    <th scope="col">Name</th>    
                    <th scope="col">Telephone</th>
                    <th scope="col">Before Wash</th>
                    <th scope="col">After Wash</th>      
                    <th scope="col">Total Barcode</th>
                </tr>
            </thead>
            <tbody id="table-body">
                
            </tbody>
        </table>
        <div class="pagination" id="pagination"></div>
        
    </div>
 


</div>







<script>
    let currentPage = 1; // Start with the first page
    const itemsPerPage = {{config('constants.per_page')}}; // Items per page
// Function to render pagination buttons


function renderPagination(totalRecords, currentPage) {
    const totalPages = Math.ceil(totalRecords / itemsPerPage);

    let customStartDate = $(".start-date").val();
    let customEndDate = $(".end-date").val();
    let name = $("#name").val();
    let telephone = $("#telephone").val();
    let orderNumber = $("#order_number").val();
    let filterValue = $('#filtersValue').val();
    let locationType = $("#location_type").val();
    

    // Clear the pagination container
    $('#pagination').empty();

    // Add "Previous" button
    if (currentPage > 1) {
        $('#pagination').append(`<button data-page="${currentPage - 1}" class="pagination-btn">Previous</button>`);
    }

    // Add page number buttons with ellipses
    const maxButtons = 5; // Max number of page buttons to show
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    // If there are too many pages, adjust start and end dynamically
    if (totalPages > maxButtons) {
        if (currentPage <= 3) {
            endPage = Math.min(maxButtons, totalPages);
        } else if (currentPage > totalPages - 3) {
            startPage = Math.max(1, totalPages - (maxButtons - 1));
        }
    }

    // Add ellipsis before start if necessary
    if (startPage > 1) {
        $('#pagination').append(`<button data-page="1" class="pagination-btn">1</button>`);
        if (startPage > 2) {
            $('#pagination').append('<span class="pagination-ellipsis">...</span>');
        }
    }

    // Add numbered buttons
    for (let i = startPage; i <= endPage; i++) {
        if(endPage>1)
        {
            const button = `<button data-page="${i}" class="pagination-btn" ${
                i === currentPage ? 'style="font-weight: bolder;color: #FFF;background-color: #282828;"' : ''
            }>${i}</button>`;
            $('#pagination').append(button);
        }
    }

    // Add ellipsis after end if necessary
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            $('#pagination').append('<span class="pagination-ellipsis">...</span>');
        }
        $('#pagination').append(`<button data-page="${totalPages}" class="pagination-btn">${totalPages}</button>`);
    }

    // Add "Next" button
    if (currentPage < totalPages) {
        $('#pagination').append(`<button data-page="${currentPage + 1}" class="pagination-btn">Next</button>`);
    }

    // Attach click event to pagination buttons
    $('.pagination-btn').on('click', function() {
        const page = parseInt($(this).attr('data-page'));

        let objParams = {
            'page': page,
            'filterValue': filterValue,
            'customStartDate': customStartDate,
            'customEndDate': customEndDate,
            'name': name,
            'telephone': telephone,
            'orderNumber': orderNumber,
            'locationType':locationType
        };

        getCountData(objParams);
    });
}


function getCountData(objParams = null) 
{
    var filterValue = customStartDate=customEndDate = name = telephone = orderNumber = locationType ='';
    var page =1;

    if(objParams)
    {
        if (objParams.filterValue != undefined || objParams.filterValue != null) 
        {
            filterValue = objParams.filterValue;
        }  
        
        if (objParams.customStartDate != undefined || objParams.customStartDate != null) 
        {
            customStartDate = objParams.customStartDate;
        } 

        if (objParams.customEndDate != undefined || objParams.customEndDate != null) 
        {
            customEndDate = objParams.customEndDate;
        } 

        if (objParams.name != undefined || objParams.name != null) 
        {
            name = objParams.name;
        } 

        if (objParams.telephone != undefined || objParams.telephone != null) 
        {
            telephone = objParams.telephone;
        } 

        if (objParams.orderNumber != undefined || objParams.orderNumber != null) 
        {
            orderNumber = objParams.orderNumber;
        } 

        if (objParams.page != undefined || objParams.page != null) 
        {
            page = objParams.page;
        } 

        if (objParams.locationType != undefined || objParams.locationType != null) 
        {
            locationType = objParams.locationType;
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".loader").show();
    $.ajax({
        url: "{{route('get.jabchaho.dashboard.count.data')}}?page="+page+"&limit="+itemsPerPage,
        dataType: 'json',
        method: 'post',
        data: 
        {
            filterValue: filterValue,
            customStartDate: customStartDate,
            customEndDate: customEndDate,
            name:name,
            telephone:telephone,
            orderNumber:orderNumber,
            locationType:locationType
        },
        success: function(result) 
        {
            $('#table-body').empty();
            
            $('#total-orders').text(JSON.stringify(result.orders.total?result.orders.total:0));
            
            $('#orders-in-process').text(JSON.stringify(result.orders.process?result.orders.process:0));
            
            $('#completed-orders').text(JSON.stringify(result.orders.completed?result.orders.completed:0));

            $('#before-email-count').text(JSON.stringify(result.orders.before_email?result.orders.before_email:0));
            $('#after-email-count').text(JSON.stringify(result.orders.after_email?result.orders.after_email:0));
           
            $('#before-wash').text(JSON.stringify(result.orderItemImage.before_wash?result.orderItemImage.before_wash:0));
            $('#after-wash').text(JSON.stringify(result.orderItemImage.after_wash?result.orderItemImage.after_wash:0));

            var orderWithItemTotalCount =0;
            if(result.ordersWithItemsCount  && Object.keys(result.ordersWithItemsCount).length > 0)
            {
                orderWithItemTotalCount = result.orderWithItemTotalCount;
                
                result.ordersWithItemsCount.forEach(function(item) {
                
                    var locationtype = '';

                    if(item.location_type)
                    { 
                        locationtype = "{{config('constants.laundry_location_type.store')}}";
                    }
                    else
                    {
                        locationtype = "{{config('constants.laundry_location_type.facility')}}";
                    }
                
                    var row = "<tr data-url='/orders/" + item.id + "/edit'>" +
                    "<td><a target='_blank' href='/orders/" + item.id + "/edit'>" + item.order_id + '</a></td>' +
                    '<td>' + locationtype + '</td>' +
                    '<td>' + item.customer_name + '</td>' +
                    '<td>' + item.telephone + '</td>' +
                    '<td>' + item.before_count + '</td>' +
                    '<td>' + item.after_count + '</td>' +
                    '<td>' + item.item_count + '</td>' +
                    '</tr>';
                
                // Append the row to tbody
                $('#table-body').append(row);
            });
            }
            else
            {
                // Append the row to tbody
                $('#table-body').append('<tr><td colspan="7" class="text-center" >No Record Found</td></tr>');
            }

            // Render pagination controls
            renderPagination(orderWithItemTotalCount, page); //result.totalRecords
            
            
            $(".loader").hide();
            

        },
        error: function(data, textStatus, errorThrown) {
            $(".loader").hide();
            console.log(JSON.stringify(data));
        }
    });
}


$("#showDateFilterBox").click(function() {
    $("#dateFilterBox").toggle();
    $('#filtersValue').val('');
    $(this).addClass('btn-dark');
    $('.filters').removeClass('btn-dark');
    $('.filters').addClass('bg-theme-dark-300');
});

$(document).on('click', '.filters', function() {

    $('.filters').removeClass('btn-dark');
    $('.filters').addClass('bg-theme-dark-300');

    $(this).addClass('btn-dark');
    $(this).removeClass('bg-theme-dark-300');

    $('#showDateFilterBox').removeClass('btn-dark');
    $('#showDateFilterBox').addClass('bg-theme-dark-300');

    $('#filtersValue').val($(this).attr("data-value"));

    let name            = $("#name").val();
    let telephone       = $("#telephone").val();
    let orderNumber     = $("#order_number").val();
    let locationType = $("#location_type").val();
    let filterValue     = $(this).attr("data-value");

    let objParams ={'filterValue' : filterValue,'name':name, 'telephone':telephone, 'orderNumber':orderNumber,'locationType':locationType  };

    getCountData(objParams);

});




$(document).on('click', '.custom-date-search', function() {

    let customStartDate = $(".start-date").val();
    let customEndDate = $(".end-date").val();
    let name = $("#name").val();
    let telephone = $("#telephone").val();
    let orderNumber = $("#order_number").val();
    let locationType = $("#location_type").val();
    
    $(".custom-date-search").prop("selectedIndex", 0);
    
    let objParams ={'customStartDate' : customStartDate, 'customEndDate': customEndDate,'name':name, 'telephone':telephone, 'orderNumber':orderNumber,'locationType':locationType };
    
    getCountData(objParams);

});


$(document).on('click', '.custom-order-search', function() {

let customStartDate = $(".start-date").val();
let customEndDate = $(".end-date").val();
let name = $("#name").val();
let telephone = $("#telephone").val();
let orderNumber = $("#order_number").val();
let locationType = $("#location_type").val();
let filterValue = $('#filtersValue').val();

$(".custom-date-search").prop("selectedIndex", 0);

let objParams ={'filterValue' : filterValue,'customStartDate' : customStartDate, 'customEndDate': customEndDate,'name':name, 'telephone':telephone, 'orderNumber':orderNumber,'locationType':locationType };

getCountData(objParams);

});


document.getElementById('clickableTable').addEventListener('click', function(event) {
const row = event.target.closest('tr'); // Get the clicked <tr>
if (row && row.dataset.url) {
    //window.location.href = row.dataset.url;
    window.open(row.dataset.url, '_blank');
}
});

function handleClick(url) {
    window.location.href = url;
}


getCountData(null);
renderPagination()
</script>

@endsection