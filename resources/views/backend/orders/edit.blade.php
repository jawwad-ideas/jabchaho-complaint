@extends('backend.layouts.app-master')
<style>
    @media only screen and (max-width: 767px) {
        .page-content{
            /*margin-top: 0 !important;*/
        }
        .order-img {
            width:80px !important;
            height: 100px !important;
        }
    }
</style>
@section('content')

    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Edit Order</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>

        <form method="POST" action="{{route('orders.save')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Edit Order And Upload Images.</h4>
                        </div>
                    </div>

                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="order" class="form-label">Order#</label>
                            <input value="{{ $order->order_id }}" type="text" class="form-control" name="order_number"
                                   placeholder="Order Number" readonly>
                            <input value="{{ $order->id }}" type="hidden" class="form-control" name="order_id"
                                   placeholder="Order Number" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks#</label>
                            <textarea name="remarks" class="form-control" value="{{ $order->remarks }}" placeholder="Order Remarks" ></textarea>
                        </div>

                        @foreach ($order->orderItems as $item)
                        <div class="itemForm">
                            <div class="item-form-row p-3 bg-light rounded border-light mb-4 ">
                                <div class="itemLabel"><label class="fw-bold">{{$item->barcode}} - {{$item->item_name}}</label></div>
                                    <div class="inner-row d-flex justify-content-between pb-4 gap-4">
                                        <div class="mb-3 col-lg-6 bg-white py-3 px-2 border-light">
                                            <label for="pickup_images" class="form-label fw-bold">Before Wash Images</label>
                                            <input value="" type="file" class="form-control" name="image[{{$item->id}}][pickup_images][]"
                                                   placeholder="Before Wash Images" multiple>
                                            <div class="table-scroll-hr mt-4">
                                                <table class="table table-bordered table-striped table-compact ">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($item->images as $image)
                                                        <tr>
                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                            </td>
{{--                                                                <?php $truncateLength = 20; ?>--}}
{{--                                                            <th>{{ Str::limit($image->imagename, $truncateLength) }}</th>--}}
{{--                                                            <th>{{ $image->created_at }}</th>--}}
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>

                                        <div class="mb-3 col-lg-6 bg-white py-3 px-2 border-light">
                                            <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                            <input value="" type="file" class="form-control" name="image[{{$item->id}}][delivery_images][]"
                                                   placeholder="After Wash Images" multiple>
                                            <div class="table-scroll-hr mt-4">
                                                <table class="table table-bordered table-striped table-compact ">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($item->images as $image)
                                                        <tr>
                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                        @endforeach

                        <div>&nbsp;</div>
                        <div class="mb-3">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Update order</button>
                            <a href="{{ route('orders.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
