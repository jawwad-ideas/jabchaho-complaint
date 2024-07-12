@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Edit District</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <div class="container mt-4">
            <form method="post" action="{{ route('district.update', $district->id) }}" autocomplete="off">
                @method('patch')
                @csrf
                <input type="hidden" name="district_id" id="district_id" value="{{ $district->id }}" />
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-section mb-5">
                            <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                                <h4 class="fw-bold mt-0">Edit this District</h4>
                            </div>
                        </div>
                        <div class="container mt-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    value="@if (old('name')) {{ old('name') }}@elseif(empty(old('name')) && old('_token')) {{ '' }}@else{{ Arr::get($district, 'name') }} @endif"
                                    type="text" class="form-control" name="name" placeholder="Name">
                            </div>
                            <button type="submit"
                                class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Update
                                District</button>
                            <a href="{{ route('district.index') }}" class="btn btn-default">Cancel</a></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
