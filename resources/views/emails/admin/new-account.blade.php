@component('mail::message')
# Dear {{ $name }},

We are excited to welcome you to our team! Your new staff account has been created, and here are your login details:

<strong>Email</strong>: {{ $email }}<br>
<strong>Password</strong>: {{ $password }}

Please keep this information secure. You can log in using the provided credentials.

@component('mail::button', ['url' => $url])
    Login Here
@endcomponent

If you have any questions or need assistance, please feel free to contact our support team at [Support Email/Contact].

Thank you for choosing to be a part of our organization. We look forward to working with you.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
