@component('mail::message')
# Hello,

I hope this email finds you well. I wanted to inform you that a new guest pass has been requested on our platform. Here are the details:

<strong>Requester Name</strong>: {{ $name }}<br>
<strong>Requester Email</strong>: {{ $email }}<br>
<strong>Requester Phone</strong>: {{ $phone_number }}<br>
<strong>Requested Date</strong> {{ $created_at }}

Please review the request and take appropriate action. If approved, kindly generate a guest pass and provide the necessary details to the requester. If denied, please communicate the decision and reason to the requester promptly.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
