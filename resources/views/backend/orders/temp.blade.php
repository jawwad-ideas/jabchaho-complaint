@extends('backend.layouts.app-master')
<style>
@media only screen and (max-width: 767px) {
    .page-content {
        /*margin-top: 0 !important;*/
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

{{--<div class="page-content bg-white p-lg-5 px-2">
    <form method="POST" action="{{route('orders.upload.save')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0 ">Edit Order And Upload Images.</h4>
                    </div>
                </div>

                <div class="container mt-4 p-0">

                    <div class="mb-3">
                        <label for="remarks_attachment" class="form-label">Attachment</label>
                        <input value="" type="file" class="form-control" name="remarks_attachment" placeholder="" accept="image/*" capture="environment">
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3"> Update order </button>
                        <a href="{{ route('orders.index') }}/1" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>--}}



<div class="page-content bg-white p-lg-5 px-2">
    <form id="imageForm" method="POST" action="{{ route('orders.upload.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Edit Order and Upload Images with Annotations</h4>
                    </div>
                </div>

                <div class="container mt-4 p-0">
                    <!-- File Upload Inputs -->
                    <div class="mb-3">
                        <label for="remarks_attachment" class="form-label">Attachment 1</label>
                        <input id="fileInput1" type="file" class="form-control" name="remarks_attachment" accept="image/*" capture="environment">
                        <input type="hidden" id="annotatedImage1" name="annotated_image_1">

                        <label for="remarks_attachment2" class="form-label mt-3">Attachment 2</label>
                        <input id="fileInput2" type="file" class="form-control" name="remarks_attachment2" accept="image/*" capture="environment">
                        <input type="hidden" id="annotatedImage2" name="annotated_image_2">
                    </div>

                    <!-- Hidden Modal for Editing -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Image Annotations</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Drawing Type Selection -->
                                    <div class="mb-3">
                                        <label for="drawingType" class="form-label">Choose Drawing Type</label>
                                        <select id="drawingType" class="form-select">
                                            <option value="circle">Circle</option>
                                            <option value="rectangle">Rectangle</option>
                                            <option value="freeDraw">Free Draw</option>
                                        </select>
                                    </div>

                                    <!-- Color Picker -->
                                    <div class="mb-3">
                                        <label for="colorPicker" class="form-label">Choose Color</label>
                                        <input id="colorPicker" type="color" value="#ff0000" />
                                    </div>

                                    <!-- Fabric.js Canvas -->
                                    <div class="mb-3">
                                        <label class="form-label">Highlight Spots on Image</label>
                                        <canvas id="clothingCanvas" style="border: 1px solid #ddd;"></canvas>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="mb-3">
                                        <button type="button" id="deleteHighlight" class="btn bg-danger text-light">Delete Selected Highlight</button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- Save Image Button (AJAX based) -->
                                    <button type="button" id="saveImageBtn" class="btn btn-primary">Save Image</button>
                                    <!-- Cancel Button (Submit Form) -->
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Order Button (Outside Modal) -->
                <div class="form-group">
                    <button type="submit" id="updateOrderBtn" class="btn bg-theme-yellow text-dark">Update Order</button>
                    <a href="{{ route('orders.index') }}/1" class="btn bg-theme-dark-300 text-light">Back</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include Fabric.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
    const canvas = new fabric.Canvas('clothingCanvas');
    const canvasContainer = document.getElementById('clothingCanvas');
    let isImageLoaded = false;
    let currentDrawingType = 'circle'; // Default drawing type
    let currentColor = '#ff0000'; // Default color
    let currentFileInput = null; // Track which file input triggered the event
    let currentAnnotatedImageInput = null; // Track the hidden input for storing the annotation

    // Function to resize canvas based on image dimensions
    function resizeCanvas(image) {
        const aspectRatio = image.width / image.height;
        const maxCanvasWidth = 800;
        const maxCanvasHeight = 600;

        if (aspectRatio >= 1) {
            canvas.setWidth(maxCanvasWidth);
            canvas.setHeight(maxCanvasWidth / aspectRatio);
        } else {
            canvas.setHeight(maxCanvasHeight);
            canvas.setWidth(maxCanvasHeight * aspectRatio);
        }

        canvasContainer.style.width = `${canvas.width}px`;
        canvasContainer.style.height = `${canvas.height}px`;
    }

    // Load Uploaded Image to Canvas
    function loadImageToCanvas(fileInput, annotatedImageInput) {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                fabric.Image.fromURL(event.target.result, function (img) {
                    canvas.clear();
                    resizeCanvas(img);
                    img.scaleToWidth(canvas.width);
                    img.scaleToHeight(canvas.height);
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                    isImageLoaded = true;

                    // Store the current annotated image input field reference
                    currentAnnotatedImageInput = annotatedImageInput;

                    // Show Modal when Image is Loaded
                    $('#editModal').modal('show');
                });
            };
            reader.readAsDataURL(file);
        }
    }

    // Add event listeners for both file inputs
    document.getElementById('fileInput1').addEventListener('change', function (e) {
        currentFileInput = e.target;
        loadImageToCanvas(currentFileInput, document.getElementById('annotatedImage1'));
    });

    document.getElementById('fileInput2').addEventListener('change', function (e) {
        currentFileInput = e.target;
        loadImageToCanvas(currentFileInput, document.getElementById('annotatedImage2'));
    });

    // Add or Adjust Highlight Spots
    canvas.on('mouse:down', function (opt) {
        if (!isImageLoaded) {
            alert('Please upload an image first.');
            return;
        }

        const pointer = canvas.getPointer(opt.e);

        // Check if an object is selected (to adjust)
        const activeObject = canvas.getActiveObject();
        if (activeObject) {
            return; // If an object is selected, do nothing on click
        }

        // Disable free drawing when drawing shapes (circle, rectangle)
        if (currentDrawingType !== 'freeDraw') {
            canvas.isDrawingMode = false; // Disable free drawing when adding shapes
        }

        // Draw based on current drawing type
        if (currentDrawingType === 'circle') {
            const circle = new fabric.Circle({
                left: pointer.x,
                top: pointer.y,
                radius: 30,
                fill: '', // No fill color
                stroke: currentColor,
                strokeWidth: 2,
                originX: 'center',
                originY: 'center',
                selectable: true, // Allow selection for adjustments
            });
            canvas.add(circle);
            canvas.setActiveObject(circle);
        } else if (currentDrawingType === 'rectangle') {
            const rect = new fabric.Rect({
                left: pointer.x,
                top: pointer.y,
                width: 60, // Adjust the width as needed
                height: 40, // Adjust the height as needed
                fill: '', // No fill color
                stroke: currentColor,
                strokeWidth: 2,
                originX: 'center',
                originY: 'center',
                selectable: true, // Allow selection for adjustments
            });
            canvas.add(rect);
            canvas.setActiveObject(rect);
        } else if (currentDrawingType === 'freeDraw') {
            // Enable free drawing mode with selected color
            canvas.isDrawingMode = true;
            canvas.freeDrawingBrush.color = currentColor;
            canvas.freeDrawingBrush.width = 2; // Set the brush width for free drawing
        }
    });

    // Change Drawing Type (Circle, Rectangle, Free Draw)
    document.getElementById('drawingType').addEventListener('change', function (e) {
        currentDrawingType = e.target.value;
        canvas.isDrawingMode = false; // Disable drawing mode when switching to shapes
    });

    // Change Color (Using Color Picker)
    document.getElementById('colorPicker').addEventListener('input', function (e) {
        currentColor = e.target.value;
        if (canvas.isDrawingMode) {
            canvas.freeDrawingBrush.color = currentColor; // Change color for free draw
        }
    });

    // Allow Object Adjustments (for resizing or moving)
    canvas.on('object:moving', function (e) {
        const obj = e.target;
        obj.left = Math.max(obj.width / 2, Math.min(obj.left, canvas.width - obj.width / 2));
        obj.top = Math.max(obj.height / 2, Math.min(obj.top, canvas.height - obj.height / 2));
    });

    // Delete Selected Highlight (if any)
    document.getElementById('deleteHighlight').addEventListener('click', function () {
        const activeObject = canvas.getActiveObject();
        if (activeObject) {
            canvas.remove(activeObject); // Remove selected object
        } else {
            alert('No object selected to delete.');
        }
    });

    // Save Image (AJAX-based)
    document.getElementById('saveImageBtn').addEventListener('click', function () {
        if (!isImageLoaded) {
            alert('Please upload and annotate an image before saving.');
            return;
        }

        // Capture Annotated Image for the specific hidden input
        if (currentAnnotatedImageInput) {
            const annotatedImage = canvas.toDataURL('image/png');
            currentAnnotatedImageInput.value = annotatedImage;
        }

        // Use AJAX to send form data to the server
        const formData = new FormData(document.getElementById('imageForm'));

        fetch('{{ route('orders.upload.save') }}', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Image saved successfully!');
                    $('#editModal').modal('hide'); // Hide modal after successful save
                } else {
                    alert('Error saving image.');
                }
            })
            .catch(error => {
                alert('Error saving image.');
                console.error(error);
            });
    });
</script>




@endsection
