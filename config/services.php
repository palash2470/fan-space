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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
            /*'client_id' => '380027999653432',
            'client_secret' => '928f715c9bddd901ec4e2ddd56c8e80b',
            'redirect' => 'https://pritamnew.aqualeafitsol.com/talent-collection/social-login/facebook-callback',*/
        /*'client_id' => '733109433912785',
        'client_secret' => '76a94e81a06dfedd90bd37a8e7d3d6c1',
        'redirect' => 'https://sudipta.aqualeafitsol.com/talent-collection/social-login/facebook-callback',*/
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
      ],
      'google'=>[
        /*'client_id' => '408014823442-oeqj88upc77vb3fe7vkj9v58n224raqs.apps.googleusercontent.com',
        'client_secret' => 'ko2yVKLBb5RQPpgfkDAwICIS',
        'redirect' => 'https://pritamnew.aqualeafitsol.com/talent-collection/social-login/google-callback',*/
        /*'client_id' => '719918324481-iei2i4o7lin7na9h28feqmv2j0rr6nj6.apps.googleusercontent.com',
        'client_secret' => 'QusgbYUFiZm7m5tmxslHMQd_',
        'redirect' => 'https://sudipta.aqualeafitsol.com/talent-collection/social-login/google-callback',*/
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
      ]

];
