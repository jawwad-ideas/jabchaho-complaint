<table class="wrapper" width="100%" style="border-collapse:collapse;margin:0 auto">
    <tbody>
        <tr>
            <td class="wrapper-inner" align="center" style="vertical-align:top;padding-bottom:30px;width:100%;font-family:'Poppins','Helvetica Neue','Helvetica','Arial',sans-serif;">
                <table class="main" align="center" style="border-collapse:collapse;margin:0 auto;text-align:left;width:660px;">
                    <tbody>
                        <tr>
                            <td class="header" style="vertical-align:top;background-color:#000;padding:40px 25px 25px;">
                                <div>
                                    <a class="logo" href="#" style="color:#000;text-decoration:none" target="_other" rel="nofollow">
                                        <img width="190" height="39" src="{{ $app_url }}/assets/images/jc-logo.png" alt="Jabchaho" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="main-content" style="vertical-align:top;background-color:#fff;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear {{$name}},</p>
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Thank you for your order!</p>
                                <p style="margin-top:0;margin:20px 0">The details of your Order No. <b>{{$orderNo}}</b> are provided below.</p>
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Total Items: <b>{{ $orderItemCount }}</b></p>

                                @if(!empty($orderItems))
                                <table style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <!-- Table 1 -->
                                        <td style="width:50%; vertical-align:top; padding-right:10px;">
                                            <table border="1" style="width:100%; border-collapse:collapse;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Before Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $beforeCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'before_wash_count')))
                                                            
                                                                <tr>
                                                                    <td>{{ $beforeCounter }}</td>
                                                                    <td>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}<br/><b>Barcode:</b><br/><span style="font-size: 10px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span></td>
                                                                    <td>
                                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                            @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')
                                                                                <a href="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="text-decoration: none;" download>
                                                                                    @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename'))))
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px; margin:5px;" />
                                                                                    @else
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px; margin:5px;" />
                                                                                    @endif
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                @php $beforeCounter++; @endphp
                                                            
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>

                                        <!-- Table 2 -->
                                        <td style="width:50%; vertical-align:top; padding-left:10px;">
                                            <table border="1" style="width:100%; border-collapse:collapse;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>After Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $afterCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'after_wash_count')))
                                                           
                                                                <tr>
                                                                    <td>{{ $afterCounter }}</td>
                                                                    <td>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}<br/><b>Barcode:</b><br/><span style="font-size: 10px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span></td>
                                                                    <td>
                                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                            @if(Arr::get($orderItemsImage, 'image_type') == 'After Wash')
                                                                                <a href="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="text-decoration: none;" download>
                                                                                    @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename'))))
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px; margin:5px;" />
                                                                                    @else
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px; margin:5px;" />
                                                                                    @endif
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                                @php $afterCounter++; @endphp
                                                           
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                @endif

                                <p>Best regards,</p>
                                <p><b>JabChaho</b></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
