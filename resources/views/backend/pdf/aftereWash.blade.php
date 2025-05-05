<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 40px; color: #333;">

<div style="background-color: #000; padding: 20px; border-top-left-radius: 20px; border-top-right-radius: 20px; display: flex; align-items: center; justify-content: flex-start;">
    <img width="190" height="39" src="{{public_path('assets/images/jc-logo.png') }}" alt="Jabchaho" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; max-width: 150px;">
  </div>

  <div style="background: #fff; border: 1px solid #ddd; border-top: none; padding: 30px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
  <p style="margin: 1px 0; line-height: 1.6;"><strong>Dear {{$name}},</strong></p>
  <p style="margin: 1px 0; line-height: 1.6;">Thank you for your order!</p>
  <p style="margin: 1px 0; line-height: 1.6;">The details of your Order No. <strong>{{$orderNo}}</strong> are provided below.</p>
  <p style="margin: 1px 0; line-height: 1.6;">Total Items: <strong>{{ $orderItemCount }}</strong></p>

  @if(!empty($orderItems))
    <!-- Two column layout -->
    <div style="width: 100%;">

        <!-- Before Wash Column -->
        <div style="width: 48%; float: left;">
            <h2 style="color: #444;">Before Wash</h2>

            @foreach($orderItems as $orderItem)
                @if(!empty(Arr::get($orderItem, 'before_wash_count')))

                    <ul style="list-style: none; padding-left: 0;">
                        <li style="margin: 10px 0;"><strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}</strong> | {{ Arr::get($orderItem, 'barcode') }}</li>
                    </ul>
                    
                    @if(!empty($orderItem->issues))
                    <p style="margin: 15px 0; line-height: 1.6;">
                            <strong>Item Issue:</strong>
                            <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$orderItem->id}}">
                                @foreach($orderItem->issues as $row)
                                <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                @endforeach
                            </div>
                        </p>
                    @endif

                    @php $imageIndex = 0; @endphp
                    @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                        @if(Arr::get($orderItemsImage, 'image_type') == 'Before Wash')


                            @php
                                $imageName = Arr::get($orderItemsImage, 'imagename');
                                $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.$imageName;
                                $fullPath = 'assets/uploads/orders/'.$orderNo.'/before/'.$imageName;
                                $thumbUrl = url($thumbPath);
                                $fullUrl = url($fullPath);
                            @endphp

                            <div style="width: 30%; margin: 5px; display: inline-block; vertical-align: top; font-size: 12px;">
                                <a href="{{ $fullUrl }}" style="text-decoration: none; display: block; width: 100%;" download>
                                    @if(File::exists(public_path($thumbPath)))  
                                        <img src="{{ $thumbUrl }}" style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;"  />
                                    @else
                                        <img src="{{ $fullUrl }}" style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;"  />
                                    @endif
                                </a>
                            </div>

                        @endif
                    @endforeach

                @endif
            @endforeach

        </div>

        <!-- After Wash Column -->
        <div style="width: 48%; float: right;">
            <h2 style="color: #444;">After Wash</h2>
            @foreach($orderItems as $orderItem)
                @if(!empty(Arr::get($orderItem, 'after_wash_count')))

                    <ul style="list-style: none; padding-left: 0;">
                        <li style="margin: 10px 0;"><strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}</strong> | {{ Arr::get($orderItem, 'barcode') }}</li>
                    </ul>
                    
                    @if( $orderItem->is_issue_identify == 2 )
                                                                        
                        @if( $orderItem->is_issue_fixed == 2 )
                            <p style="margin: 15px 0; line-height: 1.6;">    
                            <small>Issue Fixed:</small>
                            <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$orderItem->id}}">
                                @foreach($orderItem->issues as $row)
                                <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                @endforeach
                            </div>
                        </p>
                        @endif
                    @endif


                    @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)
                        @if(Arr::get($orderItemsImage, 'image_type') == 'After Wash')

                            @php
                                $imageName = Arr::get($orderItemsImage, 'imagename');
                                $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/after/'.$imageName;
                                $fullPath = 'assets/uploads/orders/'.$orderNo.'/after/'.$imageName;

                                $thumbUrl = url($thumbPath);
                                $fullUrl = url($fullPath);
                            @endphp

                            <div style="width: 30%; margin: 5px; display: inline-block; vertical-align: top; font-size: 12px;">
                                <a href="{{ $fullUrl }}" style="text-decoration: none; display: block; width: 100%;" download>
                                    @if(File::exists(public_path($thumbPath)))  
                                        <img src="{{ $thumbUrl }}" style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;"  />
                                    @else
                                        <img src="{{ $fullUrl }}" style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;"  />
                                    @endif
                                </a>
                            </div>
                            

                        @endif
                    @endforeach

                @endif
            @endforeach

            
        </div>

    </div>
  @endif

  <div style="clear: both;"></div>

   <p style="margin: 15px 0; line-height: 1.6;">
      Contact us at <strong>021-111-524-246</strong> for any queries or concerns.
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">
        <strong>Note: You are kindly requested to check the items within 7 days of delivery, with the attached bar code intact. Complaints received after this period will not be entertained. </strong>
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">Best regards,</p>
    <p style="margin: 15px 0; line-height: 1.6;"><strong>JabChaho</strong></p>

</body>
</html>
