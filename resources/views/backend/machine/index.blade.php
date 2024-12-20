@extends('backend.layouts.app-master')

@section('content')


<div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Washing detail</h3>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('machine.detail.create') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-user-plus"></i><span>Add</span></small>
                </a>
            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">

        <div class="" id="filterBox" >
               
        </div>

     

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="1%">#</th>
                            <th scope="col" width="1%">Machine type</th>
                            <th scope="col" width="15%">Process At</th>
                            <th scope="col" width="1%">Image</th>
                            <th scope="col" width="15%">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$machineDetails->isEmpty())
                            @foreach ($machineDetails as $machineDetail)
                                <tr>
                                    <td scope="row">{{ Arr::get($machineDetail,'id') }}</td>
                                    <td width="15%">{{ Arr::get($machineDetail->machine,'name') }}</td>
                                    <td width="15%">{{ date('l, F j, Y, \a\t h:i A', strtotime(Arr::get($machineDetail,'created_at'))) }}</td>
                                    <td scope="row">
                                                @if(file_exists(public_path(asset(config('constants.files.machines')).'/'.Arr::get($machineDetail,'id').'/'.Arr::get($machineDetail->machineImages[0],'file'))))
                                                <a href="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetail,'id') }}/{{Arr::get($machineDetail->machineImages[0],'file')}}" target="_blank" class="d-block">
                                                    <img src="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetail,'id') }}/thumbnail/{{Arr::get($machineDetail->machineImages[0],'file')}}" class="img-fluid rounded-lg shadow-sm"> 
                                                </a>
                                                @endif
                                    </td>
                                    <td><a href="{{ route('machine.detail.show', Arr::get($machineDetail,'id')) }}" class="btn bg-theme-yellow btn-sm"><i class="fa fa-eye"></i></a>
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
                {!! $machineDetails->links() !!}
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