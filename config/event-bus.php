<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Action types.
    |--------------------------------------------------------------------------
    |
    | List of action types, this references who initiated the event e.g.
    | a user on the site, an administrator, via an api or internally
    | in the system or app or via a data migration.
    |
    */

    'action_types' => [
        'user',
        'admin',
        'api',
        'system',
        'app',
        'migration',
        'other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Venture configuration
    |--------------------------------------------------------------------------
    |
    | Venture configuration allows you to specify credentials, culture
    | excluded events and other configurations related to the event
    | bus client.
    |
    */

    'venture' => [
        'culture' => env('RINGIER_SB_CULTURE', 'en_GB'),
        'dont_report' => [
            // Add service bus event types here that
            // should not be logged (too much traffic).
            //
            // For example: ListingViewed
        ],
        'enabled' => env('RINGIER_SB_ENABLED', true),
        'endpoint' => env('RINGIER_SB_ENDPOINT', 'https://bus.ritdu.net/v1/'),
        'password' => env('RINGIER_SB_PASSWORD'),
        'username' => env('RINGIER_SB_USER'),
        'venture_config_id' => env('RINGIER_SB_VENTURE_CONFIG_ID'),
        'version' => env('RINGIER_SB_VERSION', '0.3.0'),
        'send_notifications' => env('RINGIER_SB_SEND_NOTIFICATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue name
    |--------------------------------------------------------------------------
    |
    | Specify here on which queue event bus jobs should be dispatched to.
    | If not specified your default queue will be used.
    |
    */

    'queue' => env('RINGIER_SB_QUEUE'),

    /*
    |--------------------------------------------------------------------------
    | Queue connection
    |--------------------------------------------------------------------------
    |
    | Specify here on which queue connection event bus jobs should be dispatched to.
    | If not specified your default connection will be used.
    |
    */

    'queue_connection' => env('RINGIER_SB_QUEUE_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Delay
    |--------------------------------------------------------------------------
    |
    | Add a delay prior to the processing of the event bus job. This may be of
    | use in reducing the total number of jobs being queue if many
    | of the are duplicates
    |
    */

    'queue_delay' => env('RINGIER_SB_QUEUE_DELAY'),
];
