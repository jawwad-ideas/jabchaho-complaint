@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Add New Permission</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Add a New Permission.</h4>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input value="{{ old('name') }}" type="text" class="form-control" name="name"
                                placeholder="Name">
                        </div>

                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Save
                            permission</button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
