@component('mail::message')
# Hello,

I hope this email finds you well. I wanted to bring to your attention a referral request received for {{ config('app.name') }}. Here are the details:

<strong>Referrer's Name</strong>: {{ $name }}<br>
<strong>Referrer's Email</strong>: {{ $email }}<br>
<strong>Referrer's Phone</strong>: {{ $phone_number }}<br>
<strong>Referred Member's Name</strong>: {{ $referred_name }}<br>
<strong>Referred Member's Email</strong>: {{ $referred_email }}<br>
<strong>Requested Date</strong> {{ $created_at }}

The referrer has recommended the referred member to join our gym community. As per our referral program, we kindly request you to review and process the referral accordingly. Please take the necessary steps to ensure a seamless onboarding experience for the referred member.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
