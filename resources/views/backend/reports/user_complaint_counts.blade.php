@extends('backend.layouts.app-master')

@section('content')

    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Search Reports by User.</h3>
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


            <div class="" id="filterBox" style="display:none;">
                <form class="form-inline" method="GET" action="{{ route('users.index') }}">
                    <div class="row mb-3">
                        <div class="col-lg-8 d-flex flex-wrap">

                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="full_name"
                                    value="{{ $filterData['full_name'] ?? '' }}" placeholder="Full Name">
                            </div>

                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('users.index') }}"
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
                            @foreach ($statusNames as $status)
                                <th scope="col" width="15%">{{ ucfirst($status) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                            @if($reportData)
                                @php $sumComplaints = 0; @endphp
                                @foreach ($reportData as $data)
                                    @php $sumComplaints+= Arr::get($data,'total_complaints'); @endphp
                                    <tr>
                                        <td width="15%">{{ Arr::get($data,'user_name') }}</td>
                                        <td width="15%">{{  Arr::get($data,'total_complaints') }}</td> 
                                        @foreach ($statusNames as $status)
                                            <td>{{ $data->{$status.'_count'} }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td width="15%">Grand Total</td>
                                        <td width="15%">{{$sumComplaints}}</td> 
                                        @foreach ($statusNames as $status)
                                            <td>{{ $totals[$status.'_count'] }}</td>
                                        @endforeach
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
</script>
@endsection