@extends('backend.layouts.app-master')

@section('content')



<div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Machine detail</h3>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">

            </div>
        </div>

    </div>


    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">

            <div class="table-scroll-hr">
                <div>
                    <strong>Machine type:</strong> {{ Arr::get($machineDetailData->machine,'name') }}
                </div>
                <div>
                    <strong>Process At:</strong> {{ date('l, F j, Y, \a\t h:i A', strtotime(Arr::get($machineDetailData,'created_at'))) }}
                </div>

                <div>
                    <strong>Images:</strong> 
                    @if(!empty(Arr::get($machineDetailData,'machineImages')))
                        @foreach(Arr::get($machineDetailData,'machineImages') as $row)
                            <a href="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetailData,'id') }}/{{Arr::get($row,'file')}}" target="_blank" >    
                                <img src="{{asset(config('constants.files.machines'))}}/{{Arr::get($machineDetailData,'id') }}/thumbnail/{{Arr::get($row,'file')}}" class="img-thumbnail"> 
                            </a>
                            @endforeach
                    @endif
                </div>

                <div>
                    <strong>Barcodes:</strong> 
                    <ul>
                        @if(!empty(Arr::get($machineDetailData,'machineBarcodes')))
                            @foreach(Arr::get($machineDetailData,'machineBarcodes') as $row)
                                <li><a href="{{route('orders.barcode.images')}}?barcode={{Arr::get($row,'barcode')}}" target="_blank" />{{Arr::get($row,'barcode')}}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

            </div>


            <div class="d-flex">
                <a href="{{ route('machine.details') }}" class="btn bg-theme-dark-300 text-light">Back</a>
            </div>

        </div>
    </div>
    
@endsection
