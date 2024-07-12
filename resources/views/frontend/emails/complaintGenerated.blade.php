<table class="wrapper" width="100%" style="border-collapse:collapse;margin:0 auto">
    <tbody>
        <tr>
            <td class="wrapper-inner" align="center" style="vertical-align:top;padding-bottom:30px;width:100%;font-family:'Poppins','Helvetica Neue','Helvetica','Arial',sans-serif;">
                <table class="main" align="center" style="border-collapse:collapse;margin:0 auto;text-align:left;width:660px;">
                    <tbody>
                        <tr>
                            <td class="header" style="vertical-align:top;background-color:#ec23262b;padding:40px 25px 25px;">
                                <!--[if gte mso 9]>
                                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:660px;height:auto;">
                                    <v:fill type="tile" color="#ec23262b"></v:fill>
                                    <v:textbox inset="0,0,0,0">
                                <![endif]-->
                                <div>
                                    <a class="logo" href="#" style="color:#ed2024;text-decoration:none" target="_other" rel="nofollow">
                                        <img width="190" height="39" src="{{$app_url}}/assets/images/jc-logo.svg" alt="Ideas" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
                                    </a>
                                </div>
                                <!--[if gte mso 9]>
                                    </v:textbox>
                                </v:rect>
                                <![endif]-->
                            </td>
                        </tr>
                        <tr>
                            <td class="main-content" style="vertical-align:top;background-color:#efef;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear {{$fullName}},</p>
                                <b style="margin-top:0;margin-bottom:10px;color:#0e7b3d">Your complaint has been registered successfully at <a href="{{$app_url}}/" style="color:#ed2024;text-decoration:none" target="_other" rel="nofollow">Connect (MQM)</a></b>
                                <p style="margin-top:0;margin:20px 0">These are the details:</p>

                                <div style="margin-top: 20px;">
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">No</b><span>{{$complaint_num}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Title</b><span>{{$title}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Level One</b><span>{{$levelOneCategory}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Level Two</b><span>{{$levelTwoCategory}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Level Three</b><span>{{$levelThreeCategory}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">City</b><span>{{$city}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">District</b><span>{{$district}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">UC</b><span>{{$unionCouncil}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Area</b><span>{{$newArea}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Status</b><span>{{$complaintStatus}}</span>
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
