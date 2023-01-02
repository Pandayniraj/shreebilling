<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Settings driver
     | ------------------------------------------------------------------------------------------------
     |  Supported : 'json', 'database', 'memory', 'array'
     */
    'default' => 'json',

    /* ------------------------------------------------------------------------------------------------
     |  Settings supported drivers
     | ------------------------------------------------------------------------------------------------
     */
    'stores' => [
        'json'     => [
            'path' => storage_path('app/settings.json'),
        ],

        'database' => [
            'connection' => null,
            'table'      => 'settings',
        ],
    ],

    'enable_audit' => '1',

    'eloquent-ldap_label_internal'=>null,
    'auth_email_validation'=>null,
    'mail_system_sender_address'=>'system.noreply@lesk.io',
    'mail_system_sender_label'=>'LESK System',
    'app_email_notifications'=>null,
    'mail_system_sender_address'=>'system.noreply@lesk.io',
    'auth_enable_remember_token'=>null,
    'lern_enable_record'=>true,
    'lern_enable_notify'=>true,
    'lern_notify_channel'=>'LESK',
    'audit_purge_retention'=>365,
    'app_home_route'=>'welcome',
    'app_supportedLocales'=>[
        'en' => 'English',
        'es' => 'español',
        'fr' => 'français',
        'np' => 'नेपाली',
    ],
    'walled-garden_enabled'=>null,
    'walled-garden_exemptions-regex'=>[
        0 => "/password\/reset\/.*/",
        1 => "/auth\/verify\/.*/",
    ],
    'menu-builder_framework_handler'=>App\Handlers\LESKSecuredMenuHandler::class,
    'menu-builder_framework_handler_erp3'=>App\Handlers\LESKSecuredMenuHandler3::class,
    'app_allow_registration'=>true,
    'app_right_sidebar'=>true,
    'app_tag_line'=>'Powered by Meronetwork',
    'app_copyright_line'=>'<strong>Copyright &copy; 2020 <a href="#">Meronetwork</a>.</strong> All rights reserved.',
    'app_context_help_area'=>true,
    'app_notification_area'=>true,
    'app_extended_user_menu'=>true,
    'app_user_profile_link'=>true,
    'app_right_sidebar'=>true,
    'app_search_box'=>true,
    'app_flash_notification_auto_hide_enabled'=>true,
    'app_flash_notification_auto_hide_delay'=>null,
    'time_format'=>null,
];
