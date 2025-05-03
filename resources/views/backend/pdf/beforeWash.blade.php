<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inspection Notice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 40px;
      color: #333;
    }

    .header {
      background-color: #000;
      padding: 20px;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
    }

    .header img {
      height: 40px;
    }

    .letter-container {
      background: #fff;
      border: 1px solid #ddd;
      border-top: none;
      padding: 30px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .letter-container p {
      margin: 15px 0;
      line-height: 1.6;
    }

    .letter-container ul {
      list-style: none;
      padding-left: 0;
    }

    .letter-container li {
      margin: 10px 0;
    }

    .image-section {
      margin: 20px 0;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .image-section img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid #ccc;
      padding: 4px;
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>

  <div class="header">
    <img width="190" height="39" src="{{public_path('assets/images/jc-logo.png') }}" alt="Jabchaho" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
  </div>

  <div class="letter-container">
    <p>Dear {{$name}},</p>

    <p>
      Please be informed that during the inspection at our in-house facility, 
      we discovered that there are defects in a few items which include <b>{{$options}}</b> 
      (pictures are attached).
    </p>

    <p>
      However, we will do our best to remove the stains, as long as it does not damage 
      the fabric and we will be proceeding with the laundry services as requested.
    </p>

    <p>The following items have been affected:</p>

    @if(!empty($orderItems))
        @foreach($orderItems as $orderItem)
            <ul>
                <li><strong>{{ \Illuminate\Support\Str::limit(Arr::get($orderItem, 'item_name'), 10, '...') }}</strong> | {{ Arr::get($orderItem, 'barcode') }}</li>
            </ul>
            
            @if(!empty($orderItem->issues))
                <p>
                    <strong>Item Issue:</strong>
                    @foreach($orderItem->issues as $row)
                        <span style="border-radius: 50rem !important; background-color: #f7dd0282 !important; padding: .25rem !important; display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; color: #000; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; font-family: 'Comfortaa', sans-serif !important; text-transform: capitalize !important;">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                    @endforeach 
                </p>
            @endif

            <div class="image-section">
                @foreach(Arr::get($orderItem, 'images', []) as $orderItemsImage)

                    @php
                        $imageName = Arr::get($orderItemsImage, 'imagename');
                        $thumbPath = 'assets/uploads/orders/'.$orderNo.'/thumbnail/before/'.$imageName;
                        $fullPath = 'assets/uploads/orders/'.$orderNo.'/before/'.$imageName;

                        $thumbUrl = url($thumbPath);
                        $fullUrl = url($fullPath);
                    @endphp

                    <div style="">
                        <a href="{{ $fullUrl }}" style="text-decoration: none;">
                            @if(File::exists(public_path($thumbPath)))  
                                <img src="{{ $thumbUrl }}"  style="max-width:60px;  height:60px; margin:2px;"/>
                            @else
                                <img src="{{ $fullUrl }}" style="max-width:60px;  height:60px; margin:2px;"/>
                            @endif
                        </a>
                    </div>

                @endforeach                 
               
            </div>

        @endforeach

    @endif

    <p><em>*All images are taken prior to washing and/or processing</em></p>

    <p>
      Contact us at <strong>021-111-524-246</strong> for any queries or concerns.
    </p>

    <p>
        You are kindly requested to check the items within 7 days of delivery, with the attached bar code intact. Complaints received after this period will not be entertained.
    </p>

    <p>Best regards,</p>
    <p><strong>JabChaho</strong></p>
  </div>

</body>
</html>
