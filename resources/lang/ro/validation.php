<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute trebuie să fie acceptat.',
    'active_url' => ':attribute nu este o adresă URL validă.',
    'after' => ':attribute trebuie să fie o dată după :date.',
    'after_or_equal' => ':attribute trebuie să fie o dată după sau egală cu :date.',
    'alpha' => ':attribute poate conține doar litere.',
    'alpha_dash' => ':attribute poate conține doar litere, cifre, liniuțe și underscore-uri.',
    'alpha_num' => ':attribute poate conține doar litere și cifre.',
    'array' => ':attribute trebuie să fie un array.',
    'before' => ':attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal' => ':attribute trebuie să fie o dată înainte sau egală cu :date.',
    'between' => [
        'numeric' => ':attribute trebuie să fie între :min și :max.',
        'file' => ':attribute trebuie să aibă între :min și :max kilobytes.',
        'string' => ':attribute trebuie să aibă între :min și :max caractere.',
        'array' => ':attribute trebuie să aibă între :min și :max elemente.',
    ],
    'boolean' => 'Câmpul :attribute trebuie să fie adevărat sau fals.',
    'confirmed' => 'Confirmarea :attribute nu se potrivește.',
    'date' => ':attribute nu este o dată validă.',
    'date_equals' => ':attribute trebuie să fie o dată egală cu :date.',
    'date_format' => ':attribute nu corespunde formatului :format.',
    'different' => ':attribute și :other trebuie să fie diferite.',
    'digits' => ':attribute trebuie să aibă :digits cifre.',
    'digits_between' => ':attribute trebuie să aibă între :min și :max cifre.',
    'dimensions' => ':attribute are dimensiuni de imagine nevalide.',
    'distinct' => 'Câmpul :attribute are o valoare duplicată.',
    'email' => ':attribute trebuie să fie o adresă de email validă.',
    'ends_with' => ':attribute trebuie să se termine cu una dintre următoarele: :values.',
    'exists' => ':attribute selectat nu este valid.',
    'file' => ':attribute trebuie să fie un fișier.',
    'filled' => 'Câmpul :attribute trebuie să aibă o valoare.',
    'gt' => [
        'numeric' => ':attribute trebuie să fie mai mare decât :value.',
        'file' => ':attribute trebuie să aibă mai mult de :value kilobytes.',
        'string' => ':attribute trebuie să aibă mai mult de :value caractere.',
        'array' => ':attribute trebuie să aibă mai mult de :value elemente.',
    ],
    'gte' => [
        'numeric' => ':attribute trebuie să fie mai mare sau egal cu :value.',
        'file' => ':attribute trebuie să aibă cel puțin :value kilobytes.',
        'string' => ':attribute trebuie să aibă cel puțin :value caractere.',
        'array' => ':attribute trebuie să aibă cel puțin :value elemente.',
    ],
    'image' => ':attribute trebuie să fie o imagine.',
    'in' => ':attribute selectat nu este valid.',
    'in_array' => 'Câmpul :attribute nu există în :other.',
    'integer' => ':attribute trebuie să fie un număr întreg.',
    'ip' => ':attribute trebuie să fie o adresă IP validă.',
    'ipv4' => ':attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6' => ':attribute trebuie să fie o adresă IPv6 validă.',
    'json' => ':attribute trebuie să fie un șir JSON valid.',
    'lt' => [
        'numeric' => ':attribute trebuie să fie mai mic decât :value.',
        'file' => ':attribute trebuie să aibă mai puțin de :value kilobytes.',
        'string' => ':attribute trebuie să aibă mai puțin de :value caractere.',
        'array' => ':attribute trebuie să aibă mai puțin de :value elemente.',
    ],
    'lte' => [
        'numeric' => ':attribute trebuie să fie mai mic sau egal cu :value.',
        'file' => ':attribute trebuie să aibă cel mult :value kilobytes.',
        'string' => ':attribute trebuie să aibă cel mult :value caractere.',
        'array' => ':attribute nu trebuie să aibă mai mult de :value elemente.',
    ],
    'max' => [
        'numeric' => ':attribute nu poate fi mai mare de :max.',
        'file' => ':attribute nu poate avea mai mult de :max kilobytes.',
        'string' => ':attribute nu poate avea mai mult de :max caractere.',
        'array' => ':attribute nu poate avea mai mult de :max elemente.',
    ],
    'mimes' => ':attribute trebuie să fie un fișier de tip: :values.',
    'mimetypes' => ':attribute trebuie să fie un fișier de tip: :values.',
    'min' => [
        'numeric' => ':attribute trebuie să fie cel puțin :min.',
        'file' => ':attribute trebuie să aibă cel puțin :min kilobytes.',
        'string' => ':attribute trebuie să aibă cel puțin :min caractere.',
        'array' => ':attribute trebuie să aibă cel puțin :min elemente.',
    ],
    'not_in' => ':attribute selectat nu este valid.',
    'not_regex' => 'Formatul :attribute nu este valid.',
    'numeric' => ':attribute trebuie să fie un număr.',
    'password' => 'Parola este incorectă.',
    'present' => 'Câmpul :attribute trebuie să fie prezent.',
    'regex' => 'Formatul :attribute nu este valid.',
    'required' => 'Câmpul :attribute este obligatoriu.',
    'required_if' => 'Câmpul :attribute este obligatoriu când :other este :value.',
    'required_unless' => 'Câmpul :attribute este obligatoriu cu excepția cazului în care :other este în :values.',
    'required_with' => 'Câmpul :attribute este obligatoriu când :values este prezent.',
    'required_with_all' => 'Câmpul :attribute este obligatoriu când toate :values sunt prezente.',
    'required_without' => 'Câmpul :attribute este obligatoriu când :values nu este prezent.',
    'required_without_all' => 'Câmpul :attribute este obligatoriu când niciuna dintre :values nu este prezentă.',
    'same' => ':attribute și :other trebuie să se potrivească.',
    'size' => [
        'numeric' => ':attribute trebuie să fie de :size.',
        'file' => ':attribute trebuie să aibă :size kilobytes.',
        'string' => ':attribute trebuie să aibă :size caractere.',
        'array' => ':attribute trebuie să conțină :size elemente.',
    ],
    'starts_with' => ':attribute trebuie să înceapă cu una dintre următoarele: :values.',
    'string' => ':attribute trebuie să fie un șir de caractere.',
    'timezone' => ':attribute trebuie să fie o zonă validă.',
    'unique' => ':attribute a fost deja utilizat.',
    'uploaded' => ':attribute nu a putut fi încărcat.',
    'url' => 'Formatul :attribute nu este valid.',
    'uuid' => ':attribute trebuie să fie un UUID valid.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'email',
        'title' => 'titlu',
        'plan' => 'plan',
        'name' => 'nume',
        'first_name' => 'prenume',
        'last_name' => 'nume de familie',
        'phone_number' => 'numar de telefon',
        'line1' => 'randul1',
        'city' => 'oras',
        'postal_code' => 'cod postal',
        'country' => 'tara',
        'interval' => 'interval',
        'password' => 'parola',
        'password_confirmation' => 'parola confirmata',
        'address' => [
            'line1' => 'randul1',
            'city' => 'oras',
            'postal_code' => 'cod postal',
            'country' => 'tara',
        ],
        'media' => 'fisiere',
        'items' => 'articole',
        'source' => 'sursa',
        'message' => 'mesaj',
        'label' => 'eticheta',
        'monthly_fee' => 'taxa lunara',
        'yearly_fee' => 'taxa anuala',
        'subject' => 'subiect',
        'users' => 'utilizatori',
        'user' => 'utilizator',
        'admin' => 'admin',
    ],

];
