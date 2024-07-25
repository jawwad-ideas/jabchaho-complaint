<table class="wrapper" width="100%" style="border-collapse:collapse;margin:0 auto">
    <tbody>
        <tr>
            <td class="wrapper-inner" align="center" style="vertical-align:top;padding-bottom:30px;width:100%;font-family:'Poppins','Helvetica Neue','Helvetica','Arial',sans-serif;">
                <table class="main" align="center" style="border-collapse:collapse;margin:0 auto;text-align:left;width:660px;">
                    <tbody>
                        <tr>
                            <td class="header" style="vertical-align:top;background-color:#ec23262b;padding:40px 25px 25px;">
                                <div>
                                    <a class="logo" href="#" style="color:#ed2024;text-decoration:none" target="_other" rel="nofollow">
                                        <img width="190" height="39" src="{{ $app_url }}/assets/images/jc-logo.png" alt="Ideas" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="main-content" style="vertical-align:top;background-color:#efef;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear {{$name}},</p>
                                <b style="margin-top:0;margin-bottom:10px;color:#0e7b3d">Your complaint has been registered successfully.</b>
                                <p style="margin-top:0;margin:20px 0">These are the details:</p>

                                <div style="margin-top: 20px;">
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">No</b><span>{{$complaintNumber}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Order Id</b><span>{{$orderId}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Complaint Type</b><span>{{$complaintType}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Name</b><span>{{$name}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Email</b><span>{{$email}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Mobile Number</b><span>{{$mobileNumber}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Additional Comments</b><span>{{$additionalComments}}</span>
                                    </div>
                                </div>
                                <p>Thank you,</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
