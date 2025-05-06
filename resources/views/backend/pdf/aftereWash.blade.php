<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0px 20px; color: #333;">

  <div style="background-color: #000; padding: 20px; border-top-left-radius: 20px; border-top-right-radius: 20px;">
    <img width="190" height="39" src="{{ public_path('assets/images/jc-logo.png') }}" alt="Jabchaho" style="border: 0; height: auto; max-width: 150px;">
  </div>

    <div style="background: #fff; border: 1px solid #ddd; border-top: none; padding: 30px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <p style="margin: 1px 0; line-height: 1.6;"><strong>Dear {{$name}},</strong></p>
        <p style="margin: 1px 0; line-height: 1.6;">Thank you for your order!</p>
        <p style="margin: 1px 0; line-height: 1.6;">The details of your Order No. <strong>{{$orderNo}}</strong> are provided below.</p>
        <p style="margin: 1px 0; line-height: 1.6;">Total Items: <strong>{{ $orderItemCount }}</strong></p>


        @if(!empty($orderItems))
        
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0; padding: 0;">
            <tr valign="top">
                <!-- Before Wash Column -->
                <td width="48%" style="padding-right: 10px;">
                    <h3 style="color: #444; margin-bottom: 10px;">Before Wash</h3>
                    @foreach($orderItems as $orderItem)
                    @if(!empty(Arr::get($orderItem, 'before_wash_count')))
                        <hr/>
                        <div style="">
                        <strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 20, '...') }}</strong> | <span style="font-size:.6em">{{ Arr::get($orderItem, 'barcode') }}</span>
                        </div>

                        @if(!empty($orderItem->issues))
                        <div style="margin: 5px 0px;">
                            <strong style="display: inline-flex;"><small>Item Issue:</strong></small>
                            @foreach($orderItem->issues as $row)
                            <span style="background-color: #f7dd0282; padding: .35em .65em; font-size: .6em; font-weight: bold; color: #000; border-radius: 20px;text-wrap-mode: nowrap;flex-flow: nowrap;display: inline-flex; margin-top: 5px">
                                {{ config('constants.issues.'.Arr::get($row, 'issue')) }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <div style="margin-top: 5px;">
                        @php $beforeCounter = 0; @endphp
                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)

                            @if($beforeCounter == 3)
                                @php break; @endphp
                            @endif

                        
                            @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')
                            @php
                                $imageName = Arr::get($orderItemsImage, 'imagename');
                                $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.$imageName;
                                $fullPath = 'assets/uploads/orders/'.$orderNo.'/before/'.$imageName;
                                $thumbUrl = url($thumbPath);
                                $fullUrl = url($fullPath);

                                $beforeCounter++;
                            @endphp

                            <div style="width: 20%; margin: 5px; display: inline-block; vertical-align: top; font-size: 12px;">
                                <a href="{{ $fullUrl }}" style="text-decoration: none; display: block; width: 100%;">
                                    @if(File::exists(public_path($thumbPath)))  
                                        <img src="{{ $thumbUrl }}"  style="max-width:60px;height:70px;" />
                                    @else
                                        <img src="{{ $fullUrl }}"  style="max-width:60px;height:70px;" />
                                    @endif
                                </a>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    @endif
                    @endforeach
                </td>

                <!-- After Wash Column -->
                <td width="48%" style="padding-left: 10px">
                    <h3 style="color: #444; margin-bottom: 10px;">After Wash</h3>
                    @foreach($orderItems as $orderItem)
                    @if(!empty(Arr::get($orderItem, 'after_wash_count')))
                        <hr/>
                        <div style="">
                        <strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 20, '...') }}</strong> | <span style="font-size:.6em">{{ Arr::get($orderItem, 'barcode') }}</span>
                        </div>

                        @if($orderItem->is_issue_identify == 2 && $orderItem->is_issue_fixed == 2)
                        <div style="margin: 5px 0;">
                            <strong style="display: inline-flex;"><small>Item Fixed:</strong></small>
                            @foreach($orderItem->issues as $row)
                            <span style="background-color: #f7dd0282; padding: .35em .65em; font-size: .6em; font-weight: bold; color: #000; border-radius: 20px;text-wrap-mode: nowrap;flex-flow: nowrap;display: inline-flex; margin-top: 5px">
                                {{ config('constants.issues.'.Arr::get($row, 'issue')) }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <div style="margin-top: 5px;">
                        @php $afterCounter = 0; @endphp
                        @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)

                            @if($afterCounter == 3)
                                @php break; @endphp
                            @endif

                            @if(Arr::get($orderItemsImage, 'image_type') == 'After Wash')
                            @php
                                $imageName = Arr::get($orderItemsImage, 'imagename');
                                $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.$imageName;
                                $fullPath = 'assets/uploads/orders/'.$orderNo.'/after/'.$imageName;
                                $thumbUrl = url($thumbPath);
                                $fullUrl = url($fullPath);

                                $afterCounter++;
                            @endphp
                            <div style="width: 20%; margin: 5px; display: inline-block; vertical-align: top; font-size: 12px;">
                                <a href="{{ $fullUrl }}" style="text-decoration: none; display: block; width: 100%;">
                                    @if(File::exists(public_path($thumbPath)))  
                                        <img src="{{ $thumbUrl }}"  style="max-width:60px;height:70px;"/>
                                    @else
                                        <img src="{{ $fullUrl }}"  style="max-width:60px;height:70px;" />
                                    @endif
                                </a>
                            </div>
                            @endif
                        @endforeach
                        </div>
                    @endif
                    @endforeach
                </td>
            </tr>
        </table>
        @endif

        <hr/>
        <p style="margin: 20px 0; line-height: 1.6;">
        Contact us at <strong>021-111-524-246</strong> for any queries or concerns.
        </p>

        <p style="margin: 15px 0; line-height: 1.6;">
        <strong>Note: You are kindly requested to check the items within 7 days of delivery, with the attached bar code intact. Complaints received after this period will not be entertained.</strong>
        </p>

        <p style="margin: 15px 0; line-height: 1.6;">Best regards,</p>
        <p style="margin: 15px 0; line-height: 1.6;"><strong>JabChaho</strong></p>
    </div>

</body>
</html>
