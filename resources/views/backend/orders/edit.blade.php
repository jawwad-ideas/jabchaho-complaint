@extends('backend.layouts.app-master')
<style>
@media only screen and (max-width: 767px) {
    .page-content {
        /*margin-top: 0 !important;*/
    }

    /* .order-img {
        width: 80px !important;
        height: 100px !important;
    } */
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


/*new css*/
.items-images-sec {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    width: 50%;
}

.items-images-sec .img-item {
    position: relative;
}

.items-images-sec .img-item img {
    width: 90px;
    height: 90px;
}

.item-img-action-btn {
    position: absolute;
    top: -5px;
    right: -8px;
}

.item-img-action-btn .delete-image {
    padding: 0;
    width: 25px;
    height: 26px;
    border-radius: 100%;
}

.item-img-action-btn .delete-image i.fa.fa-trash {
    font-size: 11px;
}
</style>
@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Edit Order</h3>
    </div>

        <!-- Modal -->
        <div class="modal fade checkListBeforeWashModal" id="checkListBeforeWash" tabindex="-1" aria-labelledby="checkListBeforeWashLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="checkListBeforeWashLabel">Send Email Before Wash</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <div class="form-floating">
                <textarea class="form-control mt-3 mb-2" placeholder="Before Wash Order Remarks" id="floatingbeforewashremarks" style="height:200px;"></textarea>
                <label for="floatingbeforewashremarks" >Before Wash Order Remarks</label>
            </div>

                <?php
                    $beforeWashCheckList = "color fading, iron shine ,burn, shrinkage, tears and torn, holes, missed button,stitching,embroidery,missed logo, lint, rexine, sole damaged, snagging, rust, food, ink, paint, oil, hard, color stains";
                    $beforeWashCheckListArr = explode(",", $beforeWashCheckList);
                ?>
            <div class="form-check-list">
                @foreach ($beforeWashCheckListArr as $listItem)
                    <div class="form-check">
                        <input class="form-check-input beforeWashCheckListItem" type="checkbox" value="{{$listItem}}" id="{{$listItem}}" >
                        <label class="form-check-label text-capitalize" for="{{$listItem}}">{{$listItem}}</label>
                    </div>
                @endforeach
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button data-order-id="{{ $order->id }}" data-email-type="before_email" type="button" id="sendEmailBeforeWashBtn" class="sendEmailBeforeWashBtn btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold">Send Email</button>
            </div>
            </div>
        </div>
        </div>

    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group order-action-btns" role="group">

            <div class="mb-3 complete-button-div" @if ( $showCompleteButton ) style="display:block;" @else style="display:none;" @endif >
                <button data-order-id="{{ $order->id }}" type="button" class="complete-order btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                    Complete Order
                </button>
            </div>

            <div class="mb-3 complete-button-div" @if ( $sendBeforeEmail ) style="display:block;" @else style="display:none;" @endif >
                <button data-order-id="{{ $order->id }}" data-email-type="before_email" type="button" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2" data-bs-toggle="modal" data-bs-target="#checkListBeforeWash">
                    {{$sendBeforeEmailTitle}}
                </button>
            </div>


            <div class="mb-3 complete-button-div" @if ( $sendFinalEmail ) style="display:block;" @else style="display:none;" @endif>
                <button data-order-id="{{ $order->id }}" data-email-type="final_email" type="button" class="sendEmailBeforeWashBtn btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                    {{$sendFinalEmailTitle}}
                </button>
            </div>
            <!-- <div class="mb-3 update-order-button-div">
                
                <button type="button" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"> Update order </button>
            </div> -->


        {{--<div class="mb-3 complete-button-div">
                <button type="button" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2" data-bs-toggle="modal" data-bs-target="#checkListBeforeWash">
                        Send Email Before Wash
                </button>
            </div>--}}

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
                                <h6 class="inr-vl">  {{ $order->customer_email }}  </h6>
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

                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel">Mark The Effected Areas!</h5>
                                    <button type="button" id="imageModalClosebtn" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <div class="toolbar mb-3 d-flex align-items-center justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="pencilTool"><i class="fa fa-pencil"></i>Draw</button>
                                    <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="circleTool"><i class="fa fa-circle"></i>Circle</button>
                                    <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="squareTool"><i class="fa fa-square"></i>Square</button>
                                </div>
                                    <canvas id="imageCanvas" width="500" height="500" style="border: 1px solid #ccc;"></canvas>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="clearCanvasBtn" class="btn btn-sm btn-danger rounded border-0 fw-bold d-flex align-items-center p-2 gap-2"><i class="fa fa-solid fa-eraser"></i> Remove Marking</button>
                                    <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="saveImage"><i class="fa fa-solid fa-upload"></i> Upload Image</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="{{asset('assets/js/uploadOrderImage.js')}}"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
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


                    @foreach ($order->orderItems as $item)
                    <div class="itemForm orderItemSec border-bottom border-2">
                        <div class="item-form-row p-3 bg-light rounded border-light">
                            <div class="itemLabel">
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
                                    <span> {{$item->barcode}} </span>
                                </label>
                            </div>
                            <div class="inner-row d-xl-flex d-lg-flex d-md-block justify-content-between pb-2 gap-4">
                                <div class="col-lg-6 pb-1 pt-3 border-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <label for="pickup_images" class="form-label fw-bold">Before Wash Images</label>
                                    </div>

                                    <div class="upload-img-input-sec" id="image-upload-container-pickup_images-{{ $item->id }}">
                                        <input value="" type="file" class="form-control img-upload-input"
                                               name="image[{{$item->id}}][pickup_images][]" placeholder="" accept="image/png, image/jpeg, image/jpg" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="pickup_images" data-item-id="{{ $item->id }}"  id="uploadImage-{{ $item->id }}">
                                               
                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Item having Issue:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input" type="radio" name="is_issue_identify[{{$item->id}}]" id="yesfault" value="2" @if( $item->is_issue_identify == 2 ) checked @endif >
                                                    <label class="form-check-label" for="yesfault">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input" type="radio" name="is_issue_identify[{{$item->id}}]" id="nofault" value="1" @if( $item->is_issue_identify != 2 ) checked @endif>
                                                    <label class="form-check-label" for="nofault">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"
                                                onclick="addMoreImageUpload({{ $item->id }},'pickup_images')">Add More</button>
                                    </div> -->
                                    <div class="items-images-sec mt-3" id="items-images-sec-pickup_images-{{ $item->id }}">
                                    @if( $item->images->isNotEmpty() )
                                    
                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "Before Wash" )
                                          <?php
                                              $beforeMainImage = asset(config('constants.files.orders')).'/'.$order->order_id.'/before/'.$image->imagename;
                                              $beforeThumbnail = asset(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/before/'.$image->imagename;
                                              $isBeforeThumbnail = public_path(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/before/'.$image->imagename;
                                              if(  !\Illuminate\Support\Facades\File::exists($isBeforeThumbnail)  ){
                                                  $beforeThumbnail = $beforeMainImage;
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

                                <div class="col-lg-6 pb-1 pt-3  border-light">
                                    <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                    <div class="upload-img-input-sec" id="image-upload-container-delivery_images-{{ $item->id }}">
                                        <input value="" type="file" class="form-control img-upload-input" name="image[{{$item->id}}][delivery_images][]" placeholder=""  accept="image/png, image/jpeg, image/jpg" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}"  id="uploadImage-{{ $item->id }}">
                                    </div>
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"  onclick="addMoreImageUpload({{ $item->id }},'delivery_images')">Add More</button>
                                    </div> -->
                                    
                                    <div class="items-images-sec mt-3" id="items-images-sec-delivery_images-{{ $item->id }}">
                                   

                                    @if( $item->images->isNotEmpty() )
                                   
                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "After Wash" )
                                            <?php
                                            $afterMainImage = asset(config('constants.files.orders')).'/'.$order->order_id.'/after/'.$image->imagename;
                                            $afterThumbnail = asset(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/after/'.$image->imagename;
                                            $isAfterThumbnail = public_path(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/after/'.$image->imagename;
                                            if( !\Illuminate\Support\Facades\File::exists($isAfterThumbnail)  ){
                                                $afterThumbnail = $afterMainImage;
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
                        $attachmentMainImage = asset(config('constants.files.orders')).'/'.$order->order_id.'/'.$order->attachments;
                        $attachmentThumbnail = asset(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/'.$order->attachments;
                        $isAttachmentThumbnail = public_path(config('constants.files.orders')).'/'.$order->order_id.'/thumbnail/'.$order->attachments;

                        if(  !\Illuminate\Support\Facades\File::exists( $isAttachmentThumbnail )   ){
                            $attachmentThumbnail = $attachmentMainImage;
                        }
                        ?>

                        <a href="{{$attachmentMainImage}}" target="_blank">
                            <img class="order-img order-attachment-img img-thumbnail mt-3" src="{{$attachmentThumbnail}}"  alt="{{$order->attachments}}" class=" img-thumbnail image-fluid w-50"  style="height:150px;">
                        </a>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3"> Update order </button>
                        <a href="{{ route('orders.index') }}/{{$order->status}}" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // function addMoreImageUpload(itemId,fieldName) {
    //     event.preventDefault();
    //     const container = document.getElementById(`image-upload-container-${fieldName}-${itemId}`);

    //     const wrapperDiv = document.createElement('div');
    //     wrapperDiv.className = 'input-wrapper d-flex align-items-center mt-2 addMoreinputWrapper';

    //     // Create a new input element
    //     const newInput = document.createElement('input');
    //     newInput.type = 'file';
    //     newInput.className = 'form-control img-upload-input ';
    //     newInput.name = `image[${itemId}][${fieldName}][]`;
    //     newInput.multiple = true;


    //     const removeButton = document.createElement('span');
    //     removeButton.type = 'span';
    //     removeButton.className = 'bg-danger p-2 text-sm text-white rounded-circle fa fa-trash 2 ms-1';
    //     //removeButton.innerText = 'Remove';

    //     // Add click event to remove button
    //     removeButton.onclick = function () {
    //         wrapperDiv.remove();
    //     };

    //     // Append input and button to the wrapper
    //     wrapperDiv.appendChild(newInput);
    //     wrapperDiv.appendChild(removeButton);

    //     // Append the wrapper to the container
    //     container.appendChild(wrapperDiv);
    // }

$(document).ready(function() {
    // Handle delete button click
    $(document).on('click', '.delete-image', function(event) {
        event.preventDefault(); // Prevent any default action (just in case)
        event
    .stopPropagation(); // Stop event bubbling (in case it's nested in other clickable elements)
        const button = $(this); // Get the button that was clicked
        const imageId = button.data('image-id');
        const orderNumber = button.data('order-number');
        // Confirmation dialog
        if (!confirm('Are you sure you want to delete this image?')) {
            return false;
        }

        var url = '{{route('orders.delete')}}'

        $.ajax({
            type: 'POST',
            url: url, // Get the form action URL
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                imageId: imageId,
                orderNumber: orderNumber
            },
            success: function(response) {
                if (response.success) {
                    alert('Image deleted successfully!');
                    location.reload(); // Refresh the page to reflect changes
                } else {
                    alert('Failed to delete the image. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            }
        });
    });

    function sendEmail( data ){
        var url = '{{route('send.email')}}';
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data ,
            success: function (response) {
                if (response.success) {
                    alert('Email sent successfully!');
                    location.reload(); // Refresh the page to reflect changes
                } else {
                    alert('Error sending email.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            }
        });
    }

    $('.sendEmailBeforeWashBtn').on('click', function () {

        if (!confirm('Are you sure you want to send email to customer?')) {
            return false;
        }

        const button = $(this); // Get the button that was clicked
        const orderId = button.data('order-id');
        const emailType = button.data('email-type');

        let formattedList;
        var remarks = "";
        if( emailType === "before_email" ) {
            let beforeWashCheckListItemList = [];
            $('.modal-body .form-check-input:checked').each(function () {
                beforeWashCheckListItemList.push($(this).val());
            });

            if (beforeWashCheckListItemList.length === 1) {
                // Only one item, no need for commas or "and"
                formattedList = beforeWashCheckListItemList[0];
            } else if (beforeWashCheckListItemList.length === 2) {
                // Two items, join with "and"
                formattedList = beforeWashCheckListItemList.join(' and ');
            } else {
                // More than two items, comma-separate them with "and" before the last item
                formattedList = beforeWashCheckListItemList.slice(0, -1).join(', ') + ' and ' + beforeWashCheckListItemList.slice(-1);
            }

            if (beforeWashCheckListItemList.length === 0) {
                alert('Please select at least one checkbox.');
                return;
            }

            remarks = $("#floatingbeforewashremarks").val();
        }

       let data = {
           orderId : orderId,
           emailType :emailType,
           remarks : remarks ,
           itemsIssues: formattedList
       }

        sendEmail( data );
    });


    $(document).on('click', '.complete-order', function(event) {
        event.preventDefault(); // Prevent any default action (just in case)
        event
    .stopPropagation(); // Stop event bubbling (in case it's nested in other clickable elements)
        const button = $(this); // Get the button that was clicked
        const orderId = button.data('order-id');
        // Confirmation dialog
        if (!confirm('Are you sure you want to complete this order?')) {
            return false;
        }

        var url = '{{route('orders.complete')}}'

        $.ajax({
            type: 'POST',
            url: url, // Get the form action URL
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                orderId: orderId
            },
            success: function(response) {
                if (response.success) {
                    alert('Order Complete successfully!');
                    location.reload(); // Refresh the page to reflect changes
                } else {
                    alert('Failed to mark order complete. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            }
        });
    });
});
</script>
@endsection
