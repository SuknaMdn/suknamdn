<?php

return [
    'general_settings' => [
        'title' => 'الإعدادات العامة',
        'heading' => 'الإعدادات العامة',
        'subheading' => 'إدارة إعدادات الموقع العامة هنا.',
        'navigationLabel' => 'عام',
        'sections' => [
            'site' => [
                'title' => 'موقع',
                'description' => 'إدارة الإعدادات الأساسية.',
            ],
            'theme' => [
                'title' => 'سمة',
                'description' => 'تغيير الموضوع الافتراضي.',
            ],
            'unit_reservation' => [
                'title' => 'حجز الوحدة',
                'description' => 'إدارة إعدادات حجز الوحدة.',
            ],
            'term_and_condition' => [
                'title' => 'المصطلح والشرط',
                'description' => 'إدارة إعدادات المصطلح والشروط.',
            ],
            'privacy_policy' => [
                'title' => 'سياسة الخصوصية',
                'description' => 'إدارة إعدادات سياسة الخصوصية.',
            ],
            'payment_timeout_days' => [
                'title' => 'أيام مهلة الدفع',
                'description' => 'إدارة إعدادات أيام مهلة الدفع.',
            ],
        ],
        'fields' => [
            'brand_name' => 'اسم العلامة التجارية',
            'site_active' => 'حالة الموقع',
            'brand_logoHeight' => 'ارتفاع شعار العلامة التجارية',
            'brand_logo' => 'شعار العلامة التجارية',
            'site_favicon' => 'أيقونة الموقع المفضلة',
            'primary' => 'أساسي',
            'secondary' => 'ثانوي',
            'gray' => 'رمادي',
            'success' => 'نجاح',
            'danger' => 'خطر',
            'info' => 'معلومات',
            'warning' => 'تحذير',
            'serious_value_for_unit_reservation' => 'قيمة جدية لحجز الوحدة',
            'term_and_condition' => 'المصطلح والشرط',
            'privacy_policy' => 'سياسة الخصوصية',
            'payment_timeout_days' => 'أيام مهلة الدفع',
        ],
    ],
    'mail_settings' => [
        'title' => 'إعدادات البريد',
        'heading' => 'إعدادات البريد',
        'subheading' => 'إدارة تكوين البريد.',
        'navigationLabel' => 'بريد',
        'sections' => [
            'config' => [
                'title' => 'إعدادات',
                'description' => 'وصف',
            ],
            'sender' => [
                'title' => 'من (المرسل)',
                'description' => 'وصف',
            ],
            'mail_to' => [
                'title' => 'البريد إلى',
                'description' => 'وصف',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'البريد الإلكتروني للمتلقي..',
            ],
            'driver' => 'سائق',
            'host' => 'يستضيف',
            'port' => 'ميناء',
            'encryption' => 'التشفير',
            'timeout' => 'نفذ الوقت',
            'username' => 'اسم المستخدم',
            'password' => 'كلمة المرور',
            'email' => 'بريد إلكتروني',
            'name' => 'اسم',
            'mail_to' => 'البريد إلى',
        ],
        'actions' => [
            'send_test_mail' => 'إرسال بريد الاختبار',
        ],
    ]
    ];
