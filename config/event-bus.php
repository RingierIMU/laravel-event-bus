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
    | Credentials.
    |--------------------------------------------------------------------------
    |
    | Specify endpoint, username, password and api version for communicating
    | with the event bus.
    |
    */

    'credentials' => [
        'endpoint' => env('RINGIER_SB_ENDPOINT', 'https://bus.ritdu.net/v1/'),
        'password' => env('RINGIER_SB_PASSWORD'),
        'username' => env('RINGIER_SB_USER'),
        'version' => env('RINGIER_SB_VERSION', '0.3.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Culture string.
    |--------------------------------------------------------------------------
    |
    | Default culture string.
    |
    */
    
    'culture' => env('RINGIER_SB_CULTURE', 'en_GB'),
    
    /*
    |--------------------------------------------------------------------------
    | Don't report.
    |--------------------------------------------------------------------------
    |
    | Add service bus event types here that should not be logged (too much traffic).
    | For example: ListingViewed
    |
    */
    
    'dont_report' => [],

    /*
    |--------------------------------------------------------------------------
    | Enabled.
    |--------------------------------------------------------------------------
    |
    | Controls whether events are being broadcast to the event bus. Accepted
    | values are true or false.
    |
    */

    'enabled' => env('RINGIER_SB_ENABLED', true),
    
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
    | Send notifications
    |--------------------------------------------------------------------------
    |
    | Controls whether notifications are being sent. Accepted values
    | are true or false.
    |
    */

    'send_notifications' => env('RINGIER_SB_SEND_NOTIFICATION', true),

    /*
    |--------------------------------------------------------------------------
    | Validator URL
    |--------------------------------------------------------------------------
    |
    | Specify URL on which to validate events.
    |
    */
    
    'validator_url' => env('RINGIER_SB_VALIDATOR_URL', 'https://validator.bus.520152236921.ritdu.tech/api/schema/validate'),

    /*
    |--------------------------------------------------------------------------
    | Venture config ID.
    |--------------------------------------------------------------------------
    |
    | Specify venture config ID.
    |
    */
    
    'venture_config_id' => env('RINGIER_SB_VENTURE_CONFIG_ID'),

];
