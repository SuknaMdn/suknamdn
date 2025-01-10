<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sample Page
    |--------------------------------------------------------------------------
    */
    // 'page' => [
    //     'title' => 'Page Title',
    //     'heading' => 'Page Heading',
    //     'subheading' => 'Page Subheading',
    //     'navigationLabel' => 'Page Navigation Label',
    //     'section' => [],
    //     'fields' => []
    // ],

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'general_settings' => [
        'title' => 'General Settings',
        'heading' => 'General Settings',
        'subheading' => 'Manage general site settings here.',
        'navigationLabel' => 'General',
        'sections' => [
            "site" => [
                "title" => "Site",
                "description" => "Manage basic settings."
            ],
            "theme" => [
                "title" => "Theme",
                "description" => "Change default theme."
            ],
            "unit_reservation" => [
                "title" => "Unit Reservation",
                "description" => "Manage unit reservation settings."
            ],
            "term_and_condition" => [
                "title" => "Term and Condition",
                "description" => "Manage term and condition settings."
            ],
            "privacy_policy" => [
                "title" => "Privacy Policy",
                "description" => "Manage privacy policy settings."
            ],
            "payment_timeout_days" => [
                "title" => "Payment Timeout Days",
                "description" => "Manage payment timeout days settings."
            ],
        ],
        "fields" => [
            "brand_name" => "Brand Name",
            "site_active" => "Site Status",
            "brand_logoHeight" => "Brand Logo Height",
            "brand_logo" => "Brand Logo",
            "site_favicon" => "Site Favicon",
            "primary" => "Primary",
            "secondary" => "Secondary",
            "gray" => "Gray",
            "success" => "Success",
            "danger" => "Danger",
            "info" => "Info",
            "warning" => "Warning",
            "serious_value_for_unit_reservation" => "Serious Value for Unit Reservation",
            "term_and_condition" => "Term and Condition",
            "privacy_policy" => "Privacy Policy",
            'payment_timeout_days' => 'Payment Timeout Days',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail Settings
    |--------------------------------------------------------------------------
    */
    'mail_settings' => [
        'title' => 'Mail Settings',
        'heading' => 'Mail Settings',
        'subheading' => 'Manage mail configuration.',
        'navigationLabel' => 'Mail',
        'sections' => [
            "config" => [
                "title" => "Configuration",
                "description" => "description"
            ],
            "sender" => [
                "title" => "From (Sender)",
                "description" => "description"
            ],
            "mail_to" => [
                "title" => "Mail to",
                "description" => "description"
            ],
        ],
        "fields" => [
            "placeholder" => [
                "receiver_email" => "Receiver email.."
            ],
            "driver" => "Driver",
            "host" => "Host",
            "port" => "Port",
            "encryption" => "Encryption",
            "timeout" => "Timeout",
            "username" => "Username",
            "password" => "Password",
            "email" => "Email",
            "name" => "Name",
            "mail_to" => "Mail to",
        ],
        "actions" => [
            "send_test_mail" => "Send Test Mail"
        ]
    ],

];
