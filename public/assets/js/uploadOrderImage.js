    $(document).ready(function () {
        
        let activeItemId = null; 
        let activeItemType = null;
        let canvas = null; 
        let imagesArray = {}; 

        
        $('.img-upload-input').on('change', function (e) {
            const itemId = $(this).data('item-id'); 
            const itemType = $(this).data('item-type');
            const orderNum = $(this).data('order-num');
            const orderId = $(this).data('order-id');
            const file = this.files[0];

            console.log(orderId);

            if (file) {
                activeItemId = itemId; 
                activeItemType = itemType;
                activeOrderNum = orderNum;
                activeOrderId = orderId;


                const reader = new FileReader();

                console.log(reader);
                reader.onload = function (e) {
                    
                    if (!canvas) {
                        canvas = new fabric.Canvas('imageCanvas');
                        // canvas.setHeight(500);
                        // canvas.setWidth(500);
                    } else {
                        canvas.clear(); 
                    }

                    
                    fabric.Image.fromURL(e.target.result, function (img) {
                        img.scaleToWidth(canvas.getWidth());
                        img.scaleToHeight(canvas.getHeight());
                        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                    });             
                    
                    canvas.isDrawingMode = true;
                    canvas.freeDrawingBrush.color = 'red';
                    canvas.freeDrawingBrush.width = 5;
                };

                reader.readAsDataURL(file);

                
                $('#imageModal').modal('show');
                
                       
            }
        });

        $('#clearCanvasBtn').on('click', function () {
            if (canvas) {
                const drawingObjects = canvas.getObjects('path');
                drawingObjects.forEach(function (path) {
                    canvas.remove(path);
                });
        
                // Optional: You can also disable the drawing mode after clearing
                // canvas.isDrawingMode = false;
            }
        });

        $('#saveImage').on('click', function () {
            if (canvas && activeItemId) {
                const dataURL = canvas.toDataURL({
                    format: 'png',
                    quality: 1
                });
        
                var url = '/upload-order-image';
                $.ajax({
                    url: url,  
                    method: 'POST',
                    data: {
                        item_id: activeItemId,
                        imageType: activeItemType,
                        image_data: dataURL,
                        order_num: activeOrderNum,
                        order_id :activeOrderId,
                        _token: $('meta[name="csrf-token"]').attr('content')  
                    },
                    success: function (response) {
                        if (response.success) {
                            
                           
                            // Add the image to the UI
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
        
                            // Clear the file input field
                            $(`#uploadImage-${activeItemId}`).val('');
        
                            // Hide the modal
                            $('#imageModal').modal('hide');

                            $('.img-upload-input').val('');
        
                            // Clear the canvas for the next image
                            canvas.clear();
                        } else {
                            alert('Failed to save image.');
                        }
                    },
                    error: function (xhr, status, error) {
                      
                        alert('An error occurred while saving the image. Check the console for details.');
                    }
                });
            }
        });
        

        
        // $(document).on('click', '.delete-image', function () {
        //     $(this).closest('.img-item').remove();
        // });

       
        $('#imageModal').on('hidden.bs.modal', function () {
            if (canvas) {
                canvas.clear(); 
            }
        });
    });
