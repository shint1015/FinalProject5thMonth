<?php
// config/config.php
// General application configuration

return [
    'app_name'   => 'My Simple API',
    'timezone'   => 'Asia/Tokyo',
    'api_prefix' => '/api', // if you want to manage /api here

    'default_headers' => [
        'Content-Type' => 'application/json; charset=utf-8',
    ],
];