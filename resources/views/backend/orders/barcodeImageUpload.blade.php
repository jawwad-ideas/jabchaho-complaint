@extends('backend.layouts.app-master')

@section('content')

<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Barcode Image Upload</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="container mt-4">
                <div class="mb-3">
                    <form class="d-flex align-items-center" id="barcodeImageUploadForm" method="GET" action="{{ route('barcode.image.upload') }}" onsubmit="return validateForm()">
                        <input type="text" class="form-control me-3" id="barcode" name="barcode" required placeholder="Barcode" style="max-width: 300px;" autocomplete="off" value="{{$barcode}}">
                        <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Search</button>
						<a href="{{ route('barcode.image.upload') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 ms-1 text-decoration-none">
                                <span>Clear</span>
                        </a>
                    </form>
                </div>
				 <div class="mb-3 update-order-button-div d-xl-none d-lg-none d-md-block d-sm-block d-block ">
					<button id="startScanner" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">  Barcode Scan</button>
				</div>
            </div>
        </div>
    </div>
	
	   <!-- Div to display barcode scanning -->
	  <div id="#barcode_scanner_section" >
		  <div id="scanner-container"></div>
	  </div>


    @if(!empty($barcode))

    <div class="itemForm orderItemSec border-bottom border-2">
        <div class="item-form-row p-xl-3 p-lg-3 p-md-3 p-sm-0 bg-light rounded border-light">
            @if($items->isNotEmpty())
            @foreach($items as $item)
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
<!-- open camera-->
<button class="btn btn-primary" id="startWebcamBtn">Open Camera</button>
<video id="webcamVideo" autoplay playsinline style="display:none;" class="mt-3"></video>
<canvas id="webcamCanvas" width="400" height="300" style="display:none;"></canvas>
<button class="btn btn-success mt-2" id="captureWebcamBtn" style="display:none;">Capture</button>
<!-- open camera-->
                    <div class="upload-img-input-sec" id="image-upload-container-pickup_images-{{ $item->id }}">
                        <input value="" type="file" class="form-control img-upload-input"
                            name="image[{{$item->id}}][pickup_images][]" placeholder="" data-order-num="{{$item->order->order_id}}" data-order-id="{{$item->order->id}}" data-item-type="pickup_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">

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
                    <?php $beforeImagecount = 0; ?>    
                    @if( $item->images->isNotEmpty() )

                        @foreach ($item->images as $image)
                        @if( $image->image_type == "Before Wash" )
                        <?php
                        $beforeImagecount++;
                        $beforeMainImage = asset(config('constants.files.orders')) . '/' . $item->order->order_id . '/before/' . $image->imagename;
                        $beforeThumbnail = asset(config('constants.files.orders')) . '/' . $item->order->order_id . '/thumbnail/before/' . $image->imagename;
                        $isBeforeThumbnail = public_path(config('constants.files.orders')) . '/' . $item->order->order_id . '/thumbnail/before/' . $image->imagename;
                        if (!\Illuminate\Support\Facades\File::exists($isBeforeThumbnail)) {
                            $beforeThumbnail = $beforeMainImage;
                        }

                        $isBeforeMainImage = public_path(config('constants.files.orders')) . '/' . $item->order->order_id . '/before/' . $image->imagename;
                        if (!\Illuminate\Support\Facades\File::exists($isBeforeMainImage)) {
                            $beforeMainImage = $beforeThumbnail;
                        }
                        ?>

                        <div class="img-item before-image">
                            <a href="{{$beforeMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$beforeThumbnail}}" alt="{{$image->imagename}}"> </a>
                            <div class="item-img-action-btn">
                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $item->order->order_id }}" title="Delete">
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
                        <input @if( empty($beforeImagecount) ) disabled @endif value="" type="file" class="form-control img-upload-input-after barcode-img-upload" name="image[{{$item->id}}][delivery_images][]" placeholder="" data-order-num="{{$item->order->order_id}}" data-order-id="{{$item->order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">
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
                        $afterMainImage = asset(config('constants.files.orders')) . '/' . $item->order->order_id . '/after/' . $image->imagename;
                        $afterThumbnail = asset(config('constants.files.orders')) . '/' . $item->order->order_id . '/thumbnail/after/' . $image->imagename;
                        $isAfterThumbnail = public_path(config('constants.files.orders')) . '/' . $item->order->order_id . '/thumbnail/after/' . $image->imagename;
                        if (!\Illuminate\Support\Facades\File::exists($isAfterThumbnail)) {
                            $afterThumbnail = $afterMainImage;
                        }

                        $isAfterMainImage = public_path(config('constants.files.orders')) . '/' . $item->order->order_id . '/after/' . $image->imagename;
                        if (!\Illuminate\Support\Facades\File::exists($isAfterMainImage)) {
                            $afterMainImage = $afterThumbnail;
                        }
                        ?>
                        <div class="img-item">
                            <a href="{{$afterMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$afterThumbnail}}" alt="{{$image->imagename}}"> </a>
                            <div class="item-img-action-btn">
                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $item->order->order_id }}" title="Delete">
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
            @endforeach
            @else
            <div class="text-center">
                <strong>No Record Found</strong>
            </div>
        </div>
    </div>

    @endif
 

    @endif


</div>


@push('order-id')@if(!empty($item->order->id)){{$item->order->id}}@endif @endpush

@include('backend.orders.common')
<script>


document.addEventListener('DOMContentLoaded', () => {
            const barcodeInput = document.getElementById('barcode');
            let scanning = false; // Track scanning state

            // Ensure input field always has focus
            barcodeInput.focus();

            barcodeInput.addEventListener('keydown', () => {
                if (!scanning) {
                    barcodeInput.value = ''; // Clear input for new scan
                    scanning = true; // Set scanning state
                }
            });

            barcodeInput.addEventListener('input', () => {
                console.log('Current Scanned Value:', barcodeInput.value);
            });

            barcodeInput.addEventListener('keypress', (event) => {
                if (event.key === 'Enter' || event.key === 'Tab') {
                    scanning = false; // Reset scanning state
                    console.log('Final Scanned Barcode:', barcodeInput.value);

                    // Refocus the input field to prepare for the next scan
                    setTimeout(() => barcodeInput.focus(), 0);
                }
            });

            // Prevent focus from being lost if clicked outside
            
        });
</script>

<!-- Include the html5-barcode library -->
<script src="{!! url('assets/js/html5-qrcode.min.js') !!}" type="text/javascript"></script>

<script>
  $(document).ready(function () {
            let scanner; // Initialize scanner variable

            // Start barcode scanner on button click
            $("#startScanner").click(function () {
				 $("#barcode").val('');
                const scannerContainer = "scanner-container"; // ID of the container div

                // Create an instance of Html5QrcodeScanner
                scanner = new Html5QrcodeScanner(scannerContainer, {
                    fps: 10, // Frames per second
                    qrbox: { width: 250, height: 250 }, // Scanning box dimensions
                });

                // Render the scanner and define success and error callbacks
                scanner.render(onScanSuccess, onScanError);
            });

            // Success callback: Insert barcode into the text field and submit the form
            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Scanned Barcode: ${decodedText}`);
                
                // Insert the scanned barcode into the input field
                $("#barcode").val(decodedText);

                // Stop the scanner (optional, depending on use case)
                if (scanner) {
                    scanner.clear(); // Clear scanner UI
                    scanner = null; // Reset scanner instance
                }

                // Automatically submit the form
                $("#barcodeImageUploadForm").submit();
            }

            // Error callback: Handle scanning errors
            function onScanError(error) {
                console.warn(`Barcode scanning error: ${error}`);
            }
        });



        function validateForm() 
        {
            var input = document.getElementById("barcode").value;
            if (input.length < 6) {
            alert("Please enter at least 6 characters.");
            return false; // Prevent form submission
            }
            return true; // Allow form submission
        }




let stream;
const video = document.getElementById('webcamVideo');
const captureCanvas = document.getElementById('webcamCanvas');
const captureBtn = document.getElementById('captureWebcamBtn');
const itemType = 'delivery_images'; // Example
let reader = new FileReader();

$('#startWebcamBtn').on('click', function () {
    if (itemType === 'delivery_images') {
    $('#clearCanvasBtn').hide();
    $('#imageModalLabel').text('After Wash Image');
    } else {
    $('#clearCanvasBtn').show();
    $('#imageModalLabel').text('Mark The Affected Areas!');
    }

    navigator.mediaDevices.getUserMedia({ video: true }).then(function (mediaStream) {
    stream = mediaStream;
    video.srcObject = stream;
    $('#webcamVideo, #captureWebcamBtn').show();
    }).catch(function (err) {
    alert('Unable to access webcam: ' + err.message);
    });
});


$('#captureWebcamBtn').on('click', function () {
  const ctx = captureCanvas.getContext('2d');
  const w = captureCanvas.width;
  const h = captureCanvas.height;

  // Flip context to undo mirror effect
  ctx.save();
  ctx.translate(w, 0);
  ctx.scale(-1, 1);
  ctx.drawImage(video, 0, 0, w, h);
  ctx.restore();

  captureCanvas.toBlob(function (blob) {
    const file = new File([blob], 'captured-image.png', { type: 'image/png' });

    // Stop webcam stream
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }

    $('#webcamVideo, #captureWebcamBtn').hide();


    if (file) {
            activeItemId = 136;
            activeItemType = itemType;
            activeOrderNum = 101624;
            activeOrderId = 11;
            reader.value = null;
            reader.readAsDataURL(file);

        }


  }, 'image/png');
});

$(document).ready(function () {
reader.onload = function (e) {
        imageData = e.target.result;
        $('#imageModal').modal('show');
    };
});

</script>
  <style>
    #webcamVideo {
      width: 100%;
      max-width: 400px;
      transform: scaleX(-1); /* Flip video horizontally */
    }
    #modalImagePreview {
      max-width: 100%;
      height: auto;
    }
  </style>
@endsection