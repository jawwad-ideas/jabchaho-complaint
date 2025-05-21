<style>
@media only screen and (max-width: 767px) {
    .page-content {
        /*margin-top: 0 !important;*/
    }

    /* .order-img {
        width: 80px !important;
        height: 100px !important;
    } */

    .scroll-to-scanner {
    position: fixed;
    bottom: 5px;
    right: 10px;
    background: #000;
    border-radius: 100%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    }

.scroll-to-scanner button {color: #fff;}

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
.page-title-section.sticky {
    position: sticky;
    top: 0;
    background: #fbee7e !important;
    padding: 30px 60px 30px 80px;
    z-index: 1;
}
@media only screen and (max-width: 600px) {
    .page-title-section.sticky {
        padding: 10px 0 15px !important;
    }
}
 </style>

<script>
    document.addEventListener('scroll', () => {
        const pageTitleSection = document.querySelector('.page-title-section');

        if (pageTitleSection) {

            const pageTitleSectionTop = pageTitleSection.offsetTop-25;

            if (window.scrollY >= pageTitleSectionTop) {
                pageTitleSection.classList.add('sticky');
                // pageTitleSection.style.top =
            } else {
                pageTitleSection.classList.remove('sticky');
            }
        }
    });


    function scrollToScanner() {
        window.scrollTo({
        top: 300,
        behavior: 'smooth',
        })
    }
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
        $("#itemIssues").modal({
            backdrop:false,
        });
        $("#itemIssues").modal('show');
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

    $(document).on('click', '#updateOrderTopButton', function(event) {
        console.log("click btn");
        $("#UpdateOrderBtn").click();
    })
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

    function sendWhatsApp( data )
    {
        const now = new Date();
        const hour = now.getHours(); // returns 0–23

        @php
            $hourStart              = Arr::get($configurations, 'laundry_order_whatsapp_sending_start_time'); //id of array
            $hourEnd                = Arr::get($configurations, 'laundry_order_whatsapp_sending_end_time'); //id of array

            $hourStartTimeFormat    = config('constants.hours.'.$hourStart); //10:00 AM.
            $hourEndTimeFormat      = config('constants.hours.'.$hourEnd); //10:00 PM.

        @endphp

        var hourStart  = "{{$hourStart}}";
        var hourEnd  = "{{$hourEnd}}";

        if (hour >= hourStart && hour < hourEnd) 
        {
            var url = '{{route('send.whatsapp')}}';
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data ,
                success: function (response) {
                    if (response.success) {
                        alert('WhatsApp sent successfully!');
                        location.reload(); // Refresh the page to reflect changes
                    } else {
                        alert('Error sending WhatsApp.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                }
            });    
        } 
        else 
        {
            //call hold method
            var url = '{{route('mark.hold.whatsapp.order')}}';
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data ,
                success: function (response) {
                    if (response.success) {
                        alert("WhatsApp messages can only be sent between {{$hourStartTimeFormat}} and {{$hourEndTimeFormat}}");
                    } else {
                        alert('Error holding WhatsApp.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                }
            });    
        }
    }

    //sendWhatsApp
    $('.sendWhatsApp').on('click', function () {

        if (!confirm('Are you sure you want to send a WhatsApp message to the customer?')) {
            return false;
        }

        const button        = $(this); // Get the button that was clicked
        const orderId       = button.data('order-id');
        const orderNumber   = button.data('order-number');
        const whatsAppType  = button.data('w-type');

        let data = {
           orderId : orderId,
           orderNumber :orderNumber,
           whatsAppType : whatsAppType,
       }

       sendWhatsApp( data );

    });

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

$('.btn[data-toggle="collapse"]').on('click', function () {
    $(this).find('.toggle-icon').toggleClass('fa-chevron-down fa-chevron-up');
  });


</script>

<script src="{{asset('assets/js/uploadOrderImage.js')}}?v={{config('constants.js_version')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>


<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Mark The Effected Areas!</h5>
                <button type="button" id="imageModalClosebtn" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="toolbar mb-3 d-flex align-items-center justify-content-end gap-2">
                <button type="button" id="clearCanvasBtn" class="btn btn-sm btn-danger rounded border-0 fw-bold d-flex align-items-center p-2 gap-2"><i class="fa fa-solid fa-eraser"></i> Remove Marking</button>
                <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="saveImage"><i class="fa fa-solid fa-upload"></i> Upload Image</button>
                <!-- <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="pencilTool"><i class="fa fa-pencil"></i>Draw</button> -->
                <!-- <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="circleTool"><i class="fa fa-circle"></i>Circle</button>
                <button type="button" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold d-flex align-items-center p-2 gap-2" id="squareTool"><i class="fa fa-square"></i>Square</button> -->
            </div>
                <canvas id="imageCanvas" style="border: 1px solid #ccc;"></canvas>
            </div>
            <!-- <div class="modal-footer">
            </div> -->
        </div>
    </div>
</div>




      <!--item issue modal start-->
      <div class="modal fade itemIssuesModal" id="itemIssues" tabindex="-1" data-bs-backdrop="" data-backdrop="false" aria-labelledby="itemIssuesLabel" aria-hidden="true">
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
                    <button data-order-id="@stack('order-id', '')" data-email-type="before_email" type="button" id="saveItemIssue" class="btn btn-sm rounded bg-theme-yellow text-dark border-0 fw-bold">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!--item issue modal end-->