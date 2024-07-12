@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Edit Complaint Status</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>
    <div class="container mt-4">
        <form method="post" action="{{ route('complaints.status.update', $complaintStatus->id) }}" autocomplete="off">
            @csrf
            <input type="hidden" id="complaintStatusId" name="complaintStatusId"
                value="{{ Arr::get($complaintStatus, 'id') }}" />
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Edit this Complaint Status</h4>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                value="@if (old('name')) {{ old('name') }}@elseif(empty(old('name')) && old('_token')) {{ '' }}@else{{ Arr::get($complaintStatus, 'name') }} @endif"
                                type="text" class="form-control" name="name" placeholder="Name">
                        </div>

                        <div class="mb-3">
                            <label for="is_enabled" class="form-label">Is Enabled?</label>
                            <select class="form-control form-control-sm" id="is_enabled" name="is_enabled">
                                <option value="">Select...</option>
                                <option value="1"
                                    {{ old('is_enabled') == '1' || Arr::get($complaintStatus, 'is_enabled') == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('is_enabled') == '0' || Arr::get($complaintStatus, 'is_enabled') == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                        </div>


                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Update
                            Complaint Status</button>
                        <a href="{{ route('complaints.status.index') }}" class="btn btn-default">Cancel</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection