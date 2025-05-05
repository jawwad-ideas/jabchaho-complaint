<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inspection Notice</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 40px; color: #333;">

<div style="background-color: #000; padding: 20px; border-top-left-radius: 20px; border-top-right-radius: 20px; display: flex; align-items: center; justify-content: flex-start;">
    <img width="190" height="39" src="{{public_path('assets/images/jc-logo.png') }}" alt="Jabchaho" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; max-width: 150px;">
  </div>

  <div style="background: #fff; border: 1px solid #ddd; border-top: none; padding: 30px; max-width: 800px; margin: auto; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
  <p style="margin: 1px 0; line-height: 1.6;">Dear {{$name}},</p>

  <p style="margin: 15px 0; line-height: 1.6;">
      Please be informed that during the inspection at our in-house facility, 
      we discovered that there are defects in a few items which include <b>{{$options}}</b> 
      (pictures are attached).
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">
      However, we will do our best to remove the stains, as long as it does not damage 
      the fabric and we will be proceeding with the laundry services as requested.
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">The following items have been affected:</p>

    @if(!empty($orderItems))
        @foreach($orderItems as $orderItem)
        <ul style="list-style: none; padding-left: 0;">
            <li style="margin: 10px 0;"><strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}</strong> | {{ Arr::get($orderItem, 'barcode') }}</li>
            </ul>
            
            @if(!empty($orderItem->issues))
            <p style="margin: 15px 0; line-height: 1.6;">
                    <strong>Item Issue:</strong>
                    @foreach($orderItem->issues as $row)
                    <span style="border-radius: 0.25rem; background-color: #f7dd0282; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; font-family: 'Comfortaa', sans-serif; text-transform: capitalize; display: inline-block;">
                          {{config('constants.issues.'.Arr::get($row, 'issue'))}}
                        </span>
                    @endforeach 
                </p>
            @endif

            <div style="margin: 20px 0; font-size: 0;">
                @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)

                    @php
                        $imageName = Arr::get($orderItemsImage, 'imagename');
                        $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.$imageName;
                        $fullPath = 'assets/uploads/orders/'.$orderNo.'/before/'.$imageName;

                        $thumbUrl = url($thumbPath);
                        $fullUrl = url($fullPath);
                    @endphp

                    <div style="width: 30%; margin: 5px; display: inline-block; vertical-align: top; font-size: 12px;">
                        <a href="{{ $fullUrl }}" style="text-decoration: none; display: block; width: 100%;">
                            @if(File::exists(public_path($thumbPath)))  
                                <img src="{{ $thumbUrl }}"  style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;"/>
                            @else
                                <img src="{{ $fullUrl }}"  style="width: 100%; max-width: 100%; height: 100px; object-fit: cover; overflow: hidden; border-radius: 10px;" />
                            @endif
                        </a>
                    </div>

                @endforeach                 
            </div>

        @endforeach
    @endif

    <p style="margin: 15px 0; line-height: 1.6;"><em>*All images are taken prior to washing and/or processing</em></p>

    <p style="margin: 15px 0; line-height: 1.6;">
      Contact us at <strong>021-111-524-246</strong> for any queries or concerns.
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">
        <strong> You are kindly requested to check the items within 7 days of delivery, with the attached bar code intact. Complaints received after this period will not be entertained. </strong>
    </p>

    <p style="margin: 15px 0; line-height: 1.6;">Best regards,</p>
    <p style="margin: 15px 0; line-height: 1.6;"><strong>JabChaho</strong></p>
  </div>

</body>
</html>
