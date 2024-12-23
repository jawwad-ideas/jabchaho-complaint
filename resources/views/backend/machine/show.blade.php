@extends('backend.layouts.app-master')

@section('content')



<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Washing Detail</h3>
    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group" role="group">
            <!-- Add action buttons here if needed -->
        </div>
    </div>
</div>

<div class="page-content bg-white p-lg-5 px-2 rounded-lg shadow-sm">
    <div class="bg-light p-4 rounded-lg shadow-sm">

        <div class="">
            <div class="mb-3">
                <strong class="d-block mb-1 text-dark">Machine Type:</strong> 
                <span class="text-muted">{{ Arr::get($machineDetailData->machine,'name') }}</span>
            </div>
            
            <div class="mb-3">
                <strong class="d-block mb-1 text-dark">Process At:</strong>
                <span class="text-muted">{{ date('j M, Y, \a\t h:i A', strtotime(Arr::get($machineDetailData,'created_at'))) }}</span>
            </div>

            <div class="mb-3">
                <strong class="d-block mb-1 text-dark">Images:</strong> 
                @if(!empty(Arr::get($machineDetailData,'machineImages')))
                    <div class="row">
                        @foreach(Arr::get($machineDetailData,'machineImages') as $row)
                            <div class="col-4 mb-3">
                                <a href="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetailData,'id') }}/{{Arr::get($row,'file')}}" target="_blank" class="d-block">
                                    <img src="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetailData,'id') }}/thumbnail/{{Arr::get($row,'file')}}" class="img-fluid rounded-lg shadow-sm"> 
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <strong class="d-block mb-1 text-dark">Barcodes:</strong> 
                @if(!empty(Arr::get($machineDetailData,'machineBarcodes')))
                    <ul class="list-unstyled">
                        @foreach(Arr::get($machineDetailData,'machineBarcodes') as $row)
                            <li>
                                <a href="{{route('orders.barcode.images')}}?barcode={{Arr::get($row,'barcode')}}" target="_blank" class="text-muted text-decoration-none">
                                    {{Arr::get($row,'barcode')}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>

        <div class="d-flex mt-4">
            <a href="{{ route('machine.details') }}" class="btn bg-theme-dark-300 text-light rounded-lg shadow-sm">
                <i class="bi bi-arrow-left-circle me-2"></i> Back
            </a>
        </div>

    </div>
</div>

    
@endsection
