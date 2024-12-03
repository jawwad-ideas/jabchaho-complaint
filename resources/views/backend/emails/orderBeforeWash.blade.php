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

                                <p class="greeting" style="margin-top:0;margin:20px 0">We hope this email finds you well.</p>

                                <p class="greeting" style="margin-top:0;margin:20px 0">Please allow us to inform you that during the inspection at our in-house facility, we discovered that there are defects in a few items which include <b>{{$options}}</b> (pictures are attached). </p>


                                <p class="greeting" style="margin-top:0;margin:20px 0">However, we will do our best to remove the stains, as long as it does not damage the fabric and we will be proceeding with the laundry services as requested.</p>


                                <p class="greeting" style="margin-top:0;margin:20px 0">Please feel free to contact us at 021-111-524-246 for any queries or concerns.</p>


                                @if(!empty($remarks))
                                    <p class="greeting" style="margin-top:0;margin:20px 0">Additional Remarks: {{$remarks}}</p>
                                @endif

                                <p class="greeting" style="margin-top:0;margin-bottom:10px">
                                Before wash:<a href="{{ route('download.images', ['orderId' => $orderNo, 'folderName' => 'before', 'token' => $orderToken]) }}">Download Images</a></p>

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
