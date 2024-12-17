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
                <button data-order-id="{{ $order->id }}" data-email-type="before_email" type="button"  class="sendEmailBeforeWashBtn btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold">Send Email</button>
            </div>
            </div>
        </div>
        </div>

        <!--item issue modal start-->
    <div class="modal fade itemIssuesModal" id="itemIssues" tabindex="-1" aria-labelledby="itemIssuesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="itemIssuesLabel">The item contains issues</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="alert alert-danger"  id="errorIssuesMessage"   style="display:none"></div>
                <div class="alert alert-success" id="successIssuesMessage" style="display:none"></div>
                    <div class="my-3"><strong>Barcode:</strong><span id="itemIssuesBarcode"></span></div>
                    <input value="" type="hidden" class="form-control" id="modal_item_id" name="item_id" readonly>
                    <div class="form-check-list">
                        @if(!empty(config('constants.issues')))
                            @foreach (config('constants.issues') as $key=>$listItem)
                            <div class="form-check">
                                <input class="form-check-input itemIssueList" type="checkbox" value="{{$key}}" id="{{$listItem}}-{{$key}}">
                                <label class="form-check-label text-capitalize" for="{{$listItem}}-{{$key}}">{{$listItem}}</label>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button data-order-id="{{ $order->id }}" data-email-type="before_email" type="button" id="saveItemIssue" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!--item issue modal end-->




    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group order-action-btns" role="group">

            <div class="mb-3 complete-button-div" @if ( $showCompleteButton ) style="display:block;" @else style="display:none;" @endif >
                <button data-order-id="{{ $order->id }}" type="button" class="complete-order btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                    Complete Order
                </button>
            </div>

            <div class="mb-3 complete-button-div" @if ( $sendBeforeEmail ) style="display:block;" @else style="display:none;" @endif >
                <button id="sendEmailBeforeWashBtn" data-order-id="{{ $order->id }}" data-email-type="before_email" type="button" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2 sendEmailBeforeWashBtn">
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
                                                    <input class="form-check-input yesfault" type="radio" data-barcode="{{$item->barcode}}" data-item="{{$item->id}}" name="is_issue_identify[{{$item->id}}]" id="yesfault-{{$item->id}}" value="2" data-bs-toggle="modal" data-bs-target="#itemissues"
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
                                        <input @if( $disableAfterUploadInput ) disabled @endif value="" type="file" class="form-control img-upload-input-after" name="image[{{$item->id}}][delivery_images][]" placeholder=""  accept="image/png, image/jpeg, image/jpg" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}"  id="uploadImage-{{ $item->id }}">
                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Issue Fixed:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input yesFixed issueFixed" type="radio" data-item="{{$item->id}}" name="is_issue_fixed[{{$item->id}}]" id="yesfixed-{{$item->id}}" value="2"
                                                    @if( $item->is_issue_fixed == 2 ) checked @endif   @if( $item->is_issue_identify == 1 ) disabled @endif  >
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


    $(document).on('click', '.issueFixed', function(event) {
        var itemId = $(this).data('item');
        var isIssueFixed =  $(this).val();

        // Send AJAX call to remove the option
        var url = '{{ route('is.item.issue.fixed') }}';
        $.ajax({
            type: 'POST',
            url: url, // Get the form action URL
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                itemId: itemId,
                isIssueFixed:isIssueFixed
            },
            success: function(response) {

            }
        });

    });

    $(document).on('click', '.yesfault', function(event) {
        var itemId = $(this).data('item');
        var itemBarcode = $(this).data('barcode'); // Get the 'data-item' value of the clicked radio button
        var savedIssues = $(this).data('saved-issue-'+itemId);

        $('#modal_item_id').val(itemId); // Set the modal content with the item data
        $('#itemIssuesBarcode').text(itemBarcode);

        // Ensure that savedIssues is treated as a string
        savedIssues = String(savedIssues || '');  // Convert to string or use empty string if undefined/null
                // Check if the string contains a comma (multiple values) or is a single value
        if (savedIssues.includes(',')) {
            // Multiple values (comma-separated)
            var issueArray = savedIssues.split(',');  // Convert the string to an array

        } else {
            // Single value
            var issueArray = [savedIssues];  // Treat it as a single value in an array

        }

        // Check if savedIssues contains a comma (i.e., multiple values) or is a single value
        var issueArray = savedIssues.includes(',') ? savedIssues.split(',') : [savedIssues];  // Convert to array if multiple values, else keep as array with one value

        // Loop through all checkboxes with class 'itemIssueList'
        $('.modal .form-check input.itemIssueList').each(function() {
            var checkboxValue = $(this).val();  // Get the value of the checkbox (e.g., 1, 2, 3)

            // If issueArray has more than one element, perform array comparison, else perform string comparison
            if (issueArray.length > 1) {
                // Array comparison
                if (issueArray.map(String).includes(String(checkboxValue))) {
                    $(this).prop('checked', true);  // Check the checkbox if it exists in the array
                } else {
                    $(this).prop('checked', false);  // Uncheck the checkbox if it doesn't match
                }
            } else {
                // String comparison (when only one value)
                if (String(checkboxValue) === String(issueArray[0])) {
                    $(this).prop('checked', true);  // Check the checkbox if it matches the single value
                } else {
                    $(this).prop('checked', false);  // Uncheck the checkbox if it doesn't match
                }
            }
        });

    });

    //savedOrderItemIssues

    $(document).on('click', '.nofault', function(event) {
        var itemId = $(this).data('item');

        // Show confirmation dialog
        var confirmAction = confirm('Are you sure you want to remove the issue for this item?');

        // If the user confirms, proceed with the action
        if (confirmAction) {
            // Empty the HTML content and set attribute to empty
            $('#savedOrderItemIssues-' + itemId).html('');
            $('#yesfault-' + itemId).attr('data-saved-issue-' + itemId, '');

            // Send AJAX call to remove the option
            var url = '{{ route('remove.item.issue') }}';
            $.ajax({
                type: 'POST',
                url: url, // Get the form action URL
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    itemId: itemId
                },
                success: function(response) {
                    // Handle response if necessary
                }
            });
        } else {
            // Action cancelled, no further action taken
        }
    });

    //saveItemIssue
    $(document).on('click', '#saveItemIssue', function(event) {
        $('#errorIssuesMessage').html('');
        $('#errorIssuesMessage').hide();
        $('#successIssuesMessage').html('');
        $('#successIssuesMessage').hide();
        $(".loader").show(); //

        var itemId =  $('#modal_item_id').val(); //itemIssueList

        var itemIssueList = [];

        // Iterate over each checkbox with the 'itemIssueList' class
        $('.itemIssueList:checked').each(function() {
            itemIssueList.push($(this).val()); // Push the value of the checkbox
        });



        // Validation: Check if no checkboxes are selected
        if (itemIssueList.length === 0)
        {
            $(".loader").hide();
            $('#errorIssuesMessage').show(); // Show the error message
            $('#errorIssuesMessage').html('Please select at least one issue to proceed.'); // Display the error text
            return; // Stop further execution
        }

        var url = '{{route('save.item.issue')}}'

        $.ajax({
            type: 'POST',
            url: url, // Get the form action URL
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                itemId: itemId,
                itemIssueList: itemIssueList
            },
            success: function(response) {
                if (response.status)
                {
                    $('#successIssuesMessage').show();
                    $('#successIssuesMessage').html(response.message);
                    $('#yesfixed-'+itemId).removeAttr('disabled');
                    $('#nofixed-'+itemId).removeAttr('disabled');

                    $(".loader").hide(); //
                    setTimeout(() => {

                        location.reload(); // Refresh the page to reflect changes
                    }, 1000);

                } else
                {
                    $('#errorIssuesMessage').show();
                    $('#errorIssuesMessage').html(response.message);
                    $(".loader").hide(); //
                }
            },
            error: function(xhr, status, error)
            {
                $('#errorIssuesMessage').show();
                $('#errorIssuesMessage').html("Something went wrong. Please try again.");
                $(".loader").hide(); //
            }
        });


    });

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
        // if( emailType === "before_email" ) {
        //     let beforeWashCheckListItemList = [];
        //     $('.modal-body .form-check-input:checked').each(function () {
        //         beforeWashCheckListItemList.push($(this).val());
        //     });

        //     if (beforeWashCheckListItemList.length === 1) {
        //         // Only one item, no need for commas or "and"
        //         formattedList = beforeWashCheckListItemList[0];
        //     } else if (beforeWashCheckListItemList.length === 2) {
        //         // Two items, join with "and"
        //         formattedList = beforeWashCheckListItemList.join(' and ');
        //     } else {
        //         // More than two items, comma-separate them with "and" before the last item
        //         formattedList = beforeWashCheckListItemList.slice(0, -1).join(', ') + ' and ' + beforeWashCheckListItemList.slice(-1);
        //     }

        //     if (beforeWashCheckListItemList.length === 0) {
        //         alert('Please select at least one checkbox.');
        //         return;
        //     }

        //     remarks = $("#floatingbeforewashremarks").val();
        // }

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
