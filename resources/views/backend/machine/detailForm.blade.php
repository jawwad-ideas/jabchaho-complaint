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

    <form method="POST" action="">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Add Machine Detail.</h4>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">Machine Image</label>
                        <input type="file" class="form-control img-upload-input" name="image" placeholder="" accept="image/png, image/jpeg, image/jpg" >

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Type</label>
                        <select class="form-control img-upload-input" id="machine_type">
                            <option>--Select--</option>
                            @if(!empty($machines) )
                                @foreach($machines as $row )
                                        <option value="{{Arr::get($row, 'id')}}">{{Arr::get($row, 'name')}}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Barcodes</label>
                        <textarea name="remarks" class="form-control" style="height: 300px;"></textarea>

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


@endsection
