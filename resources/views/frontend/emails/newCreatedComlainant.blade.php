<p><strong>Dear {{$fullName}},</strong></p>

<p>Thank you for contacting us. We've created a new account to help you easily file a complaint.</p>

<p><strong>Your Login Information:</strong></p>

<p>Username: {{$email}}</p>
<p>Password: {{$password}}</p>
<p><strong>This is a temporary password</strong>. Please change it after logging in for security reasons.</p>
<p>Thank you,</p>

<table class="wrapper" width="100%" style="border-collapse: collapse ; margin: 0 auto ; border:1px solid">
    <tbody>
        <tr style=" border:1px solid">
            <td class="wrapper-inner" align="center" style=" vertical-align: top ; padding-bottom: 30px ; width: 100%; border:1px solid">
                <table class="main" align="center"
                    style="border-collapse: collapse ; margin: 0 auto ; text-align: left ; width: 660px;border:1px solid">
                    <tbody>
                        <tr style="border:1px solid">
                            <td class="header"
                                style="vertical-align: top ; background-color: #f5f5f5 ; padding: 40px 25px 0 ; background: #fff ;border:1px solid">
                                <a class="logo" href="#" style="color: #1979c3 ; text-decoration: none" target="_other"
                                    rel="nofollow"> <img width="190" height="39"
                                        src="{{$app_url}}/assets/website/images/logo-two.png"
                                        alt="Ideas" border="0"
                                        style="border: 0 ; height: auto ; line-height: 100% ; outline: none ; text-decoration: none ; max-width: 120px"></a>
                            </td>
                        </tr>
                        <tr style="border:1px solid">
                            <td class="main-content"
                                style="vertical-align: top ; background-color: #fff ; padding: 30px ; font-family: &quot;open sans&quot; , sans-serif ;border:1px solid">
                                <p class="greeting" style="margin-top: 0 ; margin-bottom: 10px">Hi {{$fullName}} ,</p>
                                <p style="margin-top: 0 ; margin-bottom: 10px ; color: #8dc63f">We’re glad to invite you
                                    as a registered customer at <a href="{{$app_url}}/"
                                        style="color: #1979c3 ; text-decoration: none" target="_other"
                                        rel="nofollow">{{$app_url}}</a> </p>
                                <p style="margin-top: 0 ; margin-bottom: 10px"> To sign in to our site, use the below
                                    mentioned credentials </p>
                                <ul style="margin-top: 0 ; margin-bottom: 25px ; list-style: none">
                                    <li style="margin-top: 0 ; margin-bottom: 10px"> <strong
                                            style="font-weight: 700">Email:</strong> {{$email}}</li>
                                    <li style="margin-top: 0 ; margin-bottom: 10px"> <strong
                                            style="font-weight: 700">Password:</strong> <em
                                            style="font-style: italic">{{$password}}</em> </li>
                                </ul>
                                <p>Thank you,</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>