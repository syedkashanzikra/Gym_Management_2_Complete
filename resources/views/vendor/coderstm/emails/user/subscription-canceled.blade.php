@component('mail::message')
# Dear {{ $name }},

We wanted to notify you that your subscription with us has canceled as of {{ $ends_at }}.

<strong>Plan</strong>: {{ $plan }}<br>
<strong>Price</strong>: {{ $price }}<br>
<strong>Billing Cycle</strong>: {{ $interval }}<br>
<strong>Expiration Date</strong>: {{ $ends_at }}

As your subscription has ended, you will no longer have access to our premium features and services. We hope you've enjoyed your experience with us during your subscription period.

To renew your subscription and continue enjoying our premium offerings, please click the button below:

@component('mail::button', ['url' => config('coderstm.member_url') . '/billing'])
    Renew Subscription
@endcomponent

If you have any questions or need assistance regarding your subscription, our dedicated support team is here to help. Feel free to reach out to us at [Support Email/Contact].

Thank you for being a part of our service. We hope to see you again soon!

Best regards,<br>
{{ config('app.name') }}
@endcomponent
