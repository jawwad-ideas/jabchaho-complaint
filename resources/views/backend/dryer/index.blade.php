@extends('backend.layouts.app-master')

@section('content')
<style>
    table.table td, table.table th {
        word-wrap: break-word;
        white-space: normal;
    }

    table.table td {
        max-width: 300px; /* Adjust this value for your desired maximum width */
    }
</style>

<div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Sunny Dryer Detail</h3>
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
            
        </div>

     

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="5%">Status</th>
                            <th scope="col" width="30%">Before Dry</th>
                            <th scope="col" width="30%">After Dry</th>
                            <th scope="col" width="20%">Created</th>
                            <th scope="col" width="10%">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$dryerlots->isEmpty())
                            @foreach ($dryerlots as $dryerlot)
                                <tr>
                                    <td >{{ Arr::get($dryerlot,'id') }}</td>
                                    <td >{{ config('constants.dryer_statues.'.Arr::get($dryerlot, 'status')) }}</td>
                                    <td >{{ Arr::get($dryerlot,'before_barcodes') }}</td>
                                    <td >{{ Arr::get($dryerlot,'after_barcodes') }}</td>
                                    <td >{{ date('j M, Y, \a\t h:i A', strtotime(Arr::get($dryerlot,'created_at'))) }}</td>
                                    
                                    <td><a href="{{ route('sunny.dryer.edit', Arr::get($dryerlot,'id')) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" align="center">No record Found</td>
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
</script>

@endsection