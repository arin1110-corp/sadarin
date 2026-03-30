<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'drive_folder' => [
        'PNS_IKD' => env('GOOGLE_DRIVE_FOLDER_PNS_IKD'),
        'PPPK_IKD' => env('GOOGLE_DRIVE_FOLDER_PPPK_IKD'),
        'PARUHWAKTU_IKD' => env('GOOGLE_DRIVE_FOLDER_PARUHWAKTU_IKD'),
        'NONASN_IKD' => env('GOOGLE_DRIVE_FOLDER_NONASN_IKD'),

        'PNS_CORETAX_2026' => env('GOOGLE_DRIVE_FOLDER_PNS_CORETAX_2026'),
        'PPPK_CORETAX_2026' => env('GOOGLE_DRIVE_FOLDER_PPPK_CORETAX_2026'),
        'PARUHWAKTU_CORETAX_2026' => env('GOOGLE_DRIVE_FOLDER_PARUHWAKTU_CORETAX_2026'),
        'NONASN_CORETAX_2026' => env('GOOGLE_DRIVE_FOLDER_NONASN_CORETAX_2026'),
    ],

];