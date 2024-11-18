@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Orders</h3>
            <small class="text-dark">Manage your orders here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('orders.create') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-user-plus"></i><span>New Order</span></small>
                </a>

                <small id="showFilterBox" type="button"
                       class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="bg-light p-2 rounded">

             {{--<form class="form-inline" method="GET">
                <input type="text" id="order_number" placeholder="order number" name="order_number"
                    maxlength="50" value="{{ $order_number}}">
            </form>--}}
            <!--Assign To Modal -->
            <div id="modalDiv"></div>

            <div class="" id="filterBox" style="display:none;">
                <form class="form-inline" method="GET" action="{{ route('orders.index') }}">
                    <div class="row mb-3">
                        <div class="col-lg-8 d-flex flex-wrap">
                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="order_number"
                                       value="{{ $order_number ?? '' }}" placeholder="Order Number">
                            </div>
                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                    id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('orders.index') }}"
                               class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex my-2">
                Showing results {{ ($orders->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($orders->currentPage() * config('constants.per_page'), $orders->total()) }} of {{ $orders->total() }}
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="1%">#</th>
                            <th scope="col" width="15%">Order Number</th>
                            <th scope="col" width="15%">Total Count Before Wash</th>
                            <th scope="col" width="10%">Total Count After Wash</th>
                            <th scope="col" width="10%">Created At</th>
                            {{--<th scope="col" width="10%">Roles</th>--}}
                            <th scope="col" width="1%" colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @if ($order->email == 'admin@gmail.com')
                                @php continue; @endphp
                            @endif

                            <tr>
                                <th scope="row">{{ $order->id }}</th>
                                <td width="15%">{{ $order->order_number }}</td>
                                <td width="15%">{{ $order->before }}</td>
                                <td width="15%">{{ $order->after }}</td>
                                <td width="15%">{{ $order->created_at }}</td>
                                <td><a href="{{ route('orders.edit', $order->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex">
                {!! $orders->links() !!}
            </div>

        </div>
    </div>
    <!--Assign To Modal -->
    <div id="modalDiv"></div>

    <script>
        $("#showFilterBox").click(function() {
            $("#filterBox").toggle();
        });
    </script>

@endsection
