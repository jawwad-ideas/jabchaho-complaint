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
                                        <img width="190" height="39" src="{{ $app_url }}/assets/images/jc-logo.png" alt="Jabchaho" style="border:0;height:auto;line-height:100%;outline:none;text-decoration:none;max-width:150px">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="main-content" style="vertical-align:top;background-color:#efef;padding:30px;">
                                <p class="greeting" style="margin-top:0;margin-bottom:10px">Dear {{$name}},</p>
                                <p style="margin-top:0;margin:20px 0">Welcome to Jabchaho Complaint Portal! Your account has been successfully created. Below are your credentials:</p>

                                <div style="margin-top: 20px;">
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">URL# </b><span>{{$app_url}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Username: </b><span>{{$username}}</span>
                                    </div>
                                    <div style="width:100%;display:flex;padding:10px 5px;">
                                        <b style="width:25%;">Password: </b><span>{{$password}}</span>
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
