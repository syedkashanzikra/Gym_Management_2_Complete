<?php

return [
    'email' => [
        'required' => 'An email address is required.',
        'exists' => 'Your email address doens\'t exists.',
    ],
    'password' =>  [
        'required' => 'The password field is required.',
        'match' => 'Your password doesn\'t match with our records.',
        'old_match' => 'Old password doesn\'t match!',
        'confirmation' => 'Password confirmation is required.'
    ],
    'subscription' => [
        'downgrade_plan' => 'A valid plan is necessary for downgrading the subscription.',
        'plan_already' => 'You already subscribed to :plan plan.',
    ],
];
