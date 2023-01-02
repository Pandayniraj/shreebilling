<?php

return [
    'user' => [
        'model' => App\User::class,
    ],
    'broadcast' => [
        'enable' => true,
        'app_name' => 'meronetwork-crm',
        'pusher' => [
            'app_id' => '1007457',
            'app_key' => '4df5c0b0bf2e03e7c74c',
            'app_secret' => 'cee364ffe5af925ad97d',
            'options' => [
            'cluster' => 'ap2',
            'encrypted' => true,
            ],
        ],
    ],

    'app_id' => '1007457',
    'app_key' => '4df5c0b0bf2e03e7c74c',
    'app_secret' => 'cee364ffe5af925ad97d',
    'cluster' => 'ap2',
    'chat_channel'=>'meronetwork-chat-channel',
];
