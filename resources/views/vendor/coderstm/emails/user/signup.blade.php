@component('mail::message')
# Dear {{ $name }},

Thank you for choosing {{ config('app.name') }}! We are thrilled to have you as a valued member of our community. As
promised, here are the details of your subscription plan:

<strong>Plan</strong>: {{ $plan }}<br>
<strong>Price</strong>: {{ $price }}<br>
<strong>Billing Cycle</strong>: {{ $interval }}

With this plan, you'll have access to a wide range of exciting features, exclusive content, and premium benefits. We're
confident that you'll find great value and enjoy a seamless experience throughout your subscription.

If you have any questions or need assistance regarding your subscription, our dedicated support team is here to help.
Feel free to reach out to us at [Support Email/Contact].

Once again, welcome to {{ config('app.name') }}! We can't wait to see what you'll achieve with us.

Best regards,<br>
{{ config('app.name') }}
@endcomponent
