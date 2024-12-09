<table class="wrapper" width="100%" style="border-collapse:collapse;margin:0 auto">
    <tbody>
        <tr>
            <td class="wrapper-inner" align="center" style="vertical-align:top;padding-bottom:30px;width:100%;font-family:'Poppins','Helvetica Neue','Helvetica','Arial',sans-serif;">
                <table class="main" align="center" style="border-collapse:collapse;margin:0 auto;text-align:left;width:660px;border-radius:30px;overflow:hidden;">
                    <tbody style="background: #fce1004f;">
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
                            <td class="main-content" style="vertical-align:top;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear <span style="font-size: 20px;font-weight: 600;display: block;">{{$name}},</span></p>
                                <p class="greeting" style="font-style: italic;margin-top:0;margin-bottom:10px;">Thank you for your order!</p>
                                <p style="margin-top:0;margin:20px 0">The details of your Order No. <b>{{$orderNo}}</b> are provided below.</p>
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Total Items: <b>{{ $orderItemCount }}</b></p>

                                @if(!empty($orderItems))
                                <table style="width:100%; border-collapse:collapse;">
                                    <tr>
                                        <!-- Table 1 -->
                                        <td style="width:50%; vertical-align:top; padding-right:10px;">
                                            <table border="1" style="width:100%;border-collapse:collapse;border: none;">
                                                <thead style="background: #fce100;">
                                                    <tr>
                                                        <th style="border: none;padding: 10px 10px;">#</th>
                                                        <th style="border: none;padding: 10px 10px;">Name</th>
                                                        <th style="border: none;padding: 10px 10px;">Before Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="border:1px solid #fefad4;">
                                                    @php $beforeCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'before_wash_count')))
                                                            
                                                                <tr  @if($beforeCounter % 2 == 0) style="background-color: #ffffff75;" @endif>
                                                                    <td style="border:none;padding:10px;">{{ $beforeCounter }}</td>
                                                                    <td style="border:none;padding:10px;">{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 15, '...') }}<br/><small style="display:block;">Barcode:</small><span style="font-size: 10px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span></td>
                                                                    <td style="border:none;padding:10px;">
                                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                            @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')
                                                                            <span style="width:50%;float:left;">
                                                                                <a href="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="text-decoration: none; display:block;" download>
                                                                                    @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename'))))
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;" />
                                                                                    @else
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;" />
                                                                                    @endif
                                                                                </a>
                                                                            </span>
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
                                            <table border="1" style="width:100%;border-collapse:collapse;border: none;">
                                                <thead style="background: #fce100;">
                                                    <tr>
                                                        <th style="border: none;padding: 10px 10px;">#</th>
                                                        <th style="border: none;padding: 10px 10px;">Name</th>
                                                        <th style="border: none;padding: 10px 10px;">After Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="border:1px solid #fefad4;">
                                                    @php $afterCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'after_wash_count')))
                                                           
                                                                <tr  @if($afterCounter % 2 == 0) style="background-color: #ffffff75;" @endif>
                                                                    <td style="border:none;padding:10px;">{{ $afterCounter }}</td>
                                                                    <td style="border:none;padding:10px;">{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 15, '...') }}<br/><small style="display:block;">Barcode:</small><span style="font-size: 10px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span></td>
                                                                    <td style="border:none;padding:10px;">
                                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                            @if(Arr::get($orderItemsImage, 'image_type') == 'After Wash')
                                                                            <span style="width:50%;float:left;">
                                                                                <a href="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="text-decoration: none;display:block;" download>
                                                                                    @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename'))))
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;" />
                                                                                    @else
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;" />
                                                                                    @endif
                                                                                </a>
                                                                            </span>
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
