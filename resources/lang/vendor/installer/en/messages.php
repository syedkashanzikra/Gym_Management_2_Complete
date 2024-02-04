<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => 'Coderstm Installer',
    'next' => 'Next Step',
    'back' => 'Previous',
    'finish' => 'Install',
    'forms' => [
        'errorTitle' => 'The following errors occurred:',
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Welcome',
        'title'   => 'Coderstm Installer',
        'message' => 'Welcome to Coderstm. Before getting started, we need some information on the database. You will need to know the following items before proceeding.',
        'next'    => "Let's go",
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'title' => 'Server Requirements',
        'next'    => 'Check Permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Step 2 | Permissions',
        'title' => 'Permissions',
        'next' => 'Configure Environment',
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Step 3 | Environment Settings',
            'title' => 'Environment Settings',
            'desc' => 'Please select how you want to configure the apps <code>.env</code> file.',
            'wizard-button' => 'Form Wizard Setup',
        ],
        'wizard' => [
            'templateTitle' => 'Step 3 | Environment Settings | Guided Wizard',
            'title' => 'Guided <code>.env</code> Wizard',
            'tabs' => [
                'general' => 'General',
                'database' => 'Database',
                'application' => 'Application',
            ],
            'form' => [
                'name_required' => 'An environment name is required.',
                'app_name_label' => 'App Name',
                'app_name_placeholder' => 'App Name',
                'app_admin_email_label' => 'App Admin Email',
                'app_admin_email_placeholder' => 'App Admin Email',
                'app_env_label' => 'App Environment',
                'app_env_label_local' => 'Local',
                'app_env_label_developement' => 'Development',
                'app_env_label_production' => 'Production',
                'app_env_label_other' => 'Other',
                'app_env_placeholder_other' => 'Enter your environment...',
                'app_debug_label' => 'App Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_url_label' => 'App Url',
                'app_url_placeholder' => 'App Url',
                'license_key_label' => 'License Key',
                'license_key_placeholder' => '0|XXXXXXXXXXXXXXX000XXXXXXXXXXXXXXXXXXXXXX',
                'db_connection_failed' => 'Could not connect to the database.',
                'db_connection_label' => 'Database Connection',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Database Host',
                'db_host_placeholder' => 'Database Host',
                'db_port_label' => 'Database Port',
                'db_port_placeholder' => 'Database Port',
                'db_name_label' => 'Database Name',
                'db_name_placeholder' => 'Database Name',
                'db_username_label' => 'Database User Name',
                'db_username_placeholder' => 'Database User Name',
                'db_password_label' => 'Database Password',
                'db_password_placeholder' => 'Database Password',

                'app_tabs' => [
                    'more_info' => 'More Info',

                    'recaptcha_label' => 'Recaptcha',
                    'recaptcha_site_key_label' => 'Recaptcha Site Key',
                    'recaptcha_site_key_palceholder' => '6LfE-w0jAAAAAJKu5mqPTmd2ofZ6eVRtbh55LeHH',
                    'recaptcha_secret_key_label' => 'Recaptcha Secret Key',
                    'recaptcha_secret_key_palceholder' => '6LfE-w0jAAAAAN06SHV8jbWvIOPY5OXBDm7u1vao',

                    'stripe_label' => 'Stripe',
                    'stripe_key_label' => 'Stripe Key',
                    'stripe_key_palceholder' => 'pk_test_XXQ8m5rUcQoXi9sHSLXnuMReXX',
                    'stripe_secret_label' => 'Stripe Secret',
                    'stripe_secret_palceholder' => 'sk_test_XX6i5bZlDKyNW4dSkznIOgXX',
                    'stripe_webhook_secret_label' => 'Stripe Webhook Secret',
                    'stripe_webhook_secret_palceholder' => 'whsec_XXAs3s6XcJhxjYjzkmJIB43utn2C7XX',
                ],
                'buttons' => [
                    'setup_database' => 'Setup Database',
                    'setup_application' => 'Setup Application',
                    'install' => 'Install Application',
                ],
            ],
        ],
        'success' => 'Your .env file settings have been saved.',
        'errors' => 'Unable to save the .env file, Please create it manually.',
    ],

    'install' => 'Install',

    /*
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Coderstm Installer successfully INSTALLED on ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation Finished',
        'templateTitle' => 'Installation Finished',
        'finished' => 'Application has been successfully installed.',
        'migration' => 'Migration &amp; Seed Console Output:',
        'console' => 'Application Console Output:',
        'log' => 'Installation Log Entry:',
        'env' => 'Final .env File:',
        'exit' => 'Click here to exit',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater' => [
        /*
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Welcome To The Updater',
            'message' => 'Welcome to the update wizard.',
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Overview',
            'message' => 'There is 1 update.|There are :number updates.',
            'install_updates' => 'Install Updates',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Finished',
            'finished' => 'Application\'s database has been successfully updated.',
            'exit' => 'Click here to exit',
        ],

        'log' => [
            'success_message' => 'Coderstm Installer successfully UPDATED on ',
        ],
    ],
];
