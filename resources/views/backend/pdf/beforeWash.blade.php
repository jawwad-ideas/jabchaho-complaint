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
                                        <img width="190" height="39" src="{{public_path('assets/images/jc-logo.png') }}" alt="Jabchaho" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="main-content" style="vertical-align:top;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear <span style="font-size: 20px;font-weight: 600;display: block;">{{$name}},</span></p>

                                <p class="greeting" style="font-style: italic;margin-top:0;margin-bottom:10px;">We hope this email finds you well.</p>

                                <p class="greeting" style="margin-top:0;margin:20px 0">Please allow us to inform you that during the inspection at our in-house facility, we discovered that there are defects in a few items which include <b>{{$options}}</b> (pictures are attached). </p>


                                <p class="greeting" style="margin-top:0;margin:20px 0">However, we will do our best to remove the stains, as long as it does not damage the fabric and we will be proceeding with the laundry services as requested.</p>

                                @if(!empty($orderItems))
                                    <table border="1" style="width:100%; border-collapse:collapse;border:none;">
                                        <thead style="background: #fce100;">
                                            <tr>
                                                <th style="border: none;padding: 10px 10px;">#</th>
                                                <th style="border: none;padding: 10px 10px;">Name</th>
                                                <th style="border: none;padding: 10px 10px;">Before Wash Images</th>
                                            </tr>
                                        </thead>
                                        <tbody style="border:1px solid #fefad4;">
                                        @php $beforeCounter = 1; @endphp
                                            @foreach($orderItems as $orderItem)
                                                <tr  @if($beforeCounter % 2 == 0) style="background-color: #ffffff75;" @endif>
                                                    <td style="border:none;padding:10px;">{{ $beforeCounter }}</td>
                                                    <td  style="border:none;padding:10px;">{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}<br/> <small style="display:inline-block;font-weight:700;">Barcode:</small><span style="font-size: 12px; color: #555;">{{ Arr::get($orderItem, 'barcode') }}</span>
                                                        
                                                            @if(!empty($orderItem->issues))
                                                            <br/><small>Item having Issue:</small>
                                                                <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$orderItem->id}}">
                                                                    @foreach($orderItem->issues as $row)
                                                                    <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                                                    @endforeach
                                                                </div>

                                                            @endif
                                                    </td>
                                                    <td  style="border:none;padding:10px;">
                                                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)

                                                            @php
                                                                $imageName = Arr::get($orderItemsImage, 'imagename');
                                                                $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.$imageName;
                                                                $fullPath = 'assets/uploads/orders/'.$orderNo.'/before/'.$imageName;

                                                                $thumbUrl = url($thumbPath);
                                                                $fullUrl = url($fullPath);
                                                            @endphp

                                                            <div style="display:inline-block;margin-top:20px;">
                                                                <a href="{{ $fullUrl }}" style="text-decoration: none;" download>
                                                                    @if(File::exists(public_path($thumbPath)))  
                                                                        <img src="{{ $thumbUrl }}"  style="max-width:60px;  height:60px; margin:2px;"/>
                                                                    @else
                                                                        <img src="{{ $fullUrl }}" style="max-width:60px;  height:60px; margin:2px;"/>
                                                                    @endif
                                                                </a>
                                                            </div>

                                                        @endforeach

                                                    </td>
                                                </tr>
                                                @php $beforeCounter++; @endphp
                                            @endforeach
                                        </tbody>
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
