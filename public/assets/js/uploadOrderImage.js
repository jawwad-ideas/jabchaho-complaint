$(document).ready(function () {

    let activeItemId = null;
    let activeItemType = null;
    let activeOrderNum = null;
    let activeOrderId = null;
    let canvas = null;
    let imageLoaded = false;
    let imageData;
    let reader = new FileReader();

    $('.img-upload-input-after').on('change', function (e) {
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
            },
            error: function (xhr, status, error) {
                alert('An error occurred while saving the image. Check the console for details.');
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
        var modalHeight = modalBody.height();

        const screenHeight = window.innerHeight;
        modalHeight = screenHeight / 2;

        if (!canvas) {
            canvas = new fabric.Canvas('imageCanvas');
        }

        canvas.setWidth(modalWidth);
        canvas.setHeight(modalHeight);

        if (!imageLoaded) {
            imageLoaded = true;
            fabric.Image.fromURL(imageData, function (img) {
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

        canvas.isDrawingMode = true;
        canvas.freeDrawingBrush.color = 'red';
        canvas.freeDrawingBrush.width = 5;

        // $('#pencilTool').on('click', function () {
        //     canvas.isDrawingMode = true;
        //     canvas.freeDrawingBrush.color = 'red';
        //     canvas.freeDrawingBrush.width = 5;
        // });
        // $('#circleTool').on('click', function () {
        //     canvas.isDrawingMode = false;
        //     const circle = new fabric.Circle({
        //         radius: 50,
        //         fill: 'transparent',
        //         stroke: 'red',
        //         strokeWidth: 2,
        //         left: canvas.width / 2 - 50,
        //         top: canvas.height / 2 - 50,
        //         selectable: true
        //     });
        //     canvas.add(circle);
        // });
        // $('#squareTool').on('click', function () {
        //     canvas.isDrawingMode = false;
        //     const square = new fabric.Rect({
        //         width: 100,
        //         height: 100,
        //         fill: 'transparent',
        //         stroke: 'red',
        //         strokeWidth: 2,
        //         left: canvas.width / 2 - 50,
        //         top: canvas.height / 2 - 50,
        //         selectable: true
        //     });
        //     canvas.add(square);
        // });

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
                    order_id: activeOrderId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
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
                        $('.img-upload-input').val('');


                        $('#imageModal').modal('hide');
                        imageLoaded = false;

                        if( !response.disableAfterUploadInput  ){
                            $(".img-upload-input-after").attr("disabled", false);
                        }

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

});
