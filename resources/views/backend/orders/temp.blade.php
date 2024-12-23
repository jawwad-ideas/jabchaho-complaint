@extends('backend.layouts.app-master')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        #scanner-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        video {
            width: 100%;
            height: auto;
        }
        #barcode-result {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
    </style>

    <div id="scanner-container">
        <video id="scanner"></video>
        <div id="barcode-result">Scanned Barcode: <span id="barcode-value">None</span></div>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize QuaggaJS
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner'), // Video element
                    constraints: {
                        facingMode: "environment" // Use rear camera
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader"] // Supported barcode formats
                }
            }, function (err) {
                if (err) {
                    console.error(err);
                    alert("Failed to start the barcode scanner!");
                    return;
                }
                Quagga.start();
                console.log("Barcode scanner started");
            });

            // Process scanned barcode
            Quagga.onDetected(function (data) {
                var code = data.codeResult.code;
                $("#barcode-value").text(code); // Display scanned barcode
                console.log("Scanned Barcode:", code);

                // Stop scanner after successful scan
                Quagga.stop();
            });
        });
    </script>




@endsection
