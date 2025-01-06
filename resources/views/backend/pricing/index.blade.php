@extends('backend.layouts.app-master')

@section('content')

<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Pricing Json</h3>
    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
            <a href="{{ route('pricing.sync') }}" class="text-decoration-none" onclick="return confirmSync()">
                <small id="" type="button" class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                    <i class="fa fa-solid fa fa-sync"></i><span>Sync Pricing</span>
                </small>
            </a>
               

            </div>
        </div>
</div>

<div class="page-content bg-white p-lg-5 px-2">
    <div class="bg-light p-2 rounded">

        <div class="mb-3">
            <label for="regular" class="form-label fw-bold text-dark m-0">Regular:</label>
            <div class="d-flex align-items-center">
                <textarea name="regular" id="regular" class="form-control" style="height: 300px;" readonly>
                @if($regular && !empty($regular->getContent())){{$regular->getContent()}}@endif
                </textarea>
                <button class="btn btn-primary ms-2" onclick="copyToClipboard('regular')">Copy</button>
            </div>
        </div>

        <div class="mb-3">
            <label for="express" class="form-label fw-bold text-dark m-0">Express:</label>
            <div class="d-flex align-items-center">
                <textarea name="express" id="express" class="form-control" style="height: 300px;" readonly>
                @if($express && !empty($express->getContent())){{$express->getContent()}}@endif
                </textarea>
                <button class="btn btn-primary ms-2" onclick="copyToClipboard('express')">Copy</button>
            </div>
        </div>

    </div>
</div>


<script>
    $(document).ready(function () {
        // Prettify Regular JSON
        var regularContent = $('#regular').val();
        try {
            var prettyRegular = JSON.stringify(JSON.parse(regularContent), null, 4);
            $('#regular').val(prettyRegular);
        } catch (error) {
            console.error("Invalid JSON in Regular field", error);
        }

        // Prettify Express JSON
        var expressContent = $('#express').val();
        try {
            var prettyExpress = JSON.stringify(JSON.parse(expressContent), null, 4);
            $('#express').val(prettyExpress);
        } catch (error) {
            console.error("Invalid JSON in Express field", error);
        }
    });

    // Function to copy content to clipboard
    function copyToClipboard(fieldId) {
        var content = document.getElementById(fieldId).value;
        navigator.clipboard.writeText(content).then(() => {
            alert("Copied to clipboard!");
        }).catch(err => {
            alert("Failed to copy: " + err);
        });
    }

    // Function to show the confirmation dialog
    function confirmSync() {
        return confirm("Are you sure you want to sync the pricing?");
    }
</script>

@endsection