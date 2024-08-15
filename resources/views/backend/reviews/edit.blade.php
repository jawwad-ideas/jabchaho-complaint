@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
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
        <div class="row">
            <div class="col-lg-12">
                <div class="form-section mb-5">
                    <div class="form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Edit Review.</h4>
                    </div>
                </div>

                <div class="container mt-4">

                    <div class="mb-3">
                        <label for="name" class="form-label">Rating</label>
                        <div class="star-rating">
                            @for ($i = 1; $i<= 5; $i++)
                                <span class="fa fa-star @if($i <= Arr::get($review,'rating')) checked @endif"></span>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Status</label>
                        <select class="form-control c-select" name="status">
                            <option value="">Select Status</option>
                            @foreach ($reviewStatuses as $id=>$status)
                                <option value="{{ $id }}" {{ Arr::get($review, 'status') == $id ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Name</label>
                        <input
                            value="@if (old('name')) {{ old('name') }}@elseif(empty(old('name')) && old('_token')) {{ '' }}@else{{ Arr::get($review, 'name') }} @endif"
                            type="text" class="form-control" name="name" placeholder="Name">

                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Review</label>
                        <textarea class="form-control" name="comments" rows="3"
                        cols="50">@if(old('comments')){{old('comments')}}@elseif(empty(old('comments')) && old('_token')) {{''}}@else{{ Arr::get($review, 'comments') }} @endif</textarea>
                    </div>
                    
                       
                        

            <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Update
                Review</button>
            <a href="{{ route('reviews') }}" class="btn btn-default">Cancel</a></button>
        </div>
</div>
</div>
</form>
</div>



<link href="{!! url('assets/css/rating/font-awesome.min.css') !!}" rel="stylesheet">
<style>
    .star-rating {
        display: flex;               /* Ensures all stars are in a single line */
        align-items: center;         /* Vertically centers the stars if needed */
        font-size: 24px;             /* Adjusts the size of the stars */
    }

    .star-rating .fa-star {
        color: #ffc107;              /* Color for checked stars (gold) */
        margin-right: 2px;           /* Optional: Adjusts spacing between stars */
    }

    .star-rating .fa-star:not(.checked) {
        color: #808080;              /* Color for unchecked stars (light gray) */
    }
</style>

@endsection