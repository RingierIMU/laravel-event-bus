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
    ]
];
