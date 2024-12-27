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
                        <label for="username" class="form-label">Before the dryer barcodes:</label>
                        <textarea name="before_barcodes" id="before-barcode" class="form-control" style="height: 300px;" disabled>{{ $beforeBarcodesNewLineSeparated }}</textarea>
                    </div>


                    <div class="mb-3">
                        <label for="username" class="form-label">After the dryer barcodes:</label>
                        <textarea name="after_barcodes" id="after-barcode" class="form-control" style="height: 300px;"  @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed')) readonly @else disabled @endif required>@if(old('after_barcodes')) {{ old('after_barcodes') }} @else {{$afterBarcodesNewLineSeparated}} @endif</textarea>
                        @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed'))
                        <input type="button" class="btn btn-danger remove-file-btn mt-3" id="after-removeLine" value="Remove After Barcode">
                        @endif    
                    </div>

                    <div>&nbsp;</div>
                    <div class="mb-3">
                        @if(Arr::get($dryer, 'status') != config('constants.dryer_statues_id.completed'))
                            <input type="submit"
                                class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3" value="Save">
                        @endif    
                        <a href="{{ route('sunny.dryer') }}" class="btn bg-theme-dark-300 text-light">Back</a>
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

  let activeInput = null; // Tracks which input field is active
  let barcodeData = ""; // Temporary storage for the current barcode
  let scannerTimeout;

  // Listen for focus events to track active input field

  afterBarcodeInput.addEventListener("focus", () => {
    activeInput = afterBarcodeInput;
  });

  // Capture barcode input
  document.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      // When "Enter" is pressed, append barcode to the active input field
      if (activeInput) {
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
  beforeBarcodeInput.readOnly = true;
});




// Remove a line from the "after-barcode" textarea
document.getElementById("after-removeLine").addEventListener("click", function () {
    removeLine("after-barcode");
});

// Remove a line from the "before-barcode" textarea
document.getElementById("before-removeLine").addEventListener("click", function () {
    removeLine("before-barcode");
});

/**
 * Removes the line where the caret is located in the specified textarea.
 * @param {string} textareaId - The ID of the textarea.
 */
function removeLine(textareaId) {
    const textarea = document.getElementById(textareaId);

    // Get the content of the textarea
    const content = textarea.value;

    // Get the caret position in the textarea
    const startPos = textarea.selectionStart;

    // Split content into lines
    const lines = content.split("\n");

    // Calculate which line the cursor is on
    let charCount = 0; // Cumulative character count
    let selectedLineIndex = -1;

    for (let i = 0; i < lines.length; i++) {
        // Count characters in this line + 1 for the newline
        charCount += lines[i].length + 1;

        // If the startPos falls within this line, capture the index
        if (startPos <= charCount - 1) {
            selectedLineIndex = i;
            break;
        }
    }

    // Remove the selected line only if a valid line is found
    if (selectedLineIndex !== -1) {
        lines.splice(selectedLineIndex, 1); // Remove the line
        textarea.value = lines.join("\n"); // Update the textarea content
    }
}





</script>


 
@endsection
