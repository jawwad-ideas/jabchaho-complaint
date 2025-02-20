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
                                                        <th style="border: none;padding: 10px 10px; width:5%;">#</th>
                                                        <th style="border: none;padding: 10px 10px; width:45%;">Name</th>
                                                        <th style="border: none;padding: 10px 10px; width:50%;">Before Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="border:1px solid #fefad4;">
                                                    @php $beforeCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'before_wash_count')))
                                                            
                                                                <tr  @if($beforeCounter % 2 == 0) style="background-color: #ffffff75;" @else style="border:1px solid #36342145;" @endif>
                                                                    <td style="border:none;padding:10px;">{{ $beforeCounter }}</td>
                                                                    <td style="border:none;padding:10px;">{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}<br/><small style="display:inline-block;font-weight:700;">Barcode:</small><span style="font-size: 12px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span>
                                                                        @if(!empty($orderItem->issues))
                                                                        <br/><small>Item having Issue:</small>
                                                                            <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$orderItem->id}}">
                                                                                @foreach($orderItem->issues as $row)
                                                                                <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                                                                @endforeach
                                                                            </div>

                                                                        @endif
                                                                </td>
                                                                    <td style="border:none;padding:10px;">
                                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                                                                            @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')
                                                                            <span style="width:50%;float:left;">
                                                                                <a href="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="text-decoration: none; display:block;" download>
                                                                                    @if(File::exists(public_path('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename'))))
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;height:35px;"  />
                                                                                    @else
                                                                                        <img src="{{ url('assets/uploads/orders/'.$orderNo.'/before/'.Arr::get($orderItemsImage, 'imagename')) }}" style="max-width:30px;height:35px;"  />
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
                                                        <th style="border: none;padding: 10px 10px; width:5%;">#</th>
                                                        <th style="border: none;padding: 10px 10px; width:45%;">Name</th>
                                                        <th style="border: none;padding: 10px 10px; width:50%;">After Wash</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="border:1px solid #fefad4;">
                                                    @php $afterCounter = 1; @endphp
                                                    @foreach($orderItems as $orderItem)
                                                        @if(!empty(Arr::get($orderItem, 'after_wash_count')))
                                                           
                                                                <tr  @if($afterCounter % 2 == 0) style="background-color: #ffffff75;" @endif>
                                                                    <td style="border:none;padding:10px;">{{ $afterCounter }}</td>
                                                                    <td style="border:none;padding:10px;">{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}<br/><small style="display:inline-block;font-weight:700;">Barcode:</small><span style="font-size: 12px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span>
                                                                    @if( $orderItem->is_issue_identify == 2 )
                                                                        
                                                                        @if( $orderItem->is_issue_fixed == 2 )
                                                                            <small>Issue Fixed:</small>
                                                                            @if(!empty($orderItem->issues))
                                                                                <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$orderItem->id}}">
                                                                                    @foreach($orderItem->issues as $row)
                                                                                    <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                                                                    @endforeach
                                                                                </div>

                                                                            @endif

                                                                        @endif
                                                                    @endif
                                                                </td>
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
                                <p>Please feel free to contact us at 021-111-524-246 for any queries or concerns.</p>
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
