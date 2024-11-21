@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Add New Order</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{route('orders.save')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Add New Order And Upload Images.</h4>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="order" class="form-label">Order#</label>
                        <input value="{{ old('order_number') }}" type="text" class="form-control" name="order_number"
                            placeholder="Order Number">

                    </div>
                    <div class="mb-3">
                        <label for="pickup_images" class="form-label">Before Wash Images *</label>
                        <input value="{{ old('pickup_images[]') }}" type="file" class="form-control" name="pickup_images[]"
                            placeholder="Before Wash Images" multiple required>

                    </div>

                    <div class="mb-3">
                        <label for="delivery_images" class="form-label">After Wash Images</label>
                        <input value="{{ old('delivery_images[]') }}" type="file" class="form-control" name="delivery_images[]"
                               placeholder="After Wash Images" multiple>
                    </div>

                    <div>&nbsp;</div>
                    <div class="mb-3">
                        <button type="submit"
                            class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Save order</button>
                        <a href="{{ route('orders.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function(e) {
    $('.mySelect').select2();
});
</script>
<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection
