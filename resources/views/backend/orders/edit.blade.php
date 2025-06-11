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
            @if ($sendBeforeEmail ||  $sendFinalEmail)
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
                    @if ($sendFinalEmail)
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

                    <!-- Div to display barcode scanning -->
                    <div id="#barcode_scanner_section">
                        <div id="scanner-container"></div>
                    </div>

                    @foreach ($order->orderItems as $item)
                        @include('backend.orders.partialOrderItem', ['order' => $order])
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