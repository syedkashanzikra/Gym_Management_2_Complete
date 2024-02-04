@component('mail::message')
# Hello,

I hope this email finds you well. I wanted to bring to your attention that a user on our platform has recently changed their avatar. Here are the details:

<strong>Member Name</strong>: {{ $name }}<br>
<strong>Member ID</strong>: {{ $id }}<br>
<strong>Member Email</strong>: {{ $email }}<br>
<strong>Member Phone</strong>: {{ $phone_number }}<br>
<strong>New Avatar</strong>: <a href="{{ $avatar_url }}" target="_blank" rel="noopener noreferrer">{{ $avatar_name }}</a>

Kindly review the new avatar to ensure it complies with our community guidelines and terms of service. If you find any issues or violations, please take appropriate action as per our policies.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
