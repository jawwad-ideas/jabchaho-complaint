@extends('backend.layouts.app-master')

@section('content')

    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Search Reports by User</h3>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
          
                <small id="showFilterBox" type="button"
                    class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">


            <div class="" id="filterBox" 
                    @if (request()->has('name')) 
                            style="display:block;" 
                    @else 
                            style="display:none;" 
                    @endif
            >
                <form class="form-inline" method="GET" action="{{ route('report.by.user') }}">

                    <div class="form-row row">
                        <div class="form-group  mb-3 col-md-3">
                            <h6 class="fw-bold" for="start_date">Start Date:</h6>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                value="{{ request('start_date') }}">
                        </div>

                        <!-- End Date Field -->
                        <div class="form-group  mb-3 col-md-3">
                            <h6 class="fw-bold" for="end_date">End Date:</h6>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                value="{{ request('end_date') }}">
                        </div>

                        <div class="form-group  mb-3 col-md-3">
                            <h6 class="fw-bold" for="end_date">Name:</h6>
                                <input type="text" class="form-control p-2" autocomplete="off" name="name"
                                    value="{{ request('name') }}" >
                            </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-lg-12 d-flex flex-wrap">

                            <div class="form-group  mb-3 col-md-3">
                                <h6 class="fw-bold" for="model">Status:</h6>
                                <select class="mySelect form-control form-control-sm" id="complaint_status_id"
                                    name="complaint_status_id[]" multiple="multiple">
                                    <option value="">--Select--</option>
                                    @if (!empty($complaintStatuses))
                                        @foreach ($complaintStatuses as $row)
                                            <option value="{{ trim(Arr::get($row, 'id')) }}"
                                                {{ in_array(trim(Arr::get($row, 'id')), request('complaint_status_id', [])) ? 'selected' : '' }}>
                                                {{ trim(Arr::get($row, 'name')) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                               
                            </div>
                        </div>

                        

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('report.by.user') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>

       
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="table-heading">
                    <h3>Results</h3>
                </div>
                <div class="btn-group" role="group">
                   
                        <form action="{{ route('report.by.user') }}" method="GET">
                            
                        <input type="hidden"  name="name" value="{{ request('name') }}" placeholder="Name">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="complaint_status_id"
                        value="{{ is_array(request('complaint_status_id')) ? implode(',', request('complaint_status_id')) : request('complaint_status_id') }}">


                                <button type="submit" name="export" value="excel"
                                    class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2">
                                    <i class="fa fa-file-export"></i> <span> Export CSV </span>
                                </button>
                        </form>
                       
                </div>
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="15%">Name</th>
                            <th scope="col" width="15%">Total Complaints</th>
                            @if($statusNames)
                                @foreach ($statusNames as $status)
                                    <th scope="col" width="15%">{{ ucfirst($status) }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                            @if($reportData)
                                @php $sumComplaints = 0; @endphp
                                @foreach ($reportData as $data)
                                    @php $sumComplaints+= Arr::get($data,'total_complaints'); @endphp
                                    <tr>
                                        <td width="15%">@if(!empty(Arr::get($data,'user_name'))) {{ Arr::get($data,'user_name') }} @else <span class="text-danger">Unassigned</span> @endif</td>
                                        <td width="15%">{{  Arr::get($data,'total_complaints') }}</td> 
                                        @foreach ($statusNames as $status)
                                            <td>{{ $data->{$status.'_count'} }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td width="15%">Grand Total</td>
                                        <td width="15%">{{$sumComplaints}}</td> 
                                        @if($statusNames)
                                            @foreach ($statusNames as $status)
                                                <td>{{ $totals[$status.'_count'] }}</td>
                                            @endforeach
                                        @endif
                                    </tr>
                                    
                            @endif
                    </tbody>
                </table>
            </div>

            

        </div>
    </div>

    <script>
        $("#showFilterBox").click(function() {
            $("#filterBox").toggle();
        });

        $(document).ready(function() 
        {
            $('.mySelect').select2();
        });
</script>

    <!--Select 2 -->
    <link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
    <script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection