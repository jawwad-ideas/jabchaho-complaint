@extends('backend.layouts.app-master')

@section('content')
<style>
tr[data-url] {
  cursor: pointer;
  transition: background-color 0.3s;
}

tr[data-url]:hover {
  background-color: #f0f0f0;
}
</style>
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0"> Barcodes </h3>
            <small class="text-dark">Manage your barcodes here.</small>
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
            <div id="modalDiv"></div>

            <div class="" id="filterBox" style="display:block;"
                 @if (request()->has('order_number') || request()->has('customer_email') || request()->has('customer_name') || request()->has('telephone')  )
                     style="display:block;"
                 @else
                     style="display:none;"
                @endif
            >
                <form class="form-inline" method="GET" action="{{ route('orders.barcode.images') }}">
                    <div class="row mb-3">
                        <div class="col-lg-12 d-flex flex-wrap">

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="barcode"
                                       value="{{ $barcode ?? '' }}" placeholder="Barcode">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="service_type"
                                       value="{{ $service_type ?? '' }}" placeholder="Service Type">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="item_name"
                                       value="{{ $item_name ?? '' }}" placeholder="Item Name">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="order_number"
                                       value="{{ $order_number ?? '' }}" placeholder="Order Number">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="customer_email"
                                       value="{{ $customer_email ?? '' }}" placeholder="Customer Email">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="customer_name"
                                       value="{{ $customer_name ?? '' }}" placeholder="Customer Name">
                            </div>

                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="telephone"
                                       value="{{ $telephone ?? '' }}" placeholder="Telephone">
                            </div>


                            <div class="col-sm-3 px-2 mt-2">
                                <select class="form-select p-2" id="before_email" name="issue">
                                    <option value=''>Having Issue</option>
                                    @if(!empty($is_issue_identify_options) )
                                        @foreach($is_issue_identify_options as $key => $option )
                                            @if( $key == $issue )
                                                <option value="{{$key}}" selected>
                                                    {{$option}}</option>
                                            @else
                                                <option value="{{$key}}">
                                                    {{$option}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                    id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('orders.barcode.images') }}"
                               class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>


            <div class="d-flex my-2">
                Showing results {{ ($orderItemImage->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($orderItemImage->currentPage() * config('constants.per_page'), $orderItemImage->total()) }} of {{ $orderItemImage->total() }}
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact " id="clickableTable">
                    <thead>
                    <tr>
                        <th scope="col" width="1%">Sr no.</th>
                        <th scope="col" width="15%">Order#</th>
                        <th scope="col" width="15%">Image</th>
                        <th scope="col" width="15%">Barcode</th>
                        <th scope="col" width="15%">Item Name</th>
                        <th scope="col" width="15%">Service Type</th>
                        <th scope="col" width="10%">Created At</th>
                        <th scope="col" width="15%">Customer Name</th>
                        <th scope="col" width="15%">Customer Email</th>
                        <th scope="col" width="15%">Telephone#</th>
                        <th scope="col" width="1%" colspan="3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orderItemImage as $order)
                        @if ($order->email == 'admin@gmail.com')
                            @php continue; @endphp
                        @endif

                        <tr data-url="{{ route('orders.edit', $order->orderItem->order->id) }}">
                            <th scope="row">{{ $order->id }}</th>
                            <td width="15%">
                                <a href="{{ route('orders.edit', $order->orderItem->order->id ) }}" class="btn bg-theme-yellow btn-sm">{{ $order->orderItem->order->order_id }}</a>
                            </td>
                            <td>
                                <?php
                                $type = ( $order->image_type == "Before Wash" )?"before":"after";
                                $afterMainImage = asset(config('constants.files.orders')).'/'.$order->orderItem->order->order_id.'/'.$type.'/'.$order->imagename;
                                $afterThumbnail = asset(config('constants.files.orders')).'/'.$order->orderItem->order->order_id.'/thumbnail/'.$type.'/'.$order->imagename;
                                $isAfterThumbnail = public_path(config('constants.files.orders')).'/'.$order->orderItem->order->order_id.'/thumbnail/'.$type.'/'.$order->imagename;
                                if( !\Illuminate\Support\Facades\File::exists($isAfterThumbnail)  ){
                                    $afterThumbnail = $afterMainImage;
                                }
                                ?>
                                <a href="{{$afterMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$afterThumbnail}}" alt="{{$order->imagename}}"> </a>
                            </td>
                            <td width="15%">{{ $order->orderItem->barcode }}</td>
                            <td width="15%">{{ $order->orderItem->item_name }}</td>
                            <td width="15%">{{ $order->orderItem->service_type }}</td>
                            <td width="15%">{{ $order->orderItem->created_at }}</td>
                            <td width="15%">{{ $order->orderItem->order->customer_name }}</td>
                            <td width="15%">{{ $order->orderItem->order->customer_email }}</td>
                            <td width="15%">{{ $order->orderItem->order->telephone }}</td>
                            <td><a href="{{ route('orders.edit', $order->orderItem->order->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex">
                {!! $orderItemImage->links() !!}
            </div>

        </div>
    </div>
    <!--Assign To Modal -->
    <div id="modalDiv"></div>

<script>
    $(document).ready(function () {
        $("#showFilterBox").click(function () {
            $("#filterBox").toggle();
        });
    });
</script>
@endsection
