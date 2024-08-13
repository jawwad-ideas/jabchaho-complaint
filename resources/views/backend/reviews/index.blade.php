@extends('backend.layouts.app-master')

@section('content')


<div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Reviews</h3>
            <small class="text-dark">Manage your reviews here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
          
                <small id="showFilterBox" type="button"
                    class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">

        <div class="d-flex my-2">
                Showing results {{ ($reviews->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($reviews->currentPage() * config('constants.per_page'), $reviews->total()) }} of {{ $reviews->total() }}
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="1%">#</th>
                            <th scope="col" width="1%">Order Id</th>
                            <th scope="col" width="10%">Rating</th>
                            <th scope="col" width="15%">Name</th>
                            <th scope="col" width="15%">Email</th>
                            <th scope="col" width="10%">Mobile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td scope="row">{{ $review->id }}</td>
                                <td width="15%">{{ $review->order_id }}</td>
                                <td width="15%">
                                    
                                    <div class="star-rating">
                                        @for ($i = 1; $i<= 5; $i++)
                                            <span class="fa fa-star @if($i <= Arr::get($review,'rating')) checked @endif"></span>
                                        @endfor
                                    </div>
                                </td>
                                <td width="15%">{{ $review->name }}</td>
                                <td width="15%">{{ $review->email }}</td>
                                <td width="15%">{{ $review->mobile_number }}</td>
                                
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        <div class="d-flex">
                {!! $reviews->links() !!}
            </div>

        </div>
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