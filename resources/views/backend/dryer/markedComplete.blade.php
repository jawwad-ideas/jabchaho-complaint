@extends('backend.layouts.app-master')

@section('content')


<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Sunny dryer</h3>
    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
      
        @if(Auth::user()->can('sunny.dryer.create'))
            <div class="btn-group" role="group">
                <a href="{{ route('sunny.dryer.create') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-atom"></i><span>Add</span></small>
                </a>
            </div>
        @endif 

        
    </div>

</div>


<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{ route('sunny.dryer.marked.complete') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-3">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Mark the details of the Sunny Dryer as complete.</h4>
                    </div>
                </div>

                <div class="container mt-4" >
                    <div class="mb-3">
                        <label for="username" class="form-label">After the dryer barcodes<span class="red"> *</span></label>
                        <textarea name="barcode" id="barcode" class="form-control" style="height: 300px;" required>{{ old('barcode'). "\r\n" }}</textarea>
                        
                    </div>
                    
                    <div class="mb-3">
                    <a href="javascript:history.back()" class="btn bg-theme-dark-300 text-light">Back</a>
                        <input type="submit"
                            class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3" value="Save">
                            
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>





 
@endsection
