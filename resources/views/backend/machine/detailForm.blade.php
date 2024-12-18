@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Machine Detail</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{ route('machine.detail.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-3">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Add Machine Detail.</h4>
                    </div>
                </div>

                <div class="container mt-4" >
                    <div class="mb-3">
                        <label for="email" class="form-label">Type</label>
                        <select class="form-control img-upload-input" id="machine_type" name="machine_id">
                            <option>--Select--</option>
                            @if(!empty($machines) )
                                @foreach($machines as $row )
                                        <option value="{{Arr::get($row, 'id')}}">{{Arr::get($row, 'name')}}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                    <div class="row align-items-center mb-3" id="file-input-container" >
                        <label for="email" class="form-label">Machine Image</label>
                        <div class="row align-items-center mb-3" >
                            <div class="col">
                                <input 
                                    type="file" 
                                    class="form-control img-upload-input" 
                                    name="image[]" 
                                    placeholder="" 
                                    accept="image/png, image/jpeg, image/jpg">
                            </div>
                            <div class="col-auto">
                                <button type="button" id="add-file-btn" class="btn btn-primary"> <i class="fas fa-plus"></i></button>
                                
                            </div>
                        </div>
                    </div>
                   
                    <div class="mb-3">
                        <label for="username" class="form-label">Barcodes</label>
                        <textarea name="barcode" class="form-control" style="height: 300px;" ></textarea>

                    </div>

                    <div>&nbsp;</div>
                    <div class="mb-3">
                        <button type="submit"
                            class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Save</button>
                        <a href="{{ route('users.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>





<script>
    $(document).ready(function () {
    let fileCount = 0;
    const maxFiles = 2; // Limit to 2 file inputs

    // Add file input
    $('#add-file-btn').click(function () {
        if (fileCount < maxFiles) {
            fileCount++;
            $('#file-input-container').append(`
                <div class="row align-items-center mb-3" id="file-${fileCount}">
                    <div class="col">
                            <input 
                                type="file" 
                                class="form-control img-upload-input" 
                                name="image[]" 
                                placeholder="" 
                                accept="image/png, image/jpeg, image/jpg">
                        </div>
                        <div class="col-auto">
                            <button 
                                type="button" 
                                class="btn btn-danger remove-file-btn" 
                                data-id="file-${fileCount}">
                                <i class="fa fa-trash"></i>
                            </button>

                        </div>
                    
                </div>
            `);

            // Disable add button if max limit is reached
            if (fileCount >= maxFiles) {
                $('#add-file-btn').prop('disabled', true);
            }
        } else {
            alert(`You can only upload up to ${maxFiles} files.`);
        }
    });

    // Remove file input
    $('#file-input-container').on('click', '.remove-file-btn', function () {
        const id = $(this).data('id');
        $('#' + id).remove();
        fileCount--;

        // Re-enable add button if below max limit
        if (fileCount < maxFiles) {
            $('#add-file-btn').prop('disabled', false);
        }
    });
});


</script>    
@endsection
