@extends('backend.layouts.app-master')

@section('content')
<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Update Review</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>


    <form method="post" action="{{ route('reviews.update', $review->id) }}" autocomplete="off">
        @method('patch')
        @csrf

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Edit Review</h4>
                        <p class="text-muted mb-0">Update review details and status.</p>
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Service Quality</label>
                        <div class="rating-box">
                            <div class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="fa fa-star {{ $i <= Arr::get($review, 'service_quality') ? 'checked' : '' }}"></span>
                                    @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Timeliness & Convenience</label>
                        <div class="rating-box">
                            <div class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="fa fa-star {{ $i <= Arr::get($review, 'timelines_convenience') ? 'checked' : '' }}"></span>
                                    @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Pricing & Value</label>
                        <div class="rating-box">
                            <div class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="fa fa-star {{ $i <= Arr::get($review, 'pricing_value') ? 'checked' : '' }}"></span>
                                    @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Order No.</label>
                        <div class="form-view-box">
                            <a href="{{ config('app.admin_order_url') }}/{{ Arr::get($review, 'order_id') }}"
                                target="_blank"
                                class="text-decoration-none fw-semibold text-primary">
                                {{ Arr::get($review, 'order_id') }}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Created</label>
                        <div class="form-view-box">
                            {{ date("d M, Y h:i A", strtotime(Arr::get($review, 'created_at'))) }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Customer Email</label>
                        <div class="form-view-box">
                            {{ Arr::get($review, 'email') ?: '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Customer Phone</label>
                        <div class="form-view-box">
                            {{ Arr::get($review, 'mobile_number') ?: '-' }}
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold text-dark">Status</label>
                        <select class="form-control c-select custom-input" name="status" id="status">
                            <option value="">Select Status</option>
                            @foreach ($reviewStatuses as $id => $status)
                            <option value="{{ $id }}" {{ Arr::get($review, 'status') == $id ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-dark">Name</label>
                        <input
                            id="name"
                            type="text"
                            class="form-control custom-input"
                            name="name"
                            placeholder="Name"
                            value="@if(old('name')){{ old('name') }}@elseif(empty(old('name')) && old('_token')){{ '' }}@else{{ Arr::get($review, 'name') }}@endif">
                    </div>

                    <div class="col-12">
                        <label for="comments" class="form-label fw-semibold text-dark">Review</label>
                        <textarea
                            id="comments"
                            class="form-control custom-input"
                            name="comments"
                            rows="5"
                            placeholder="Write review here...">@if(old('comments')){{ old('comments') }}@elseif(empty(old('comments')) && old('_token')){{ '' }}@else{{ Arr::get($review, 'comments') }}@endif</textarea>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn bg-theme-yellow text-dark px-4 py-2 fw-semibold">
                                Update Review
                            </button>
                            <a href="{{ route('reviews') }}" class="btn btn-outline-secondary px-4 py-2">
                                Cancel
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>



<link href="{!! url('assets/css/rating/font-awesome.min.css') !!}" rel="stylesheet">
<style>
    .star-rating {
        display: flex;
        /* Ensures all stars are in a single line */
        align-items: center;
        /* Vertically centers the stars if needed */
        font-size: 24px;
        /* Adjusts the size of the stars */
    }

    .star-rating .fa-star {
        color: #ffc107;
        /* Color for checked stars (gold) */
        margin-right: 2px;
        /* Optional: Adjusts spacing between stars */
    }

    .star-rating .fa-star:not(.checked) {
        color: #808080;
        /* Color for unchecked stars (light gray) */
    }

    .form-view-box {
        min-height: 46px;
        padding: 12px 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        color: #212529;
        display: flex;
        align-items: center;
        word-break: break-word;
    }

    .custom-input {
        min-height: 46px;
        border-radius: 10px;
        border: 1px solid #dcdfe3;
        box-shadow: none;
    }

    .custom-input:focus {
        border-color: #f3c623;
        box-shadow: 0 0 0 0.15rem rgba(243, 198, 35, 0.18);
    }

    textarea.custom-input {
        min-height: 130px;
        resize: vertical;
    }

    .rating-box {
        min-height: 46px;
        padding: 12px 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        display: flex;
        align-items: center;
    }

    .star-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 20px;
        line-height: 1;
    }

    .star-rating .fa-star {
        color: #d1d5db;
    }

    .star-rating .fa-star.checked {
        color: #ffc107;
    }
</style>

@endsection