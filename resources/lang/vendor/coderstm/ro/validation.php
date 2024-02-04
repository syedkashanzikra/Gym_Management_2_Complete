<?php

return [
    'email' => [
        'required' => 'Este necesară o adresă de e-mail.',
        'exists' => 'Adresa ta de e-mail nu exista',
    ],
    'password' =>  [
        'required' => 'Campul pentru parolă este obligatoriu.',
        'match' => 'Parola dvs. nu se potrivește cu înregistrările noastre.',
        'old_match' => 'Parola veche nu se potrivește!',
        'confirmation' => 'Este necesară confirmarea parolei.'
    ],
    'subscription' => [
        'downgrade_plan' => 'Este necesar un plan valid pentru a face downgrade a abonamentului.',
        'plan_already' => 'Te-ai abonat deja la :plan plan.',
    ],
];
