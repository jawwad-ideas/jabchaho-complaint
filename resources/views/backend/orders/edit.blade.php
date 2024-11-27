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
    <?php $ismarkComleteButtonEnable = false; ?>
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

                    <div class="mb-3 complete-button-div"
                         @if ( $order->status == 2 )
                             style="display:block;"
                         @else
                             style="display:none;"
                        @endif
                    >
                        <button
                            class="btn btn-success btn-sm complete-order"
                            data-order-id="{{ $order->id }}"
                            title="Complete Order"> Complete Order
                        </button>
                    </div>


                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="order" class="form-label">Order#</label>
                            <input value="{{ $order->order_id }}" type="text" class="form-control" name="order_number"
                                   placeholder="Order Number" readonly>
                            <input value="{{ $order->id }}" type="hidden" class="form-control" name="order_id"
                                   placeholder="Order Number" readonly>
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
                                            @if( !empty($item->images) )
                                            <div class="table-scroll-hr mt-4">
                                                <table class="table table-bordered table-striped table-compact ">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th colspan="2">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($item->images as $image)
                                                        @if( $image->image_type == "Before Wash" )
                                                        <tr>
                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" alt="{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                            <td>
                                                                <button
                                                                    class="btn btn-danger btn-sm delete-image"
                                                                    data-image-id="{{ $image->id }}"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="mb-3 col-lg-6 bg-white py-3 px-2 border-light">
                                            <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                            <input value="" type="file" class="form-control" name="image[{{$item->id}}][delivery_images][]"
                                                   placeholder="After Wash Images" multiple>

                                            @if( !empty($item->images) )
                                            <div class="table-scroll-hr mt-4">
                                                <table class="table table-bordered table-striped table-compact ">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th colspan="2">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($item->images as $image)
                                                        @if( $image->image_type == "After Wash" )
                                                            <?php $ismarkComleteButtonEnable = true; ?>
                                                        <tr>
                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" alt="{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                            <td>
                                                                <button
                                                                    class="btn btn-danger btn-sm delete-image"
                                                                    data-image-id="{{ $image->id }}"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>

                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                        @endforeach

                        <div>&nbsp;</div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Order Remarks</label>
                            <textarea name="remarks" class="form-control" placeholder="Order Remarks" >{{ $order->remarks }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="remarks_attachment" class="form-label">Attachment</label>
                            <input value="" type="file" class="form-control" name="remarks_attachment"
                                   placeholder="Before Wash Images" >
                            @if( $order->attachments )
                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$order->attachments}}"  target="_blank">
                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$order->attachments}}" alt="{{$order->attachments}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                </a>
                            @endif
                        </div>

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

    <script>
        $(document).ready(function () {
            // Handle delete button click
            $(document).on('click', '.delete-image', function (event) {
                event.preventDefault(); // Prevent any default action (just in case)
                event.stopPropagation(); // Stop event bubbling (in case it's nested in other clickable elements)
                const button = $(this); // Get the button that was clicked
                const imageId = button.data('image-id');
                // Confirmation dialog
                if (!confirm('Are you sure you want to delete this image?')) {
                    return false;
                }

                var url = '{{ route('orders.delete') }}'

                $.ajax({
                    type: 'POST',
                    url: url, // Get the form action URL
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { imageId:imageId },
                    success: function (response) {
                        if (response.success) {
                            alert('Image deleted successfully!');
                            location.reload(); // Refresh the page to reflect changes
                        } else {
                            alert('Failed to delete the image. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('Something went wrong. Please try again.');
                    }
                });
            });



            $(document).on('click', '.complete-order', function (event) {
                event.preventDefault(); // Prevent any default action (just in case)
                event.stopPropagation(); // Stop event bubbling (in case it's nested in other clickable elements)
                const button = $(this); // Get the button that was clicked
                const orderId = button.data('order-id');
                // Confirmation dialog
                if (!confirm('Are you sure you want to complete this order?')) {
                    return false;
                }

                var url = '{{ route('orders.complete') }}'

                $.ajax({
                    type: 'POST',
                    url: url, // Get the form action URL
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { orderId:orderId },
                    success: function (response) {
                        if (response.success) {
                            alert('Order Complete successfully!');
                            location.reload(); // Refresh the page to reflect changes
                        } else {
                            alert('Failed to mark order complete. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
