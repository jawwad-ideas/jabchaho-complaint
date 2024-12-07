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
                                <p style="margin-top:0;margin:20px 0">The details of your Order No. <b>{{$orderNo}}</b> are provided below.</p>
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">
                                

                                @if(!empty($orderItems))
                                    <table border="1" style="width:100%; border-collapse:collapse;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Before Wash Images</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orderItems as $orderItem)
                                                @if(count(Arr::get($orderItem, 'images', [])) > 0) <!-- Corrected the condition -->
        
                                                    @if(collect(Arr::get($orderItem, 'images'))->where('image_type', 'Before Wash')->count() > 0)
                                                        <tr>
                                                            <td>{{ Arr::get($orderItem, 'item_name') }}</td>
                                                            <td>
                                                                @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                    
                                                                    @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')
                                                                        <a href="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" download>
                                                                            @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename'))))    
                                                                                <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:100px; margin:5px;" />
                                                                            @else
                                                                                <img src="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:100px; margin:5px;" />
                                                                            @endif    
                                                                        </a>
                                                                    @else
                                                                        @continue
                                                                    @endif

                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br/>
                                    <table border="1" style="width:100%; border-collapse:collapse;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>After Wash Images</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orderItems as $orderItem)
                                                @if(count(Arr::get($orderItem, 'images', [])) > 0) <!-- Corrected the condition -->

                                                    @if(collect(Arr::get($orderItem, 'images'))->where('image_type', 'After Wash')->count() > 0)
                                                        <tr>
                                                            <td>{{ Arr::get($orderItem, 'item_name') }}</td>
                                                            <td>
                                                                @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                    
                                                                    @if(Arr::get($orderItemsImage, 'image_type') == 'After Wash')
                                                                        <a href="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" download>
                                                                            
                                                                            @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename'))))    
                                                                                <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:100px; margin:5px;" />
                                                                            @else
                                                                                <img src="{{ url('assets/uploads/orders/'.$orderNo.'/after/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:100px; margin:5px;" />
                                                                            @endif
                                                                        </a>
                                                                    @else
                                                                        @continue
                                                                    @endif

                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                              
                                <p>Thank you,</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
