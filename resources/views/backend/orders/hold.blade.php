@extends('backend.layouts.app-master')

@section('content')


<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">{{config('constants.order_type.'.$type)}} </h3>
        <small class="text-dark">Manage your WhatsApp Hold Orders here.</small>
    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        
    </div>
    
</div>

    <div class="page-content bg-white p-lg-5 px-2">

        <div class="bg-light p-2 rounded">
            <div id="modalDiv"></div>

            <div class="" id="filterBox" >
               
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact " id="clickableTable">
                    <thead>
                    <tr>
                        <th scope="col" width="1%"><input type="checkbox" id="select-all"></th>
                        <th scope="col" width="15%">Order#</th>
                        <th scope="col" width="15%">Location</th>
                        <th scope="col" width="15%">Customer Name</th>
                        <th scope="col" width="15%">Customer Email</th>
                        <th scope="col" width="15%">Telephone#</th>
                        <th scope="col" width="10%">Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    @foreach ($orders as $order)
                    
                        <tr data-url="{{ route('orders.edit', $order->id) }}">
                            <td scope="row"> <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox"></td>
                            <td width="15%"><a href="{{ route('orders.edit', $order->id) }}" >{{ $order->order_id }}</a></td>
                            <td scope="row">@if(!empty($order->location_type)) {{config('constants.laundry_location_type.store')}} @else {{config('constants.laundry_location_type.facility')}} @endif</td>
                            <td width="15%">{{ $order->customer_name }}</td>
                            <td width="15%">{{ $order->customer_email }}</td>
                            <td width="15%">{{ $order->telephone }}</td>
                            <td width="15%">{{ $order->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                 <input type="hidden" id="whatsAppType" value="{{ $type }}">
                <input type="button" value="Process Hold Orders" id="processHoldOrders">
            </div>

        </div>
    </div>


    <script>

     


         // Select all checkboxes
    $('#select-all').on('click', function () {
        $('.order-checkbox').prop('checked', this.checked);
    });

    $('#processHoldOrders').on('click', function () {

        const now = new Date();
        const hour = now.getHours(); // returns 0–23

         var orderIds = [];
        $('.order-checkbox:checked').each(function () {
            orderIds.push($(this).val());
        });

        if (orderIds.length === 0) {
            alert("Please select at least one order.");
            return;
        }


        @php
            $hourStart              = Arr::get($configurations, 'laundry_order_whatsapp_sending_start_time'); //id of array
            $hourEnd                = Arr::get($configurations, 'laundry_order_whatsapp_sending_end_time'); //id of array

            $hourStartTimeFormat    = config('constants.hours.'.$hourStart); //10:00 AM.
            $hourEndTimeFormat      = config('constants.hours.'.$hourEnd); //10:00 PM.

        @endphp

        var hourStart  = "{{$hourStart}}";
        var hourEnd  = "{{$hourEnd}}";

        if (hour >= hourStart && hour < hourEnd) 
        {

           
            var whatsAppType = $('#whatsAppType').val();

            $.ajax({
                url: "{{ route('process.hold.whatsapp.orders') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_ids: orderIds,
                    whatsapp_type: whatsAppType
                },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message);
                }
            });
        }
        else
        {
            alert("WhatsApp messages can only be sent between {{$hourStartTimeFormat}} and {{$hourEndTimeFormat}}");
        }
    });

    </script>

@endsection