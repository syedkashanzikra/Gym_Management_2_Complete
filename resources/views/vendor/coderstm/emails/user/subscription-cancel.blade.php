@component('mail::message')
# Dear {{ $name }},

We are sorry to see you go, but we've received your subscription cancellation request. Here are the details:

<strong>Plan</strong>: {{ $plan }}<br>
<strong>Cancellation Date</strong>: {{ $ends_at }}

If you've changed your mind and would like to reactivate your subscription, you can do so by clicking the following button:

@component('mail::button', ['url' => config('coderstm.member_url') . '/billing'])
    Reactivate Subscription
@endcomponent

If you have any questions or need assistance regarding your subscription, our dedicated support team is here to help. Feel free to reach out to us at [Support Email/Contact].

Thank you for being a part of our service. We hope to see you again soon!

Best regards,<br>
{{ config('app.name') }}
@endcomponent
