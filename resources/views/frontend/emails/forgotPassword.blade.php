<p>Dear {{$fullName}},</p>
<p>Please click on the following link to reset your password.</p>
<p>-------------------------------------------------------------</p>
<p><a href="{{ config('app.url') . 'reset-password/' . $token }}" target="_blank">
{{ config('app.url') . '/reset-password/' . $token }}</a></p>
<p>-------------------------------------------------------------</p>
<p>Please be sure to copy the entire link into your browser.
The link will expire after {{ env('COMPLAINANT_PASSWORD_RESET_TOKEN_EXPIRE_IN_DAYS')}} day for security reason.</p>
<p>If you did not request this forgotten password email, no action
is needed, your password will not be reset. However, you may want to log into
your account and change your security password as someone may have guessed it.</p>
<p>Thanks</p>
