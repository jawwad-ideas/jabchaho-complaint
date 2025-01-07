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
                @if( $status == 2 )
                    Complete Dryer Detail
                @elseif( $status == 1 )
                    Pending Dryer Detail
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
            <div class="btn-group" role="group">
                <a href="{{ route('sunny.dryer.create') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-atom"></i><span>Add</span></small>
                </a>
            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">

        <div class="" id="filterBox" style="display:block;" >
        <form class="form-inline" method="GET" action="{{ route('sunny.dryer') }}/{{$status}}">
                    <div class="row mb-3">
                        <div class="col-lg-12 d-flex flex-wrap">
                            <div class="col-sm-3 px-2 ">
                                <input type="text" class="form-control p-2" autocomplete="off" name="lot_number"
                                       value="{{ $lotNumber}}" placeholder="Lot">
                            </div>
                        
                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="before_barcodes"
                                       value="{{$beforeBarcodes}}" placeholder="Before Dry Barcode">
                            </div>
                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="after_barcodes"
                                       value="{{$afterBarcodes}}" placeholder="After Dry Barcode">
                            </div>

                            <div class="form-group px-2">
                                <label class="fw-bold text-dark" for="from_time">From:</label>
                                <input type="datetime-local" class="form-control p-2" name="from" id="from" value="{{ $from}}"  autocomplete="off">
                            </div>

                            <div class="form-group px-2 ">
                                <label class="fw-bold text-dark" for="to_time">To:</label>
                                <input type="datetime-local" class="form-control p-2" name="to" id="to" value="{{ $to}}"  autocomplete="off">
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
                            <th scope="col" width="2%">#</th>
                            <th scope="col" width="2%">Lot</th>
                            <th scope="col" width="34%">Before Dry</th>
                            <th scope="col" width="2%">Pre-Count</th>
                            <th scope="col" width="34%">After Dry</th>
                            <th scope="col" width="2%">Post-Count</th>
                            <th scope="col" width="4%">Status</th>
                            <th scope="col" width="10%">Created</th>
                            <th scope="col" width="10%">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$dryerlots->isEmpty())
                            @foreach ($dryerlots as $dryerlot)
                                <tr data-url="{{ route('sunny.dryer.edit', Arr::get($dryerlot,'id')) }}">
                                    <td >{{ Arr::get($dryerlot,'id') }}</td>
                                    <td >{{ Arr::get($dryerlot,'lot_number') }}</td>
                                    <td >{{ Arr::get($dryerlot,'before_barcodes') }}</td>
                                    <td >@if(!empty(Arr::get($dryerlot,'before_barcodes'))) {{ count(explode(',', Arr::get($dryerlot,'before_barcodes')))}} @else 0 @endif</td>
                                    <td >{{ Arr::get($dryerlot,'after_barcodes') }}</td>
                                    <td >@if(!empty(Arr::get($dryerlot,'after_barcodes'))) {{ count(explode(',', Arr::get($dryerlot,'after_barcodes')))  }} @else 0 @endif</td>
                                    <td >{{ config('constants.dryer_statues.'.Arr::get($dryerlot, 'status')) }}</td>
                                    <td >
                                        {{ date('j M, Y', strtotime(Arr::get($dryerlot, 'created_at'))) }}<br>
                                        <small>{{ date('h:i A', strtotime(Arr::get($dryerlot, 'created_at'))) }}</small>
                                    </td>
                                    
                                    <td><a href="{{ route('sunny.dryer.edit', Arr::get($dryerlot,'id')) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" align="center">No record Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        <div class="d-flex">
                {!! $dryerlots->links() !!}
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

        document.getElementById('clickableTable').addEventListener('click', function(event) {
        const row = event.target.closest('tr'); // Get the clicked <tr>
        if (row && row.dataset.url) {
            window.location.href = row.dataset.url;
        }
        });
</script>

@endsection