@component('mail::message')
# Dear {{ $name }},

Your subscription has been upgraded successfully. Here are the details:

<strong>Previous Plan</strong>: {{ $oldPlan }}<br>
<strong>New Plan</strong>: {{ $plan }}<br>
<strong>Price</strong>: {{ $price }}<br>
<strong>Billing Cycle</strong>: {{ $interval }}

If you have any questions or need assistance regarding your subscription, our dedicated support team is here to help. Feel free to reach out to us at [Support Email/Contact].

Thank you for using our service!

Best regards,<br>
{{ config('app.name') }}
@endcomponent
