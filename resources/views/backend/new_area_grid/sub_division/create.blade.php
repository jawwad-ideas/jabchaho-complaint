@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Add Sub
                Division</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <form method="post" action="{{ route('sub.division.create') }}" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Add a Sub
                                Division</h4>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input value="{{ old('name') }}" type="text" class="form-control" name="name"
                                placeholder="Name">
                        </div>
                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Create Sub
                            Division</button>
                        <a href="{{ route('sub.division.index') }}" class="btn btn-default">Cancel</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
