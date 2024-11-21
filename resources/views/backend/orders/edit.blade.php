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
                            <input value="{{ $order->order_number }}" type="text" class="form-control" name="order_number"
                                   placeholder="Order Number" readonly>

                        </div>
                        <div class="mb-3">
                            <label for="pickup_images" class="form-label">Before Wash Images</label>
                            <input value="" type="file" class="form-control" name="pickup_images[]"
                                   placeholder="Before Wash Images" multiple>

                        </div>

                        <div class="mb-3">
                            <label for="delivery_images" class="form-label">After Wash Images</label>
                            <input value="" type="file" class="form-control" name="delivery_images[]"
                                   placeholder="After Wash Images" multiple>
                        </div>

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

        <div class="bg-light p-2 rounded">
            <div id="modalDiv"></div>
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                    <tr>
                        <th scope="col" width="1%">#</th>
                        <th scope="col" width="15%">Image</th>
                        <th scope="col" width="15%">Image Name</th>
                        <th scope="col" width="15%">Uploaded At</th>
                        <th scope="col" width="15%">Image Type</th>
                        <th scope="col" width="1%" colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($order->images as $image)
                        <tr>
                            <th scope="row">{{ $image->id }}</th>
                            <td width="15%">
                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_number}}/{{$image->filename}}"  target="_blank"><img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_number}}/{{$image->filename}}"
                                                                                                                                                          class=" img-thumbnail image-fluid w-50" style="height:150px;" ></a>
                            </td>

                                <?php $truncateLength = 20; ?>
                            <th scope="row">{{ Str::limit($image->filename, $truncateLength) }}</th>
                            <th scope="row">{{ $image->created_at }}</th>
                            <td width="15%">{{ $image->image_type }}</td>
                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_number}}/{{$image->filename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                            </td>
                            <td>
                                {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' =>  ['orders.delete', $order->id, $image->id],
                                        'style' => 'display:inline',
                                        'onsubmit' => 'return ConfirmDelete()',
                                    ]) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete'
                                ]) !!}
                                {!! Form::close() !!}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
