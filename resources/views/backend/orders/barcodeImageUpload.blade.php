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
                        @php
                            $order = $item->order;
                        @endphp
                        
                        @include('backend.orders.partialOrderItem', ['order' => $order])
                    @endforeach
                @else
                <div class="text-center">
                    <strong>No Record Found</strong>
                </div>
                @endif
            </div>
        </div>

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

</script>
@endsection