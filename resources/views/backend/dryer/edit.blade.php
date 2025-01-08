@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Sunny dryer</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{ route('sunny.dryer.update',Arr::get($dryer,'id')) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-3">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Edit the details of the Sunny Dryer.</h4>
                    </div>
                </div>

                <div class="container mt-4" >
                    <div class="mb-3">
                        <label for="username" class="form-label">Status:</label>
                        {{config('constants.dryer_statues.'.Arr::get($dryer, 'status'))}}
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Lot<span class="red"> *</span></label>
                        <input value="{{Arr::get($dryer, 'lot_number')}}" type="text" class="form-control" name="lot_number" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Before the dryer barcodes<span class="red"> *</span></label>
                        <textarea name="before_barcodes" id="before-barcode" class="form-control" style="height: 300px;" disabled>{{ $beforeBarcodesNewLineSeparated }}</textarea>
                        <strong><small>Total Before the dryer barcodes: @if(!empty(Arr::get($dryer,'before_barcodes'))) {{ count(explode(',', Arr::get($dryer,'before_barcodes')))}} @else 0 @endif</small></strong>
                    </div>


                    <div class="mb-3">
                        <label for="username" class="form-label">After the dryer barcodes<span class="red"> *</span></label>
                        <textarea name="after_barcodes" id="after-barcode" class="form-control" style="height: 300px;" @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed')) readonly @else disabled @endif required>@if(old('after_barcodes')){{old('after_barcodes'). "\r\n"}}@else{{$afterBarcodesNewLineSeparated}}@endif</textarea>
                        @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed'))
                        <input type="button" class="btn btn-danger remove-file-btn mt-3" id="after-removeLine" value="Remove Selected Barcode">
                        @else
                        <strong><small>Total After the dryer barcodes: @if(!empty(Arr::get($dryer,'after_barcodes'))) {{ count(explode(',', Arr::get($dryer,'after_barcodes')))}} @else 0 @endif</small></strong>
                        @endif    
                    </div>

                    <div>&nbsp;</div>
                    <div class="mb-3">
                        @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed'))
                            <input type="submit"
                                class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3" value="Save">
                        @endif    
                        <a href="javascript:history.back()" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script>

//insert barcodes in readonly 
document.addEventListener("DOMContentLoaded", () => {
  const afterBarcodeInput = document.getElementById("after-barcode");
  //const afterBarcodeInput = document.getElementById("after-barcode");

  let activeInput = null; // Tracks which input field is active
  let barcodeData = ""; // Temporary storage for the current barcode
  let scannerTimeout;

  // Listen for focus events to track active input field
  afterBarcodeInput.addEventListener("focus", () => {
    activeInput = afterBarcodeInput;
  });
//   afterBarcodeInput.addEventListener("focus", () => {
//     activeInput = afterBarcodeInput;
//   });



  // Capture barcode input
  document.addEventListener("keypress", (event) => {

    if (event.key === "Enter") {
      // When "Enter" is pressed, append barcode to the active input field
      if (activeInput) 
      {
        activeInput.value += barcodeData.trim() + "\n"; // Append barcode
      }
      barcodeData = ""; // Reset barcode data for the next scan
    } else {
      // Accumulate barcode data
      barcodeData += event.key;

      // Reset the barcode data after a short timeout to detect scanning pauses
      clearTimeout(scannerTimeout);
      scannerTimeout = setTimeout(() => {
        barcodeData = ""; // Reset after timeout
      }, 200); // Adjust timeout based on scanner speed
    }
  });

  // Ensure beforeBarcodeInput is readonly
  afterBarcodeInput.readOnly = true;
});





// Remove a line from the "after-barcode" textarea
document.getElementById("after-removeLine").addEventListener("click", function () {
    removeLine("after-barcode");
});



/**
 * Removes the line where the caret is located in the specified textarea.
 * @param {string} textareaId - The ID of the textarea.
 */
function removeLine(textareaId) {
    const textarea = document.getElementById(textareaId);

    // Get the selected text
    const selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);

    // Check if any text is selected
    if (!selectedText.trim()) {
        console.log("No text selected. Nothing to remove.");
        textarea.focus(); // Ensure the field remains focused
        return;
    }

    // Get the content of the textarea
    const content = textarea.value;

    // Split content into lines
    const lines = content.split("\n");

    // Find and remove the line containing the selected text
    for (let i = 0; i < lines.length; i++) {
        if (lines[i].includes(selectedText)) {
            lines.splice(i, 1); // Remove the line containing the selection
            textarea.value = lines.join("\n"); // Update the textarea content
            break;
        }
    }

    // Restore focus and cursor position
    textarea.focus();
}

// Handle barcode scanner input
document.getElementById("after-barcode").addEventListener("input", (event) => {
    const textarea = event.target;
    let content = textarea.value;

    // Normalize line endings
    content = content.replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    // If the content doesn't end with a newline, append one
    if (content && !content.endsWith("\n")) {
        textarea.value = content + "\n";
    }

    // Prevent concatenation: reset if the textarea is empty
    if (!textarea.value.trim()) {
        textarea.value = "";
    }

    // Scroll to the bottom to show the most recent barcode
    textarea.scrollTop = textarea.scrollHeight;
});




</script>


 
@endsection
