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

        <div class="" id="filterBox"  @if (request()->has('name')) 
                            style="display:block;" 
                    @else 
                            style="display:none;" 
                    @endif
        >
                <form class="form-inline" method="GET" action="{{ route('reviews') }}">
                    <div class="row mb-3">
                        

                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="order_id"
                                    value="{{request('order_id')}}" placeholder="Order No.">
                            </div>

                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="name"
                                    value="{{ request('name') }}" placeholder="Name">
                            </div>

                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="email"
                                    value="{{ request('email') }}" placeholder="Email">
                            </div>
                    
                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="mobile_nnumber"
                                    value="{{ request('mobile_nnumber') }}" placeholder="Mobile">
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control c-select" name="status">
                                    <option value="">Select Status</option>
                                    @foreach ($reviewStatuses as $id=>$status)
                                        <option value="{{ $id }}" {{ request('status') == $id ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                   
                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('reviews') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>

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
                            <th scope="col" width="15%">Name</th>
                            <th scope="col" width="15%">Email</th>
                            <th scope="col" width="10%">Mobile</th>
                            @if(Auth::user()->can('reviews.edit') || Auth::user()->can('reviews.destroy'))
                            <th scope="col" width="1%" colspan="2">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td scope="row">{{ $review->id }}</td>
                                <td width="15%">{{ $review->order_id }}</td>
                                <td width="15%">{{ $review->name }}</td>
                                <td width="15%">{{ $review->email }}</td>
                                <td width="15%">{{ $review->mobile_number }}</td>
                                @if(Auth::user()->can('reviews.edit') || Auth::user()->can('reviews.destroy'))
                                <td colspan="2">
                                    @if(Auth::user()->can('reviews.edit'))    
                                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                    @endif

                                    @if(Auth::user()->can('reviews.destroy'))
                                    
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['reviews.destroy', $review->id],
                                        'style' => 'display:inline',
                                        'onsubmit' => 'return ConfirmDelete()',
                                    ]) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-sm',
                                            'title' => 'Delete'
                                        ]) !!}
                                    {!! Form::close() !!}
                                @endif
                                </td>
                                @endif
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


<script>
        $("#showFilterBox").click(function() {
            $("#filterBox").toggle();
        });

        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                return true;
            } else {

                event.preventDefault();
                return false;
            }
        }
</script>

@endsection