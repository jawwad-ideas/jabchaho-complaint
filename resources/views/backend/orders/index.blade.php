@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">
                @if( $order_status == 2 )
                    Complete Orders
                @elseif( $order_status == 1 )
                    Pending Orders
                @else
                    Orders
                @endif
                </h3>
            <small class="text-dark">Manage your orders here.</small>
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

            {{--<form class="form-inline" method="GET">
               <input type="text" id="order_number" placeholder="order number" name="order_number"
                   maxlength="50" value="{{ $order_number}}">
           </form>--}}
            <!--Assign To Modal -->
            <div id="modalDiv"></div>

            <div class="" id="filterBox"
                 @if (request()->has('order_number') || request()->has('customer_email') || request()->has('customer_name') || request()->has('telephone')  )
                     style="display:block;"
                 @else
                     style="display:none;"
                @endif
            >
                <form class="form-inline" method="GET" action="{{ route('orders.index') }}/{{$order_status}}">
                    <div class="row mb-3">
                        <div class="col-lg-8 d-flex flex-wrap">
                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="order_number"
                                       value="{{ $order_number ?? '' }}" placeholder="Order Number">
                            </div>

                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="customer_email"
                                       value="{{ $customer_email ?? '' }}" placeholder="Customer Email">
                            </div>

                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="customer_name"
                                       value="{{ $customer_name ?? '' }}" placeholder="Customer Name">
                            </div>

                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="telephone"
                                       value="{{ $telephone ?? '' }}" placeholder="Telephone">
                            </div>
                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                    id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('orders.index') }}/{{$order_status}}"
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
                        <th scope="col" width="15%">Customer Name</th>
                        <th scope="col" width="15%">Customer Email</th>
                        <th scope="col" width="15%">Customer Telephone</th>
                        <th scope="col" width="15%">Before Wash Image Count</th>
                        <th scope="col" width="15%">After Wash Image Count</th>
                        <th scope="col" width="15%">Email Sent</th>
                        <th scope="col" width="10%">Created At</th>
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
                            <td width="15%">{{ $order->order_id }}</td>
                            <td width="15%">{{ $order->customer_name }}</td>
                            <td width="15%">{{ $order->customer_email }}</td>
                            <td width="15%">{{ $order->telephone }}</td>

                            <td width="15%">{{ $order->before_wash_images_count ?? 0 }}</td>
                            <td width="15%">{{ $order->after_wash_images_count ?? 0 }}</td>

                            {{--<td width="15%">{{ $order->remarks }}</td>
                            <td width="15%">

                                <a href="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$order->attachments}}"  target="_blank">
                                    <img class="order-img" src="{{asset(config('constants.files.orders'))}}/{{$order->order_id}}/{{$order->attachments}}" alt="{{$order->attachments}}" class=" img-thumbnail image-fluid w-50" style="height:60px;">
                                </a>

                            </td>--}}
                            <td width="15%">@if( $order->is_email_sent  ) Yes @else No @endif</td>
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
