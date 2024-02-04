@component('mail::message')
# Hello,

I hope this email finds you well. I wanted to inform you that a user on our platform has been marked as <strong>{{ $status }}</strong>. Here are the details:

<strong>Member's Name</strong>: {{ $name }}<br>
<strong>Member's Email</strong>: {{ $email }}<br>
<strong>Member's Phone</strong>: {{ $phone_number }}<br>
<strong>Plan</strong>: {{ $plan }}<br>
@if ($price)
<strong>Price</strong>: {{ $price }}<br>
<strong>Billing Cycle</strong>: {{ $interval }}<br>
@endif
<strong>Admin</strong>: {{ $admin }}<br>
<strong>Date at</strong>: {{ $date_time }}<br>
<strong>Previous Status</strong>: {{ $previous }}
@if ($note)
<br>
<strong>Note</strong>: {{ $note }}
@endif

The user has met the criteria for activating their account and is now ready to access the full range of features and services provided by our platform.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
