<p>Hello {{ $adminName }},</p>

<p>Your admin account has been created successfully. Below are your login details:</p>
<p><strong>Username:</strong>{{ $email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>
<p><strong>Domain:</strong><a href="http://{{$domain}}" target="_blank">{{ $domain }}</a></p>
<p>Thank you!</p>
