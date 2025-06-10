$(document).ready(function () {

    //let activeItemType = null;
    //let activeOrderNum = null;
    //let activeOrderId = null;
    let canvas = null;
    let imageLoaded = false;
    let reader = new FileReader();

    if (typeof imageData === 'undefined' || imageData === null) {
       let imageData; // declare it
    }

    if (typeof activeItemId === 'undefined' || activeItemId === null) {
       let activeItemId = null; // declare it
    }


    if (typeof activeItemType === 'undefined' || activeItemType === null) {
       let activeItemType = null; // declare it
    }

    if (typeof activeOrderNum === 'undefined' || activeOrderNum === null) {
       let activeOrderNum = null; // declare it
    }

    if (typeof activeOrderId === 'undefined' || activeOrderId === null) {
       let activeOrderId = null; // declare it
    }


    $(document).on('change', '.img-upload-input-after', function (e) {
        //alert("Debugging After Upload");
        const itemId = $(this).data('item-id');
        const itemType = $(this).data('item-type');
        const orderNum = $(this).data('order-num');
        const orderId = $(this).data('order-id');
        const file = e.target.files[0];

        if (!file) {
            alert('Please select a file.');
            return;
        }

        activeItemId = itemId;
        activeItemType = itemType;
        activeOrderNum = orderNum;
        activeOrderId = orderId;

        let formData = new FormData();
        formData.append('image', file);
        formData.append('item_id', itemId);
        formData.append('imageType', itemType);
        formData.append('order_num', orderNum);
        formData.append('order_id', orderId);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $(".loader").addClass("show");

        var url = '/upload-order-image-whithoutbase64';
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false, // Required for FormData
            contentType: false,
            success: function (response) {

                if (response.success) {
                    const imageHtml = `
                            <div class="img-item">
                                <a href="${response.image_url}" target="_blank">
                                    <img class="img-thumbnail" src="${response.image_url}" alt="Edited Image">
                                </a>
                                <div class="item-img-action-btn">
                                    <button class="btn btn-danger btn-sm delete-image ms-2" title="Delete" data-image-id="${response.item_image_id}" data-order-number="${activeOrderNum}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    $(`#items-images-sec-${activeItemType}-${activeItemId}`).append(imageHtml);


                    $(`#uploadImage-${activeItemId}`).val('');
                    $('.img-upload-input-after').val('');

                    imageLoaded = false;

                } else {
                    alert('Failed to save image.');
                }
                $(".loader").removeClass("show");
            },

            error: function (xhr, status, error) {
                alert('An error occurred while saving the image. Check the console for details.');
                $(".loader").removeClass("show");
            }
        });
    });

    $('.img-upload-input').on('change', function (e) {
        const itemId = $(this).data('item-id');
        const itemType = $(this).data('item-type');
        const orderNum = $(this).data('order-num');
        const orderId = $(this).data('order-id');
        const file = this.files[0];


        console.log(itemType);
        if (itemType == 'delivery_images') {
            $("#clearCanvasBtn").attr("style", "display: none !important;");
            $('#imageModalLabel').text("After Wash Image");

        } else {
            $("#clearCanvasBtn").attr("style", "display: flex !important;");
            $('#imageModalLabel').text("Mark The Affected Areas!");
        }

        if (file) {
            activeItemId = itemId;
            activeItemType = itemType;
            activeOrderNum = orderNum;
            activeOrderId = orderId;
            reader.value = null;
            reader.readAsDataURL(file);

        }
    });




    $('#imageModal').on('shown.bs.modal', function () {
        const modalBody = $('#imageModal .modal-body');
        const modalWidth = modalBody.width();
        const screenHeight = window.innerHeight;
        const modalHeight = screenHeight / 2;
    
        if (!canvas) {
            canvas = new fabric.Canvas('imageCanvas');
        }
    
        canvas.clear(); // Ensure no duplicate images
        canvas.setWidth(modalWidth);
        canvas.setHeight(modalHeight);
    
        if (!imageLoaded) {
            imageLoaded = true;
    
            fabric.Image.fromURL(imageData, function (img) {
                originalImage = img; // Store original full-size image
    
                // Scale for modal preview
                const scaleWidth = modalWidth / img.width;
                const scaleHeight = modalHeight / img.height;
                const scale = Math.min(scaleWidth, scaleHeight);
    
                img.scale(scale);
                img.set({
                    left: (modalWidth - img.getScaledWidth()) / 2,
                    top: (modalHeight - img.getScaledHeight()) / 2,
                    selectable: false
                });
    
                canvas.add(img);
                canvas.sendToBack(img);
                canvas.renderAll();
            });
        }
    
        // Enable drawing mode
        canvas.isDrawingMode = true;
        canvas.freeDrawingBrush.color = 'red';
        canvas.freeDrawingBrush.width = 5;
    });
    

    $('#imageModal').on('hidden.bs.modal', function (e) {
        if (canvas) {
            canvas.clear();
            imageLoaded = false;
        }

        $('.img-upload-input').val('');
    });

    reader.onload = function (e) {
        imageData = e.target.result;
        $('#imageModal').modal('show');
    };

    $('#clearCanvasBtn').on('click', function () {
        if (canvas) {
            canvas.getObjects().forEach(function (obj) {
                if (!(obj instanceof fabric.Image)) {
                    canvas.remove(obj);
                }
            });
        }
    });


    $('#saveImage').on('click', async function () {

        if (canvas && activeItemId) {
            $(".loader").addClass("show");
    
            let fullSizeImageData = await getOriginalSizeImageWithMarkings();
    
            if (!fullSizeImageData) {
                alert("Failed to generate full-size image.");
                $(".loader").removeClass("show");
                return;
            }
    
            const formData = new FormData();
            formData.append('item_id', activeItemId);
            formData.append('imageType', activeItemType);
            formData.append('order_num', activeOrderNum);
            formData.append('order_id', activeOrderId);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('image_data', fullSizeImageData);
    
            $.ajax({
                url: '/upload-order-image',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        const imageHtml = `
                            <div class="img-item before-image">
                                <a href="${response.image_url}" target="_blank">
                                    <img class="img-thumbnail" src="${response.image_url}" alt="Edited Image">
                                </a>
                                <div class="item-img-action-btn">
                                    <button class="btn btn-danger btn-sm delete-image ms-2" title="Delete" data-image-id="${response.item_image_id}" data-order-number="${activeOrderNum}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        $(`#items-images-sec-${activeItemType}-${activeItemId}`).append(imageHtml);
    
                        $('#uploadImage-' + activeItemId).val('');
                        $('.img-upload-input').val('');
                        $('#imageModal').modal('hide');
                        imageLoaded = false;
                        $(".img-upload-input-after").attr("disabled", false);
                        $('.barcode-img-upload').attr("disabled", false);
                        canvas.clear();
                    } else {
                        alert('Failed to save image.');
                    }
                    $(".loader").removeClass("show");
                },
                error: function () {
                    alert('An error occurred while saving the image.');
                    $(".loader").removeClass("show");
                }
            });
        }
    });



    function getOriginalSizeImageWithMarkings() {
        return new Promise((resolve) => {
            if (!originalImage) {
                resolve(null);
                return;
            }
    
            // Create and configure original canvas
            let originalCanvas = document.createElement('canvas');
            originalCanvas.width = originalImage.width;
            originalCanvas.height = originalImage.height;
            let ctx = originalCanvas.getContext('2d');
            let imgElement = originalImage.getElement();
            
            // Draw the original image onto the canvas
            ctx.drawImage(imgElement, 0, 0, originalImage.width, originalImage.height);
    
            // Create a temporary canvas for the drawing objects
            let tempCanvas = new fabric.Canvas();
            tempCanvas.setWidth(originalImage.width);
            tempCanvas.setHeight(originalImage.height);
            
            // Add canvas objects to tempCanvas, scaled appropriately
            canvas.getObjects().forEach((obj) => {
                if (obj.type !== 'image') {
                    let clonedObj = fabric.util.object.clone(obj);
                    let scaleX = originalImage.width / canvas.width;
                    let scaleY = originalImage.height / canvas.height;
                    clonedObj.scaleX *= scaleX;
                    clonedObj.scaleY *= scaleY;
                    clonedObj.left *= scaleX;
                    clonedObj.top *= scaleY;
                    clonedObj.setCoords();
                    tempCanvas.add(clonedObj);
                }
            });
    
            tempCanvas.renderAll();
    
            // Convert the drawings to an image once tempCanvas is ready
            let drawingImage = new Image();
            drawingImage.src = tempCanvas.toDataURL("image/png");
    
            drawingImage.onload = function () {
                // Draw the final image with markings onto originalCanvas
                ctx.drawImage(drawingImage, 0, 0, originalImage.width, originalImage.height);
    
                // Dynamic resizing while maintaining aspect ratio
                const SCALE_FACTOR = 0.5;
                let width = originalImage.width * SCALE_FACTOR;
                let height = originalImage.height * SCALE_FACTOR;
    
                // Optional max width/height limits
                const MAX_WIDTH = 1800;
                const MAX_HEIGHT = 1800;
    
                // Apply max size restrictions if needed
                if (width > MAX_WIDTH || height > MAX_HEIGHT) {
                    const ratio = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
                    width *= ratio;
                    height *= ratio;
                }
    
                // Resize the image if needed
                let scaledCanvas = document.createElement('canvas');
                scaledCanvas.width = width;
                scaledCanvas.height = height;
                let scaledCtx = scaledCanvas.getContext('2d');
                scaledCtx.drawImage(originalCanvas, 0, 0, width, height);
    
                // Compress and return image in original format
                let format = originalImage.getElement().src.split(';')[0].split('/')[1];
                let mimeType = `image/${format}`;
    
                scaledCanvas.toBlob((blob) => {
                    let reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function () {
                        resolve(reader.result); // Return the compressed image
                    };
                }, mimeType, 0.5); // Compress to 50%
            };
        });
    }
    
    
    
    
    
    
    
});
