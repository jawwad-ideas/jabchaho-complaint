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
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa fa-bank fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="store-orders"></h1>
                            <h6 class="mb-0 text-dark">Store Orders</h6>
                        </div>
                    </div>
                </div>
                <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px">
                    <div
                        class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                        <div class="stats-icon">
                            <i class="fa fa-solid fa-shopping-cart fa-2x text-dark mb-2"></i>
                        </div>
                        <div class="stats-count">
                            <h1 class="fw-bold text-dark" id="facility-orders"></h1>
                            <h6 class="mb-0 text-dark">Facility Orders</h6>
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
              
                <h3>Issues Type</h3>
                <div class="d-flex align-items-center flex-wrap">
                   

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=1')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-paint-brush fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="color_fading"></h1>
                                <h6 class="mb-0 text-dark">Color Fading</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=2')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-fire fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="iron_shine"></h1>
                                <h6 class="mb-0 text-dark">Iron Shine</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=3')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-fire fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="burn"></h1>
                                <h6 class="mb-0 text-dark">Burn</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=4')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-compress fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="shrinkage"></h1>
                                <h6 class="mb-0 text-dark">Shrinkage</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=5')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-cut fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="tears_and_torn"></h1>
                                <h6 class="mb-0 text-dark">Tears And Torn</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=6')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-circle fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="holes"></h1>
                                <h6 class="mb-0 text-dark">Holes</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=7')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-circle fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="missed_button"></h1>
                                <h6 class="mb-0 text-dark">Missed Button</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=8')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-scissors fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="stitching"></h1>
                                <h6 class="mb-0 text-dark">Stitching</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=9')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-cogs fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="embroidery"></h1>
                                <h6 class="mb-0 text-dark">Embroidery</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=10')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-exclamation-circle fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="missed_logo"></h1>
                                <h6 class="mb-0 text-dark">Missed Logo</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=11')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-leaf fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="lint"></h1>
                                <h6 class="mb-0 text-dark">Lint</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=12')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-cube fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="rexine"></h1>
                                <h6 class="mb-0 text-dark">Rexine</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=13')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-male fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="sole_damaged"></h1>
                                <h6 class="mb-0 text-dark">Sole Damaged</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=14')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-tint fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="snagging"></h1>
                                <h6 class="mb-0 text-dark">Snagging</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=15')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-cogs fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="rust"></h1>
                                <h6 class="mb-0 text-dark">Rust</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=16')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-utensils fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="food"></h1>
                                <h6 class="mb-0 text-dark">Food</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=17')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-pencil fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="ink"></h1>
                                <h6 class="mb-0 text-dark">Ink</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=18')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-paint-brush fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="paint"></h1>
                                <h6 class="mb-0 text-dark">Paint</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=19')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-tint fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="oil"></h1>
                                <h6 class="mb-0 text-dark">Oil</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=20')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-hammer fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="hard"></h1>
                                <h6 class="mb-0 text-dark">Hard</h6>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card border bg-theme-yellow px-2 m-r-10 w-180px m-b-10" onclick="handleClick('{{ route('orders.barcode.images') }}/?issue_type=21')" style="cursor: pointer;">
                        <div class="stats-card-content d-flex align-items-center justify-content-between align-items-center py-1 px-0">
                            <div class="stats-icon">
                                <i class="fa fa-tint fa-2x text-dark mb-2"></i>
                            </div>
                            <div class="stats-count">
                                <h1 class="fw-bold text-dark" id="color_stains"></h1>
                                <h6 class="mb-0 text-dark">Color Stains</h6>
                            </div>
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
                <input type="text" class="form-control p-2" autocomplete="off" name="order_number" id="order_number" value="" placeholder="Order Number">
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

            <div class="col-sm-3 px-2 mt-2">
                <select class="form-select p-2" id="issue_type" name="issue_type">
                    <option value=''>Issue Type</option>
                    @if(!empty(config('constants.issues')) )
                        @foreach(config('constants.issues') as $key => $option )
                            
                                <option value="{{$key}}">
                                    {{ucfirst($option)}}</option>
                            
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
    const itemsPerPage = {
        {
            config('constants.per_page')
        }
    }; // Items per page
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
            if (endPage > 1) {
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
                'locationType': locationType
            };

            getCountData(objParams);
        });
    }


    function getCountData(objParams = null) {
        var filterValue = customStartDate = customEndDate = name = telephone = orderNumber = locationType = '';
        var page = 1;

        if (objParams) {
            if (objParams.filterValue != undefined || objParams.filterValue != null) {
                filterValue = objParams.filterValue;
            }

            if (objParams.customStartDate != undefined || objParams.customStartDate != null) {
                customStartDate = objParams.customStartDate;
            }

            if (objParams.customEndDate != undefined || objParams.customEndDate != null) {
                customEndDate = objParams.customEndDate;
            }

            if (objParams.name != undefined || objParams.name != null) {
                name = objParams.name;
            }

            if (objParams.telephone != undefined || objParams.telephone != null) {
                telephone = objParams.telephone;
            }

            if (objParams.orderNumber != undefined || objParams.orderNumber != null) {
                orderNumber = objParams.orderNumber;
            }

            if (objParams.page != undefined || objParams.page != null) {
                page = objParams.page;
            }

            if (objParams.locationType != undefined || objParams.locationType != null) {
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
            url: "{{route('get.jabchaho.dashboard.count.data')}}?page=" + page + "&limit=" + itemsPerPage,
            dataType: 'json',
            method: 'post',
            data: {
                filterValue: filterValue,
                customStartDate: customStartDate,
                customEndDate: customEndDate,
                name: name,
                telephone: telephone,
                orderNumber: orderNumber,
                locationType: locationType
            },
            success: function(result) {
                console.log(result.itemIssueWithCount.color_fading);

                $('#table-body').empty();

                $('#total-orders').text(JSON.stringify(result.orders.total ? result.orders.total : 0));
                $('#store-orders').text(JSON.stringify(result.orders.store ? result.orders.store : 0));
                $('#facility-orders').text(JSON.stringify(result.orders.facility ? result.orders.facility : 0));

                $('#orders-in-process').text(JSON.stringify(result.orders.process ? result.orders.process : 0));

                $('#completed-orders').text(JSON.stringify(result.orders.completed ? result.orders.completed : 0));

                $('#before-email-count').text(JSON.stringify(result.orders.before_email ? result.orders.before_email : 0));
                $('#after-email-count').text(JSON.stringify(result.orders.after_email ? result.orders.after_email : 0));

                $('#before-wash').text(JSON.stringify(result.orderItemImage.before_wash ? result.orderItemImage.before_wash : 0));
                $('#after-wash').text(JSON.stringify(result.orderItemImage.after_wash ? result.orderItemImage.after_wash : 0));

                //issue count
                $('#color_fading').text(JSON.stringify(result.itemIssueWithCount.color_fading ? result.itemIssueWithCount.color_fading : 0));
                $('#iron_shine').text(JSON.stringify(result.itemIssueWithCount.iron_shine ? result.itemIssueWithCount.iron_shine : 0));
                $('#burn').text(JSON.stringify(result.itemIssueWithCount.burn ? result.itemIssueWithCount.burn : 0));
                $('#shrinkage').text(JSON.stringify(result.itemIssueWithCount.shrinkage ? result.itemIssueWithCount.shrinkage : 0));
                $('#tears_and_torn').text(JSON.stringify(result.itemIssueWithCount.tears_and_torn ? result.itemIssueWithCount.tears_and_torn : 0));
                $('#holes').text(JSON.stringify(result.itemIssueWithCount.holes ? result.itemIssueWithCount.holes : 0));
                $('#missed_button').text(JSON.stringify(result.itemIssueWithCount.missed_button ? result.itemIssueWithCount.missed_button : 0));
                $('#stitching').text(JSON.stringify(result.itemIssueWithCount.stitching ? result.itemIssueWithCount.stitching : 0));
                $('#embroidery').text(JSON.stringify(result.itemIssueWithCount.embroidery ? result.itemIssueWithCount.embroidery : 0));
                $('#missed_logo').text(JSON.stringify(result.itemIssueWithCount.missed_logo ? result.itemIssueWithCount.missed_logo : 0));
                $('#lint').text(JSON.stringify(result.itemIssueWithCount.lint ? result.itemIssueWithCount.lint : 0));
                $('#rexine').text(JSON.stringify(result.itemIssueWithCount.rexine ? result.itemIssueWithCount.rexine : 0));
                $('#sole_damaged').text(JSON.stringify(result.itemIssueWithCount.sole_damaged ? result.itemIssueWithCount.sole_damaged : 0));
                $('#snagging').text(JSON.stringify(result.itemIssueWithCount.snagging ? result.itemIssueWithCount.snagging : 0));
                $('#rust').text(JSON.stringify(result.itemIssueWithCount.rust ? result.itemIssueWithCount.rust : 0));
                $('#food').text(JSON.stringify(result.itemIssueWithCount.food ? result.itemIssueWithCount.food : 0));
                $('#ink').text(JSON.stringify(result.itemIssueWithCount.ink ? result.itemIssueWithCount.ink : 0));
                $('#paint').text(JSON.stringify(result.itemIssueWithCount.paint ? result.itemIssueWithCount.paint : 0));
                $('#oil').text(JSON.stringify(result.itemIssueWithCount.oil ? result.itemIssueWithCount.oil : 0));
                $('#hard').text(JSON.stringify(result.itemIssueWithCount.hard ? result.itemIssueWithCount.hard : 0));
                $('#color_stains').text(JSON.stringify(result.itemIssueWithCount.color_stains ? result.itemIssueWithCount.color_stains : 0));


                var orderWithItemTotalCount = 0;
                if (result.ordersWithItemsCount && Object.keys(result.ordersWithItemsCount).length > 0) {
                    orderWithItemTotalCount = result.orderWithItemTotalCount;

                    result.ordersWithItemsCount.forEach(function(item) {

                        var locationtype = '';

                        if (item.location_type) {
                            locationtype = "{{config('constants.laundry_location_type.store')}}";
                        } else {
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
                } else {
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

        let name = $("#name").val();
        let telephone = $("#telephone").val();
        let orderNumber = $("#order_number").val();
        let locationType = $("#location_type").val();
        let filterValue = $(this).attr("data-value");

        let objParams = {
            'filterValue': filterValue,
            'name': name,
            'telephone': telephone,
            'orderNumber': orderNumber,
            'locationType': locationType
        };

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

        let objParams = {
            'customStartDate': customStartDate,
            'customEndDate': customEndDate,
            'name': name,
            'telephone': telephone,
            'orderNumber': orderNumber,
            'locationType': locationType
        };

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

        let objParams = {
            'filterValue': filterValue,
            'customStartDate': customStartDate,
            'customEndDate': customEndDate,
            'name': name,
            'telephone': telephone,
            'orderNumber': orderNumber,
            'locationType': locationType
        };

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