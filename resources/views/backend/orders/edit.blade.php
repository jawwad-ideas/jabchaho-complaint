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

    .itemLabel label.d-flex {
        gap: 24px;
        width: 30%;
        justify-content: space-between;
    }

    .itemLabel h6.d-inline-block.fw-bold {
        width: 50%;
    }

    .itemLabel label.d-flex span {
        width: 50%;
    }
    .itemForm .inner-row .form-control {
        background: #dfdfdf;
        border: none;
    }
</style>
@section('content')
    <?php $ismarkComleteButtonEnable = false;  ?>
    @foreach ($order->orderItems as $item)
        @foreach ($item->images as $image)
            @if( $ismarkComleteButtonEnable == true )
                <?php $ismarkComleteButtonEnable = true;
                    break;
                ?>
            @endif
            @if( $image->image_type == "After Wash" )
                <?php $ismarkComleteButtonEnable = true;
                    break;
                ?>
            @endif
        @endforeach
    @endforeach

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
                         @if ( $order->status == 2 || $ismarkComleteButtonEnable )
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
                        <div class="mb-3 d-flex order-no-bar p-3 align-items-center gap-2">
                            <h4 for="order" class="fw-bold ">Order#</h4>
                            <h2 class="order-no fw-bold">
                                {{ $order->order_id }}
                            </h2>
                            <input value="{{ $order->order_id }}" type="hidden" class="form-control" name="order_number"
                                   placeholder="Order Number" readonly>
                            <input value="{{ $order->id }}" type="hidden" class="form-control" name="order_id"
                                   placeholder="Order Number" readonly>
                        </div>

                        @foreach ($order->orderItems as $item)
                        <div class="itemForm border-bottom border-2">
                            <div class="item-form-row p-3 bg-light rounded border-light mb-4 ">
                                <div class="itemLabel">
                                    <label class="d-flex">
                                        <h6 class="d-inline-block fw-bold">
                                            Service Type:
                                        </h6>
                                        <span>
                                        {{$item->service_type}}
                                        </span>
                                    </label>
                                    <label class="d-flex">
                                        <h6 class="fw-bold d-inline-block">
                                            Product:
                                        </h6>

                                        <span>
                                        {{$item->item_name}}
                                        </span>
                                    </label>


                                    <label class="d-flex ">
                                        <h6 class="d-inline-block fw-bold">
                                            Barcode:
                                        </h6>
                                        <span>
                                            {{$item->barcode}}
                                        </span>
                                         </label>
                                </div>
                                    <div class="inner-row d-flex justify-content-between pb-2 gap-4">
                                        <div class="col-lg-6 pb-1 pt-3 border-light">
                                            <label for="pickup_images" class="form-label fw-bold">Before Wash Images</label>
                                            <input value="" type="file" class="form-control w-50" name="image[{{$item->id}}][pickup_images][]"
                                                   placeholder="" multiple>
                                            @if( $item->images->isNotEmpty() )
                                            <div class="table-scroll-hr mt-4 w-50">
                                                <table class="table table-bordered table-striped table-compact table-sm">
                                                    {{--<thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>--}}
                                                    <tbody>
                                                        <?php
                                                            $count = 0 ;
                                                            $totalCount =  count( $item->images );
                                                        ?>
                                                    @foreach ($item->images as $key => $image)
                                                        @if( $image->image_type == "Before Wash" )
                                                            @if( $count == 0 )
                                                                <tr>
                                                            @endif
                                                                        <?php  $count++; ?>

                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/before/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img img-thumbnail" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/before/{{$image->imagename}}" alt="{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/before/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                                <button
                                                                    class="btn btn-danger btn-sm delete-image ms-2"
                                                                    data-image-id="{{ $image->id }}"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>

                                                                @if( $count == 2 )
                                                                    </tr>
                                                                        <?php
                                                                        $count = 0;
                                                                        ?>
                                                                        @endif


                                                        @endif
                                                    @endforeach
                                                        @if( $count = 0 )


                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-lg-6 pb-1 pt-3  border-light">
                                            <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                            <input value="" type="file" class="form-control w-50" name="image[{{$item->id}}][delivery_images][]"
                                                   placeholder="" multiple>

                                            @if( $item->images->isNotEmpty() )
                                            <div class="table-scroll-hr mt-4 w-50">
                                                <table class="table table-bordered table-striped table-compact table-sm">
                                                    {{--<thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>--}}
                                                    <tbody>

                                                        <?php
                                                        $count = 0 ;
                                                        $totalCount =  count( $item->images );
                                                        ?>

                                                    @foreach ($item->images as $image)
                                                        @if( $image->image_type == "After Wash" )
                                                            <?php $ismarkComleteButtonEnable = true; ?>

                                                            @if( $count == 0 )
                                                                <tr>
                                                            @endif
                                                                <?php  $count++; ?>

                                                            <td>
                                                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/after/{{$image->imagename}}"  target="_blank">
                                                                    <img class="order-img img-thumbnail" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/after/{{$image->imagename}}" alt="{{$image->imagename}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                                                </a>
                                                            </td>
                                                            <td><a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/after/{{$image->imagename}}" class="btn bg-theme-yellow btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                                                <button
                                                                    class="btn btn-danger btn-sm delete-image ms-2"
                                                                    data-image-id="{{ $image->id }}"
                                                                    data-order-number="{{ $order->order_id }}"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>

                                                                    @if( $count == 2 )
                                                                </tr>
                                                                    <?php
                                                                    $count = 0;
                                                                    ?>
                                                            @endif


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
                                   placeholder="" >
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
                const orderNumber = button.data('order-number');
                // Confirmation dialog
                if (!confirm('Are you sure you want to delete this image?')) {
                    return false;
                }

                var url = '{{ route('orders.delete') }}'

                $.ajax({
                    type: 'POST',
                    url: url, // Get the form action URL
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { imageId:imageId, orderNumber:orderNumber },
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
