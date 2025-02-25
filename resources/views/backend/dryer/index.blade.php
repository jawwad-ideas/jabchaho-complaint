@extends('backend.layouts.app-master')

@section('content')
<style>
table.table {
    border-collapse: collapse;
    width: 100%;
}

table.table th, table.table td {
    border: 1px solid #ddd;
    padding: 8px;
    word-wrap: break-word;
    white-space: normal;
}

table.table td {
    max-width: 300px;
}

@media (max-width: 600px) {
    table.table td {
        max-width: 100%;
        display: block;
        word-wrap: break-word;
    }
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
            <h3 class="fw-bold text-dark m-0"> 
                @if( $status == config('constants.dryer_statues_id.completed') )
                    Complete Sunny Dryer Details
                @elseif( $status == config('constants.dryer_statues_id.pending') )
                    Pending Sunny Dryer Details
                @else
                    Dryer Detail
                @endif 
            </h3>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <small id="showFilterBox" type="button"
                       class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>
            </div>
            @if(Auth::user()->can('sunny.dryer.create'))
                <div class="btn-group" role="group">
                    <a href="{{ route('sunny.dryer.create') }}" class="text-decoration-none">
                        <small id="" type="button"
                            class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                                class="fa fa-solid fa-atom"></i><span>Add</span></small>
                    </a>
                </div>
            @endif 

            @if(Auth::user()->can('sunny.dryer.marked.complete.form'))
                <div class="btn-group" role="group">
                    <a href="{{ route('sunny.dryer.marked.complete.form') }}" class="text-decoration-none">
                        <small id="" type="button"
                            class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                                class="fa fa-solid fa-check-circle"></i><span>Mark Complete</span></small>
                    </a>
                </div>
            @endif 
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">

        <div class="" id="filterBox" 
        @if (request()->has('barcode') || request()->has('from') ||request()->has('to')  )
                style="display:block;"
            @else
                style="display:none;"
        @endif
        >
        <form class="form-inline" method="GET" action="{{ route('sunny.dryer') }}/{{$status}}">
                    <div class="row mb-3">
                        <div class="col-lg-12 d-flex flex-wrap">
                            <div class="col-sm-3 px-2 ">
                                <label class="fw-bold text-dark" for="from_time"></label>
                                <input type="text" class="form-control p-2" autocomplete="off" name="barcode"
                                       value="{{ $barcode}}" placeholder="Barcodes">
                            </div>
                        
                            <div class="form-group px-2">
                                <label class="fw-bold text-dark" for="from_time">From:</label>
                                <input type="date" class="form-control p-2" name="from" id="from" value="{{ $from}}"  autocomplete="off">
                            </div>

                            <div class="form-group px-2 ">
                                <label class="fw-bold text-dark" for="to_time">To:</label>
                                <input type="date" class="form-control p-2" name="to" id="to" value="{{ $to}}"  autocomplete="off">
                            </div>

                        </div>
                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                    id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('sunny.dryer') }}/{{$status}}"
                               class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
        </form>
        </div>

     
            <div class="d-flex my-2">
                Showing results {{ ($dryerlots->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($dryerlots->currentPage() * config('constants.per_page'), $dryerlots->total()) }} of {{ $dryerlots->total() }}
            </div>
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact " id="clickableTable">
                    <thead>
                        <tr>
                            <th scope="col" width="10%">#</th>
                            <th scope="col" width="40%">Barcode </th>
                            <th scope="col" width="20%">Status</th>
                            @if( $status == config('constants.dryer_statues_id.completed') )
                                <th scope="col" width="30%">Completed</th>
                            @else
                                <th scope="col" width="30%">Initiated</th>
                            @endif 
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$dryerlots->isEmpty())
                            @foreach ($dryerlots as $dryerlot)
                                <tr>
                                    <td >{{ Arr::get($dryerlot,'id') }}</td>
                                    <td >{{ Arr::get($dryerlot,'barcode') }}</td>
                                    <td >{{ config('constants.dryer_statues.'.Arr::get($dryerlot, 'status')) }}</td>
                                    @if( $status == config('constants.dryer_statues_id.completed') )
                                        <td >
                                            {{ date('j M, Y', strtotime(Arr::get($dryerlot, 'updated_at'))) }}
                                            <small>{{ date('h:i A', strtotime(Arr::get($dryerlot, 'updated_at'))) }}</small>
                                        </td>
                                    @else
                                        <td >
                                            {{ date('j M, Y', strtotime(Arr::get($dryerlot, 'created_at'))) }}
                                            <small>{{ date('h:i A', strtotime(Arr::get($dryerlot, 'created_at'))) }}</small>
                                        </td>
                                    @endif 
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" align="center">No record Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        <div class="d-flex">
                {!! $dryerlots->appends(Request::except('page'))->render() !!}
            </div>

        </div>
    </div>


<script>
        $("#showFilterBox").click(function() {
            $("#filterBox").toggle();
        });

        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                return true;
            } else {

                event.preventDefault();
                return false;
            }
        }

</script>

@endsection