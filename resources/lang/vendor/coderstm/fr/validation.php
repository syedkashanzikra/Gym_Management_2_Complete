<?php

return [
    'email' => [
        'required' => 'Une adresse email est requise.',
        'exists' => 'Votre adresse email n\'existe pas.',
    ],
    'password' => [
        'required' => 'Le champ du mot de passe est requis.',
        'match' => 'Votre mot de passe ne correspond pas à nos enregistrements.',
        'old_match' => 'L\'ancien mot de passe ne correspond pas !',
        'confirmation' => 'La confirmation du mot de passe est requise.',
    ],

];
