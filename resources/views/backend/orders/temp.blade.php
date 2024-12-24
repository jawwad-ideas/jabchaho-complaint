@extends('backend.layouts.app-master')

@section('content')


    <style>
        /* Button to start the barcode scanner */
        #startScanner {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #startScanner:hover {
            background-color: #0056b3;
        }

        /* Media query to hide the button on larger screens */
        @media (min-width: 768px) {
            #startScanner {
                display: none; /* Hide the button on tablet and larger screens */
            }
        }
       

        /* Style for the video feed */
        #scanner {
            width: 100%;
            max-width: 500px;
            height: auto;
            border: 2px solid black;
            margin: 20px auto;
            display: block;
        }

        /* Display scanned barcode value */
        #barcode-value {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }

        .container {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .highlight {
            background-color: #ffff99;
            transition: background-color 0.5s ease;
        }
    </style>


    <!-- Button to start the barcode scanner -->
    <button id="startScanner" class="mobile-only">Barcode Scan</button>

    <!-- Div to display barcode scanning -->
    <div id="scanner-container"></div>

    <!-- Display scanned barcode -->
    <div id="barcode-value">Scanned Barcode: <span id="code"></span></div>

    <!-- List of barcodes (for demonstration purposes) -->
	<label class="d-flex ">
		<h6 class="d-inline-block fw-bold"> Barcode: </h6>
		<span class="barcode"> 1234567890 </span>
	</label>
		<label class="d-flex ">
		<h6 class="d-inline-block fw-bold"> Barcode: </h6>
		<span class="barcode"> 9876543210 </span>
	</label>
		<label class="d-flex ">
		<h6 class="d-inline-block fw-bold"> Barcode: </h6>
		<span class="barcode"> 102288_10589_9_3 </span>
	</label>
		<label class="d-flex ">
		<h6 class="d-inline-block fw-bold"> Barcode: </h6>
		<span class="barcode"> 102288_10589_9_2 </span>
	</label>
		<label class="d-flex ">
		<h6 class="d-inline-block fw-bold"> Barcode: </h6>
		<span class="barcode"> 102288_10589_9_1 </span>
	</label>


    <!-- Include the html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            let scanner;

            // Add click event for the "Start Barcode Scanner" button
            $("#startScanner").click(function () {
                const scannerContainer = "scanner-container"; // Use the ID of the div, not the element itself

                // Create an instance of the barcode scanner
                scanner = new Html5QrcodeScanner(scannerContainer, {
                    fps: 10, // Frames per second for scanning
                    qrbox: { width: 250, height: 250 }, // Define the scanning box size
                });

                // Start scanning
                scanner.render(onScanSuccess, onScanError);
            });

            // Success callback when barcode is scanned
            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Scanned Barcode: ${decodedText}`);
                $("#code").text(decodedText);

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
                    labelElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
