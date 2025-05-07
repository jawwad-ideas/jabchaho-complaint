@extends('backend.layouts.app-master')

@section('content')
<style>
@media (max-width: 576px) {
    .order-action-btns {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .order-action-btns > div {
        width: 48%; /* Two per row with some gap */
    }

    .order-action-btns .btn {
        width: 100%;
        border-radius: 8px; /* rectangle with slight round */
        padding: 12px;
        justify-content: center;
        text-align: center;
        white-space: normal;
        height: auto; /* allow height to expand based on content */
    }
}

/* Change active/focus/hover background of dropdown items */
.dropdown-menu .dropdown-item:active,
.dropdown-menu .dropdown-item:focus,
.dropdown-menu .dropdown-item:hover {
    background-color: #343a40 !important; /* Replace with your desired color */
    color: #fff !important; /* Optional: ensure text stays readable */
}

</style>
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Edit Order</h3>
    </div>


    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group order-action-btns d-flex flex-wrap justify-content-center" role="group">


            {{-- Barcode Scan (mobile only) --}}
            <div class="mb-3 update-order-button-div d-xl-none d-lg-none d-md-block d-sm-block d-block">
                <button id="startScanner" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                    Barcode Scan
                </button>
            </div>

            {{-- Order Action Dropdown --}}
            <div class="mb-3 order-action-button-div dropdown">
                <button class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2 dropdown-toggle"
                    type="button" id="orderActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Order Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="orderActionDropdown">
                    {{-- Complete Order --}}
                    <li @if ( $showCompleteButton ) style="display:block;" @else style="display:none;" @endif>
                        <a data-order-id="{{ $order->id }}" class="complete-order dropdown-item">
                            Complete Order
                        </a>
                    </li>
                    {{-- Update Order --}}
                    <li>
                        <a id="updateOrderTopButton" class="dropdown-item">
                            Update Order
                        </a>
                    </li>

                </ul>
            </div>


            {{-- WhatsApp Actions Dropdown --}}
            @if ($sendBeforeEmail || (Auth::guard('web')->user()->email == 'admin@jabchaho.com' && $sendFinalEmail))
            <div class="mb-3 complete-button-div dropdown">
                <button class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2 dropdown-toggle"
                    type="button" id="whatsappDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    WhatsApp Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="whatsappDropdown">
                    @if ($sendBeforeEmail)
                    <li>
                        <a class="dropdown-item sendWhatsApp" href="#" id="before_whatsapp"
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_id }}"
                            data-w-type="before_whatsapp">
                            {{ $beforewhatsppTitle }}
                        </a>
                    </li>
                    @endif
                    @if (Auth::guard('web')->user()->email == 'admin@jabchaho.com' && $sendFinalEmail)
                    <li>
                        <a class="dropdown-item sendWhatsApp" href="#" id="after_whatsapp"
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_id }}"
                            data-w-type="after_whatsapp">
                            {{ $afterwhatsppTitle }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif

            {{-- Email Actions Dropdown --}}
            @if ($sendBeforeEmail || $sendFinalEmail)
            <div class="mb-3 complete-button-div dropdown">
                <button class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2 dropdown-toggle"
                    type="button" id="emailDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Email Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="emailDropdown">
                    @if ($sendBeforeEmail)
                    <li>
                        <a class="dropdown-item sendEmailBeforeWashBtn" href="#" id="sendEmailBeforeWashBtn"
                            data-order-id="{{ $order->id }}"
                            data-email-type="before_email">
                            {{ $sendBeforeEmailTitle }}
                        </a>
                    </li>
                    @endif
                    @if ($sendFinalEmail)
                    <li>
                        <a class="dropdown-item sendEmailBeforeWashBtn" href="#"
                            data-order-id="{{ $order->id }}"
                            data-email-type="final_email">
                            {{ $sendFinalEmailTitle }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif

        </div>
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
                        <h4 class="fw-bold mt-0 ">Edit Order And Upload Images.</h4>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <input type="text"
                            class="form-control me-3"
                            id="barcode"
                            name="barcode"
                            placeholder="Barcode"
                            autocomplete="off"
                            style="max-width: 300px;">
                        <input type="button"
                            value="Search"
                            class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3" onclick="scrollToBarcode()">
                    </div>
                </div>

                <div class="container mt-4 p-0">
                    <div class="order-basic-info mb-3">
                        <div class="d-flex order-no-bar px-3 align-items-center gap-2 mb-3">
                            <h4 for="order" class="fw-bold ">Order#</h4>
                            <h2 class="order-no fw-bold"> {{ $order->order_id }} </h2>
                        </div>
                        <div class="customer-basic-detail">
                            <div class="d-xl-flex d-lg-flex d-md-block cust-inr-detail-bar px-3 align-items-center gap-2">
                                <h6 class="fw-bold inr-lbl">Customer Name:</h6>
                                <h6 class="inr-vl"> {{ $order->customer_name }} </h6>
                            </div>
                            <div class="d-xl-flex d-lg-flex d-md-block cust-inr-detail-bar px-3 align-items-center gap-2">
                                <h6 class="fw-bold inr-lbl">Customer Email:</h6>
                                <h6 class="inr-vl"> {{ $order->customer_email }} </h6>
                            </div>
                            <div class="d-xl-flex d-lg-flex d-md-block cust-inr-detail-bar px-3 align-items-center gap-2">
                                <h6 class="fw-bold inr-lbl">Telephone:</h6>
                                <h6 class="inr-vl"> {{ $order->telephone }} </h6>
                            </div>
                            <div class="d-xl-flex d-lg-flex d-md-block cust-inr-detail-bar px-3 align-items-center gap-2">
                                <h6 class="fw-bold inr-lbl">Location:</h6>
                                <h6 class="inr-vl">@if(!empty($order->location_type)) {{config('constants.laundry_location_type.store')}} @else {{config('constants.laundry_location_type.facility')}} @endif</h6>
                            </div>
                        </div>

                        <input value="{{ $order->order_id }}" type="hidden" class="form-control" name="order_number" placeholder="Order Number" readonly>
                        <input value="{{ $order->id }}" type="hidden" class="form-control" name="order_id" placeholder="Order Number" readonly>
                    </div>


                    <!-- <script>
                        $(document).ready(function () {
                            let activeItemId = null; // Tracks the current item's ID
                            let canvas = null; // Fabric.js canvas instance
                            let imagesArray = {}; // Stores images for each item ID

                            // Handle file input change
                            $('.img-upload-input').on('change', function (e) {
                                const itemId = $(this).data('item-id'); // Get item ID
                                const file = this.files[0];

                                if (file) {
                                    activeItemId = itemId; // Set active item ID
                                    const reader = new FileReader();

                                    reader.onload = function (e) {
                                        // Initialize or clear the Fabric.js canvas
                                        if (canvas) {
                                            canvas.clear();
                                        } else {
                                            canvas = new fabric.Canvas('imageCanvas');
                                        }

                                        // Load the selected image onto the canvas
                                        fabric.Image.fromURL(e.target.result, function (img) {
                                            img.scaleToWidth(canvas.getWidth());
                                            img.scaleToHeight(canvas.getHeight());
                                            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                                        });

                                        // Enable drawing mode
                                        canvas.isDrawingMode = true;
                                        canvas.freeDrawingBrush.color = 'red';
                                        canvas.freeDrawingBrush.width = 5;
                                    };

                                    reader.readAsDataURL(file);

                                    // Show the modal
                                    $('#imageModal').modal('show');
                                }
                            });

                            // Save button handler
                            $('#saveImage').on('click', function () {
                                if (canvas && activeItemId) {
                                    const dataURL = canvas.toDataURL({
                                        format: 'png',
                                        quality: 1
                                    });

                                    // Add to images array
                                    if (!imagesArray[activeItemId]) {
                                        imagesArray[activeItemId] = [];
                                    }
                                    imagesArray[activeItemId].push(dataURL);

                                    // Append the new image to the relevant item's section
                                    const imageHtml = `
                                        <div class="img-item">
                                            <a href="${dataURL}" target="_blank">
                                                <img class="img-thumbnail" src="${dataURL}" alt="Edited Image">
                                            </a>
                                            <div class="item-img-action-btn">
                                                <button class="btn btn-danger btn-sm delete-image ms-2" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;
                                    $(`#items-images-sec-${activeItemId}`).append(imageHtml);

                                    // Clear the file input field
                                    $(`#uploadImage-${activeItemId}`).val('');

                                    // Hide the modal
                                    $('#imageModal').modal('hide');

                                    // Clear the canvas
                                    canvas.clear();
                                }
                            });

                            // Delete image handler
                            $(document).on('click', '.delete-image', function () {
                                $(this).closest('.img-item').remove();
                            });
                        });
                    </script> -->

                    <!-- Div to display barcode scanning -->
                    <div id="#barcode_scanner_section">
                        <div id="scanner-container"></div>
                    </div>

                    @foreach ($order->orderItems as $item)
                    <div class="itemForm orderItemSec border-bottom border-2">
                        <div class="item-form-row p-xl-3 p-lg-3 p-md-3 p-sm-0 bg-light rounded border-light">
                            <div class="itemLabel p-3">
                                <label class="d-flex">
                                    <h6 class="d-inline-block fw-bold"> Service Type: </h6>
                                    <span> {{$item->service_type}} </span>
                                </label>
                                <label class="d-flex">
                                    <h6 class="fw-bold d-inline-block"> Product: </h6>
                                    <span> {{$item->item_name}} </span>
                                </label>
                                <label class="d-flex ">
                                    <h6 class="d-inline-block fw-bold"> Barcode: </h6>
                                    <span class="barcode"> {{$item->barcode}} </span>
                                </label>
                            </div>
                            <button type="button" class="btn bg-theme-yellow fw-bold text-dark w-100 d-flex justify-content-between align-items-center mb-3" data-toggle="collapse" data-target="#machine-detail-{{$item->id}}">
                                Washing Detail <i class="toggle-icon fa fa-chevron-down text-right"></i></button>

                            <div id="machine-detail-{{$item->id}}" class="collapse mb-2">

                                <table class="table table-bordered table-striped table-compact mt-3">
                                    <tr>
                                        <th scope="col" width="45%">Machine Type</th>
                                        <th scope="col" width="45%">Process At</th>
                                        <th scope="col" width="10%">Image</th>
                                    </tr>
                                    @if(!Arr::get($item,'machineBarcode')->isEmpty())
                                    @foreach(Arr::get($item,'machineBarcode') as $row)
                                    @if(!empty($row->machineDetail))
                                    <tr>
                                        <td>@if(!empty($row->machineDetail->machine)){{Arr::get($row->machineDetail->machine,'name')}} @endif</td>
                                        <td>{{date('j M, Y, \a\t h:i A', strtotime(Arr::get($row->machineDetail,'created_at')))}}</td>
                                        <td>
                                            @if(!empty($row->machineDetail->machineImages[0]))
                                            @if(file_exists(public_path(asset(config('constants.files.machines')).'/'.Arr::get($row->machineDetail,'id').'/'.Arr::get($row->machineDetail->machineImages[0],'file'))))
                                            <a href="{{asset(config('constants.files.machines'))}}/{{Arr::get($row->machineDetail,'id') }}/{{Arr::get($row->machineDetail->machineImages[0],'file')}}" target="_blank" class="d-block">
                                                <img src="{{asset(config('constants.files.machines'))}}/{{Arr::get($row->machineDetail,'id') }}/thumbnail/{{Arr::get($row->machineDetail->machineImages[0],'file')}}" class="img-fluid rounded-lg shadow-sm">
                                            </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @else
                                    <tr>
                                        <td colspan="3" align="center">No record Found</td>
                                    </tr>

                                    @endif
                                </table>
                            </div>

                            <div class="inner-row d-xl-flex d-lg-flex d-md-block justify-content-between pb-2 gap-0">
                                <div class="col-lg-6 pb-1 pt-3 border-light px-3" style="border-bottom: 4px double;border-color: #f7e441 ! IMPORTANT;background: #eee;">
                                    <div class="d-flex align-items-center gap-3">
                                        <label for="pickup_images" class="form-label fw-bold">Before Wash Images</label>
                                    </div>

                                    <div class="upload-img-input-sec" id="image-upload-container-pickup_images-{{ $item->id }}">
                                        <input value="" type="file" class="form-control img-upload-input"
                                            name="image[{{$item->id}}][pickup_images][]" placeholder="" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="pickup_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">

                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Item having Issue:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input yesfault" type="radio" data-barcode="{{$item->barcode}}" data-item="{{$item->id}}" name="is_issue_identify[{{$item->id}}]" id="yesfault-{{$item->id}}" value="2"
                                                        data-saved-issue-{{$item->id}}=@if(!empty($item->issues)) "{{ implode(',', $item->issues->map(fn($row) => Arr::get($row->toArray(), 'issue'))->sort()->toArray()) }}" @else "" @endif
                                                    @if( $item->is_issue_identify == 2 ) checked @endif >
                                                    <label class="form-check-label" for="yesfault">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input nofault" type="radio" data-item="{{$item->id}}" name="is_issue_identify[{{$item->id}}]" id="nofault-{{$item->id}}" value="1" @if( $item->is_issue_identify != 2 ) checked @endif>
                                                    <label class="form-check-label" for="nofault">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($item->issues))

                                    <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$item->id}}">
                                        @foreach($item->issues as $row)
                                        <span class="rounded-pill badge-sm badge p-1 bg-theme-yellow text-dark">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                        @endforeach
                                    </div>

                                    @endif
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"
                                                onclick="addMoreImageUpload({{ $item->id }},'pickup_images')">Add More</button>
                                    </div> -->
                                    <div class="items-images-sec mt-3" id="items-images-sec-pickup_images-{{ $item->id }}">
                                        @if( $item->images->isNotEmpty() )

                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "Before Wash" )
                                        <?php
                                        $beforeMainImage = asset(config('constants.files.orders')) . '/' . $order->order_id . '/before/' . $image->imagename;
                                        $beforeThumbnail = asset(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/before/' . $image->imagename;
                                        $isBeforeThumbnail = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/before/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isBeforeThumbnail)) {
                                            $beforeThumbnail = $beforeMainImage;
                                        }

                                        $isBeforeMainImage = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/before/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isBeforeMainImage)) {
                                            $beforeMainImage = $beforeThumbnail;
                                        }
                                        ?>

                                        <div class="img-item">
                                            <a href="{{$beforeMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$beforeThumbnail}}" alt="{{$image->imagename}}"> </a>
                                            <div class="item-img-action-btn">
                                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $order->order_id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        @endif


                                    </div>

                                </div>

                                <div class="col-lg-6 pb-1 pt-3 border-light px-3" style="background: #fbee7e4f;">
                                    <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                    <div class="upload-img-input-sec" id="image-upload-container-delivery_images-{{ $item->id }}">
                                        <input @if( $disableAfterUploadInput ) disabled @endif value="" type="file" class="form-control img-upload-input-after" name="image[{{$item->id}}][delivery_images][]" placeholder="" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">
                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Issue Fixed:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input yesFixed issueFixed" type="radio" data-item="{{$item->id}}" name="is_issue_fixed[{{$item->id}}]" id="yesfixed-{{$item->id}}" value="2"
                                                        @if( $item->is_issue_fixed == 2 ) checked @endif @if( $item->is_issue_identify == 1 ) disabled @endif >
                                                    <label class="form-check-label" for="yesFixed">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input noFixed issueFixed" @if( $item->is_issue_identify == 1 ) disabled @endif type="radio" data-item="{{$item->id}}" name="is_issue_fixed[{{$item->id}}]" id="nofixed-{{$item->id}}" value="1" @if( $item->is_issue_fixed != 2 ) checked @endif>
                                                    <label class="form-check-label" for="noFixed">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"  onclick="addMoreImageUpload({{ $item->id }},'delivery_images')">Add More</button>
                                    </div> -->

                                    <div class="items-images-sec mt-3" id="items-images-sec-delivery_images-{{ $item->id }}">


                                        @if( $item->images->isNotEmpty() )

                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "After Wash" )
                                        <?php
                                        $afterMainImage = asset(config('constants.files.orders')) . '/' . $order->order_id . '/after/' . $image->imagename;
                                        $afterThumbnail = asset(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/after/' . $image->imagename;
                                        $isAfterThumbnail = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/after/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isAfterThumbnail)) {
                                            $afterThumbnail = $afterMainImage;
                                        }

                                        $isAfterMainImage = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/after/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isAfterMainImage)) {
                                            $afterMainImage = $afterThumbnail;
                                        }
                                        ?>
                                        <div class="img-item">
                                            <a href="{{$afterMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$afterThumbnail}}" alt="{{$image->imagename}}"> </a>
                                            <div class="item-img-action-btn">
                                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $order->order_id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div>&nbsp;</div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Order Remarks</label>
                        <textarea name="remarks" class="form-control" placeholder="Order Remarks">{{ $order->remarks }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="remarks_attachment" class="form-label">Attachment</label>
                        <input value="" type="file" class="form-control" name="remarks_attachment" placeholder="" accept="image/*" capture="environment">
                        @if( $order->attachments )

                        <?php
                        $attachmentMainImage = asset(config('constants.files.orders')) . '/' . $order->order_id . '/' . $order->attachments;
                        $attachmentThumbnail = asset(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/' . $order->attachments;
                        $isAttachmentThumbnail = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/' . $order->attachments;

                        if (!\Illuminate\Support\Facades\File::exists($isAttachmentThumbnail)) {
                            $attachmentThumbnail = $attachmentMainImage;
                        }
                        ?>

                        <a href="{{$attachmentMainImage}}" target="_blank">
                            <img class="order-img order-attachment-img img-thumbnail mt-3" src="{{$attachmentThumbnail}}" alt="{{$order->attachments}}" class=" img-thumbnail image-fluid w-50" style="height:150px;">
                        </a>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="submit" id="UpdateOrderBtn" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3"> Update Order </button>
                        <a href="{{ route('orders.index') }}/{{$order->status}}" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="scroll-to-scanner d-xl-none d-lg-none d-md-block d-sm-block">
        <button onclick="scrollToScanner()" class="btn btn-sm text-white p-2 "><i class="fa fa-angle-up"></i></button>
    </div>
</div>


@push('order-id')@if(!empty($order->id)){{$order->id}}@endif @endpush

@include('backend.orders.common')
<script>
    let currentMatchIndex = 0; // Keeps track of the current match


    // Attach event listener to the input field
    document.getElementById('barcode').addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission or default behavior
            scrollToBarcode(); // Call the function
        }
    });


    function scrollToBarcode() {
        // Get input value
        const barcodeInput = document.getElementById('barcode').value.trim();

        if (!barcodeInput) {
            alert('Please enter a barcode to search.');
            return;
        }

        // Find all matching barcode elements (partial match)
        const matchingElements = Array.from(document.querySelectorAll('.barcode')).filter(el =>
            el.textContent.trim().includes(barcodeInput)
        );

        if (matchingElements.length > 0) {
            // Loop back to the first match if at the end
            if (currentMatchIndex >= matchingElements.length) {
                currentMatchIndex = 0;
            }

            // Get the current matching element
            const barcodeElement = matchingElements[currentMatchIndex];
            const labelElement = barcodeElement.closest('label');

            // Scroll to the label element and highlight it
            labelElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            labelElement.style.backgroundColor = '#ffff99'; // Highlight

            // Remove highlight after 2 seconds
            setTimeout(() => {
                labelElement.style.backgroundColor = ''; // Remove highlight
            }, 2000);

            // Move to the next match
            currentMatchIndex++;
        } else {
            alert('No matching barcodes found for: ' + barcodeInput);
        }
        $("#barcode").val('');
    }
    //barcode scanner

    document.addEventListener('DOMContentLoaded', () => {
        let barcodeInput = ''; // To capture the barcode input
        let timer; // For debouncing

        // Listen for keypress events
        document.addEventListener('keypress', (event) => {
            // If Enter key is pressed, search for the barcode
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent default behavior (if needed)

                // Locate the barcode element and its parent label
                const barcodeElement = Array.from(document.querySelectorAll('.barcode')).find(el =>
                    el.textContent.trim() === barcodeInput.trim()
                );

                if (barcodeElement) {
                    // Get the parent label
                    const labelElement = barcodeElement.closest('label');

                    // Scroll to the label element and highlight it
                    labelElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    labelElement.style.backgroundColor = '#ffff99'; // Highlight

                    // Remove highlight after 2 seconds
                    setTimeout(() => {
                        labelElement.style.backgroundColor = ''; // Remove highlight
                    }, 2000);
                } else {
                    alert(' Barcode not found!   ' + barcodeInput);
                }

                // Reset the barcode input
                barcodeInput = '';
                return;
            }

            // Append the character to the barcodeInput
            barcodeInput += event.key;

            // Reset input after a delay (debouncing)
            clearTimeout(timer);
            timer = setTimeout(() => {
                barcodeInput = '';
            }, 500); // Reset after 500ms
        });
    });
</script>
<!-- Include the html5-barcode library -->
<script src="{!! url('assets/js/html5-qrcode.min.js') !!}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        let scanner;

        // Add click event for the "Start Barcode Scanner" button
        $("#startScanner").click(function() {
            const scannerContainer = "scanner-container"; // Use the ID of the div, not the element itself

            // Create an instance of the barcode scanner
            scanner = new Html5QrcodeScanner(scannerContainer, {
                fps: 10, // Frames per second for scanning
                qrbox: {
                    width: 250,
                    height: 250
                }, // Define the scanning box size
            });

            // Start scanning
            scanner.render(onScanSuccess, onScanError);
        });

        // Success callback when barcode is scanned
        function onScanSuccess(decodedText, decodedResult) {


            // Find the barcode element on the page
            const barcodeElement = Array.from(document.querySelectorAll('.barcode')).find(el =>
                el.textContent.trim() === decodedText.trim()
            );

            if (barcodeElement) {
                /*   const containerElement = barcodeElement.closest('.container');
                  containerElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                  containerElement.classList.add('highlight');

                  setTimeout(() => {
                      containerElement.classList.remove('highlight');
                  }, 2000); */

                const labelElement = barcodeElement.closest('label');

                // Scroll to the label element and highlight it
                labelElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                labelElement.style.backgroundColor = '#ffff99'; // Highlight

                // Remove highlight after 2 seconds
                setTimeout(() => {
                    labelElement.style.backgroundColor = ''; // Remove highlight
                }, 2000);
            } else {
                alert('Barcode not found on the page: ' + decodedText);
            }

            // Reset the scanner state without stopping the scanning
            resetScannerState();
        }

        // Error callback
        function onScanError(errorMessage) {
            console.error("Barcode Scan Error:", errorMessage);
        }

        // Function to reset the scanner state without stopping it
        function resetScannerState() {
            // Reset scanned barcode text
            $("#code").text('');

            // Optional: You can reset the UI or do other tasks if needed
        }
    });
</script>

@endsection