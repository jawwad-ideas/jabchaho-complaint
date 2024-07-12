@extends('frontend.layouts.app-master')
@section('content')
<style>
.row {
    margin-right: 30px !important;
    margin-left: 0px !important;
}

body {
    font-family: Arial;
}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

.col-sm-2 {
    margin-top: 7px !important;
}
</style>

<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Complaint Details</h3>
        <h5 class="text-white mb-0">{{Arr::get($complaintData,'title')}}</h5>
    </div>
</div>
<div class="page-content bg-white p-lg-5 px-2">
    <div class=" mb-4 border-0">
        <div class="card-body">

            <div class="inner-row d-flex gap-4 mb-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Complaint #</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0"> {{Arr::get($complaintData,'complaint_num')}}</p>
                </div>
            </div>


            <!--  -->

            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Status</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0"> {{Arr::get($complaintData->complaintStatus,'name')}}</p>
                </div>
            </div>




            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Complaint Category</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->levelOneCategory,'name')}}</p>
                </div>
            </div>
            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Level Two</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->levelTwoCategory,'name')}}</p>
                </div>
            </div>

            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Level Three</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->levelThreeCategory,'name')}}</p>
                </div>
            </div>

            <div class="inner-row d-flex gap-4 my-3">

                <div class="inner-label">
                    <p class="mb-0"><b>City</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->city,'name')}}</p>
                </div>
            </div>


            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>District</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->district,'name')}}</p>
                </div>
            </div>
            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Sub Division</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->subDivision,'name')}} -</p>
                </div>
            </div>


            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Charge</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->charge,'name')}}</p>
                </div>
            </div>
            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Union Council</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->unionCouncil,'name')}}</p>
                </div>
            </div>


            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Ward</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->ward,'name')}}</p>
                </div>
            </div>
            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>National Assembly</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->nationalAssembly,'name')}}</p>
                </div>
            </div>


            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Provincial Assembly</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->provincialAssembly,'name')}}</p>
                </div>
            </div>
            <div class="inner-row d-flex gap-4 my-3">
                <div class="inner-label">
                    <p class="mb-0"><b>Area</b></p>
                </div>
                <div class="inner-value">
                    <p class="text-muted mb-0">{{Arr::get($complaintData->newArea,'name')}}</p>
                </div>

            </div>
            <div class="inner-row d-flex gap-4 my-3">


            </div>
        </div>
        <!--Row 3-->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3 p-0">
                    <h6 class="card-header">Follow Ups</h6>
                    <div class="card-body">
                        @if(!empty($complaintFollowUps))

                        @foreach($complaintFollowUps as $complaintFollowUp)
                        <div class="row">
                            <div class="py-2 px-4 border">
                                <div class="p-0">
                                    <div class="row">
                                        <div class="p-0"> <small>@if(!empty(Arr::get($complaintFollowUp,
                                                'created_at'))){{ date("M d, Y", strtotime(Arr::get($complaintFollowUp, 'created_at'))) }}@endif
                                                | Status:
                                                {{ Arr::get($complaintFollowUp->complaintStatus,'name') }}</small>
                                        </div>
                                    </div>
                                    <p class="font-weight-bold ">{!! Arr::get($complaintFollowUp,'description') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-start">
                            {{ $complaintFollowUps->links() }}
                        </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
        <!--Row 3-->
    </div>


</div>





@endsection