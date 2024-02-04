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

    'accepted' => 'यह :attribute स्वीकृत किया जाना चाहिए।',
    'active_url' => ':attribute एक मान्य URL नहीं है।',
    'after' => ':attribute को :date के बाद की तारीख होनी चाहिए।',
    'after_or_equal' => ':attribute को :date के बाद या उसके समान तारीख होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर हो सकते हैं।',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्या, डैश और अंडरस्कोर हो सकते हैं।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्या हो सकती हैं।',
    'array' => ':attribute एक एरे होना चाहिए।',
    'before' => ':attribute को :date के पहले की तारीख होनी चाहिए।',
    'before_or_equal' => ':attribute को :date के पहले या उसके समान तारीख होनी चाहिए।',
    'between' => [
        'numeric' => ':attribute को :min और :max के बीच होना चाहिए।',
        'file' => ':attribute को :min और :max किलोबाइट्स के बीच होना चाहिए।',
        'string' => ':attribute को :min और :max वर्णों के बीच होना चाहिए।',
        'array' => ':attribute में :min और :max आइटम होने चाहिए।',
    ],
    'boolean' => ':attribute फ़ील्ड true या false होना चाहिए।',
    'confirmed' => ':attribute पुष्टिकरण मेल नहीं खाता।',
    'date' => ':attribute मान्य तारीख नहीं है।',
    'date_equals' => ':attribute को :date के समान तारीख होनी चाहिए।',
    'date_format' => ':attribute :format प्रारूप के साथ मेल नहीं खाता।',
    'different' => ':attribute और :other अलग होने चाहिए।',
    'digits' => ':attribute :digits अंक होने चाहिए।',
    'digits_between' => ':attribute :min और :max अंकों के बीच होना चाहिए।',
    'dimensions' => ':attribute में अमान्य छवि आयाम हैं।',
    'distinct' => ':attribute फ़ील्ड में एक प्रतिलिपि मूल्य है।',
    'email' => ':attribute मान्य ईमेल पता होना चाहिए।',
    'ends_with' => ':attribute को निम्नलिखित में से किसी एक के साथ ख़तम होना चाहिए: :values।',
    'exists' => 'चयनित :attribute अमान्य है।',
    'file' => ':attribute एक फ़ाइल होनी चाहिए।',
    'filled' => ':attribute फ़ील्ड में एक मूल्य होना चाहिए।',
    'gt' => [
        'numeric' => ':attribute :value से अधिक होना चाहिए।',
        'file' => ':attribute :value किलोबाइट्स से अधिक होना चाहिए।',
        'string' => ':attribute :value वर्णों से अधिक होना चाहिए।',
        'array' => ':attribute में :value आइटमों से अधिक होने चाहिए।',
    ],
    'gte' => [
        'numeric' => ':attribute :value या उससे अधिक होना चाहिए।',
        'file' => ':attribute :value किलोबाइट्स या उससे अधिक होना चाहिए।',
        'string' => ':attribute :value वर्णों या उससे अधिक होना चाहिए।',
        'array' => ':attribute में :value आइटमों से अधिक होने चाहिए।',
    ],
    'image' => ':attribute एक छवि होनी चाहिए।',
    'in' => 'चयनित :attribute अमान्य है।',
    'in_array' => ':attribute फ़ील्ड :other में मौजूद नहीं है।',
    'integer' => ':attribute पूर्णांक होना चाहिए।',
    'ip' => ':attribute मान्य IP पता होना चाहिए।',
    'ipv4' => ':attribute मान्य IPv4 पता होना चाहिए।',
    'ipv6' => ':attribute मान्य IPv6 पता होना चाहिए।',
    'json' => ':attribute मान्य JSON स्ट्रिंग होना चाहिए।',
    'lt' => [
        'numeric' => ':attribute :value से कम होना चाहिए।',
        'file' => ':attribute :value किलोबाइट्स से कम होना चाहिए।',
        'string' => ':attribute :value वर्णों से कम होना चाहिए।',
        'array' => ':attribute :value आइटमों से कम होना चाहिए।',
    ],
    'lte' => [
        'numeric' => ':attribute :value या उससे कम होना चाहिए।',
        'file' => ':attribute :value किलोबाइट्स या उससे कम होना चाहिए।',
        'string' => ':attribute :value वर्णों या उससे कम होना चाहिए।',
        'array' => ':attribute :value आइटमों से अधिक नहीं होना चाहिए।',
    ],
    'max' => [
        'numeric' => ':attribute :max से अधिक नहीं हो सकता।',
        'file' => ':attribute :max किलोबाइट्स से अधिक नहीं हो सकता।',
        'string' => ':attribute :max वर्णों से अधिक नहीं हो सकता।',
        'array' => ':attribute :max आइटमों से अधिक नहीं हो सकता।',
    ],
    'mimes' => ':attribute एक :values प्रकार की फ़ाइल होनी चाहिए।',
    'mimetypes' => ':attribute एक :values प्रकार की फ़ाइल होनी चाहिए।',
    'min' => [
        'numeric' => ':attribute कम से कम :min होना चाहिए।',
        'file' => ':attribute कम से कम :min किलोबाइट्स होनी चाहिए।',
        'string' => ':attribute कम से कम :min वर्णों होने चाहिए।',
        'array' => ':attribute में कम से कम :min आइटम होने चाहिए।',
    ],
    'not_in' => 'चयनित :attribute अमान्य है।',
    'not_regex' => ':attribute प्रारूप अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए।',
    'password' => 'पासवर्ड गलत है।',
    'present' => ':attribute फ़ील्ड मौजूद होनी चाहिए।',
    'regex' => ':attribute प्रारूप अमान्य है।',
    'required' => ':attribute फ़ील्ड आवश्यक है।',
    'required_if' => ':attribute फ़ील्ड की आवश्यकता है जब :other :value है।',
    'required_unless' => ':attribute फ़ील्ड की आवश्यकता है जब तक :other :values में नहीं है।',
    'required_with' => ':attribute फ़ील्ड की आवश्यकता है जब :values मौजूद है।',
    'required_with_all' => ':attribute फ़ील्ड की आवश्यकता है जब :values सभी मौजूद हैं।',
    'required_without' => ':attribute फ़ील्ड की आवश्यकता है जब :values मौजूद नहीं है।',
    'required_without_all' => ':attribute फ़ील्ड की आवश्यकता है जब :values सभी मौजूद नहीं हैं।',
    'same' => ':attribute और :other मेल खाना चाहिए।',
    'size' => [
        'numeric' => ':attribute :size होना चाहिए।',
        'file' => ':attribute :size किलोबाइट्स होना चाहिए।',
        'string' => ':attribute :size वर्णों का होना चाहिए।',
        'array' => ':attribute में :size आइटम होना चाहिए।',
    ],
    'starts_with' => ':attribute निम्नलिखित में से किसी एक के साथ शुरू होना चाहिए: :values।',
    'string' => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone' => ':attribute मान्य समय क्षेत्र होना चाहिए।',
    'unique' => ':attribute पहले से ही लिया गया है।',
    'uploaded' => ':attribute अपलोड करने में विफल हुआ।',
    'url' => ':attribute प्रारूप अमान्य है।',
    'uuid' => ':attribute मान्य UUID होना चाहिए।',


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
        'email' => 'ईमेल',
        'title' => 'टाइटल',
        'plan' => 'प्लान',
        'name' => 'नाम',
        'first_name' => 'पहला नाम',
        'last_name' => 'लास्ट नाम',
        'phone_number' => 'फ़ोन नंबर',
        'line1' => 'सड़क का पता',
        'city' => 'सिटी',
        'postal_code' => 'पोस्टल कोड',
        'country' => 'कंट्री',
        'interval' => 'इंटरवल',
        'password' => 'पासवर्ड',
        'password_confirmation' => 'पासवर्ड कन्फ़र्मेशन',
        'address' => [
            'line1' => 'सड़क का पता',
            'city' => 'सिटी',
            'postal_code' => 'पोस्टल कोड',
            'country' => 'कंट्री',
        ],
        'media' => 'फाइल',
        'items' => 'आइटम्स',
        'source' => 'सोर्स',
        'message' => 'मैसेज',
        'label' => 'लेबल',
        'monthly_fee' => 'मासिक फीस',
        'yearly_fee' => 'वार्षिक फीस',
        'subject' => 'सब्जेक्ट',
        'users' => 'उसेर्स',
        'user' => 'यूजर',
        'admin' => 'एडमिन',
    ],

];
