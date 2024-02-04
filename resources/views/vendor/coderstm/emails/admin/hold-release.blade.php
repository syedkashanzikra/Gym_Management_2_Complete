@component('mail::message')
# Hello,

I hope this email finds you well. I wanted to inform you that the hold placed on a member's gym subscription has been released, and their access to {{ config('app.name') }} has been restored. Here are the details:

<strong>Member Name</strong>: {{ $name }}<br>
<strong>Member ID</strong>: {{ $id }}<br>
<strong>Member Email</strong>: {{ $email }}<br>
<strong>Member Phone</strong>: {{ $phone_number }}

Please update the member's status in our system accordingly and ensure that they have full access to the gym facilities and services. It is essential to provide them with a smooth transition back into their regular membership privileges.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
